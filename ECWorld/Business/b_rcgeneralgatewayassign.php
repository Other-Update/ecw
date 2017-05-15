<?php
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Database/d_rcgeneralgatewayassign.php';

class b_rcgeneralgatewayassign{
	private $filename='b_rcgeneralgatewayassign';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_rcgeneralgatewayassign($me,$mysqlObj);
	}

	function get(){
		return $this->dbObj->get();
	}
	function upsert($checkedUsersArr){
		$resultObj = new httpresult();
		$upsertRes = 0;
		//echo '$ugObj->RCUserGatewayID='.$ugObj->RCUserGatewayID;
		$upsertRes = $this->dbObj->upsert($checkedUsersArr);
		//$upsertRes will be either true/false in case of update or integer(inserted id) in case of insert
		if($upsertRes){
			$resultObj->isSuccess = true;
			$resultObj->message=$this->lang['update_success'];
		}else{
			$resultObj->isSuccess = false;
			$resultObj->message=$this->lang['update_failed'];
		}
		return $resultObj;
	}
	function delete_NIU($id){
		$resultObj = new httpresult();
		$RcGatewayID = $this->dbObj->delete($id);
		if($RcGatewayID){
			$resultObj->isSuccess = true;
			$resultObj->message=$this->lang['user_amnt_delete_success'];
		}else{
			$resultObj->isSuccess = false;
			$resultObj->message=$this->lang['user_amnt_delete_failed'];
		}
		return $resultObj;
	}
}
?>