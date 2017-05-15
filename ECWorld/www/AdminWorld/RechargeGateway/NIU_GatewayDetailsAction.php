<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_rcgatewaydetails.php';
$action = $_POST['Action'];
switch($action){
	case 'Upsert':
		Upsert($mysqlObj,$lang);
		break;
	case 'GetGateWayDetails_DT':
		GetGateWayDetails_DT($mysqlObj,$lang);
		break;
}


function GetGateWayDetails_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$rcgObj=new b_rcgatewaydetails($userDetails->user,$mysqlObj,$lang);
	echo $rcgObj->get_DT($_POST["GatewayID"]);
	/* $loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$dtObj=new b_datatable($loggedInUserDetails->user,$mysqlObj,$lang);
	$res = $dtObj->get('m_service', 'ServiceID', array('Name', 'RechargeCode', 'TopupCode','DefaultType'),"");
	echo $res; */
}

function GetGatewayListIds($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$userObj=new b_rcgateway($userDetails->user,$mysqlObj,$lang);
	echo json_encode($userObj->GetGatewayListIds());
}


function Upsert($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_rcgatewaydetails($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->RCGatewayDetailsID = $_POST['RCGatewayDetailsID'];
	$sObj->Code = $_POST['Code'];
	echo json_encode($sObj->upsert($sObj));
}
?>