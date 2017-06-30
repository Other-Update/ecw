<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_common.php';
include_once APPROOT_URL.'/Business/b_rcgateway.php';
include_once APPROOT_URL.'/Business/b_rcgatewaydetails.php';
include_once APPROOT_URL.'/Business/b_rcusergateway.php';
include_once APPROOT_URL.'/Business/b_rcgeneralgatewayassign.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'GetUserGateway_DT':
		getUserGateway_DT($mysqlObj,$lang);
		break;
	case 'UpdateUserGatewayAmount':
		upsertUserGatewayAmount($mysqlObj,$lang);
		break;
	case 'GetGatewayAPIList':
		getGatewayAPIs($mysqlObj,$lang);
		break;
	case 'GetGatewayAPIDetails_DT':
		getGatewayAPIDetails_DT($mysqlObj,$lang);
		break;
	case 'UpsertGatewayAPI':
		upsertGatewayAPI($mysqlObj,$lang);
		break;
	case 'UpsertAPIRechargeCode':
		upsertAPIRechargeCode($mysqlObj,$lang);
		break;
	case 'DeleteGatewayApi':
		delete($mysqlObj,$lang,"m_rcgateway","RCGatewayID",$_POST["RCGatewayID"]);
		break;
	case 'GetAssignedUsers':
		getAssignedUsers($mysqlObj,$lang);
		break;
	case 'UpsertGeneralAssignUsers':
		upsertGeneralAssignUsers($mysqlObj,$lang);
		break;
	case 'UpsertGeneralApi':
		upsertGeneralApi($mysqlObj,$lang);
		break;
}

function delete($mysqlObj,$lang,$table,$field,$value){
	$userDetails = s_GetUserDetails();
	$common=new b_common($userDetails->user,$mysqlObj,$lang);
	echo $common->makeInActive($table,$field,$value);
}

function upsertGeneralAssignUsers($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$gaObj=new b_rcgeneralgatewayassign($userDetails->user,$mysqlObj,$lang);
	//$ugObj->RCGenralGatewayAssignID=$_POST['RCGenralGatewayAssignID'];
	//$ugObj->RCUserGatewayUD=$_POST['RCUserGatewayUD'];
	//$ugObj->UserID=$_POST['UserID'];
	$UserCheckArr=$_POST['UserCheck'];
	/* $strUserCheck="";
	for($i=0;$i<count($UserCheckArr);$i++){
		if($i>0)
			$strUserCheck.=",".$UserCheckArr[$i];
		else
			$strUserCheck=$UserCheckArr[$i];
	} 
	echo $strUserCheck;*/
	echo json_encode($gaObj->upsert($UserCheckArr));
}
function getAssignedUsers($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$assignObj=new b_rcgeneralgatewayassign($userDetails->user,$mysqlObj,$lang);
	echo json_encode($assignObj->get());
}
function upsertUserGatewayAmount($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);
	$ugObj->RCUserGatewayID=$_POST['RCUserGatewayID'];
	$ugObj->UserID=$_POST['UserID'];
	$ugObj->ServiceID=$_POST['ServiceID'];
	$ugObj->Amount=$_POST['Amount'];
	echo json_encode($ugObj->upsertAmount($ugObj));
}
function upsertGatewayAPI($mysqlObj,$lang){  
	$userDetails = s_GetUserDetails();
	$rcgObj=new b_rcgateway($userDetails->user,$mysqlObj,$lang);
	$rcgObj->RCGatewayID=$_POST['RCGatewayID'];
	$rcgObj->Name=$_POST['Name'];
	$rcgObj->URL=$_POST['URL'];
	echo json_encode($rcgObj->upsert($rcgObj));
}
function upsertAPIRechargeCode($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$rcgObj=new b_rcgatewaydetails($userDetails->user,$mysqlObj,$lang);
	$rcgObj->RCGatewayDetailsID=$_POST['RCGatewayDetailsID'];
	$rcgObj->RcGatewayID=$_POST['RcGatewayID'];
	$rcgObj->ServiceID=$_POST['ServiceID'];
	$rcgObj->RechargeCode=$_POST['RechargeCode'];
	$rcgObj->TopupCode=$_POST['TopupCode'];
	echo json_encode($rcgObj->upsert($rcgObj));
}

function getGatewayAPIs($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcgateway($userDetails->user,$mysqlObj,$lang);
	echo json_encode($ugObj->get_DT());
}
function getGatewayAPIDetails_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcgatewaydetails($userDetails->user,$mysqlObj,$lang);
	echo $ugObj->get_DT($_POST['RcGatewayID']);
}
function getUserGateway_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);
	echo $ugObj->get_DT($_POST['UserID']);
}

function upsertGeneralApi($mysqlObj,$lang){  
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);
	$ugObj->ServiceID=$_POST['ServiceID'];
	$ugObj->RCUserGatewayID=$_POST['RCUserGatewayID'];
	$ugObj->UserID=$_POST['UserID'];
	$ugObj->PrimaryGateway=$_POST['PrimaryGateway'];
	$ugObj->SecondaryGateway=$_POST['SecondaryGateway'];
	echo json_encode($ugObj->generalApi($ugObj));
}

/*
function GetGatewayListIds($mysqlObj,$lang){

	$userDetails = s_GetUserDetails();
	$userObj=new b_rcgateway($userDetails->user,$mysqlObj,$lang);

	echo json_encode($userObj->GetGatewayListIds());
}


function Upsert($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_rcgateway($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->RCGatewayID = $_POST['RCGatewayID'];
	$sObj->Name = $_POST['Name'];
	$sObj->URL = $_POST['URL'];
	echo json_encode($sObj->upsert($sObj));
}
*/
?>