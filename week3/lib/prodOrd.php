<?php
    include_once("entyti.php");

    class ProductOrder extends Entity
    {
        protected $table = "productOrder";

        protected $id;
        public $orderId;
        public $productId;

    public static function create()
    {
        include("../config/config.php");
        $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $sql = "
        CREATE TABLE productorder (
  id INT AUTO_INCREMENT PRIMARY KEY,
  orderId int NOT NULL,
  productId int NOT NULL,
  FOREIGN KEY (orderId) REFERENCES orders(id),
  FOREIGN KEY (productId) REFERENCES products(id)
);
        ";
        $req = $db->exec($sql);
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
                $sqw .= "(";
                foreach ($insertData as $key => $insert) {

                    if ($insert == "productId") {
                        $tempVal = $value->$insert;
                        $tempVal = addslashes($tempVal);
                        $temp = "(SELECT id FROM products where name = '$tempVal' )";
                        $sqw .= '' . $temp . '';
                    } elseif ($insert == "orderId") {
                        $tempVal1 = $value->$insert["summa"];
                        $tempVal2 = $value->$insert["order_date"];
                        $tempVal3 = $value->$insert["email"];
                        $temp = "(SELECT id FROM orders where summa = '$tempVal1' and  order_date = '$tempVal2' and userId = (SELECT id from users where email = '$tempVal3'))";
                        $sqw .= '' . $temp . '';
                    } else {
                        $sqw .= '"' . $value->$insert . '"';
                    }

                    if ($insert != end($insertData)) {
                        $sqw .= ",";
                    }
                }
                $sqw .= "),";
            }

            $sqw = substr($sqw, 0, -1);


            $setClause = implode(',', $propsToImplode); // записываем в наш генериреумый запрос все поля
            $sqlQuery = '';

            $sqlQuery = 'INSERT INTO ' . $tableName . " $props VALUES " . $sqw . ';';
            print_r("\n");
            print_r($sqlQuery);
            print_r("\n");

            try {
                $result = $this->db->exec($sqlQuery);
                echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

    }
