<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/UserRequest.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_common.php';
include_once APPROOT_URL.'/Business/b_userrequest.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'GetAll_DT':
		GetAll_DT($mysqlObj,$lang);
		break;
	case 'Upsert':
		Upsert($mysqlObj,$lang);
		break;
	case 'GetUserRequest':
		GetUserRequest($mysqlObj,$lang);
		break; 
}

function GetAll_DT($mysqlObj,$lang){
	$userId 	= $_POST['userId'];
	$mobile 	= $_POST['mobile'];
	$fromTable 	= $_POST['fromTable'];
	$requestId 	= $_POST['requestId'];
	$status 	= $_POST['status'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$userDetails = s_GetUserDetails();
	$dmObj=new b_userrequest($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->get_DT($userId, $fromTable, $mobile, $requestId, $status, $fromDate, $toDate);
}

//Onchange event function for popup form search request id
function GetUserRequest($mysqlObj,$lang){
	$userObj=new b_userrequest('',$mysqlObj,$lang);
	echo json_encode($userObj->getUserRequest($_POST['SearchRequest']));
}

//Insert Auto
function Upsert($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$compObj=new b_userrequest($loggedInUserDetails->user,$mysqlObj,$lang);
	$compObj->ComplaintID = $_POST['ComplaintID'];
	$compObj->RequestID = $_POST['RequestID'];
	$compObj->FromTable = $_POST['FromTable'];
	$compObj->Status = $_POST['Status'];
	$compObj->PrevStatus = $_POST['PrevStatus'];
	$compObj->Remark = $_POST['Remark'];
	$compObj->SendSms = $_POST['SendSms'];
	echo json_encode($compObj->upsert($compObj));
}


?>