<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Service.php';
include_once APPROOT_URL.'/Business/b_servicepermission.php';
include_once APPROOT_URL.'/Database/d_servicepermission.php';
include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/Database/d_users.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';

$action = $_POST['Action'];
switch($action){
	case 'GetByUserID':
		GetByUserID($mysqlObj,$lang);
		break;
	case 'UpdateUserServicePermission':
		UpdateServicePermission($mysqlObj,$lang,$_POST['UserID']);
		break;
	case 'UpdateGeneralServicePermission':
		UpdateServicePermission($mysqlObj,$lang,$_POST['UserID']);
		break;
}

function GetByUserID($mysqlObj,$lang){
	$resultObj = new httpresult();
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$spObj=new b_servicepermission($loggedInUserDetails->user,$mysqlObj,$lang);
	$spRes = $spObj->getByUserID($_POST['UserID']);
	if(count($spRes)==0){
		$resultObj->isSuccess=false;
		$resultObj->message="No permission assigned. Reload the page.";
		$resultObj->data='';
		echo json_encode($resultObj);
		return;
	}
	$spaObj =new b_servicepermissionassign($loggedInUserDetails->user,$mysqlObj,$lang);
	$spaResArr = $spaObj->getBySpID($spRes->ServicePermissionID);
	//$spRes=json_decode(json_encode($spRes));
	$resultObj->isSuccess=true;
	$resultObj->message="";
	$resultObj->data='{"ServicePermission":'.json_encode($spRes).',"ServicePermissionAssign":'.json_encode($spaResArr).'}';
	echo json_encode($resultObj);
}
function UpdateServicePermission($mysqlObj,$lang,$userID){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$spObj=new b_servicepermission($loggedInUserDetails->user,$mysqlObj,$lang);
	$spObj->ServicePermissionID = $_POST['ServicePermissionID'];
	$spObj->UserID = $userID;
	$spObj->IsOTFMinCharge = isset($_POST['IsOTFMinCharge'])?1:0;
	$spObj->OTFMinCharge = $_POST['OTFMinCharge'];
	$spObj->IsOTFCommission = isset($_POST['IsOTFCommission'])?1:0;
	$spObj->IsFirstSMSCost = isset($_POST['IsFirstSMSCost'])?1:0;
	$spObj->FirstSMSCost = $_POST['FirstSMSCost'];
	$spObj->IsAppliedForGroup = isset($_POST['IsAppliedForGroup'])?1:0;
	$spObj->IsAppliedForSubGroup = isset($_POST['IsAppliedForSubGroup'])?1:0;
	//echo json_encode($spObj->upsert($spObj));
	$spObj->upsert($spObj);
	
	$spaIDArr = $_POST['ServicePermissionAssignID'];
	$spIDArr = $_POST['ServicePermissionID'];
	$serviceIDArr = $_POST['ServiceID'];
	//$isEnabledArr = $_POST['IsEnabled'];
	$isEnabledAll = $_POST['CheckSequence'];
	$minChargeArr = $_POST['MinCharge'];
	$commissionArr = $_POST['Commission'];
	//echo 'Sequence='.$_POST['CheckSequence'];
	$spaObj=new b_servicepermissionassign($loggedInUserDetails->user,$mysqlObj,$lang);
	$result='true';
	for($i=0;$i<count($spaIDArr);$i++){
		$spaObj->ServicePermissionAssignID = $spaIDArr[$i];
		$spaObj->ServicePermissionID = $_POST['ServicePermissionID'];
		$spaObj->ServiceID = $serviceIDArr[$i];
		//$spaObj->IsEnabled = isset($isEnabledArr[$i])?1:0;
		$spaObj->IsEnabled = $isEnabledAll[$i];
		$spaObj->MinCharge = $minChargeArr[$i];
		$spaObj->Commission = $commissionArr[$i];
		//echo ','.$spaObj->ServicePermissionAssignID.'-Enabled='.(isset($isEnabledAll[$i])==true?1:0);
		//echo ','.$i.'-'.$spaObj->ServicePermissionAssignID.'-Enabled='.$spaObj->IsEnabled;
		$result=$spaObj->upsert($spaObj);
	}
	
	if($spObj->IsAppliedForGroup == 1){
		$dUserObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
		$res = $dUserObj->getUsersByParentID($userID, 0, '0');
		if(count($res) != 0){
			$spObj->deleteParentIDtoSubUser($userID);
		}
		$i=0;
		
		while($i < count($res)){
			$fromUserID = $_POST['ServicePermissionID'];
			$toUserID = $res[$i]->UserID;
			$arr = $spObj->getByUserID($toUserID);
			if(count($arr) !=0){
			
				$arrVal = json_encode($arr);
				$json = json_decode($arrVal, true);
				$toUserID = $json['ServicePermissionID'];
				$spaObj->copy($fromUserID,$toUserID);
			}
			$i++;
		} 
	}
	
	echo $result;
	//echo $_POST['CheckSequence'][1];
	//echo (isset($isEnabledArr[0])?1:0).','.(isset($isEnabledArr[1])?1:0).','.(isset($isEnabledArr[2])?1:0).','.(isset($isEnabledArr[3])?1:0);
}
?>