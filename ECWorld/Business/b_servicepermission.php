<?php
include_once APPROOT_URL.'/Database/d_servicepermission.php';
include_once APPROOT_URL.'/Business/b_servicepermissionassign.php';
class b_servicepermission{
	private $filename='b_servicepermission';
	var $dbObj;
	var $mysqlObj;
	var $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->mysqlObj=$mysqlObj;
		$this->dbObj=new d_servicepermission($me,$mysqlObj);
		$this->lang=$lang;
	}
	function getByUserID($userID){
		try{
			$arr = $this->dbObj->getByUserID($userID);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
	//Reghu Code 
	function deleteParentIDtoSubUser($userID){
		$this->dbObj->deleteParentIDtoSubUser($userID);
	} //End Code
	
	function copy($fromUserID,$toUserID){
		try{
			$res = $this->dbObj->copy($fromUserID,$toUserID);
			
			$spAssignObj=new b_servicepermissionassign($this->me,$this->mysqlObj,$this->lang);
			$fromRP= $this->dbObj->getByUserID($fromUserID);//When copy, copy from Default(-1) not from any user
			$toRP= $this->dbObj->getByUserID($toUserID);
			//if(!$toRP) echo 'Something went wrong';
			//echo '<br /> fromRP='.$fromRP->ServicePermissionID.', toRP='.$toRP->ServicePermissionID;
			$res = $spAssignObj->copy($fromRP->ServicePermissionID,$toRP->ServicePermissionID);
			//echo '<br />BS- Result = '.json_encode($res);
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	function add($serviceObj){
		try{
			$res = $this->dbObj->add($serviceObj);
			//echo '<br />BS- Result = '.json_encode($res);
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	function upsert($spObj){
		try{
			$spObj->CreatedDate = date('Y-m-d h:i:s');
			$spObj->CreatedBy = $this->me->UserID;
			$spObj->ModifiedBy = $this->me->UserID;
			if($spObj->ServicePermissionID>0){
				//echo 'update='.$spObj->IsOTFMinCharge;
				$res = $this->dbObj->update($spObj);
				return $res;
			}else{
				$res = $this->dbObj->add($spObj);
				return $res;
			}
			//echo '<br />BS- Result = '.json_encode($res);
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
}
/* $obj=new b_servicepermission($mysqlObj);
$r = $obj->copy(1,2); */
//echo '<br/> End='.$r->Name;
/* $sObj='{"UserID":"1","Name":"Testing","isOTFMinCharge":"1","isOTFCommission":"1","isOTFCommission":"1","FirstSMSCost":"10","IsAppliedForGroup":"1","IsAppliedForSubGroup":"1","CreatedDate":"2016-10-02 15:40:13","CreatedBy":"1","ModifiedDate":"2016-10-02 15:40:13","ModifiedBy":"1"}';

$obj=new b_servicepermission($mysqlObj);
$jsonObj=json_decode($sObj);
echo $jsonObj->Name;
$r = $obj->add(json_decode($sObj));  */
//echo json_encode($r);
?>