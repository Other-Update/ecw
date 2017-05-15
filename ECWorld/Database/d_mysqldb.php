<?php
include_once 'd_errorlog.php';
$config = require APPROOT_URL.'/Settings/config.php';
class mysqldb{
	private $driver;
	private $host;
	private $database;
	private $user;
	private $pass;
	private $conn;
	public $configuration;
	public $errorlog;
	function __construct($config){
		try {
			$this->configuration = $config;
			$dbConfig=$config['database'];
			$this->driver = $dbConfig['driver'];
			//echo $this->config['driver'];
			$this->host = $dbConfig['host'];
			
			$this->database = $dbConfig['database'];//'ecworldserver';
			$this->user = $dbConfig['username'];
			$this->pass = $dbConfig['password']; 
			/*$this->database = 'ecworld_ecworld';
			$this->user = 'ecworld_eworld';
			$this->pass = '5WbE]ArJ@?Q5';*/
		
			$this->conn = new PDO("$this->driver:host=$this->host; dbname=$this->database", $this->user, $this->pass);
		} catch (PDOException $e) {
			return $e->getMessage();
			die();
		}
	}
	function getDBConnection(){
		return $this->conn ? $this->conn : false;
	}
	function execute($dbquery){
		try {
			$result = $this->conn->query($dbquery);
			return $result ? true : false;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	function insert($dbquery){
		try {
			$result = $this->conn->query($dbquery);
			$insertedId=$this->conn->lastInsertId();
			//echo '<br />MySQL=last inserted id='.$insertedId;
			return $insertedId>0 ? $insertedId : 0;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	function select($dbquery){
		try {
			$result = $this->conn->query($dbquery);
			return $result ? $result : false;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	function selectJSON($query,$classname){
		try{
			$statement = $this->conn->query($query);
			$statement->setFetchMode(PDO::FETCH_CLASS,$classname);
			$arr = $statement->fetchAll();
			//$a=1/0;
			$json= json_encode($arr);				
			return $json;
		}catch(\Exception $e){
			echo 'error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($e->getMessage(),'0','$classname','$query');
		}
	}
	function selectArray($query,$classname){
		try{
			//echo "Driver=".json_encode($this->config);
			$statement = $this->conn->query($query);
			$statement->setFetchMode(PDO::FETCH_CLASS,$classname);
			$arr = $statement->fetchAll();			
			return $arr;
		}catch(\Exception $e){
			echo 'error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($e->getMessage(),'0','$classname','$query');
		}
	}
	//<<<<<<<,,,Transaction & Lock related
	function beginTransaction(){
		try {
			$result=$this->conn->beginTransaction();
			return $result;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	function commitTransaction(){
		try {
			$result=$this->conn->commit();
			return $result;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	function LockExec($dbquery){
		try {
			$result = $this->conn->exec($dbquery);
			return $result ? true : false;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	//Transaction&Lock related>>>>>>>>>>>>...
	
	function executeSP($spcall){
		try {
			$q=$this->conn->exec($spcall);
			$result = $this->conn->query($q)->fetchAll();
			return $result;
		} catch (Exception $e) {
			//TODO: Add Errorlog
			//return $e->getMessage();
			return false;
		}
	}
	function test_returnThis($msg){
		return $msg;
	}
}

global $mysqlObj;
$mysqlObj = new mysqldb($config);
$errorlog = new d_errorlog($mysqlObj);
$mysqlObj->errorlog = $errorlog;
//echo json_encode($config['database']);
//$mysqlObj->config = $config['database'];
/*if(!$mysqlObj->conn){
 die('d_mysqldb - Error'.mysql_error());
}
echo 'Success';*/
?>