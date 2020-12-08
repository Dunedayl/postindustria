<?php
include_once("entyti.php");

class Users extends Entity
{
    protected $table = "users";

    protected $id;
    public $firstName;
    public $lastName;
    public $email;


    public function createTable()
    {

        $sql = "
CREATE TABLE users 
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstName VARCHAR(200) NOT NULL,
  lastName VARCHAR(200) NOT NULL,
  email VARCHAR(200) NOT NULL,
index ids_email (email)

)
;

";
        $this->execute($sql);
    }
}
