<?php
include_once "../../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Reports/PayemntCollection.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_paycollectionreport.php';
$action = $_POST['Action'];
switch($action){
	case 'PayCollectionReport_DT':
		PayCollectionReport_DT($mysqlObj,$lang);
		break;
	case 'Delete':
		delete($mysqlObj,$lang);
		break;
	case 'GetGatewayList':
		GetGatewayListIds($mysqlObj,$lang);
		break;
}

function PayCollectionReport_DT($mysqlObj,$lang){
	$userId 	= $_POST['userId'];
	$mobile 	= $_POST['mobile'];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$userDetails = s_GetUserDetails();
	$dmObj=new b_paycollectionreport($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->getPayCollectionReport_DT($userId, $mobile, $fromDate, $toDate);
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