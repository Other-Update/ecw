<?php
include_once "../../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Reports/PayemntCollection.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
$action = $_POST['Action'];
switch($action){
	case 'RechargeReport_DT':
		RechargeReport_DT($mysqlObj,$lang);
		break;
}

function RechargeReport_DT($mysqlObj,$lang){
	$userId 	= $_POST['userId'];
	$mobile 	= $_POST['mobile'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$userDetails = s_GetUserDetails();
	$tObj=new b_recharge($userDetails->user,$mysqlObj,$lang);
	echo $tObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate,false);
}
?>