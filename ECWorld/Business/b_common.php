<?php
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Database/d_common.php';
class b_common{
	private $filename='b_common';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_common($me,$mysqlObj,$lang);
	}
	function makeInActive($table,$field,$value){
		$resultObj = new httpresult();
		$res = $this->dbObj->makeInActive($table,$field,$value);
		$resultObj->isSuccess=$res;
		if($res)
			$resultObj->message='Deleted';//$this->lang['as_success'];
		else
			$resultObj->message='Failed to delete';//$this->lang['as_success'];
		$resultObj->data="";
		return json_encode($resultObj);
	}
}

?>