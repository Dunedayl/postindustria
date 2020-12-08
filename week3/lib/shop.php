<?php
include_once("entyti.php");

class Shops extends Entity
{
    protected $table = "shops";

    protected $id;
    public $name;
    public $domain;


    public function createTable()
    {
        $sql = "
    CREATE TABLE shops 
    (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(200) NOT NULL,
      domain VARCHAR(200) NOT NULL,
      index idx_dom (domain)
    );
";
        $this->execute($sql);
    }
}
