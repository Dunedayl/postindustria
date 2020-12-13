<?php
    namespace week3\lib;
    include_once("../helper/sqlHelper.php");

    class entityRepository
    {
        public function saveAll(array $object)
        {
            $tableName = $object[0]->getTableName();
            $insertData = $this->getPropertiesNames($object[0]);
            $propertiesForSqlInsert = "(" . implode(",", $insertData) . ")"; // names of values to insert
            $sqlData = $this->generateValuesToInsert($object);
            $sqlDataQuery = 'INSERT INTO ' . $tableName . " $propertiesForSqlInsert values " . $sqlData . ';';
            sqlHelper::execute($sqlDataQuery);
        }

        protected function generateValuesToInsert(array $object)
        {
            $sqlData = ""; // Data to insert 
            //Saving Data from each object to sql query 
            $fullValuesArray = [];
            foreach ($object as $obj) {
                $valuesArray = [];
                foreach ($obj as $key => $value) {
                    if (is_array($value)){
                        $valuesArray[] = '' . $value["Value"] . '';
                    } else{
                        $valuesArray[] = '"' . $value . '"';
                    }
                }
                $sqlData = "(" . implode(",", $valuesArray) . ")"; 
                $fullValuesArray[] = $sqlData;
            }
            return implode(",", $fullValuesArray);
        }

        protected function getPropertiesNames($class)
        {
            $insertData = [];
            foreach ($class as $key => $value) {
                $insertData[] = $key;
            }
            return $insertData;
        }

        public static function createTables(){

            $sql = [];
            $sql[] = "CREATE TABLE categories ( id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200) NOT NULL); ";
            $sql[] = "
            CREATE TABLE shops 
            (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(200) NOT NULL,
                domain VARCHAR(200) NOT NULL,
                index idx_dom (domain)
            );";
            $sql[] = "
                CREATE TABLE products 
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(400) NOT NULL,
                    index ids_prod (name)
                );";
            $sql[] = "
            CREATE TABLE users(
                id INT AUTO_INCREMENT PRIMARY KEY,
                firstName VARCHAR(200) NOT NULL,
                lastName VARCHAR(200) NOT NULL,
                email VARCHAR(200) NOT NULL,
                index ids_email (email)
            );";
            $sql[] = "
            CREATE TABLE productcategory (
                id INT AUTO_INCREMENT PRIMARY KEY,
                categoryId int NOT NULL,
                productId int NOT NULL,
                FOREIGN KEY (categoryId) REFERENCES categories(id),
                FOREIGN KEY (productId) REFERENCES products(id)
            );";
            $sql[] = "
                CREATE TABLE shopcategory(
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    categoryId int NOT NULL,
                    shopId int NOT NULL,
                    FOREIGN KEY (categoryId) REFERENCES categories(id),
                    FOREIGN KEY (shopId) REFERENCES shops(id)
            );";
            $sql[] = "
                CREATE TABLE orders (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    summa int NOT NULL,
                    order_date datetime NOT NULL,
                    userId int NOT NULL,
                    shopId int NOT NULL,
                    FOREIGN KEY (userId) REFERENCES users(id),
                    FOREIGN KEY (shopId) REFERENCES shops(id),
                    index ids_summa_date_id (summa,order_date,id)
            );";
            $sql[] = "
                CREATE TABLE productorder (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    orderId int NOT NULL,
                    productId int NOT NULL,
                    FOREIGN KEY (orderId) REFERENCES orders(id),
                    FOREIGN KEY (productId) REFERENCES products(id)
                    );";

            foreach ($sql as $value) {
                sqlHelper::execute($value);
            }
            
    }
}
?>