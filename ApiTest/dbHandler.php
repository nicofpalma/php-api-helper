<?php 

include_once "Configuration/ServerConfig.php";
include_once "QueryHandler.php";

class dBHandler{
    private $PDO;
    private ServerConfig $serverConfig;
    private QueryHandler $queryHandler;
    public function __construct() {
        $this->serverConfig = new ServerConfig('apihelpertest', 'root', '');
        $this->PDO = $this->serverConfig->connectDBPDO();   
        $this->queryHandler = new QueryHandler($this->PDO);
    }

    public function getAllUsers(){
        $this->queryHandler->setTypeOfQuery(QueryHandler::SELECT);
        return $this->queryHandler->makeQuery("SELECT * FROM users");
    }

    public function getUserById($id){
        $this->queryHandler->setTypeOfQuery(QueryHandler::SELECT);
        return $this->queryHandler->makeQuery(
            "SELECT * FROM users WHERE id = :id",
            [$id],
            true
        );
    }
}