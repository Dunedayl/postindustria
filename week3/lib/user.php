<?php
    include_once ("entyti.php");
    
    class Users extends Entity
    {
        protected $table = "users";

        protected $id;
        public $firstName;
        public $lastName;
        public $email;


    public static function create()
    {
        include("../config/config.php");
        $db =  new PDO("mysql:host=$host;dbname=$database", $user, $password);
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
        $req = $db->exec($sql);
    }

    }



