<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getUserById(){
    Utils::checkRequiredParams(['id'], 'GET');
    Utils::includeOnce('ApiHelper/tests/dbHandler.php');

    $db = new dBHandler();
    $user = $db->getUserById($_GET['id']);

    return new Response(true, 'user fetched', 200, ['response' => $user]);
}