<?php
// to simulate data entry
simulateGET();
//simulateUpdate();
//simulateInsert();
//simulateDelete();

require_once __DIR__ . '/../autoload.php';

use ApiHelper\Request\RequestListener;

// ------------------------------------------------------------------------

// 1) Create the listener, passing the endpoints paths
$request = new RequestListener("method");
$request->setGETEndpoints(
    "ApiHelper/tests/functions/read/getAllUsers.php",
    "ApiHelper/tests/functions/read/getUserById.php"
);
$request->setPOSTEndpoints(
    "ApiHelper/tests/functions/write/setUser.php",
    "ApiHelper/tests/functions/update/updateUser.php",
    "ApiHelper/tests/functions/delete/deleteUser.php"
);

// 2) Listen to the request
$request->listen();

// ------------------------------------------------------------------------

function simulateGET(){
    $_GET['method'] = 'getUserById';
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
    $_POST['id'] = 6;

    $_POST['username'] = 'johndoeUpdated';
    $_POST['password'] = 'test1234Updated';
}

function simulateDelete(){
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['method'] = 'deleteUser';
    $_POST['id'] = 6;
}