<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/AutoMnpRecharge.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_common.php';
include_once APPROOT_URL.'/Business/b_automnp.php';
$action = $_POST['Action'];
switch($action){
	case 'UpsertAuto':
		UpsertAuto($mysqlObj,$lang);
		break;
	case 'UpsertMnp':
		UpsertMnp($mysqlObj,$lang);
		break;
	case 'GetAuto_DT':
		GetAuto_DT($mysqlObj,$lang);
		break;
	case 'GetMNP_DT':
		GetMNP_DT($mysqlObj,$lang);
		break;
	case 'GetNetwork':
		GetNetwork($mysqlObj,$lang);
		break;
	case 'GetNetworkList':
		GetNetworkList($mysqlObj,$lang);
		break; 
	case 'Delete':
		delete($mysqlObj,$lang,"m_auto_mnp","NeworkID",$_POST["NeworkID"]);
		break; 
}
function delete($mysqlObj,$lang,$table,$field,$value){
	$userDetails = s_GetUserDetails();
	$common=new b_common($userDetails->user,$mysqlObj,$lang);
	echo $common->makeInActive($table,$field,$value);
}
function GetAuto_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$dmObj=new b_automnp($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->get_DT($_POST['idRCType']);
}

//On Keyup function
function GetNetwork($mysqlObj,$lang){
	$userObj=new b_automnp('',$mysqlObj,$lang);
	echo json_encode($userObj->getNetwork($_POST['Mobile'], $_POST['RCType']));
}

//Dropdown 
function GetNetworkList($mysqlObj,$lang){
	$userObj=new b_automnp('',$mysqlObj,$lang);
	echo json_encode($userObj->getNetworkList());	
}

//Insert Auto
function UpsertAuto($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$autoObj=new b_automnp($loggedInUserDetails->user,$mysqlObj,$lang);
	$autoObj->NeworkID = $_POST['NeworkID'];
	$autoObj->MobileNo = $_POST['AutoMobileNo'];
	$autoObj->NetworkName = $_POST['AutoNetwork'];
	$autoObj->Name = $_POST['othresAuto'];
	$autoObj->RCType = 1;
	echo json_encode($autoObj->upsert($autoObj));
}

//Insert MNP
function UpsertMnp($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$mnpObj=new b_automnp($loggedInUserDetails->user,$mysqlObj,$lang);
	$mnpObj->NeworkID = $_POST['NeworkID'];
	$mnpObj->MobileNo = $_POST['MNPMobileNo'];
	$mnpObj->NetworkName = $_POST['MNPNetwork'];
	$mnpObj->RCType = 0;
	$mnpObj->Name = $_POST['othresMNP'];
	echo json_encode($mnpObj->upsert($mnpObj));
}




?>