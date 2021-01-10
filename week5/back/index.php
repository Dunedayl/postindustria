<?php

use Week5\api\Authenticate;
use Week5\api\Check;
use Week5\api\LogIn;
use Week5\api\LogOut;
use Week5\api\Registrate;
use Week5\api\User;
use Week5\routing\Request;
use Week5\routing\Router;

require_once("vendor/autoload.php");


header('Access-Control-Allow-Origin: http://front.loc');
header('Access-Control-Allow-Methods: PUT, GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, authorization');
header('Access-Control-Expose-Headers: "*"');
header('Access-Control-Allow-Credentials: true');


$router = new Router(new Request);

$router->get('/home', function($request) {
	$user = new User();
});


$router->get('/check', function ($request) {
	$check = new Check();
});


$router->post('/auth', function ($request) {
	$auth = new LogIn($request->phpAuthUser, $request->phpAuthPw);
});


$router->post('/logout', function ($request) {
	$logout = new LogOut();
});


$router->put('/signup', function ($request) {
	$registrate = new Registrate();
});


$router->options('/auth', function () {
});


$router->options('/signup', function () {
});