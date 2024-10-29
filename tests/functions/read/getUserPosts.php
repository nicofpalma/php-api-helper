<?php

use ApiHelper\Utils\Utils;
use ApiHelper\Response\Response;

function getUserPosts(){
    Utils::checkRequiredParams(['id'], 'GET');

    Utils::includeOnce("ApiHelper/tests/User.php");
    Utils::includeOnce("ApiHelper/tests/Post.php");

    $user = new User();
    $user->setId($_GET['id']);
    $posts = $user->hasMany(Post::class, 'userid');

    if($posts){
        return new Response(true, 'Posts fetched', 200, ['response' => $posts]);
    } else {
        return new Response(false, 'Posts not found for this user', 404);
    }
}