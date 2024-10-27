<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function updateUser(){
    Utils::checkRequiredParams(['id', 'username', 'password'], 'POST');

    Utils::includeOnce('ApiHelper/tests/dbHandler.php');
    $db = new dBHandler();
    $updated = $db->updateUser($_POST['id'], $_POST['username'], $_POST['password']);

    if($db->getAffectedRows() < 1){
        return new Response(false, 'User dont exists', 500);
    } elseif(!$updated){
        return new Response(false, 'Error when updating user', 500);
    } else {
        $user = $db->getUserById($_POST['id']);

        return new Response(true, 'User updated', 200, ["response" => $user]);
    }
}