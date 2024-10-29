<?php

use ApiHelper\Response\Response;
use ApiHelper\Utils\Utils;

function getUserPublishedPosts(){
    Utils::checkRequiredParams(['userid'], 'GET');

    //Utils::includeOnce("ApiHelper/tests/User.php");
    Utils::includeOnce("ApiHelper/tests/Post.php");

    $post = new Post();
    $fetched = $post->findByAttributes(['userid' => $_GET['userid'], 'status' => 'published']);
    
    if($fetched){
        return new Response(true, 'Posts fetched', 200, ['response' => $fetched]);
    } else {
        return new Response(false, 'No posts found', 404);
    }
}