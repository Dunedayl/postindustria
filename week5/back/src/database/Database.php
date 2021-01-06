<?php
namespace Week5\database;

use Config\sqlconfig\Config;

class Database
{

	private $pdo;

	public function __construct()
	{
		$data = Config::getConfig();
		$pdo = new \PDO('mysql:host=' . $data["host"] . ';dbname=' . $data["database"] . ';charset=utf8', $data["user"], $data["password"]);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->pdo = $pdo;
	}

	public function query($query, $all = false)
	{
		$statement = $this->pdo->prepare($query);
		$statement->execute();

		if (explode(' ', $query)[0] == 'SELECT') {

			if ($statement->rowCount() > 0) {
				if ($all == true) {
					$data = $statement->fetchall();
					return $data;
				} else {
					$data = $statement->fetch();
					return $data;
				}
			} else {
				return null;
			}
		}
	}
}
?>