<?php
include_once APPROOT_URL.'/Database/d_webserviceactions.php';
include_once APPROOT_URL.'/General/general.php';
class b_webserviceactions{
	private $me;
	private $lang;
	var $dbObj;
	function __construct($thisUser,$mysqlObj,$lang){
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->dbObj=new d_webserviceactions($mysqlObj);
	}
	function getByCode($code){
		$arr = $this->dbObj->getByCode($code);
		if(count($arr)>0)
			return $arr[0];
		else
			return null;
	}
}
?>