<?php
//include_once APPROOT_URL.'/Resource/AutoMnpRecharge.php';
include_once APPROOT_URL.'/Database/d_rechargereport.php';
include_once APPROOT_URL.'/General/general.php';
class b_rechargereport{
	private $filename='b_rechargereport';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_rechargereport($me,$mysqlObj);
	}
	
	/* function getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $requestId, $network){
		return $this->dbObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $requestId, $network);
	}  */
	
	function getNetworkList(){
		return $this->dbObj->getNetworkList();
	}
	
	
	
	
}
?>