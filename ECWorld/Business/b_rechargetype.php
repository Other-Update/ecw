<?php
include_once APPROOT_URL.'/Database/d_rechargetype.php';
class b_rechargetype{
	private $filename='b_rechargetype';
	var $dbObj;
	var $mysqlObj;
	var $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->mysqlObj=$mysqlObj;
		$this->dbObj=new d_rechargetype($me,$mysqlObj);
		$this->lang=$lang;
	}
	function getRechargeTypes(){
		try{
			$arr = $this->dbObj->getRechargeTypes();
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
}
?>