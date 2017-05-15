<?php
include_once APPROOT_URL.'/Database/d_networkprovider.php';
class b_networkprovider{
	private $filename='b_networkprovider';
	var $dbObj;
	var $mysqlObj;
	var $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->mysqlObj=$mysqlObj;
		$this->dbObj=new d_networkprovider($me,$mysqlObj);
		$this->lang=$lang;
	}
	function getNetworkProvider(){
		try{
			$arr = $this->dbObj->getNetworkProvider();
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