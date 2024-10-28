<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function setUser(){
    Utils::checkRequiredParams(['username', 'password'], 'POST');

    $username = $_POST['username'];
    $password = $_POST['password'];

    Utils::includeOnce('ApiHelper/tests/User.php');
    $user = new User();
    $user->setUsername($username);
    $user->setPassword($password);
    $inserted = $user->save();

    if($inserted){
        $lastId = $user->getLastInsertedId();
        $user = $user->findById($lastId);
    
        return new Response(true, "User created", 201, ["response" => $user]);
    } else {
        return new Response(false, "Error inserting user", 500);
    }
}