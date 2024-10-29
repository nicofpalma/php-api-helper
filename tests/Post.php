<?php

use ApiHelper\ORM\Model;

class Post extends Model{
    protected static $tableName = 'posts';

    public function __construct() {
        parent::__construct("apihelpertest", "root", "");
        $this->initializeAttributes("id");
    }

    public function setId(int $id){
        $this->attributes->setAttribute("id", $id, true);
    }

    public function getId(){
        return $this->attributes->getAttribute("id");
    }

    public function findById(int $id){
        $this->setAttributes(['id' => $id], 'id');
        return $this->findByPrimaryKey();
    }

    public function findUserId(){
        return $this->findByPrimaryKey(['userid']);
    }

    public function getUserId(){
        return $this->attributes->getAttribute('userid');
    }


}