<?php
    include_once("entyti.php");

    class Categories extends Entity
    {
        protected static $table = "categories";

        protected $id;
        public $name;

        public function createTable(){
            $sql = "CREATE TABLE categories ( id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200) NOT NULL); ";
            $this->execute($sql);
        }
    }
