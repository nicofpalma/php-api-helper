<?php

function getAllUsers(){
    $db = new dBHandler();
    $users = $db->getAllUsers();

    return new Response(true, 'users fetched', 200, ["response" => $users]);
}