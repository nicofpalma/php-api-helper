<?php 

use ApiHelper\Configuration\ServerConfig;
use ApiHelper\Request\QueryHandler;

class dBHandler{
    private $PDO;
    private ServerConfig $serverConfig;
    private QueryHandler $queryHandler;
    public function __construct() {
        $this->serverConfig = ServerConfig::getInstance('apihelpertest', 'root', '');
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

    public function insertUser($username, $password){
        $this->queryHandler->setTypeOfQuery(QueryHandler::INSERT);
        return $this->queryHandler->makeQuery(
            "INSERT INTO users (username, password) VALUES (:username, :password)",
            [$username, $password]
        );
    }

    public function updateUser($id, $username, $password){
        $this->queryHandler->setTypeOfQuery(QueryHandler::UPDATE);
        return $this->queryHandler->makeQuery(
            "UPDATE users SET username = :username, password = :password WHERE id = :id",
            [$username, $password, $id]
        );
    }

    public function deleteUser($id){
        $this->queryHandler->setTypeOfQuery(QueryHandler::DELETE);
        return $this->queryHandler->makeQuery(
            "DELETE FROM users WHERE id = :id",
            [$id]
        );
    }

    public function getLastInsertedId(){
        return $this->queryHandler->getLastInsertedId();
    }

    public function getAffectedRows(){
        return $this->queryHandler->getAffectedRows();
    }
}