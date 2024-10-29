<?php

use ApiHelper\ORM\Model;

class User extends Model{
    protected static $tableName = 'users';
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

    public function setUsername(string $username){
        $this->attributes->setAttribute("username", $username);
    }

    public function getUsername(){
        return $this->attributes->getAttribute("username");
    }

    public function setPassword(string $password){
        $this->attributes->setAttribute("password", $password);
    }

    public function getPassword(){
        return $this->attributes->getAttribute("password");
    }

    public function setUserAttributes(array $attributes){
        $this->setAttributes($attributes, 'id');
    }

    public function findById(int $id){
        $this->setUserAttributes(['id' => $id]);
        return $this->findByPrimaryKey();
    }

    public function findByUsername(string $username){
        return $this->findByAttribute("username", $username, ["password", "id"]);
    }

    public function getAllUsers(){
        return $this->findAll();
    }

    public function saveUser(){
        return $this->save();
    }

    public function deleteUser(){
        return $this->delete();
    }



}