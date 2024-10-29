<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getUserByPost(){
    Utils::checkRequiredParams(['id'], 'GET');
    Utils::includeOnce("ApiHelper/tests/User.php");
    Utils::includeOnce("ApiHelper/tests/Post.php");

    $post = new Post();
    $postFetched = $post->findById($_GET['id']);
    $user = $post->belongsTo(User::class, 'userid');

    if($user){
        return new Response(true, 'User fetched', 200, ['response' => [
            "post" => $postFetched,
            "user" => $user
        ]]);
    } else {
        return new Response(false, 'Cant fetch user', 500);
    }
}