<?php

namespace ApiHelper\Request;

use Exception;
use ApiHelper\Utils\Utils;

class Endpoint{
    private $endpoint;

    private $path;

    public function __construct($endpointPath) {
        Utils::includeOnce($endpointPath);

        $this->path = $endpointPath;
        $this->endpoint = $this->extractFunctionName($this->path);
        
        if(!is_callable($this->endpoint)){
            throw new Exception("The endpoint '{$this->endpoint}' is not callable");
        }
    }

    public function getEndpointName(){
        return $this->endpoint;
    } 

    public function callFunction(){
        return call_user_func($this->endpoint);
    }

    private function extractFunctionName(string $path){
        return pathinfo($path, PATHINFO_FILENAME);
    }
}