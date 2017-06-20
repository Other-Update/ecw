<?php
include_once "../../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Reports/PayemntCollection.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_transaction.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'TransactionReport_DT':
		TransactionReport_DT($mysqlObj,$lang);
		break;
	case 'TransactionOpenCloseBalance':
		TransactionOpenCloseBalance($mysqlObj,$lang);
		break;
}

function TransactionReport_DT($mysqlObj,$lang){
	$userId 	= $_POST['userId'];
	$mobile 	= $_POST['mobile'];
	$network 	= $_POST['network'];
	$requestId 	= $_POST['requestId'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$userDetails = s_GetUserDetails();
	$tObj=new b_transaction($userDetails->user,$mysqlObj,$lang);
	echo $tObj->getTransactionReport_DT($userId, $mobile, $network, $requestId, $fromDate, $toDate);
}


//Get Opening & Closing balance
function TransactionOpenCloseBalance($mysqlObj,$lang){
	$userId 	= $_POST['userId'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$userDetails = s_GetUserDetails();
	$tObj=new b_transaction($userDetails->user,$mysqlObj,$lang);
	echo $tObj->getTransactionOpenCloseBalance($userId, $fromDate, $toDate);
}

?>