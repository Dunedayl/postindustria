<?php
include_once("entyti.php");


class Orders extends Entity
{
    protected static $table = "orders";
    protected $id;

    public $summa;
    public $order_date;
    public $userId;
    public $shopId;


    public static function create()
    {
        include("../config/config.php");
        $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $sql = "
            CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    summa int NOT NULL,
    order_date datetime NOT NULL,
    userId int NOT NULL,
    shopId int NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (shopId) REFERENCES shops(id),
    index ids_summa_date_id (summa,order_date,id)
);
            ";
        $req = $db->exec($sql);
    }


    public function getName($var)
    {
        foreach ($GLOBALS as $varName => $value) {
            if ($value === $var) {
                return $varName;
            }
        }
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
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
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

                if ($insert == "userId") {
                    $tempVal = $value->$insert;
                    $temp = "(SELECT id FROM users where email = '$tempVal' )";
                    $sqw .= '' . $temp . '';
                } elseif ($insert == "shopId") {
                    $tempVal = $value->$insert;
                    $temp = "(SELECT id FROM shops where domain = '$tempVal' )";
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


        $setClause = implode(',', $propsToImplode);
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
