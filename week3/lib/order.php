<?php
namespace week3\lib;

class Orders extends Entity
{
    protected static $table = "orders";
    protected $id;

    public $summa;
    public $order_date;
    public $userId;
    public $shopId;

    public static function findId($summa, $order_date, $userId)
    {
        $userId = $userId['Value'];
        return "(SELECT id FROM orders where summa = '$summa' and  order_date = '$order_date' and userId = $userId)";
    }
}
