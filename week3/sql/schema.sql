CREATE TABLE categories ( id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200) NOT NULL);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    summa int NOT NULL,
    order_date datetime NOT NULL,
    userId int NOT NULL,
    shopId int NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (shopId) REFERENCES shops(id),
    index ids_summa_date_id (summa,order_date,id)
);

CREATE TABLE productorder (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderId int NOT NULL,
    productId int NOT NULL,
    FOREIGN KEY (orderId) REFERENCES orders(id),
    FOREIGN KEY (productId) REFERENCES products(id)
);

CREATE TABLE products 
    (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(400) NOT NULL,
    index ids_prod (name)
    )
;

CREATE TABLE productcategory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoryId int NOT NULL,
    productId int NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id),
    FOREIGN KEY (productId) REFERENCES products(id)
);

CREATE TABLE shops 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    domain VARCHAR(200) NOT NULL,
    index idx_dom (domain)
);

CREATE TABLE shopcategory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoryId int NOT NULL,
    shopId int NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id),
    FOREIGN KEY (shopId) REFERENCES shops(id)
);

CREATE TABLE users 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(200) NOT NULL,
    lastName VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    index ids_email (email)
)
;