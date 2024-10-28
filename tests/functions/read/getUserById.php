<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getUserById(){
    Utils::checkRequiredParams(['id'], 'GET');
    Utils::includeOnce('ApiHelper/tests/User.php');

    $user = new User();
    $userFetched = $user->findById($_GET['id']);

    return new Response(true, 'user fetched', 200, ['response' => $userFetched]);
}