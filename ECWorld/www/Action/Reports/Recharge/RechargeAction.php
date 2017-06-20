<?php
include_once "../../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Reports/PayemntCollection.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'RechargeReport_DT':
		RechargeReport_DT($mysqlObj,$lang,$langSMS,$langAPI);
		break;
}

function RechargeReport_DT($mysqlObj,$lang,$langSMS,$langAPI){
	$limit = 1000;
	$userId 	= $_POST['userId'];
	$mobile 	= $_POST['mobile'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$userDetails = s_GetUserDetails();
	$tObj=new b_recharge($userDetails->user,$mysqlObj,'',$langSMS,$langAPI,'');
	echo $tObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $limit, false);
}
?>