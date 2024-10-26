<?php
// to simulate data entry
//simulateGET();
simulateUpdate();

// ------------------------------------------------------------------------


include_once "HTTPRequest.php";

// 1) Create the index handler, passing the endpoints paths
$request = new HTTPRequest("method");
$request->setGETEndpoints(
    "ApiHelper/ApiTest/functions/read/getAllUsers.php",
    "ApiHelper/ApiTest/functions/read/getUserById.php"
);
$request->setPOSTEndpoints(
    "ApiHelper/ApiTest/functions/write/setUser.php",
    "ApiHelper/ApiTest/functions/update/updateUser.php",
    "ApiHelper/ApiTest/functions/delete/deleteUser.php"
);

// 2) Listen to the request
$request->listen();

// ------------------------------------------------------------------------

function simulateGET(){
    $_GET['method'] = 'getAllUsers';
    $_GET['id'] = 1;

}

function simulateInsert(){
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['method'] = 'setUser';
    $_POST['username'] = 'johndoe';
    $_POST['password'] = 'test1234';
}

function simulateUpdate(){
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['method'] = 'updateUser';
    $_POST['username'] = 'johndoeUpdated';
    $_POST['password'] = 'test1234Updated';
}

function simulateDelete(){
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['method'] = 'deleteUser';
    $_POST['id'] = 3;
}