<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_rcusergateway.php';
$action = $_POST['Action'];
switch($action){
	case 'GetUserRCGateway_DT':
		getUserRCGateway_DT($mysqlObj,$lang);
		break;
	case 'UpsertAmount':
		upsertAmount($mysqlObj,$lang);
		break;
	case 'Delete':
		delete($mysqlObj,$lang);
		break;
}

function getUserRCGateway_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);

	echo $ugObj->get_DT($_POST['UserID']);
}

function upsertAmount($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);
	$ugObj->RCUserGatewayID=$_POST['RCUserGatewayID'];
	$ugObj->UserID=$_POST['UserID'];
	$ugObj->ServiceID=$_POST['ServiceID'];
	$ugObj->Amount=$_POST['Amount'];
	echo json_encode($ugObj->upsertAmount($ugObj));
}
function delete($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);

	echo json_encode($ugObj->delete($_POST['UserGatewayID']));
}
?>