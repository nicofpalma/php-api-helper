<?php

class ServerConfig{
    private string $DBNAME;
    private string $DBUSER;
    private string $DBPASS;
    private $PDOMYSQL;

    public function __construct(string $DBNAME, string $DBUSER, $DBPASS) {
        $this->DBNAME = $DBNAME;
        $this->DBUSER = $DBUSER;
        $this->DBPASS = $DBPASS;
    }

    public function connectDBPDO(){
        try {
            $this->PDOMYSQL = new PDO('mysql:host=localhost; dbname=' . $this->DBNAME, $this->DBUSER, $this->DBPASS);
            $this->PDOMYSQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->PDOMYSQL->exec("SET CHARACTER SET utf8mb4");

            return $this->PDOMYSQL;
        } catch (Exception $e) {
            $success["success"] = false;
            $success["msg"] = $e->getMessage();
            echo json_encode($success);
        }
    }
}