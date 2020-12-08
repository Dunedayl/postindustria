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


    public function createTable()
    {
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
        $this->execute($sql);
    }


    protected function generateValuesToInsert(array $object, $insertData)
    {
        $sqlData = "";
        foreach ($object as $key => $value) {
            $sqlData .= "(";
            foreach ($insertData as $key => $insert) {
                // Replace filds with select from another table 
                if ($insert == "userId") {
                    $tempVal = $value->$insert;
                    $temp = "(SELECT id FROM users where email = '$tempVal' )";
                    $sqlData .= '' . $temp . '';
                } elseif ($insert == "shopId") {
                    $tempVal = $value->$insert;
                    $temp = "(SELECT id FROM shops where domain = '$tempVal' )";
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
