<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function setUser(){
    Utils::checkRequiredParams(['username', 'password'], 'POST');

    $username = $_POST['username'];
    $password = $_POST['password'];

    Utils::includeOnce('ApiHelper/tests/dbHandler.php');
    $db = new dBHandler();
    $inserted = $db->insertUser($username, $password);

    if($inserted){
        $lastId = $db->getLastInsertedId();
        $user = $db->getUserById($lastId);
    
        return new Response(true, "User created", 201, ["response" => $user]);
    } else {
        return new Response(false, "Error inserting user", 500);
    }
}