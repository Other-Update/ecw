<?php
include_once APPROOT_URL.'/Database/d_paycollectionreport.php';
include_once APPROOT_URL.'/General/general.php';
class b_paycollectionreport{
	private $filename='b_paycollectionreport'; 
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_paycollectionreport($me,$mysqlObj);
	}
	
	function getPayCollectionReport_DT($userId, $mobile, $fromDate, $toDate){
		return $this->dbObj->getPayCollectionReport_DT($userId, $mobile, $fromDate, $toDate);
	}

}
?>