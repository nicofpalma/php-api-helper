<?php

class Endpoint{
    private $endpointName;
    private $endpointBody;

    public function __construct($endpointName, callable $endpointBody) {
        $this->endpointName = $endpointName;
        $this->endpointBody = $endpointBody;
    }

    public function getEndpointName(){
        return $this->endpointName;
    } 

    public function callFunction(){
        if(is_callable($this->endpointBody)){
            return call_user_func($this->endpointBody);
        } else {
            echo 'error';
        }
    }

    public function getMethodFunction(){
        return ['endpointName' => $this->endpointName, 'endpointBody' => $this->endpointBody];
    }

    public function returnGetParams(){
        return $_GET;
    }
    
}