<?php

namespace ApiHelper\Request;
use ApiHelper\Response\Response;

class RequestListener {
    /**
     * @var Endpoint[]
     */
    private array $GETEndpoints = [];

    /**
     * @var Endpoint[]
     */
    private array $POSTEndpoints = [];

    /**
     * 
     * Name of the parameter (on the http request) who identifies the field of the name of the endpoint.
     * 
     * @var string 
     * @example "method=getUserById"
     */
    private string $endpointIdentifierField;
    public function __construct(string $endpointIdentifierField) {
        $this->endpointIdentifierField = $endpointIdentifierField;
    }

    public function setGETEndpoints(string ...$GETEndpoints){
        foreach ($GETEndpoints as $GETEndpoint) {
            $GETEndpoint = new Endpoint($GETEndpoint);
            $this->GETEndpoints[$GETEndpoint->getEndpointName()] = $GETEndpoint;
        }
    }

    public function setPOSTEndpoints(string ...$POSTEndpoints){
        foreach($POSTEndpoints as $POSTEndpoint){
            $POSTEndpoint = new Endpoint($POSTEndpoint);
            $this->POSTEndpoints[$POSTEndpoint->getEndpointName()] = $POSTEndpoint;
        }
    }

    public function listen(){
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                Response::echoExternalResponse($this->getEndpoints()); 
                break;

            case 'POST':
                $_POST = $_POST ?? json_decode(file_get_contents('php://input'), true);
                Response::echoExternalResponse($this->postEndpoints()); 
                break;
            
            default:
                $res = new Response(false, 'Requested method not found', 400);
                $res->echoResponse();
                break;
        }
    }

    public function getEndpoints(): Response{
        $requestedEndpoint = $_GET[$this->endpointIdentifierField] ?? null;

        if(isset($this->GETEndpoints[$requestedEndpoint])){
            return $this->GETEndpoints[$requestedEndpoint]->callFunction();
        }

        return new Response(false, 'GET Method not found', 400);
    }

    public function postEndpoints(): Response{
        $requestedEndpoint = $_POST[$this->endpointIdentifierField] ?? null;

        if(isset($this->POSTEndpoints[$requestedEndpoint])){
            return $this->POSTEndpoints[$requestedEndpoint]->callFunction();
        }

        return new Response(false, 'POST Method not found', 500);
    }
}

