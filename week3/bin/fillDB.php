<?php
include_once ("../lib/user.php");
include_once ("../lib/shop.php");
include_once ("../lib/order.php");
include_once ("../lib/product.php");
include_once ("../lib/category.php");
include_once ("../config/config.php");
include_once ("../lib/prodOrd.php");
include_once ("../lib/productCat.php");
include_once ("../lib/shopCategory.php");

$temp = file_get_contents("purchase_log.json");
$json = explode("\n", $temp);

$result = [];
//Getting data from JSONs
foreach ($json as $key => &$jvalue) {
    $result[] =  json_decode($jvalue, true);
}

$shopp = new Shops(true);
$userr = new Users(true);
$prodd = new Products(true);
$catt = new Categories(true);
$orderr = new Orders(true);
$prodcatt = new ProductCategory(true);
$shCatt = new ShopCategory(true);
$prodOrdd = new ProductOrder(true);


// Creating Database tables
$shopp->createTable();
$userr->createTable();
$prodd->createTable();
$catt->createTable();
$orderr->createTable();
$prodOrdd->createTable();
$prodcatt->createTable();
$shCatt->createTable();


// Arrays for storing TEMP data
$shops = [];
$users = [];
$orders = [];
$products = [];
$categories = [];
$shopCategory = [];
$productsNames = [];

//Parsing JSSONs for data
foreach ($result as $key => &$rvalue) {

    $shops[] = ["Name" => $rvalue["shop_name"], "Domain" => $rvalue["shop_domain"]];
    $users[] = ["FirstName" => $rvalue["user_first_name"], "LastName" => $rvalue["user_last_name"], "Email" => $rvalue["user_email"]];
    $orders[] = ["Sum" => $rvalue["sum"], "Date" => $rvalue["date"]];

    foreach ($rvalue["products"] as $key => &$prod) {
        $products[] = ["Name" => $prod["name"], "Category" => $prod["product_categories"]];
        $productsNames[] = ["Name" => $prod["name"]];
        $prod["product_categories"] = explode(",", $prod["product_categories"]);

        foreach ($prod["product_categories"] as $key => $valueCategory) {
            $shopCategory[] = ["ShopDomain" => $rvalue["shop_domain"], "Category" => $valueCategory];
        }
    }
}

//Getting uniq filds 
$uniqShopCategory = array_unique($shopCategory, SORT_REGULAR);
$uniqShop = array_unique($shops, SORT_REGULAR);
$uniqUsers = array_unique($users, SORT_REGULAR);
$uniqProducts = array_unique($products, SORT_REGULAR);

foreach ($uniqProducts as $key => &$upvalue) {
    $temp = explode(",", $upvalue["Category"]);
    $categories = array_merge($categories, $temp);
    $upvalue["Category"] = $temp;
}

$uniqProductCategoryPairs = $uniqProducts;
$uniqProductsNames = array_unique($productsNames, SORT_REGULAR);
$uniqCategories = array_unique($categories, SORT_REGULAR);

$addedCat = [];

foreach ($uniqProductsNames as $key => $value) {
    $addedCat[$value["Name"]] = [];
}


//Inserting all Shops in DB
$insertShops =[];
foreach ($uniqShop as $key => $value) {
    $shop = new Shops(false);
    $shop->name = $value["Name"];
    $shop->domain = $value["Domain"];
    $insertShops[] = $shop;
}
$shopp->saveAll($insertShops);

//Insert all Users in DB
$insertUsers = [];
foreach ($uniqUsers as $key => $value) {
    $user = new Users(false);
    $user->firstName = $value["FirstName"];
    $user->lastName = $value["LastName"];
    $user->email = $value["Email"];
    $insertUsers[] = $user;
}
$userr->saveAll($insertUsers);

//Insert all Products in DB
$insertProducts = [];
foreach ($uniqProductsNames as $key => $value) {
    $product = new Products(false);
    $product->name = $value["Name"];
    $insertProducts[] = $product;
}
$prodd->saveAll($insertProducts);

//Insert all Category in DB
$insertCategories = [];
foreach ($uniqCategories as $key => $value) {
    $cat = new Categories(false);
    $cat->name = $value;
    $insertCategories[] = $cat;
}
$catt->saveAll($insertCategories);

//Insert all Categoty which each shop can have in DB
$insertShopCategory = [];
foreach ($uniqShopCategory as $key => $vv) {
    $shCat = new ShopCategory(false);
    $shCat->shopId = $vv["ShopDomain"];
    $shCat->categoryId = $vv["Category"];
    $insertShopCategory[] = $shCat;
}
$shCatt->saveAll($insertShopCategory);

//Insert all Product Category pair in DB
$insertProdCat = [];
foreach ($uniqProductCategoryPairs as $key => $value) {
    foreach ($value["Category"] as $key => $ss) {
        if (!in_array($ss, $addedCat[$value["Name"]])){
            $addedCat[$value["Name"]][] = $ss;
            $prodcat = new ProductCategory(false);
            $prodcat->productId = $value["Name"];
            $prodcat->categoryId = $ss;
            $insertProdCat[] = $prodcat;
        }
    }
}
$prodcatt->saveAll($insertProdCat);


//Insert all orders in chunks by 5000
$i = 0;
$insertOrders = [];
foreach ($result as $key => $value) {
    $order = new Orders(false);
    $order->summa = $value["sum"];
    $order->order_date = $value["date"];
    $order->shopId = $value["shop_domain"];
    $order->userId = $value["user_email"];
    $insertOrders[] = $order;
    $i++;
    if ($i == 5000){
        $orderr->saveAll($insertOrders);
        $insertOrders = [];
        $i = 0;
    }
}
$orderr->saveAll($insertOrders);

//Insert all products inside Orders in chunks by 5000
$i = 0;
$insertProdOrder = [];
foreach ($result as $key => $value) {
    foreach ($value["products"] as $key => $val) {
        $prodOrd = new ProductOrder(false);
        $prodOrd->productId = $val["name"];
        $prodOrd->orderId = ["summa" =>  $value["sum"], "order_date" => $value["date"], "email" => $value["user_email"]];
        $insertProdOrder[] = $prodOrd;
    }
    $i++;
    if ($i == 5000) {
        $prodOrdd->saveAll($insertProdOrder);
        $insertProdOrder = [];
        $i = 0;
    }
}
$prodOrdd->saveAll($insertProdOrder);
