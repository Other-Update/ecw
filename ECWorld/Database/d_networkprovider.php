<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_networkprovider.php';
class d_networkprovider{
	private $db;
	var $me;
	function __construct($me,$mysqlObj){
		$this->me=$me;
		$this->db = $mysqlObj;
	}
	function getNetworkProvider(){
		try{
			$q="SELECT NetworkProviderID, Name FROM m_networkprovider where Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_networkprovider');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
}
?>