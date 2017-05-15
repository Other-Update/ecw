<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_webserviceactions.php';
class d_webserviceactions{
	private $db;
	private $dtObj;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
	}
	function getByCode($code){
		try{
			$q="SELECT * FROM m_webserviceactions where Code='$code' AND Active='1'";			
			$arr=$this->db->selectArray($q,'e_webserviceactions');
			return $arr;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
}
?>