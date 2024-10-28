<?php

namespace ApiHelper\ORM;
use ApiHelper\Configuration\ServerConfig;
use ApiHelper\Request\QueryHandler;
use ApiHelper\ORM\ModelAttributes;
use Exception;

class Model{
    protected static $tableName;
    protected ModelAttributes $attributes;
    protected ServerConfig $serverConfig;
    protected $queryHandler;

    public function __construct(string $DBNAME, string $DBUSER, string $DBPASSWD) {
        $this->serverConfig = ServerConfig::getInstance($DBNAME, $DBUSER, $DBPASSWD);
        $this->queryHandler = new QueryHandler($this->serverConfig->connectDBPDO());
    }

    public function initializeAttributes(string $primaryKeyName){
        $this->attributes = new ModelAttributes($primaryKeyName);
    }

    public function setAttributes(array $attributes, string $primaryKey){
        $this->attributes = new ModelAttributes($primaryKey);
        $this->attributes->set($attributes);
    }

    public function getLastInsertedId(){
        return $this->queryHandler->getLastInsertedId();
    }

    public function getPrimaryKeyName(){
        return $this->attributes->getPrimaryKeyName();
    }

    public function getPrimaryKeyValue(){
        return $this->attributes->getPrimaryKeyValue();
    }

    protected function customQuery(string $query){
        $queryUpperToCompare = strtoupper(trim($query));
        $queryTypes = [
            "SELECT" => QueryHandler::SELECT,
            "INSERT" => QueryHandler::INSERT,
            "UPDATE" => QueryHandler::UPDATE,
            "DELETE" => QueryHandler::DELETE
        ];

        foreach ($queryTypes as $keyword => $type) {
            if(strpos($queryUpperToCompare, $keyword) !== false){
                $this->queryHandler->setTypeOfQuery($type);
                return $this->queryHandler->makeQuery($query, $this->attributes->getAttributes());
            }
        }

        throw new Exception("Invalid Query");
    }

    public function save(){
        $pkSetted = $this->attributes->pkIsSet();
        if($pkSetted){
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    protected function insert(){
        $columns = $this->attributes->implodeAsColumns();
        $values = $this->attributes->implodeAsValues();

        $query = "INSERT INTO " . static::$tableName . " ($columns) VALUES ($values)";
        $this->queryHandler->setTypeOfQuery(QueryHandler::INSERT) ;
        $success = $this->queryHandler->makeQuery($query, $this->attributes->getAttributes());

        if($success)
            $this->attributes->setPrimaryKeyValue($this->queryHandler->getLastInsertedId());

        return $success;
    }

    protected function update(){
        $valuesToSet = "";
        $allAttributes = $this->attributes->getAttributes(); 
        $pkName = $this->getPrimaryKeyName();

        foreach ($allAttributes as $key => $value) {
            if($key !== $pkName){
                $valuesToSet .= "$key = :$key, ";
            }
        }
        $valuesToSet = rtrim($valuesToSet, ", ");

        $query = "UPDATE " . static::$tableName . " SET $valuesToSet WHERE $pkName = :$pkName"; 
        $this->queryHandler->setTypeOfQuery(QueryHandler::UPDATE);
        return $this->queryHandler->makeQuery($query, $this->attributes->getAttributes());
    }

    private function transformArrayToColumns(array $columns){
        $columnsString = "";
        foreach ($columns as $column) {
            $columnsString .= "$column,";
        }
        $columnsString = rtrim($columnsString, ", ");

        return $columnsString;
    }

    protected function findByPrimaryKey($columns = []){
        $pkName = $this->getPrimaryKeyName();

        if(!empty($columns)){
            $columns = $this->transformArrayToColumns($columns);
            $query = "SELECT $columns FROM ";
        } else {
            $query = "SELECT * FROM ";
        }

        $query .= static::$tableName . " WHERE $pkName = :$pkName";
        $this->queryHandler->setTypeOfQuery(QueryHandler::SELECT);
        $fetched = $this->queryHandler->makeQuery($query, [$pkName => $this->getPrimaryKeyValue()], true);

        if($fetched){
            $this->attributes->set($fetched);
        }

        return $fetched;
    }

    protected function findAll($columns = []){
        if(!empty($columns)){
            $columns = $this->transformArrayToColumns($columns);
            $query = "SELECT $columns FROM ";
        } else {
            $query = "SELECT * FROM ";
        }

        $query .= static::$tableName;
        $this->queryHandler->setTypeOfQuery(QueryHandler::SELECT);
        return $this->queryHandler->makeQuery($query);
    }

    protected function delete(){
        $pkName = $this->getPrimaryKeyName();
        $query = "DELETE FROM " . static::$tableName . " WHERE $pkName = :$pkName";
        $this->queryHandler->setTypeOfQuery(QueryHandler::DELETE);
        return $this->queryHandler->makeQuery($query, [$pkName => $this->getPrimaryKeyValue()]);
    }

    public function getAffectedRows(){
        return $this->queryHandler->getAffectedRows();
    }
}