<?php
    include_once ("entyti.php");
    
    class Products extends Entity
    {
        protected $table = "products";

        protected $id;
        public $name;

    public static function create()
    {
        include("../config/config.php");
        $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $sql = "
CREATE TABLE products 
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(400) NOT NULL,
  index ids_prod (name)

)
;      
";
        $req = $db->exec($sql);
    }

    }
