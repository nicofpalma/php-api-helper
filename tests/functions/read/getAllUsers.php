<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getAllUsers(){
    Utils::includeOnce('ApiHelper/tests/User.php');
    $user = new User();
    $users = $user->getAllUsers();

    return new Response(true, 'users fetched', 200, ["response" => $users]);
}