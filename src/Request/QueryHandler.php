<?php

namespace ApiHelper\Request;

use Exception;
use PDO;
use PDOException;
use InvalidArgumentException;
use RuntimeException;


class QueryHandler{
    private PDO $PDO;
    private int $typeOfQuery;
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;
    private bool $protected;
    private $lastInsertedId;
    private $affectedRows;

    public function __construct(PDO $PDO, $protected = true, int $typeOfQuery = self::SELECT) {
        $this->PDO = $PDO;
        $this->setTypeOfQuery($typeOfQuery);
        $this->protected = $protected;
    }

    public function setTypeOfQuery(int $typeOfQuery){
        if (!in_array($typeOfQuery, [self::SELECT, self::INSERT, self::UPDATE, self::DELETE], true)) {
            throw new InvalidArgumentException("Invalid query type.");
        }
        $this->typeOfQuery = $typeOfQuery;
    }

    private function extractParamNames(string $query, array $paramValues){
        if(!empty($paramValues)){
            $paramsPattern = '/:\w+/';
            preg_match_all($paramsPattern, $query, $matches);
            $paramNames = $matches[0];

            if(count($paramNames) !== count($paramValues)){
                throw new InvalidArgumentException("The number of query parameters not matches with the provided ones");
            }

            return $paramNames;
        }
    }

    public function makeQuery(string $query, array $attributes = [], $fetchOne = false){
        try {
            $stmt = $this->PDO->prepare($query);

            if(!empty($attributes)){
                foreach($attributes as $attributeName => $attributeValue){
                    if($this->protected){
                        $attributeValue = htmlentities(addslashes($attributeValue));
                    }

                    $stmt->bindValue(
                        ":$attributeName",
                        $attributeValue
                    );
                }
            }

            $success = $stmt->execute();

            if(!$success){
                throw new RuntimeException("Failed to execute query: " . implode(" ", $stmt->errorInfo()));
            }

            switch ($this->typeOfQuery) {
                case self::SELECT:
                    if($fetchOne){
                        $response = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($response && $this->protected){
                            foreach ($response as &$field) {
                                $this->quitSlashesAndEntities($field);
                            }
                        }
                    } else {
                        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if($response && $this->protected){
                            foreach ($response as &$row) {
                                foreach ($row as &$field) {
                                    $this->quitSlashesAndEntities($field);
                                }
                            }
                        }
                    }
                    break;
    
                case self::INSERT:
                    $this->lastInsertedId = $this->PDO->lastInsertId();
                case self::UPDATE:
                case self::DELETE:
                    $this->affectedRows = $stmt->rowCount();
                    $response = $this->affectedRows > 0;
                    break;
                
                default:
                    throw new InvalidArgumentException("Unsopported query type");
            }

            $stmt->closeCursor();
            return $response;

        } catch(PDOException $e){
            throw new RuntimeException("Database query failed: " . $e->getMessage());
        }
    }

    public function getLastInsertedId(){
        return $this->lastInsertedId;
    }

    public function getAffectedRows(){
        return $this->affectedRows;
    }

    private function quitSlashesAndEntities(&$value){
        if(is_string($value)){
            $value = stripslashes(html_entity_decode($value));
        }
    }
}