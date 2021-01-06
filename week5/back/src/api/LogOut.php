<?php

namespace Week5\api;


class LogOut
{

	public function __construct()
	{
		$expires = time();
		$date = date('Y-m-d H:i:s', $expires);
		header("Set-Cookie:sessionId='logOut'; Expires={$date}; SameSite=None; Path='/'; HttpOnly");
	}
}
