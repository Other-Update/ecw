<?php
include_once APPROOT_URL.'/Database/d_servicepermissionassign.php';
class b_servicepermissionassign{
	private $filename='b_servicepermissionassign';
	var $dbObj;
	var $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->dbObj=new d_servicepermissionassign($me,$mysqlObj);
		$this->lang=$lang;
	}
	function get(){
		try{
			$arr = $this->dbObj->get();
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	
	function getBySpID($spID){
		try{
			//echo 'getBySpID';
			$arr = $this->dbObj->getBySpID($spID);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	function getBySpAndServiceID($spID,$serviceID){
		try{
			//echo 'getBySpID';
			$arr = $this->dbObj->getBySpAndServiceID($spID,$serviceID);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	function copy($fromRpID,$toRpID){
		try{
			$arr = $this->dbObj->copy($fromRpID,$toRpID);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	//TODO:Use upsert() function instead of add()
	function add($serviceObj){
		try{
			$res = $this->dbObj->add($serviceObj);
			//echo '<br />BS- Result = '.json_encode($res);
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	function upsert($spaObj){
		try{
			if($spaObj->ServicePermissionAssignID>0){
				//echo ",".$spaObj->ServicePermissionAssignID."-Enabled=".$spaObj->IsEnabled;
				//echo '<br/>Udpate - $spaObj->ServicePermissionID'.$spaObj->ServicePermissionID;
				//echo 'update='.$spObj->IsOTFMinCharge;
				//echo '<br/>update - $spaObj->IsEnabled'.$spaObj->IsEnabled;
				$res = $this->dbObj->update($spaObj);
				return $res;
			}else{
				//$spaObj->ServicePermissionID = 0;
				$spaObj->CreatedDate = date('Y-m-d h:i:s');
				$spaObj->CreatedBy = $this->me->UserID;
				$spaObj->ModifiedBy = $this->me->UserID;
				$res = $this->dbObj->add($spaObj);
				return $res;
			}
			//echo '<br />BS- Result = '.json_encode($res);
			//return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
}

/* $obj=new b_servicepermissionassign($mysqlObj);
$r = $obj->copy(1,2);
echo '<br/> End -'.$r; */
/*$sObj='{"Name":"Testing","ServiceID":"1","ServicePermissionID":"1","MinCharge":"100","Commission":"5","CreatedDate":"2016-10-02 15:40:13","CreatedBy":"1","ModifiedDate":"2016-10-02 15:40:13","ModifiedBy":"1"}';

$obj=new b_servicepermissionassign($mysqlObj);
$jsonObj=json_decode($sObj);
echo $jsonObj->Name;
$r = $obj->add(json_decode($sObj));*/
//echo json_encode($r);
?>