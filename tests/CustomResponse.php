<?php

use ApiHelper\Response\Response;

/**
 * Custom response to modify $boolName and $messageName on the reponse.
 * 
 */
class CustomResponse extends Response{
    protected $boolName = 'status';
    protected $messageName = 'message';
    public function __construct($success, $msg, $httpCode, $response, ...$args) {
        parent::__construct($success, $msg, $httpCode, $response, ...$args);
    }
}

$r = new CustomResponse(true, 'Message test', 500, ['res' => ['a' => 1, 'b' => 2]], ['test' => 'test']);
$r->echoResponse();