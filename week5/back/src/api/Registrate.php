<?php

namespace Week5\api;

use Week5\database\Database;

class Registrate
{
	private $firstname, $lastname, $email, $password;
	private $database;


	public function __construct()
	{
		$postBody = file_get_contents("php://input");
		$postBody = json_decode($postBody);


		$this->database = new Database();
		$this->firstname = $postBody->firstname;
		$this->lastname = $postBody->lastname;
		$this->email = $postBody->email;
		$this->password = $postBody->password;

		$this->validate();
		$this->checkEmail();
		$this->registrate();
		$this->login();
	}

	private function validate()
	{

		if (!isset($this->firstname)) {
			$error = "Firstname is undefined";
			$this->error($error);
		} 
		if (!isset($this->lastname)) {
			$error = "Lastame is undefined";
			$this->error($error);
		} 
		if (!isset($this->email)) {
			$error = "Email is undefined";
			$this->error($error);
		} 
		if (!isset($this->password)) {
			$error = "Password is undefined";
			$this->error($error);
		}
	}

	private function checkEmail()
	{
		$query = 'SELECT * FROM Users WHERE email = "' . $this->email . '" ';
		$data = $this->database->query($query);

		if ($data == null) {
			return true;
		} else {
			$error = "Email is alredy used";
			$this->error($error);
		}
	}

	private function registrate()
	{
		$hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
		$query = 'INSERT INTO Users (firstname, lastname, email, password) values ("'. $this->firstname . '", "' . $this->lastname . '","' . $this->email . '","' . $hashed_password . '" )';
		$this->database->query($query);
	}

	private function login()
	{
		$auth = new LogIn($this->email, $this->password);
	}

	private function error($message)
	{
		http_response_code(401);
		header("Content-type: json.application");
		$error = json_encode(["error" => $message]);
		die($error);
	}
}
