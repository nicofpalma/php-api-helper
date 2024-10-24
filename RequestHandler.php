<?php

include_once 'Endpoint.php';
include_once 'Configuration/ServerConfig.php';
include_once 'Response/Response.php';

class RequestHandler {
    private array $GETEndpoints = [];
    private array $POSTEndpoints = [];
    public function __construct(array $GETEndpoints, array $POSTEndpoints) {
        foreach ($GETEndpoints as $GETEndpoint) {
            $this->GETEndpoints[] = $GETEndpoint;
        }

        foreach($POSTEndpoints as $POSTEndpoint){
            $this->POSTEndpoints[] = $POSTEndpoint;
        }
    }

    public function handleRequest(){
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                Response::exitAndSendExternalResponse($this->getEndpoints()); 
                break;

            case 'POST':
                Response::exitAndSendExternalResponse($this->postEndpoints()); 
                break;
            
            default:
                $res = new Response(false, 'Requested method not found', 400);
                $res->exitAndSendResponse();
                break;
        }
    }

    public function getEndpoints(): Response{
        $requestedEndpoint = $_GET['method'];

        foreach ($this->GETEndpoints as $GETEndpoint) {
            if($GETEndpoint->getEndpointName() === $requestedEndpoint){
                return $GETEndpoint->callFunction();
            }
        }

        return new Response(false, 'GET Method not found', 400);
    }

    public function postEndpoints(): Response{
        return new Response(false, 'dont', 500);
    }
}

