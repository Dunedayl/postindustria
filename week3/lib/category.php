<?php
namespace week3\lib;

class Categories extends Entity
{
    protected static $table = "categories";

    protected $id;
    public $name;

    public static function findId($name)
    {
        $data = "(SELECT id FROM categories where name = '$name')";
        return $data;
    }
}