<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function updateUser(){
    Utils::checkRequiredParams(['id', 'username', 'password'], 'POST');

    Utils::includeOnce('ApiHelper/tests/User.php');
    $user = new User();

    $user->setAttributes([
        "id" => $_POST['id'],
        "username" => $_POST["username"], 
        "password" => $_POST["password"]
        ], 
        "id"
    );
    $updated = $user->saveUser();

    if($user->getAffectedRows() < 1){
        return new Response(false, 'User dont exists', 500);
    } elseif(!$updated){
        return new Response(false, 'Error when updating user', 500);
    } else {
        $userUpdated = $user->findById($_POST['id']);

        return new Response(true, 'User updated', 200, ["response" => $userUpdated]);
    }
}