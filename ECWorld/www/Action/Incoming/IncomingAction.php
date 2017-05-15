<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_request.php';
$action = $_POST['Action'];
switch($action){
	case 'GetIncoming_DT':
		GetIncoming_DT($mysqlObj, '');
		break;
}

function GetIncoming_DT($mysqlObj, $lang){
	$userId 	= $_POST['userId'];
	$message 	= $_POST['message'];
	$server_no 	= $_POST['server_no'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//$userId = $loggedInUserDetails->user->UserID;
	$dmObj=new b_request($loggedInUserDetails->user,$mysqlObj,'');
	echo $dmObj->getIncoming_DT($userId, $message, $server_no, $fromDate, $toDate);
}
?>