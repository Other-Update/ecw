<?php
include_once APPROOT_URL.'/Database/z_testing.php';
class z_b_testing{
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