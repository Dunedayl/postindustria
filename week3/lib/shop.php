<?php
include_once("entyti.php");

class Shops extends Entity
{
    protected $table = "shops";

    protected $id;
    public $name;
    public $domain;


    public static function create()
    {
    include("../config/config.php");
        $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $sql = "
    CREATE TABLE shops 
    (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(200) NOT NULL,
      domain VARCHAR(200) NOT NULL,
      index idx_dom (domain)
    );
";
        $req = $db->exec($sql);
    }
}
