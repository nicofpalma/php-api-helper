<?php 

function deleteUser(){
    checkRequiredParam(['id'], 'POST');

    includeOnce('ApiHelper/ApiTest/dbHandler.php');
    $db = new dBHandler();
    $deleted = $db->deleteUser(POST['id']);

    if($db->getAffectedRows() < 1){
        return new Response(false, 'User dont exist', 500);
    } elseif(!$deleted){
        return new Response(false, 'Error deleting user', 500);
    } else {
        return new Response(true, 'user deleted', 200);
    }
}