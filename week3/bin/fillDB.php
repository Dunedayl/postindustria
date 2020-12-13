<?php

use week3\lib\Orders;
use week3\lib\ProductOrder;
use week3\lib\Users;
use week3\lib\ShopCategory;
use week3\lib\ProductCategory;
use week3\lib\Products;
use week3\lib\Shops;
use week3\lib\Categories;
use week3\lib\entityRepository;
use week3\lib\sqlHelper;

include_once("../lib/user.php");
include_once("../lib/shop.php");
include_once("../lib/order.php");
include_once("../lib/product.php");
include_once("../lib/category.php");
include_once("../config/config.php");
include_once("../lib/prodOrd.php");
include_once("../lib/productCat.php");
include_once("../lib/shopCategory.php");
include_once("../lib/entityRepository.php");
include_once("../lib/sqlHelper.php");


class fillDb
{
    //Array of pased data
    public $shops = [];
    public $users = [];
    public $orders = [];
    public $products = [];
    public $categories = [];
    public $shopCategory = [];
    public $productsNames = [];

    //Classes
    public $Repository;

    //Uniq data 
    public  $uniqShopCategory;
    public  $uniqShop;
    public  $uniqUsers;
    public  $uniqProducts;
    public  $uniqProductCategoryPairs;
    public  $uniqProductsNames;
    public  $uniqCategories;
    public  $addedCat;

    //Raw parsed JSONs
    public $result = [];

    public function __construct($fileName)
    {
        $temp = file_get_contents($fileName);
        $json = explode("\n", $temp);

        //Getting data from JSONs
        foreach ($json as $key => &$jvalue) {
            $this->result[] =  json_decode($jvalue, true);
        }

        $this->Repository = new entityRepository();

        $this->parseData();
        $this->findUniqData();
        entityRepository::createTables();
        $this->insertShops();
        $this->insertUsers();
        $this->insertProducts();
        $this->insertCategories();
        $this->insertShopCategory();
        $this->insertProductCategory();
        $this->insertOrders();
        $this->insertProductsOrder();
    }


    public function parseData()
    {

        foreach ($this->result as $key => &$rvalue) {

            $this->shops[] = ["Name" => addslashes($rvalue["shop_name"]), "Domain" => addslashes($rvalue["shop_domain"])];
            $this->users[] = ["FirstName" => addslashes($rvalue["user_first_name"]), "LastName" => addslashes($rvalue["user_last_name"]), "Email" => addslashes($rvalue["user_email"])];
            $this->orders[] = ["Sum" => $rvalue["sum"], "Date" => $rvalue["date"]];

            foreach ($rvalue["products"] as $key => &$prod) {
                $this->products[] = ["Name" => addslashes($prod["name"]), "Category" => addslashes($prod["product_categories"])];
                $this->productsNames[] = ["Name" => addslashes($prod["name"])];
                $prod["product_categories"] = explode(",", $prod["product_categories"]);

                foreach ($prod["product_categories"] as $key => $valueCategory) {
                    $this->shopCategory[] = ["ShopDomain" => $rvalue["shop_domain"], "Category" => addslashes($valueCategory)];
                }
            }
        }
    }


    public function findUniqData()
    {
        $this->uniqShopCategory = array_unique($this->shopCategory, SORT_REGULAR);
        $this->uniqShop = array_unique($this->shops, SORT_REGULAR);
        $this->uniqUsers = array_unique($this->users, SORT_REGULAR);
        $this->uniqProducts = array_unique($this->products, SORT_REGULAR);

        foreach ($this->uniqProducts as $key => &$upvalue) {
            $temp = explode(",", $upvalue["Category"]);
            $this->categories = array_merge($this->categories, $temp);
            $upvalue["Category"] = $temp;
        }

        $this->uniqProductCategoryPairs = $this->uniqProducts;
        $this->uniqProductsNames = array_unique($this->productsNames, SORT_REGULAR);
        $this->uniqCategories = array_unique($this->categories, SORT_REGULAR);
        $this->addedCat = [];

        foreach ($this->uniqProductsNames as $key => $value) {
            $this->addedCat[$value["Name"]] = [];
        }
    }

    public function insertShops()
    {
        //Inserting all Shops in DB
        $insertShops = [];
        foreach ($this->uniqShop as $key => $value) {
            $shop = new Shops();
            $shop->name = $value["Name"];
            $shop->domain = $value["Domain"];
            $insertShops[] = $shop;
        }
        $this->Repository->saveAll($insertShops);
    }


    public function insertUsers()
    {
        $insertUsers = [];
        foreach ($this->uniqUsers as $key => $value) {
            $user = new Users();
            $user->firstName = $value["FirstName"];
            $user->lastName = $value["LastName"];
            $user->email = $value["Email"];
            $insertUsers[] = $user;
        }
        $this->Repository->saveAll($insertUsers);
    }


    public function insertProducts()
    {
        $insertProducts = [];
        foreach ($this->uniqProductsNames as $key => $value) {
            $product = new Products();
            $product->name = $value["Name"];
            $insertProducts[] = $product;
        }
        $this->Repository->saveAll($insertProducts);
    }


    public function insertCategories()
    {
        $insertCategories = [];
        foreach ($this->uniqCategories as $key => $value) {
            $cat = new Categories();
            $cat->name = $value;
            $insertCategories[] = $cat;
        }
        $this->Repository->saveAll($insertCategories);
    }


    public function insertShopCategory()
    {
        //Insert all Categoty which each shop can have in DB
        $insertShopCategory = [];
        foreach ($this->uniqShopCategory as $key => $vv) {
            $shCat = new ShopCategory();
            $shCat->shopId = ["Value" => Shops::findId($vv["ShopDomain"])];
            $shCat->categoryId = ["Value" => Categories::findId($vv["Category"])];
            $insertShopCategory[] = $shCat;
        }
        $this->Repository->saveAll($insertShopCategory);
    }


    public function insertProductCategory()
    {
        //Insert all Product Category pair in DB
        $insertProdCat = [];
        foreach ($this->uniqProductCategoryPairs as $key => $value) {
            foreach ($value["Category"] as $key => $ss) {
                if (!in_array($ss, $this->addedCat[$value["Name"]])) {
                    $addedCat[$value["Name"]][] = $ss;
                    $prodcat = new ProductCategory();
                    //$prodcat->productId = $value["Name"];
                    //$prodcat->categoryId = $ss;
                    $prodcat->productId = ["Value" => Products::findId($value["Name"])];
                    $prodcat->categoryId = ["Value" => Categories::findId($ss)];
                    $insertProdCat[] = $prodcat;
                }
            }
        }
        $this->Repository->saveAll($insertProdCat);
    }


    public function insertOrders()
    {
        //Insert all orders in chunks by 5000
        $i = 0;
        $insertOrders = [];
        foreach ($this->result as $key => $value) {
            $order = new Orders();
            $order->summa = $value["sum"];
            $order->order_date = $value["date"];
            $order->shopId = ["Value" => Shops::findId($value["shop_domain"])];
            $order->userId = ["Value" => Users::findId($value["user_email"])];
            $insertOrders[] = $order;
            $i++;
            if ($i == 1000) {
                $this->Repository->saveAll($insertOrders);
                $insertOrders = [];
                $i = 0;
            }
        }
        $this->Repository->saveAll($insertOrders);
    }


    public function insertProductsOrder()
    {
        //Insert all products inside Orders in chunks by 5000
        $i = 0;
        $insertProdOrder = [];
        foreach ($this->result as $key => $value) {
            foreach ($value["products"] as $key => $val) {
                $prodOrd = new ProductOrder();
                $prodOrd->productId = ["Value" => Products::findId(addslashes($val["name"]))];
                $usersId = ["Value" => Users::findId($value["user_email"])];
                $prodOrd->orderId = ["Value" => Orders::findId($value["sum"], $value["date"],  $usersId)];
                $insertProdOrder[] = $prodOrd;
            }
            $i++;
            if ($i == 1000) {
                $this->Repository->saveAll($insertProdOrder);
                $insertProdOrder = [];
                $i = 0;
            }
        }
        $this->Repository->saveAll($insertProdOrder);
    }
}


$fillDb = new fillDb("purchase_log.json");