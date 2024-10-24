<?php
// to simulate data entry
simulate();

include_once "RequestHandler.php";
include_once "Utils/Utils.php";

// 1) Set the methods and set the body of the method
$getAllUsers = new Endpoint('getAllUsers', function(){
    includeOnce('ApiHelper/ApiTest/functions/read/getAllUsers.php');
    includeOnce('ApiHelper/ApiTest/dbHandler.php');

    return getAllUsers();
});

$getUserById = new Endpoint('getUserById', function(){
    requiredParam(GET['id']);
    
    includeOnce('ApiHelper/ApiTest/functions/read/getUserById.php');
    includeOnce('ApiHelper/ApiTest/dbHandler.php');

    return getUserById(GET['id']);
});

// 2) Create the index handler, pass the endpoints
$rh = new RequestHandler(
    [$getAllUsers, $getUserById],
    []);
$rh->handleRequest();


function simulate(){
    $_GET['method'] = 'getUserById';
    $_GET['id'] = 1;
}