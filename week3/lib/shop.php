<?php

namespace week3\lib;

class Shops extends Entity
{
    protected $table = "shops";

    protected $id;
    public $name;
    public $domain;

    public static function findId($domain)
    {
        $data = "(SELECT id FROM shops where domain = '$domain')";
        return $data;
    }
}
