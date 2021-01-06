<?php

namespace Week5\api;

use Week5\database\Database;

class User {

	private $sessionId, $database, $id;

	public function __construct() {

		if ($_SERVER['HTTP_COOKIE']) {
			$this->database = new Database();
			$coockie = explode("=", $_SERVER['HTTP_COOKIE']);
			$this->sessionId = $coockie[1];
			$auth = new Authenticate($this->sessionId);
			$this->id =  $auth->logIn();
			$this->getUser();
		} else {
			$this->error();
		}
	}

	private function error() {
		http_response_code(401);
		header("Content-type: json.application");
		echo json_encode(["error" => "No token"]);
		die();
	}

	private function succes($data)
	{
		header("Content-type: json.application");
		echo json_encode(["firstname" => $data["firstname"], "lastname" => $data["lastname"], "email" => $data["email"]]);
	}

	private function getUser() {
		$query = 'SELECT * FROM Users join Sessions on Sessions.userId = Users.id WHERE Sessions.id = "' . $this->id . '";';
		$data = $this->database->query($query);
		$this->succes($data);
	}
}
