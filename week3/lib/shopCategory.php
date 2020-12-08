<?php
include_once("entyti.php");

class ShopCategory extends Entity
{
    protected $table = "shopcategory";

    protected $id;
    public $shopId;
    public $categoryId;


    public static function create()
    {
        include("../config/config.php");
        $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $sql = "
CREATE TABLE shopcategory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoryId int NOT NULL,
    shopId int NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id),
    FOREIGN KEY (shopId) REFERENCES shops(id)
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

                if ($insert == "shopId") {
                    $tempVal = $value->$insert;
                    $tempVal = addslashes($tempVal);
                    $temp = "(SELECT id FROM shops where domain = '$tempVal' )";
                    $sqw .= '' . $temp . '';
                } elseif ($insert == "categoryId") {
                    $tempVal1 = $value->$insert;
                    $temp = "(SELECT id FROM categories where name = '$tempVal1' )";
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
