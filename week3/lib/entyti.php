<?php

    abstract class Entity
    {

        protected $tableName;

        protected $db = null;


        public function start()
        {
            include("../config/config.php");
            try {
                // параметры соединения
                $this->db = new PDO("mysql:host=$host;dbname=$database", $user, $password);
                // set the PDO error mode to exception
            } catch (\Exception $e) {
                throw new \Exception('Error creating a database connection ');
            }
        }

        public function __construct($start)
        {
            if ($start == true){
                include("../config/config.php");
                try {
                    // параметры соединения
                    $this->db = new PDO("mysql:host=$host;dbname=$database", $user, $password);
                    // set the PDO error mode to exception
                } catch (\Exception $e) {
                    throw new \Exception('Error creating a database connection ');
                }
            }
        }


        public function save()
        {
            $class = new \ReflectionClass($this);
            $tableName = '';

            if ($this->tableName != '') {
                $tableName = $this->tableName;
            } else {
                $tableName = strtolower($class->getShortName());
            }

            $propsToImplode = [];
            // собираем поля класса, которые мы будем использовать в запросе
            foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) { // собираем информацию только о полях класса которые имеют модификатор `public`
                $propertyName = $property->getName();
                $propsToImplode[] = '' . $propertyName . ' = "' . $this->{$propertyName} . '"';
            }

            $setClause = implode(',', $propsToImplode); // записываем в наш генериреумый запрос все поля
            $sqlQuery = '';

            // если айди нашего обьекта не 0, тогда обьект существует и нам нужно обновить его в бд
            if ($this->id > 0) {
                $sqlQuery = 'UPDATE ' . $tableName . ' SET ' . $setClause . ' WHERE id = ' . spl_object_id($this);
            } else { // иначе  нам нужно вставить новый объект в бд
                $sqlQuery = 'INSERT INTO ' . $tableName . ' SET ' . $setClause . ';';
            }
            // print_r(spl_object_id($this));
            // print_r("\n");
            // print_r($sqlQuery);
            // print_r("\n");

            try {
                $result = $this->db->exec($sqlQuery);
                echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            // обрабатываем ошибки
            return $result;
        }

        public function saveAll(array $object)
        {

            $class = new \ReflectionClass($this);
            $tableName = '';

            if ($this->tableName != '') {
                $tableName = $this->tableName;
            } else {
                $tableName = strtolower($class->getShortName());
            }

            $propsToImplode = [];
            $props = "(";
            $insertData = [];
            // собираем поля класса, которые мы будем использовать в запросе
            foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) { // собираем информацию только о полях класса которые имеют модификатор `public`
                $propertyName = $property->getName();
                $props .= $propertyName . ",";
                $insertData[] = $propertyName;
                $propsToImplode[] = '' . $propertyName . ' = "' . $this->{$propertyName} . '"';
            }

            $props = substr($props, 0, -1);

            $props .= ")";

            $sqw = "";

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

            //$sqw .= '("' . $value->firstName . '","' . $value->lastName . '","' . $value->email . '"),';

            $sqw = substr($sqw, 0, -1);

            //print_r($sqw);

            $setClause = implode(',', $propsToImplode); // записываем в наш генериреумый запрос все поля
            $sqlQuery = '';

            $sqlQuery = 'INSERT INTO ' . $tableName . " $props values " . $sqw . ';';

            //print_r($sqlQuery);
            print_r("\n");

            //print_r($insertData);

            try {
                $result = $this->db->exec($sqlQuery);
                echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            // обрабатываем ошибки
            //return $result;
        }


        public function saveAllQ(array $object)
        {

            $class = new \ReflectionClass($this);
            $tableName = '';

            if ($this->tableName != '') {
                $tableName = $this->tableName;
            } else {
                $tableName = strtolower($class->getShortName());
            }

            $propsToImplode = [];
            $props = "(";
            $insertData = [];
            // собираем поля класса, которые мы будем использовать в запросе
            foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) { // собираем информацию только о полях класса которые имеют модификатор `public`
                $propertyName = $property->getName();
                $props .= $propertyName . ",";
                $insertData[] = $propertyName;
                $propsToImplode[] = '' . $propertyName . ' = "' . $this->{$propertyName} . '"';
            }

            $props = substr($props, 0, -1);
            $props .= ")";
            $sqw = "";

            foreach ($object as $key => $value) {
                //$sqw .= "(";
                foreach ($insertData as $key => $insert) {
                    $sqw .= '' . $value->$insert . '';
                    if ($insert != end($insertData)) {
                        $sqw .= ",";
                    }
                }
                if ($value != end($object)){
                    $sqw .= " UNION ALL SELECT ";
                }
                //$sqw .= "),";
            }

            //$sqw = substr($sqw, 0, -1);

            $setClause = implode(',', $propsToImplode); // записываем в наш генериреумый запрос все поля
            $sqlQuery = '';

            $sqlQuery = 'INSERT INTO ' . $tableName . " $props (SELECT " . $sqw . ');';

            // print_r($sqlQuery);
            // print_r("\n");

            // print_r($insertData);

            try {
                $result = $this->db->exec($sqlQuery);
                echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            // обрабатываем ошибки
            //return $result;
        }


        public static function morph(array $object)
        {

            // создаем обект результирующего класса
            $class = new \ReflectionClass(get_called_class()); // этот метод статический, поэтому мы узнаем имя класса на котором он был вызван при помощи get_called_class 

            $entity = $class->newInstance();

            foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
                if (isset($object[$prop->getName()])) {
                    $prop->setValue($entity, $object[$prop->getName()]);
                }
            }

            $entity->initialize(); // магия инициализации обьекта

            return $entity;
        }

        public static function find($options = [])
        {
            include_once("../config/config.php");

            $result = [];
            $query = '';

            $whereClause = '';
            $whereConditions = [];

            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $whereConditions[] = '`' . $key . '` = "' . $value . '"';
                }
                $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            }

            $db = new PDO("mysql:host=$host;dbname=$database", $user, $password);

            $query = "select * from shops where name = 'a'";
            $raw = $db->query($query);

            // if ($db->errorCode()) {
            //     throw new \Exception($db->errorInfo()[2]);
            // }

            foreach ($raw as $rawRow) {
                $result[] = self::morph($rawRow);
            }

            return $result;
        }
}

?>