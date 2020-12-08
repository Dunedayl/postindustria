<?php
include_once("entyti.php");

class Products extends Entity
{
    protected $table = "products";

    protected $id;
    public $name;

    public function createTable()
    {
        $sql = "
      CREATE TABLE products 
      (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(400) NOT NULL,
        index ids_prod (name)
      )
      ;
      ";
      $this->execute($sql);
    }
}
