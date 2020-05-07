<?php
class DbModel
{
	public  $conn=null;
	function __construct()
	{
		$this->conn = new PDO("mysql:host=". HOST_DB.";dbname=". DB, USER_DB,PASS_DB);
		$this->conn->query('set names utf8');
	}

	function getTable($tableName)
	{
		$stm = $this->conn->prepare("SELECT * FROM $tableName");
		$stm->execute();
		return $stm->fetchAll();
	}

	function selectQuery($sql, $arr=array())
	{
		$stm = $this->conn->prepare($sql);
		$stm->execute($arr);
		return $stm->fetchAll(PDO::FETCH_ASSOC);
	}
	function updateQuery($sql, $arr=array())
	{
		$stm = $this->conn->prepare($sql);
		$stm->execute($arr);
		
		return $stm->rowCount();
	}
}