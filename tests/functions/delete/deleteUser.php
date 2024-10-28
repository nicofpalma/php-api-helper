<?php 

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function deleteUser(){
    Utils::checkRequiredParams(['id'], 'POST');
    Utils::includeOnce('ApiHelper/tests/User.php');

    $user = new User();
    $user->setId($_POST['id']);
    $deleted = $user->deleteUser();

    if($user->getAffectedRows() < 1){
        return new Response(false, 'User dont exist', 500);
    } elseif(!$deleted){
        return new Response(false, 'Error deleting user', 500);
    } else {
        return new Response(true, 'user deleted', 200);
    }
}