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
        echo 'dont exists' . $fullPath;
        // throw exception
    } else {
        include_once ROOT . $filePath;
    }
}

function requiredParam($param){
    if(!isset($param)) {
        includeOnce("ApiHelper/Response/Response.php");
        $response = new Response(false, "$param is missing", 400);
        $response->exitAndSendResponse();
    }
}