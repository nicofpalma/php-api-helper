<?php
// to simulate data entry
simulate();

// ------------------------------------------------------------------------

include_once "HTTPRequest.php";

// 1) Create the index handler, passing the endpoints paths
$requestHandler = new HTTPRequest("method");
$requestHandler->setGETEndpoints(
    "ApiHelper/ApiTest/functions/read/getAllUsers.php",
    "ApiHelper/ApiTest/functions/read/getUserById.php"
);
$requestHandler->setPOSTEndpoints();

// 2) Listen to the request
$requestHandler->listenRequest();

// ------------------------------------------------------------------------

function simulate(){
    $_GET['method'] = 'getAllUsers';
    $_GET['id'] = 1;
}