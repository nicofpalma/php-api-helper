<?php

function getUserById($id){
    $db = new dBHandler();
    $user = $db->getUserById($id);

    return new Response(true, 'user fetched', 200, ['response' => $user]);
}