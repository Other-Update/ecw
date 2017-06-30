<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_sms.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'GetOutgoing_DT':
		GetOutgoing_DT($mysqlObj,'','','','','');
		break;
}

function GetOutgoing_DT($mysqlObj, $lang){
	$userId 	= $_POST['userId'];
	$message 	= $_POST['message'];
	$api_name 	= $_POST['api_name'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//$userId = $loggedInUserDetails->user->UserID;
	$dmObj=new b_sms($loggedInUserDetails->user,$mysqlObj,'','','','');
	echo $dmObj->GetOutgoing_DT($userId, $message, $api_name, $fromDate, $toDate);
}
?>