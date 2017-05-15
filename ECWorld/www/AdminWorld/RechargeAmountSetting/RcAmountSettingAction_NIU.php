<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/RcAmountSetting.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_rcamountsetting.php';
$action = $_POST['Action'];
switch($action){
	case 'GetRcAmount_DT':
		GetRcAmount_DT($mysqlObj,$lang);
		break;
	case 'Upsert':
		Upsert($mysqlObj,$lang);
		break;
}

function GetRcAmount_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$dmObj=new b_rcamountsetting($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->get_DT();
}

//Insert Auto
function Upsert($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$amtObj=new b_rcamountsetting($loggedInUserDetails->user,$mysqlObj,$lang);
	$amtObj->RcAmountID = $_POST['RcAmountID'];
	$amtObj->ServiceID = $_POST['ServiceID'];
	$amtObj->RechargeTypeID = $_POST['RechargeTypeID'];
	$amtObj->RCDenomination = $_POST['RCDenomination'];
	$amtObj->TPDenomination = $_POST['TPDenomination'];
	$amtObj->InvalidAmount = $_POST['InvalidAmount'];
	echo json_encode($amtObj->upsert($amtObj));
}






?>