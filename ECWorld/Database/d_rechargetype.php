<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rechargetype.php';
class d_rechargetype{
	private $db;
	var $me;
	function __construct($me,$mysqlObj){
		$this->me=$me;
		$this->db = $mysqlObj;
	}
	function getRechargeTypes(){
		try{
			$q="SELECT * FROM m_rechargetype where Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_rechargetype');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
}
?>