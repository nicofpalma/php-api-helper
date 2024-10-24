<?php

class QueryHandler{
    private PDO $PDO;
    private int $typeOfQuery;
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;

    public function __construct(PDO $PDO, int $typeOfQuery = self::SELECT) {
        $this->PDO = $PDO;
        $this->setTypeOfQuery($typeOfQuery);
    }

    public function setTypeOfQuery(int $typeOfQuery){
        if (!in_array($typeOfQuery, [self::SELECT, self::INSERT, self::UPDATE, self::DELETE], true)) {
            throw new InvalidArgumentException("Invalid query type.");
        }
        $this->typeOfQuery = $typeOfQuery;
    }

    public function makeQuery(string $query, array $paramsValues = [], $fetchOne = false, int $assocMethod = PDO::FETCH_ASSOC){
        if(!empty($paramsValues)){
            $paramsPattern = '/:\w+/';
            preg_match_all($paramsPattern, $query, $matches);
            $paramsNames = $matches[0];

            if(count($paramsNames) !== count($paramsValues)){
                throw new InvalidArgumentException("The number of query parameters not matches with the provided ones");
            }
        }

        try {
            $stmt = $this->PDO->prepare($query);
            
            if(!empty($paramsValues)){
                foreach ($paramsNames as $index => $paramName) {
                    $stmt->bindValue($paramName, $paramsValues[$index]);
                }
            }

            switch ($this->typeOfQuery) {
                case QueryHandler::SELECT:
                    $stmt->execute();
                    $fetchOne ? $response = $stmt->fetch($assocMethod)
                              : $response = $stmt->fetchAll($assocMethod);
                    break;
    
                case QueryHandler::INSERT:
                case QueryHandler::UPDATE:
                case QueryHandler::DELETE:
                    $response = $stmt->execute();
                    break;
                
                default:
                    return false;
            }

            $stmt->closeCursor();
            return $response;

        } catch(PDOException $e){
            throw new RuntimeException("Database query failed: " . $e->getMessage());
        }
    }
}