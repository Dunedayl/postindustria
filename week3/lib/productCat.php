<?php
include_once("entyti.php");

class ProductCategory extends Entity
{
    protected $table = "productcategory";

    protected $id;
    public $productId;
    public $categoryId;


    public function createTable()
    {

        $sql = "
CREATE TABLE productcategory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoryId int NOT NULL,
    productId int NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id),
    FOREIGN KEY (productId) REFERENCES products(id)
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

                if ($insert == "productId") {
                    $tempVal = $value->$insert;
                    $tempVal = addslashes($tempVal);
                    $temp = "(SELECT id FROM products where name = '$tempVal' )";
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
