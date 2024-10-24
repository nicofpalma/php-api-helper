<?php

class Response{
    private array $response;

    public function __construct(bool $success, string $msg, int $httpCode, $response = [], ...$args) {
        $this->response = array_merge(
            [
                'success' => $success,
                'msg' => $msg,
                'httpCode' => $httpCode
            ],
            $response,
            ...$args,
        );
    }

    public function getHttpCode(){
        return $this->response['httpCode'];
    }

    public function isSucceeded(){
        return $this->response['success'];
    }

    public function getMessage(){
        return $this->response['msg'];
    }

    public function getResponseBody(){
        return array_diff_key(
            $this->response,
            array_flip(['success', 'msg', 'httpCode'])
        );
    }

    public function unsetHttpCode(){
        unset($this->response['httpCode']);
    }

    public function sendHttpCode(){
        http_response_code($this->response['httpCode']);
    }

    public function sendJsonResponse(){
        echo json_encode($this->response);
    }

    public function exitAndSendResponse(){
        $this->sendHttpCode();
        $this->unsetHttpCode();
        $this->sendJsonResponse();
        exit;
    }

    static function exitAndSendExternalResponse(Response $response){
        $response->sendHttpCode();
        $response->unsetHttpCode();
        $response->sendJsonResponse();
        exit;
    }
}