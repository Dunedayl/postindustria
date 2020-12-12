<?php 
    namespace week3\lib;
    use week3\config;

    class sqlHelper
    {
        public static function execute($sqlDataQuery)
        {
            $config = include("../config/config.php");
            $host = $config['host'];
            $database = $config['database'];
            $user = $config['user'];
            $password = $config['password'];

            try {
                $db = new \PDO("mysql:host=$host;dbname=$database", $user, $password);
            } catch (\Exception $e) {
                throw new \Exception('Error creating a database connection ');
            }

            try {
                $result = $db->exec($sqlDataQuery);
                print_r("Succes\n");
            } catch (\PDOException $e) {
                print_r($sqlDataQuery);
                echo "\nConnection failed: " . $e->getMessage();
            }
        }
    }
?>