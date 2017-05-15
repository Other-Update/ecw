<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_rcgateway.php';
$action = $_POST['Action'];
switch($action){
	case 'GetGateway_DT':
		getGateway_DT($mysqlObj,$lang);
		break;
	case 'Delete':
		delete($mysqlObj,$lang);
		break;
	case 'GetGatewayList':
		GetGatewayListIds($mysqlObj,$lang);
		break;
}

function getGateway_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$ugObj=new b_rcusergateway($userDetails->user,$mysqlObj,$lang);
	echo $ugObj->get_DT($_POST['UserID']);
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