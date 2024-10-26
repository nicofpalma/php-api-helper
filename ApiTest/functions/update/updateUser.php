<?php

function updateUser(){
    checkRequiredParams(['id', 'username', 'password'], 'POST');

    includeOnce('ApiHelper/ApiTest/dbHandler.php');
    $db = new dBHandler();
    $updated = $db->updateUser(POST['id'], POST['username'], POST['password']);

    if($db->getAffectedRows() < 1){
        return new Response(false, 'User dont exists', 500);
    } elseif(!$updated){
        return new Response(false, 'Error when updating user', 500);
    } else {
        $user = $db->getUserById(POST['id']);

        return new Response(true, 'User updated', 200, ["response" => $user]);
    }
}