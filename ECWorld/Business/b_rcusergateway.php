<?php
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Database/d_users.php';
include_once APPROOT_URL.'/Database/d_rcusergateway.php';
include_once APPROOT_URL.'/Database/d_rcgeneralgatewayassign.php';

class b_rcusergateway{
	private $filename='b_rcusergateway';
	private $dbObj;
	private $me;
	private $lang;
	var $rcggaObj;
	var $userObj;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		//echo "<br/> b_rcusergateway. me=".json_encode($me);
		$this->dbObj=new d_rcusergateway($me,$mysqlObj);
		$this->rcggaObj=new d_rcgeneralgatewayassign($me,$mysqlObj);
		$this->userObj=new d_users($mysqlObj);
	}

	function get_DT($userID){
		return $this->dbObj->get_DT($userID);
	}
	function getAssignedDetailsByUserAndService($userID,$serviceID){
		//Check whether user is assigned to general gateway
		
		$ancestorID = $this->userObj->getAncestorIDByUserID($userID);
		//echo "<br/> ancestorID=".$ancestorID;die;
		$isAssignedInGeneral = $this->rcggaObj->isUserAssigned($userID,$ancestorID);
		//echo "<br/>isAssignedInGeneral=".$isAssignedInGeneral;
		if($isAssignedInGeneral)
			$userID=1;//If it is assigned to general then take general(admins) gateway
		
		$res = $this->dbObj->getByUserAndService($userID,$ancestorID,$serviceID);
		//echo '<br/>Assigned gateway'.json_encode($res);
		return $res;
	}
	function upsertAmount($ugObj){
		$resultObj = new httpresult();
		$upsertRes = 0;
		//echo '$ugObj->RCUserGatewayID='.$ugObj->RCUserGatewayID;
		if($ugObj->RCUserGatewayID>0){
			//echo 'update';
			$upsertRes = $this->dbObj->updateAmount($ugObj);
		}else{
			//echo 'add';
			$upsertRes = $this->dbObj->add($ugObj);
		}
		//$upsertRes will be either true/false in case of update or integer(inserted id) in case of insert
		if($upsertRes){
			$resultObj->isSuccess = true;
			$resultObj->message=$this->lang['user_amnt_update_success'];
		}else{
			$resultObj->isSuccess = false;
			$resultObj->message=$this->lang['user_amnt_update_failed'];
		}
		return $resultObj;
	}
	function delete($id){
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
	
	function copy($fromUserID,$toUserID){
		try{
			/* echo "<br/>b_rcusergateway.fromUserID=".($fromUserID);
			echo "<br/>b_rcusergateway.toUserID=".($toUserID); */
			$res = $this->dbObj->copy($fromUserID,$toUserID);		
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	//Reghu -- 20-12-2016 for update multiple gateway
	function generalApi($ugObj){
		$resultObj = new httpresult();
		$upsertRes = $this->dbObj->updateGeneralApi($ugObj);
		if($upsertRes){
			$resultObj->isSuccess = true;
			$resultObj->message=$this->lang['user_amnt_update_success'];
		}else{
			$resultObj->isSuccess = false;
			$resultObj->message=$this->lang['user_amnt_update_failed'];
		}
		return $resultObj;
	}
}
?>