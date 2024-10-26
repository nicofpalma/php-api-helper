<?php

function setUser(){
    checkRequiredParams(['username', 'password'], 'POST');

    $username = POST['username'];
    $password = POST['password'];

    includeOnce('ApiHelper/ApiTest/dbHandler.php');
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