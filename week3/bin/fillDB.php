<?php

include_once ("../lib/user.php");
include_once ("../lib/shop.php");
include_once ("../lib/order.php");
include_once ("../lib/product.php");
include_once ("../lib/category.php");
include_once ("../config/config.php");
include_once ("../lib/prodOrd.php");
include_once ("../lib/orderCat.php");
include_once ("../lib/productCat.php");
include_once ("../lib/shopCategory.php");

$temp = file_get_contents("purchase_log.json");
$json = explode("\n", $temp);

$result = [];
//Getting data from JSONs
foreach ($json as $key => &$jvalue) {
    $result[] =  json_decode($jvalue, true);
}
// Creating Database tables
Users::create();
Categories::create();
Products::create();
Shops::create();
Orders::create();
ProductOrder::create();
ProductCategory::create();
ShopCategory::create();

// Arrays for storing TEMP data
$shops = [];
$users = [];
$orders = [];
$products = [];
$productsClear = [];
$uproducts = [];
$categories = [];
$shopCat = [];

//Parsing JSSONs for data
foreach ($result as $key => &$rvalue) {

    $shops[] = ["Name" => $rvalue["shop_name"], "Domain" => $rvalue["shop_domain"]];
    $users[] = ["FirstName" => $rvalue["user_first_name"], "LastName" => $rvalue["user_last_name"], "Email" => $rvalue["user_email"]];
    $orders[] = ["Sum" => $rvalue["sum"], "Date" => $rvalue["date"]];

    foreach ($rvalue["products"] as $key => &$prod) {
        $uproducts[] = ["Name" => $prod["name"]];
        $products[] = ["Name" => $prod["name"], "Category" => $prod["product_categories"]];
        $productsClear[] = ["Name" => $prod["name"]];
        $prod["product_categories"] = explode(",", $prod["product_categories"]);

        foreach ($prod["product_categories"] as $key => $prods) {
            $shopCat[] = ["ShopDomain" => $rvalue["shop_domain"], "Category" => $prods];
        }
    }
}

//Getting uniq filds 
$uShopCat = array_unique($shopCat, SORT_REGULAR);
$uniqShop = array_unique($shops, SORT_REGULAR);
$uniqUsers = array_unique($users, SORT_REGULAR);
$uniqProducts = array_unique($products, SORT_REGULAR);

foreach ($uniqProducts as $key => &$upvalue) {
    $temp = explode(",", $upvalue["Category"]);
    $categories = array_merge($categories, $temp);
    $upvalue["Category"] = $temp;
}

$uProdClear = $uniqProducts;
$uniqProducts = array_unique($productsClear, SORT_REGULAR);
$uniqCategories = array_unique($categories, SORT_REGULAR);

$addedCat = [];

foreach ($uniqProducts as $key => $value) {
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
$shopp = new Shops(true);
$shopp->start();
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
$userr = new Users(true);
$userr->start();
$userr->saveAll($insertUsers);

//Insert all Products in DB
$insertProducts = [];
foreach ($uniqProducts as $key => $value) {
    $product = new Products(false);
    $product->name = $value["Name"];
    $insertProducts[] = $product;
}
$prodd = new Products(true);
$prodd->start();
$prodd->saveAll($insertProducts);

//Insert all Category in DB
$insertCategories = [];
foreach ($uniqCategories as $key => $value) {
    $cat = new Categories(false);
    $cat->name = $value;
    $insertCategories[] = $cat;
}
$catt = new Categories(true);
$catt->start();
$catt->saveAll($insertCategories);

//Insert all Categoty witch each shop can have in DB
$insertShopCategory = [];
foreach ($uShopCat as $key => $vv) {
    $shCat = new ShopCategory(false);
    $shCat->shopId = $vv["ShopDomain"];
    $shCat->categoryId = $vv["Category"];
    $insertShopCategory[] = $shCat;
}
$shCatt = new ShopCategory(true);
$shCatt->start();
$shCatt->saveAllQ($insertShopCategory);

//Insert all Product Category pair in DB
$insertProdCat = [];
foreach ($uProdClear as $key => $value) {
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
$prodcatt = new ProductCategory(true);
$prodcatt->start();
$prodcatt->saveAllQ($insertProdCat);


//Insert all orders in chunks bt 1000
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
    if ($i == 1000){
        $order = new Orders(true);
        $order->start();
        $order->saveAllQ($insertOrders);
        $insertOrders = [];
        $i = 0;
    }
}
$order = new Orders(true);
$order->start();
$order->saveAllQ($insertOrders);

//Insert all products inside Orders
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
    if ($i == 1000) {
        $prodOrdd = new ProductOrder(true);
        $prodOrdd->start();
        $prodOrdd->saveAllQ($insertProdOrder);
        $insertProdOrder = [];
        $i =0;
    }
}
$prodOrdd = new ProductOrder(true);
$prodOrdd->start();
$prodOrdd->saveAllQ($insertProdOrder);


?>