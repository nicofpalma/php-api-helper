<?php

namespace ApiHelper\ORM;

class ModelAttributes{
    private string $primaryKeyName;

    private array $attributes;

    public function __construct($primaryKeyName) {
        $this->primaryKeyName = $primaryKeyName; 
    }

    public function setAttribute(string $attributeName, $attributeValue, $isPrimaryKey = false){
        if($isPrimaryKey){
            $this->setPrimaryKeyName($attributeName);
            $this->setPrimaryKeyValue($attributeValue);
        } else {
            $this->attributes[$attributeName] = $attributeValue;
        }
    }

    public function getAttribute(string $attributeName){
        return $this->attributes[$attributeName];
    }

    public function pkIsSet(){
        return isset($this->attributes[$this->primaryKeyName]);
    }

    public function implodeAsColumns(){
        return implode(", " , array_keys($this->attributes));
    }

    public function implodeAsValues(){
        return ":" . implode(", :", array_keys($this->attributes));
    }

    public function getPrimaryKeyName(){
        return $this->primaryKeyName;
    }

    public function getPrimaryKeyValue(){
        return $this->attributes[$this->primaryKeyName];
    }

    public function getAttributes(){
        return $this->attributes;
    }

    public function set(array $attributes){
        $this->attributes = $attributes;
    }

    public function setPrimaryKeyName($primaryKeyName){
        $this->primaryKeyName = $primaryKeyName;
    }

    public function setPrimaryKeyValue($pkValue){
        $this->attributes[$this->primaryKeyName] = $pkValue;
    }
}