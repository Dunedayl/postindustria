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
        $tableName = '';

        if ($this->tableName != '') {
            $tableName = $this->tableName;
        } else {
            $tableName = strtolower($class->getShortName());
        }
        
        $props = "("; // names of values to insert
        $insertData = [];
        //Find all public filds in class
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $props .= $propertyName . ",";
            $insertData[] = $propertyName;
        }

        $props = substr($props, 0, -1);
        $props .= ")";
        $sqw = ""; // Data to insert 
        //Saving Data from each object to sql query 
        foreach ($object as $key => $value) {
            $sqw .= "(";
            foreach ($insertData as $key => $insert) {
                $sqw .= '"' . addslashes($value->$insert) . '"';
                if ($insert != end($insertData)) {
                    $sqw .= ",";
                }
            }
            $sqw .= "),";
        }
        //Removing last , 
        $sqw = substr($sqw, 0, -1);

        $sqlQuery = 'INSERT INTO ' . $tableName . " $props values " . $sqw . ';';
        print_r("\n");

        try {
            $result = $this->db->exec($sqlQuery);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Function for saving data with conditions
    // redeclared in childs where needed 
    public function saveAllQ(array $object){

    }

}
