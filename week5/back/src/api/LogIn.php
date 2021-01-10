<?php
	namespace Week5\api;

use Week5\database\Database;

class LogIn {

	private $email, $password;
	private $date, $sessionId;
	private $database;

	public function __construct($email, $password) {
		$this->database = new Database();

		$this->email = $email;
		$this->password = $password;
		$this->genereteSessionId();
	}

	private function error() {
		http_response_code(401);
		header("Content-type: json.application");
		echo json_encode(["error" => "Invalid Email / Password"]);
		die();
	}

	private function succes($userId)
	{
		$hashed_sessionId = password_hash($this->sessionId, PASSWORD_DEFAULT);

		$query = 'INSERT INTO Sessions (sessionId, userId, expiry_date) values ("' . $hashed_sessionId . '", ' . $userId .  ',"' . $this->date . '" )';
		$this->database->query($query);
		header("Set-Cookie:sessionId={$this->sessionId}; Expires={$this->date}; SameSite=None; Path='/'; HttpOnly");

	}


	private function genereteSessionId() {
		date_default_timezone_set("UTC");

		$this->sessionId = bin2hex(random_bytes(16));

		$expires = time() + 60; // after 1 min for testing 
		//$expires = time() + 2 * 24 * 60 * 60; // after 2 days
		$this->date = date('Y-m-d H:i:s', $expires);
		
		$select = 'SELECT * FROM Users WHERE email ="'. $this->email .'"';
		$data =  $this->database->query($select);

		if ($data == null) {
			$this->error();
		}

		$userId = $data["id"];
		$storedPassword = $data["password"];
		
		if (password_verify($this->password, $storedPassword)) {
			$this->succes($userId);
		} else {
			$this->error();
		}
	}
}
