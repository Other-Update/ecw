<?php
include_once APPROOT_URL.'/Database/d_errorlog.php';
class b_errorlog{
	var $mysql;
	var $dObj;//Database Recharge
	function __construct($mysqlObj){
		$this->mysql=$mysqlObj;
		$this->dObj=new z_d_testing($mysqlObj);
	}
	function getAll(){
		return $this->dObj->getAll();
	}
	function upsert($name,$ip){
		return $this->dObj->upsert($name,$ip);
	}
}
?>