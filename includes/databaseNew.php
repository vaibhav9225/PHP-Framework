<?php

class MySqlDatabase{
	private $connection;
	public $lastQuery=null;
	public $lastSql=false;
	public function __construct(){
		$this->openConnection();
	}
	public function openConnection(){
		global $page;
		$this->connection = new mysqli(DatabaseServer,DatabaseUsername,DatabasePassword,DatabaseName);
		if (mysqli_connect_errno()){
			$page->end('The connection to the database failed.');
		}
	}
	public function closeConnection(){
		if(isset($this->connection)){
			$this->connection->close();
			unset($this->connection);
		}
	}
	public function closeSql($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		if(isset($this->lastSql)){
			$this->lastSql->close();
		}
	}
	private function confirmSql($sql){
		global $page;
		if(!$sql){
			$page->end('This query failed');
		}
	}
	public function query($query,$save=true){
		if($save){
			$this->lastQuery = $query;
			$this->lastSql = $this->connection->query($query);
			$this->confirmSql($this->lastSql);
			return $this->lastSql;
		}
		else{
			$sql = $this->connection->query($query);
			$this->confirmSql($sql);
			return $sql;		
		}
	}
	public function fetchAssoc($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return $sql->fetch_assoc();
	}
	public function fetchArray($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return $sql->fetch_array(MYSQLI_NUM);
	}
	public function fetchBoth($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return $sql->fetch_array(MYSQLI_BOTH);
	}
	public function insertId(){
		return $this->connection->insert_id;
	}
	public function affectedRows(){
		return $this->connection->affected_rows;
	}
	public function numRows($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return $sql->num_rows;
	}
	public function totalRows($tableName){
		$query = "SELECT * FROM `$tableName` WHERE 1";
		$sql = $this->query($query);
		$this->confirmSql($sql);
		return $this->numRows($sql);
	}
	public function escapeString($string){
		global $page;
		if(function_exists("mysql_real_escape_string")){
			$string = mysql_real_escape_string($string);
		}
		if(get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		else{
			$string = addslashes($string);
		}
		if(function_exists("htmlentities")){
			$string = htmlentities($string);
		}
		return htmlspecialchars_decode($string);
	}
	public function checkValue($tableName, $colName, $value){
		$value = $this->escapeString($value);
		$query = "SELECT * FROM `$tableName` WHERE `$colName`='$value'";
		$sql = $this->query($query, false);
		$count = $this->numRows($sql);
		return $count > 0 ? true : false;
	}
	public function otherValue($tableName, $colName, $value, $otherCol){
		$value = $this->escapeString($value);
		$query = "SELECT `$otherCol` FROM `$tableName` WHERE `$colName`='$value'";
		$sql = $this->query($query, false);
		$count = $this->numRows($sql);
		if($count > 0){
			$data = $this->fetchAssoc($sql);
			return $data[$otherCol];
		}
		else{
			return false;
		}
	}
}

$database = new MySqlDatabase();

?>