<?php

function getAllUsers(){
    includeOnce('ApiHelper/ApiTest/dbHandler.php');
    $db = new dBHandler();
    $users = $db->getAllUsers();

    return new Response(true, 'users fetched', 200, ["response" => $users]);
}