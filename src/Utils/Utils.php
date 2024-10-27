<?php

namespace ApiHelper\Utils;

use Exception;
use ApiHelper\Response\Response;

class Utils{
    public static $PROJECT_ROOT;
    public static $PROJECT_URL;
    private static bool $initialized = false;

    private static function initialize(){
        if(!self::$initialized){
            self::$PROJECT_ROOT = $_SERVER['DOCUMENT_ROOT'] . "/";
            self::$PROJECT_URL = 'https://' . $_SERVER['HTTP_HOST'];
            self::$initialized = true;
        }
    }

    public static function includeOnce(string $filePath){
        self::initialize();
        $fullPath = self::$PROJECT_ROOT . $filePath;
        if(!file_exists($fullPath)){
            throw new Exception("The file at path $fullPath does not exist.");
        } else {
            include_once self::$PROJECT_ROOT . $filePath;
        }
    }

    public static function checkRequiredParams(array $params, $onMethod){
        self::initialize();
        $allowedMethods = ['POST', 'GET', 'PUT', 'PATCH', 'DELETE'];
        if(!in_array($onMethod, $allowedMethods)){
            (new Response(false, "Not allowed method to check params", 500))
                ->echoResponse();
        }
    
        foreach ($params as $param) {
            self::checkRequiredParam($param, $onMethod);
        }
    }

    public static function checkRequiredParam($param, $onMethod){
        self::initialize();
        switch ($onMethod) {
            case 'GET':
                $method = $_GET;
                break;
    
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
            case 'POST':
                $method = $_POST;
        }
    
        
        if(!isset($method[$param])) {
            self::includeOnce("ApiHelper/src/Response/Response.php");
            $response = new Response(false, "$param is missing", 400);
            $response->echoResponse();
        }
    }
}