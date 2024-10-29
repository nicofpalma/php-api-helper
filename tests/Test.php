<?php
// to simulate data entry
//simulateGetUsersPosts();
//simulateGET();
simulateGetUserByPost();
//simulateGetUserPublishedPosts();

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
    "ApiHelper/tests/functions/read/getUserById.php",
    "ApiHelper/tests/functions/read/getByUsername.php",
    "ApiHelper/tests/functions/read/getUserPosts.php",
    "ApiHelper/tests/functions/read/getUserByPost.php",
    "ApiHelper/tests/functions/read/getUserPublishedPosts.php"
);
$request->setPOSTEndpoints(
    "ApiHelper/tests/functions/write/setUser.php",
    "ApiHelper/tests/functions/update/updateUser.php",
    "ApiHelper/tests/functions/delete/deleteUser.php"
);

// 2) Listen to the request
$request->listen();

// ------------------------------------------------------------------------

function simulateGetUserPublishedPosts(){
    $_GET['method'] = 'getUserPublishedPosts';
    $_GET['userid'] = 1;

}

function simulateGetUserByPost(){
    $_GET['method'] = 'getUserByPost';
    $_GET['id'] = 1;
}

function simulateGetUsersPosts(){
    $_GET['method'] = 'getUserPosts';
    $_GET['id'] = 1;
}

function simulateGET(){
    $_GET['method'] = 'getByUsername';
    $_GET['username'] = 'test1';
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