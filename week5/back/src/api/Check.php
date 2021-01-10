<?php

namespace Week5\api;


class Check
{

	public function __construct()
	{
		if ($_SERVER['HTTP_COOKIE']) {
			$coockie = explode("=", $_SERVER['HTTP_COOKIE']);
			$sessionId = $coockie[1];
			$auth = new Authenticate($sessionId);
			$auth->logIn();
		} else {
			$this->error();
		}
	}

	private function error()
	{
		http_response_code(401);
		header("Content-type: json.application");
		echo json_encode(["error" => "No token"]);
		die();
	}

}
