<?php
//session_start();
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/User.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/Business//b_users.php';
include_once APPROOT_URL.'/Business/b_role.php';
include_once APPROOT_URL.'/Business/b_fat.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_sms.php';
include_once APPROOT_URL.'/Business/b_http.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'Upsert':
		Upsert($mysqlObj,$lang,$langSMS);
		break;
	
	case 'GetLoggedInUser':
		GetLoggedInUser($mysqlObj);
		break;
	
}
function GetLoggedInUser($mysqlObj){
	echo $_SESSION['me'];
}

function Upsert($mysqlObj,$lang,$langSMS){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//Adding request
	$bReqObj=new b_request($loggedInUserDetails->user,$mysqlObj,$lang);
	$today =date('Y-m-d H:i:s');
	$bReqObj = $bReqObj->giveMeObj($loggedInUserDetails->user->UserID,$loggedInUserDetails->user->Mobile,$_SERVER["REMOTE_ADDR"],"WEB","000000","0","WEB REQUEST","Add/Update User","1",$today,$today,"WEB REQUEST","WEB");
	if($_POST['UserID']==0){
		$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"W");
		$bReqObj->RequestID = $bReqObj->add($bReqObj);
		$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
		/* 
		$bReqObj->RequestID = $bReqObj->add($bReqObj);
		$bReqObj->DisplayID = $bReqObj->updateDisplayID($bReqObj,"W"); */
	}
	
	//Adding user
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$userObj->UserID = $_POST['UserID'];
	$userObj->ParentID = $_POST['ParentID'];
	$userObj->Ancestors = $userObj->ParentID."/";
	$userObj->Name = $_POST['Name'];
	$userObj->Mobile = $_POST['Mobile'];
	$userObj->DOB = $_POST['DOB'];
	

	$userObj->Gender = $_POST['Gender'];
	$userObj->MinOpenBalanceMargin = 0;
	$res = $userObj->upsert($userObj,$bReqObj);
	//echo "<br/> Upsert res=".json_encode($res);	
	
	//SMS data
	$bHttpObj = new b_http($loggedInUserDetails->user,$mysqlObj,"");
	$bSMSObj = new b_sms($loggedInUserDetails->user,$mysqlObj,$userObj,$lang,$langSMS,$bHttpObj);
	//Updating Request
	if($_POST['UserID']==0){
		$bReqObj->UserID = $loggedInUserDetails->user->UserID;
		$bReqObj->TotalAmount =$bReqObj->TargetAmount= "0";
		$bReqObj->RequestType = "t_user";
		$bReqObj->TargetNo = $userObj->RoleID;
		if($res->isSuccess && $res->InsertedUserID!="0"){
			$bReqObj->Status="3";
			$bReqObj->Remark="Successfully added user";
			$bSMSObj->userCreation(1,$bReqObj,$res->InsertedUserID,$userObj->Mobile,$userObj->Name,$userObj->getRoleName($userObj->RoleID),$userObj->Password,$bReqObj->Remark);
		}else{
			$bReqObj->Status="4";
			$bReqObj->Remark="Failed to add user";
			$bSMSObj->userCreation(0,$bReqObj,"",$userObj->Mobile,$userObj->Name,$userObj->getRoleName($userObj->RoleID),$userObj->Password,$bReqObj->Remark);
		}
		$bReqObj->update($bReqObj);
	}
	echo json_encode($res);
}

?>