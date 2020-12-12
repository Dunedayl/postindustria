<?php

namespace week3\lib;

class ProductOrder extends Entity
{
    protected $table = "productOrder";

    protected $id;
    public $orderId;
    public $productId;

}
