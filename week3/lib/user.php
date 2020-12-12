<?php

namespace week3\lib;
include("entyti.php");

class Users extends Entity
{
    protected $table = "users";

    protected $id;
    public $firstName;
    public $lastName;
    public $email;

    public static function findId($email)
    {
        return "(SELECT id FROM users where email = '$email')";
    }
}
