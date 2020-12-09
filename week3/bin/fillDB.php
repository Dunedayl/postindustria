<?php
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
    public $shopp;
    public $userr;
    public $prodd;
    public $catt;
    public $orderr;
    public $prodcatt;
    public $shCatt;
    public $prodOrdd;

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


        $this->parseData();
        $this->findUniqData();
        $this->initClasses();
        $this->createAllTables();
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

            $this->shops[] = ["Name" => $rvalue["shop_name"], "Domain" => $rvalue["shop_domain"]];
            $this->users[] = ["FirstName" => $rvalue["user_first_name"], "LastName" => $rvalue["user_last_name"], "Email" => $rvalue["user_email"]];
            $this->orders[] = ["Sum" => $rvalue["sum"], "Date" => $rvalue["date"]];

            foreach ($rvalue["products"] as $key => &$prod) {
                $this->products[] = ["Name" => $prod["name"], "Category" => $prod["product_categories"]];
                $this->productsNames[] = ["Name" => $prod["name"]];
                $prod["product_categories"] = explode(",", $prod["product_categories"]);

                foreach ($prod["product_categories"] as $key => $valueCategory) {
                    $this->shopCategory[] = ["ShopDomain" => $rvalue["shop_domain"], "Category" => $valueCategory];
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

    public function initClasses()
    {

        include_once("../lib/user.php");
        include_once("../lib/shop.php");
        include_once("../lib/order.php");
        include_once("../lib/product.php");
        include_once("../lib/category.php");
        include_once("../config/config.php");
        include_once("../lib/prodOrd.php");
        include_once("../lib/productCat.php");
        include_once("../lib/shopCategory.php");

        $this->shopp = new Shops(true);
        $this->userr = new Users(true);
        $this->prodd = new Products(true);
        $this->catt = new Categories(true);
        $this->orderr = new Orders(true);
        $this->prodcatt = new ProductCategory(true);
        $this->shCatt = new ShopCategory(true);
        $this->prodOrdd = new ProductOrder(true);
    }

    public function createAllTables()
    {
        $this->userr->createTable();
        $this->shopp->createTable();
        $this->prodd->createTable();
        $this->catt->createTable();
        $this->orderr->createTable();
        $this->prodOrdd->createTable();
        $this->prodcatt->createTable();
        $this->shCatt->createTable();
    }

    public function insertShops()
    {
        //Inserting all Shops in DB
        $insertShops = [];
        foreach ($this->uniqShop as $key => $value) {
            $shop = new Shops(false);
            $shop->name = $value["Name"];
            $shop->domain = $value["Domain"];
            $insertShops[] = $shop;
        }
        $this->shopp->saveAll($insertShops);
    }
    public function insertUsers()
    {
        $insertUsers = [];
        foreach ($this->uniqUsers as $key => $value) {
            $user = new Users(false);
            $user->firstName = $value["FirstName"];
            $user->lastName = $value["LastName"];
            $user->email = $value["Email"];
            $insertUsers[] = $user;
        }
        $this->userr->saveAll($insertUsers);
    }
    public function insertProducts()
    {
        $insertProducts = [];
        foreach ($this->uniqProductsNames as $key => $value) {
            $product = new Products(false);
            $product->name = $value["Name"];
            $insertProducts[] = $product;
        }
        $this->prodd->saveAll($insertProducts);

    }
    public function insertCategories()
    {
        $insertCategories = [];
        foreach ($this->uniqCategories as $key => $value) {
            $cat = new Categories(false);
            $cat->name = $value;
            $insertCategories[] = $cat;
        }
        $this->catt->saveAll($insertCategories);

    }
    public function insertShopCategory()
    {
        //Insert all Categoty which each shop can have in DB
        $insertShopCategory = [];
        foreach ($this->uniqShopCategory as $key => $vv) {
            $shCat = new ShopCategory(false);
            $shCat->shopId = $vv["ShopDomain"];
            $shCat->categoryId = $vv["Category"];
            $insertShopCategory[] = $shCat;
        }
        $this->shCatt->saveAll($insertShopCategory);

    }
    public function insertProductCategory()
    {
        //Insert all Product Category pair in DB
        $insertProdCat = [];
        foreach ($this->uniqProductCategoryPairs as $key => $value) {
            foreach ($value["Category"] as $key => $ss) {
                if (!in_array($ss, $this->addedCat[$value["Name"]])) {
                    $addedCat[$value["Name"]][] = $ss;
                    $prodcat = new ProductCategory(false);
                    $prodcat->productId = $value["Name"];
                    $prodcat->categoryId = $ss;
                    $insertProdCat[] = $prodcat;
                }
            }
        }
        $this->prodcatt->saveAll($insertProdCat);

    }
    public function insertOrders()
    {
        //Insert all orders in chunks by 5000
        $i = 0;
        $insertOrders = [];
        foreach ($this->result as $key => $value) {
            $order = new Orders(false);
            $order->summa = $value["sum"];
            $order->order_date = $value["date"];
            $order->shopId = $value["shop_domain"];
            $order->userId = $value["user_email"];
            $insertOrders[] = $order;
            $i++;
            if ($i == 5000) {
                $this->orderr->saveAll($insertOrders);
                $insertOrders = [];
                $i = 0;
            }
        }
        $this->orderr->saveAll($insertOrders);
    }
    public function insertProductsOrder()
    {

        //Insert all products inside Orders in chunks by 5000
        $i = 0;
        $insertProdOrder = [];
        foreach ($this->result as $key => $value) {
            foreach ($value["products"] as $key => $val) {
                $prodOrd = new ProductOrder(false);
                $prodOrd->productId = $val["name"];
                $prodOrd->orderId = ["summa" =>  $value["sum"], "order_date" => $value["date"], "email" => $value["user_email"]];
                $insertProdOrder[] = $prodOrd;
            }
            $i++;
            if ($i == 5000) {
                $this->prodOrdd->saveAll($insertProdOrder);
                $insertProdOrder = [];
                $i = 0;
            }
        }
        $this->prodOrdd->saveAll($insertProdOrder);

    }
}


$fillDb = new fillDb("purchase_log.json");
