<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Service.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
include_once APPROOT_URL.'/Business/b_service.php';
include_once APPROOT_URL.'/Business/b_rechargetype.php';
include_once APPROOT_URL.'/Business/b_networkprovider.php';
include_once APPROOT_URL.'/Business/b_networkmode.php';
$action = $_POST['Action'];
switch($action){
	case 'Upsert':
		Upsert($mysqlObj,$lang);
		break;
	case 'Delete':
		delete($mysqlObj,$lang);
		break;
	case 'GetRechargeTypes':
		GetRechargeTypes($mysqlObj,$lang);
		break;
	case 'GetServices_DT':
		GetServices_DT($mysqlObj,$lang);
		break;
	case 'GetNetworkProvider':
		GetNetworkProvider($mysqlObj,$lang);
		break;
	case 'GetNetworkMode':
		GetNetworkMode($mysqlObj,$lang);
		break;
	case 'GetServiceList':
		GetServiceList($mysqlObj,$lang);
		break;
	case 'GetServiceOperator':
		GetServiceOperator($mysqlObj,$lang);
		break;
}
//On Keyup function for search operator in dashboard page
function GetServiceOperator($mysqlObj,$lang){
	$loggedInUserDetails = s_GetUserDetails();
	$serviceObj=new b_service('',$mysqlObj,$lang);
	if($_POST['RCType']==3){//3-DTH
		
		$rcObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,"","","");
		$dthCode = $rcObj->findDTHCode($_POST['Mobile'], "",true);
		//echo $dthCode;
		$test = array();
		$test[]=$serviceObj->getByCode($dthCode,3);
		echo json_encode($test);
	}else{
		echo json_encode($serviceObj->getServiceOperator($_POST['Mobile'], $_POST['RCType']));
	}
}
function delete($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$dmObj=new b_service($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->delete($_POST['ServiceID']);
}

function GetRechargeTypes($mysqlObj,$lang){	
	$userDetails = s_GetUserDetails();
	$rtObj=new b_rechargetype($userDetails->user,$mysqlObj,$lang);
	echo json_encode($rtObj->getRechargeTypes());
}
function GetServices_DT($mysqlObj,$lang){
	
	$userDetails = s_GetUserDetails();
	$dmObj=new b_service($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->get_DT();
	/* $loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$dtObj=new b_datatable($loggedInUserDetails->user,$mysqlObj,$lang);
	$res = $dtObj->get('m_service', 'ServiceID', array('Name', 'RechargeCode', 'TopupCode','DefaultType'),"");
	echo $res; */
}
//Not being called - 19/10
function GetAll($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_service($loggedInUserDetails->user,$mysqlObj,$lang);
	echo json_encode($sObj->getAll());
}
function Upsert($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_service($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->ServiceID = $_POST['ServiceID'];
	$sObj->Name = $_POST['Name'];
	$sObj->DefaultType = $_POST['DefaultType'];
	$sObj->RechargeCode = $_POST['RechargeCode'];
	$sObj->TopupCode = $_POST['TopupCode'];
	$sObj->NetworkProviderID = $_POST['NetworkProviderID'];
	$sObj->NetworkMode = $_POST['NetworkMode'];
	echo json_encode($sObj->upsert($sObj));
}

//Get Network Provider 
function GetNetworkProvider($mysqlObj,$lang){	
	$userDetails = s_GetUserDetails();
	$rtObj=new b_networkprovider($userDetails->user,$mysqlObj,$lang);
	echo json_encode($rtObj->getNetworkProvider());
}

//Get Network Mode 
function GetNetworkMode($mysqlObj,$lang){	
	$userDetails = s_GetUserDetails();
	$rtObj=new b_networkmode($userDetails->user,$mysqlObj,$lang);
	echo json_encode($rtObj->getNetworkMode());
}

//Get service list for dashboard page 
function GetServiceList($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$rtObj=new b_service($userDetails->user,$mysqlObj,$lang);
	echo json_encode($rtObj->getServiceList());
}

?>