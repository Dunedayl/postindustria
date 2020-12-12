<?php

namespace week3\lib;

class Products extends Entity
{
    protected $table = "products";

    protected $id;
    public $name;

    public static function findId($name)
    {
        return "(SELECT id FROM products where name = '$name' )";
    }
}
