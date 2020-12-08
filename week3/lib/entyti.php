<?php

abstract class Entity
{

    protected $tableName;
    protected $db = null;


    public function __construct($start)
    {
        if ($start == true) {
            include("../config/config.php");
            try {
                $this->db = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            } catch (\Exception $e) {
                throw new \Exception('Error creating a database connection ');
            }
        }
    }

    //Save all Data
    public function saveAll(array $object)
    {
        $class = new \ReflectionClass($this);
        $tableName = $this->getTableName($class);
        $insertData = $this->getPropertiesNames($class);
        $propertiesForSqlInsert = "(" . implode(",", $insertData) . ")"; // names of values to insert
        $sqlData = $this->generateValuesToInsert($object, $insertData);
        $sqlDataQuery = 'INSERT INTO ' . $tableName . " $propertiesForSqlInsert values " . $sqlData . ';';
        $this->execute($sqlDataQuery);
    }

    protected function getTableName($class)
    {
        $tableName = '';
        if ($this->tableName != '') {
            $tableName = $this->tableName;
        } else {
            $tableName = strtolower($class->getShortName());
        }
        return $tableName;
    }

    protected function getPropertiesNames($class)
    {
        $insertData = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $insertData[] = $propertyName;
        }
        return $insertData;
    }

    protected function execute($sqlDataQuery)
    {
        try {
            $result = $this->db->exec($sqlDataQuery);
            $class = new \ReflectionClass($this);
            $className = strtolower($class->getShortName());
            echo "\n$className successfully";
        } catch (PDOException $e) {
            echo "\nConnection failed: " . $e->getMessage();
            print_r($sqlDataQuery);
        }
    }

    protected function generateValuesToInsert(array $object, $insertData)
    {
        $sqlData = ""; // Data to insert 
        //Saving Data from each object to sql query 
        foreach ($object as $key => $value) {
            $sqlData .= "(";
            foreach ($insertData as $key => $insert) {
                $sqlData .= '"' . addslashes($value->$insert) . '"';
                if ($insert != end($insertData)) {
                    $sqlData .= ",";
                }
            }
            $sqlData .= ")";
            if ($value != end($object)) {
                $sqlData .= ",";
            }
        }
        return $sqlData;
    }

    public abstract function createTable();
}
