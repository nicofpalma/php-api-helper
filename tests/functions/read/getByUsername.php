<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getByUsername(){
    Utils::checkRequiredParams(['username'], 'GET');
    
    Utils::includeOnce('ApiHelper/tests/User.php');
    $user = new User();
    $userFetched = $user->findByUsername($_GET['username']);

    if($userFetched){
        return new Response(true, "User fetched", 200, ['response' => $userFetched]);
    } else {
        return new Response(false, 'User not found', 404);
    }
}
