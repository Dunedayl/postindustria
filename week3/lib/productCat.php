<?php

namespace week3\lib;

class ProductCategory extends Entity
{
    protected $table = "productcategory";

    protected $id;
    public $productId;
    public $categoryId;
}
