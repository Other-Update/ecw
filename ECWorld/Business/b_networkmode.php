<?php
include_once APPROOT_URL.'/Database/d_networkmode.php';
class b_networkmode{
	private $filename='b_networkmode';
	var $dbObj;
	var $mysqlObj;
	var $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->mysqlObj=$mysqlObj;
		$this->dbObj=new d_networkmode($me,$mysqlObj);
		$this->lang=$lang;
	}
	function getNetworkMode(){
		try{
			$arr = $this->dbObj->getNetworkMode();
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
}
?>