<?php



use Week5\api\Authenticate;
use Week5\api\LogIn;
use Week5\api\LogOut;
use Week5\api\Registrate;
use Week5\api\User;
use Week5\database\Database;

require_once("vendor/autoload.php");



$datb = new Database();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	// Geting user first and last name 
	if ($_GET['url'] == "home") {
		$user = new User();
	} else if ($_GET['url'] == "check") {
	// Check if cookie is valid 
		if ($_SERVER['HTTP_COOKIE']) {
			$coockie = explode("=", $_SERVER['HTTP_COOKIE']);
			$sessionId = $coockie[1];
			$auth = new Authenticate($sessionId);
			$auth->logIn();
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// Log in with username and password
	if ($_GET['url'] == "auth") {

		$postBody = file_get_contents("php://input");
		$postBody = json_decode($postBody);

		$email = $_SERVER['PHP_AUTH_USER'];
		$password = $_SERVER['PHP_AUTH_PW'];

		$auth = new LogIn($email, $password);
	// Log out 
	} else if ($_GET['url'] == "logout") {

		$logout = new LogOut();

	}

} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	// Registrate new user
	if ($_GET['url'] == "signup") {
		

		$postBody = file_get_contents("php://input");
		$postBody = json_decode($postBody);

		$firstname = $postBody->firstname;
		$lastname = $postBody->lastname;
		$email = $postBody->email;
		$password = $postBody->password;

		$registrate = new Registrate($firstname, $lastname, $email, $password);

	}
}
