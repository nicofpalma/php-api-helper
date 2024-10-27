<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getAllUsers(){
    Utils::includeOnce('ApiHelper/tests/dbHandler.php');
    $db = new dBHandler();
    $users = $db->getAllUsers();

    return new Response(true, 'users fetched', 200, ["response" => $users]);
}