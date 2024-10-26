<?php

define('GET', $_GET);
define('POST', 
    $_POST ?? json_decode(file_get_contents('php://input'), true)
);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . "/");
define('URL', 'https://' . $_SERVER['HTTP_HOST']);

function includeOnce(string $filePath){
    $fullPath = ROOT . $filePath;
    if(!file_exists($fullPath)){
        throw new Exception("The file at path $fullPath does not exist.");
    } else {
        include_once ROOT . $filePath;
    }
}

function checkRequiredParams(array $params, $onMethod){
    $allowedMethods = ['POST', 'GET', 'PUT', 'PATCH', 'DELETE'];
    if(!in_array($onMethod, $allowedMethods)){
        $response = new Response(false, "Not allowed method to check params", 500);
        $response->echoResponse();
    }

    foreach ($params as $param) {
        checkRequiredParam($param, $onMethod);
    }
}

function checkRequiredParam($param, $onMethod){
    switch ($onMethod) {
        case 'GET':
            $method = GET;
            break;

        case 'PUT':
        case 'PATCH':
        case 'DELETE':
        case 'POST':
            $method = POST;
    }
    
    if(!isset($method[$param])) {
        includeOnce("ApiHelper/Response/Response.php");
        $response = new Response(false, "$param is missing", 400);
        $response->echoResponse();
    }
}