<?php
include_once("entyti.php");

class ProductOrder extends Entity
{
    protected $table = "productOrder";

    protected $id;
    public $orderId;
    public $productId;

    public function createTable()
    {
        $sql = "
        CREATE TABLE productorder (
  id INT AUTO_INCREMENT PRIMARY KEY,
  orderId int NOT NULL,
  productId int NOT NULL,
  FOREIGN KEY (orderId) REFERENCES orders(id),
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
                } elseif ($insert == "orderId") {
                    $tempVal1 = $value->$insert["summa"];
                    $tempVal2 = $value->$insert["order_date"];
                    $tempVal3 = $value->$insert["email"];
                    $temp = "(SELECT id FROM orders where summa = '$tempVal1' and  order_date = '$tempVal2' and userId = (SELECT id from users where email = '$tempVal3'))";
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
