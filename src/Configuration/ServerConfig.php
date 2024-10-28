<?php

namespace ApiHelper\Configuration;

use PDO;
use Exception;
use ApiHelper\Response\Response;

class ServerConfig{
    private string $DBNAME;
    private string $DBUSER;
    private string $DBPASS;
    private ?PDO $PDOMYSQL = null;

    private static ?ServerConfig $instance = null;

    private function __construct(string $DBNAME, string $DBUSER, $DBPASS) {
        $this->DBNAME = $DBNAME;
        $this->DBUSER = $DBUSER;
        $this->DBPASS = $DBPASS;
    }

    public static function getInstance(string $DBNAME, string $DBUSER, string $DBPASS): ServerConfig {
        if (self::$instance === null) {
            self::$instance = new ServerConfig($DBNAME, $DBUSER, $DBPASS);
        }
        return self::$instance;
    }


    public function connectDBPDO(){
        if($this->PDOMYSQL === null){
            try {
                $this->PDOMYSQL = new PDO(
                    'mysql:host=localhost; dbname=' . $this->DBNAME, 
                    $this->DBUSER, 
                    $this->DBPASS
                );
                $this->PDOMYSQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->PDOMYSQL->exec("SET CHARACTER SET utf8mb4");
            } catch (Exception $e) {
                $res = new Response(false, 'Error connecting to DB', 500, ['SQLERROR' => $e->getMessage()]);
                $res->echoResponse();
                exit;
            }
        }
        return $this->PDOMYSQL;
    }

    public function getPDOInstance(){
        return $this->PDOMYSQL;
    }
}