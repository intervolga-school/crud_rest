<?php
require_once "singleton.php";

use PDOException;

abstract class TableModule
{
	abstract protected function getTableName(): string;

	public function delete($id)
	{
		$sql = "DELETE FROM " . $this->getTableName() . " WHERE id=:id";
		$query = Singleton::prepare($sql);
		if (!$query->execute(array(":id" => $id))) {
			throw new PDOException("При удалении произошла ошибка");
		}
	}

	public function read($fields = array())
	{
		$sql = "SELECT * FROM " . $this->getTableName() . " WHERE 1 ";
		foreach ($fields as $key => $field) {
			$sql .= "AND " . $key . "=" . $field . " ";
		}
		$query = Singleton::prepare($sql);
		$query->execute([]);
		$result = array();
		while ($slice = $query->fetch()) {
			$result[] = $slice;
		}
		return $result;
	}

	public function create($fields)
	{
		$keys = array();
		$keyparam = array();
		$arField = array();
		foreach ($fields as $key => $field) {
			$keys[] = " " . $key;
			$keyparam[] = " :" . $key;
			$arField[":" . $key] = $field;
		}
		$keys = implode(", ", $keys);
		$keys = "(" . $keys . ")";
		$keyparam = implode(", ", $keyparam);
		$keyparam = "(" . $keyparam . ")";
		$sql = "INSERT INTO " . $this->getTableName() . " " . $keys . " VALUE " . $keyparam;
		$query = Singleton::prepare($sql);
		if (!$query->execute($arField)) {
			throw new PDOException("При добавлении произошла ошибка");
		}
	}

	public function update($fields)
	{
		$keyparam = array();
		$arField = array();
		foreach ($fields as $key => $field) {
			if($key!="id"){
				$keyparam[] = " `$key`=:" . $key;
			}
			$arField[":" . $key] = $field;
		}
		$keyparam = implode(", ", $keyparam);
		$sql = "UPDATE " . $this->getTableName() . " SET " . $keyparam . " WHERE id=:id";
		$query = Singleton::prepare($sql);
		if (!$query->execute($arField)) {
			throw new PDOException("При обновлении произошла ошибка");
		}
	}

	public function exists($id): bool
	{
		$query = Singleton::prepare("SELECT * FROM " . $this->getTableName() . " WHERE id=" . $id);
		$query->execute([]);
		$find = $query->fetch();
		return boolval($find);
	}

	public function lastID()
	{
		$query = Singleton::prepare("SELECT MAX(ID) FROM " . $this->getTableName());
		$query->execute([]);
		$find = $query->fetch();
		return $find["MAX(ID)"];
	}
}