<?php
include_once("entyti.php");

class ShopCategory extends Entity
{
    protected $table = "shopcategory";

    protected $id;
    public $shopId;
    public $categoryId;


    public function createTable()
    {

        $sql = "
CREATE TABLE shopcategory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoryId int NOT NULL,
    shopId int NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id),
    FOREIGN KEY (shopId) REFERENCES shops(id)
);
";
        $this->execute($sql);

    }


    protected function generateValuesToInsert(array $object, $insertData)
    {
        $sqlData = "";
        foreach ($object as $key => $value) {
            $sqlData .= "(";
            foreach ($insertData as $key => $insert) {

                if ($insert == "shopId") {
                    $tempVal = $value->$insert;
                    $tempVal = addslashes($tempVal);
                    $temp = "(SELECT id FROM shops where domain = '$tempVal' )";
                    $sqlData .= '' . $temp . '';
                } elseif ($insert == "categoryId") {
                    $tempVal1 = $value->$insert;
                    $temp = "(SELECT id FROM categories where name = '$tempVal1' )";
                    $sqlData .= '' . $temp . '';
                } else {
                    $sqlData .= '"' . $value->$insert . '"';
                }

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
}
