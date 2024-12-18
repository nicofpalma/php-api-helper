<?php

namespace ApiHelper\ORM;
use ApiHelper\Configuration\ServerConfig;
use ApiHelper\Request\QueryHandler;
use ApiHelper\ORM\ModelAttributes;

/**
 * Main class to handle models on database.
 * Has methods to handle CRUD operations and relations.
 */
class Model{
    /** 
     * @var string Name of the table on database.
     */
    protected static $tableName;

    /** 
     * @var ModelAttributes Model attributes (database fields on the table).
     */
    protected ModelAttributes $attributes;

    /** 
     * @var ServerConfig Handler of connection to PDO.
     */
    protected ServerConfig $serverConfig;

    /** 
     * @var QueryHandler Handler of querys and responses from the database.
     */
    protected QueryHandler $queryHandler;

    /**
     * Model constructor.
     *
     * @param string $DBNAME Database name.
     * @param string $DBUSER Database user.
     * @param string $DBPASSWD User password.
     */
    public function __construct(string $DBNAME, string $DBUSER, string $DBPASSWD) {
        $this->serverConfig = ServerConfig::getInstance($DBNAME, $DBUSER, $DBPASSWD);
        $this->queryHandler = new QueryHandler($this->serverConfig->connectDBPDO());
    }

    public function getTableName(){
        return static::$tableName;
    }

    public function initializeAttributes(string $primaryKeyName){
        $this->attributes = new ModelAttributes($primaryKeyName);
    }

    public function getAttributes(){
        return $this->attributes->getAttributes();
    }



    public function setAttributes(array $attributes, string $primaryKey){
        $this->attributes = new ModelAttributes($primaryKey);
        $this->attributes->set($attributes);
    }

    public function getLastInsertedId(){
        $lastInsertedId = $this->queryHandler->getLastInsertedId();
        if(!$lastInsertedId){
            throw new \Exception("Cannot retrieve the last inserted id on table: {$this->getTableName()}");
        }
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

        throw new \Exception("Invalid Query");
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
        $allAttributes = $this->attributes->getAttributes(); 
        $pkName = $this->getPrimaryKeyName();

        $valuesToSet = $this->transformArrayToColumns($allAttributes);

        $query = "UPDATE " . static::$tableName . " SET $valuesToSet WHERE $pkName = :$pkName"; 
        $this->queryHandler->setTypeOfQuery(QueryHandler::UPDATE);
        return $this->queryHandler->makeQuery($query, $this->attributes->getAttributes());
    }

    private function transformArrayToColumns(array $columns){
        $columnsString = "";
        foreach ($columns as $column) {
            $columnsString .= "$column, ";
        }
        $columnsString = rtrim($columnsString, ", ");

        return $columnsString;
    }

    protected function findByAttribute(string $attributeName, $value, $columns = []){
        if(!empty($columns)){
            $columns = $this->transformArrayToColumns($columns);
            $query = "SELECT $columns FROM ";
        } else {
            $query = "SELECT * FROM ";
        }

        $query .= static::$tableName . " WHERE $attributeName = :$attributeName";
        $this->queryHandler->setTypeOfQuery(QueryHandler::SELECT);
        return $this->queryHandler->makeQuery($query, [$attributeName => $value]);
    }

    public function findByAttributes(array $conditions, $columns = []){
        $query = !empty($columns) ? "SELECT " . implode(", ", $columns) : "SELECT *";
        $query .= "FROM " . static::$tableName . " WHERE ";

        $whereClauses = [];
        foreach ($conditions as $key => $value) {
            $whereClauses[] = "$key = :$key";
        }
        $query .= implode(" AND ", $whereClauses);

        $this->queryHandler->setTypeOfQuery(QueryHandler::SELECT);
        return $this->queryHandler->makeQuery($query, $conditions);
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

    /**
     * One to many
     */
    public function hasMany(string $relatedModel, string $foreignKey){
        $related = new $relatedModel(
            $this->serverConfig->getDBName(),
            $this->serverConfig->getDBUser(),
            $this->serverConfig->getDBPass()
        );

        $primaryKeyValue = $this->getPrimaryKeyValue();

        return $related->findByAttribute($foreignKey, $primaryKeyValue);
    }

    /**
     * Many to one
     */
    public function belongsTo(string $relatedModel, string $foreignKey){
        $related = new $relatedModel(
            $this->serverConfig->getDBName(),
            $this->serverConfig->getDBUser(),
            $this->serverConfig->getDBPass()
        );

        $foreignKeyValue = $this->attributes->getAttribute($foreignKey);
        $related->setAttributes([$related->getPrimaryKeyName() => $foreignKeyValue], $related->getPrimaryKeyName());

        return $related->findByPrimaryKey();
    }
}