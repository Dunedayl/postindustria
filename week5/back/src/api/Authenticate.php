<?php
	namespace Week5\api;

	use Week5\database\Database;

	class Authenticate {

		private $sessionId, $database;

		public function __construct($sessionId) {
			$this->database = new Database();
			$this->sessionId = $sessionId;
		}

		public function logIn() {
			$query = 'SELECT * FROM Sessions WHERE expiry_date > NOW()';
			$data = $this->database->query($query, true);
			$hashedSessionId = null;
			foreach ($data as $value) {
				if (password_verify($this->sessionId, $value["sessionId"])) {
					$hashedSessionId = $value["id"];
					$this->succes();
				}
			}
			if ($hashedSessionId != null) {
				$this->succes();
				return $value["id"];
			} else {
				$this->error();
			}
		}

		private function error()
		{
			http_response_code(401);
			header("Content-type: json.application");
			echo json_encode(
				["error" => "Token is invalid"]
			);
			die();
		}


		private function succes()
		{
			http_response_code(200);
		}
	}
