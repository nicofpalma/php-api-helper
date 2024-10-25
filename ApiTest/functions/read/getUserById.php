<?php

function getUserById(){
    checkRequiredParam(GET['id']);
    includeOnce('ApiHelper/ApiTest/dbHandler.php');

    $db = new dBHandler();
    $user = $db->getUserById(GET['id']);

    return new Response(true, 'user fetched', 200, ['response' => $user]);
}