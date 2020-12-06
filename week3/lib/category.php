<?php
    include_once("entyti.php");

    class Categories extends Entity
    {
        protected static $table = "categories";

        protected $id;
        public $name;

        public static function create(){
            include("../config/config.php");
            $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $sql = "CREATE TABLE categories ( id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200) NOT NULL); ";
            $req = $db->exec($sql);
        }
    }
