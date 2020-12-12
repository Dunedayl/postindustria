<?php

namespace week3\lib;

class ShopCategory extends Entity
{
    protected $table = "shopcategory";

    protected $id;
    public $shopId;
    public $categoryId;

}
