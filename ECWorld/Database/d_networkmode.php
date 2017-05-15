<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_networkmode.php';
class d_networkmode{
	private $db;
	var $me;
	function __construct($me,$mysqlObj){
		$this->me=$me;
		$this->db = $mysqlObj;
	}
	function getNetworkMode(){
		try{
			$q="SELECT NetworkModeId, Name FROM m_networkmode where Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_networkmode');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
}
?>