<?php

namespace week3\lib;


abstract class Entity
{

    protected $tableName;
    protected $db = null;


    public function __construct(){}

    public function getTableName()
    {
        $tableName = '';
        $class = new \ReflectionClass($this);
        if ($this->tableName != '') {
            $tableName = $this->tableName;
        } else {
            $tableName = strtolower($class->getShortName());
        }
        return $tableName;
    }
}