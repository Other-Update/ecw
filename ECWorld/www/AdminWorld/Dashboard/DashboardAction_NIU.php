<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_common.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
$action = $_POST['Action'];
switch($action){
	case 'GetNetwork':
		GetNetwork($mysqlObj,$lang);
		break;
	case 'GetNetworkList':
		GetNetworkList($mysqlObj,$lang);
		break; 
	case 'UpsertPrepaid':
		UpsertPrepaid($mysqlObj,$lang,$langSMS);
		break;
	case 'UpsertPostpaid':
		UpsertPostpaid($mysqlObj,$lang,$langSMS);
		break;
	case 'UpsertDTH':
		UpsertDTH($mysqlObj,$lang,$langSMS);
		break;
	case 'UpsertDatacard':
		UpsertDatacard($mysqlObj,$lang,$langSMS);
		break;
	case 'UpsertLandline':
		UpsertLandline($mysqlObj,$lang,$langSMS);
		break;

}

//On Keyup function
function GetNetwork($mysqlObj,$lang){
	$userObj=new b_dashboard('',$mysqlObj,$lang);
	echo json_encode($userObj->getNetwork($_POST['Mobile'], $_POST['RCType']));
}


function GetNetworkList($mysqlObj,$lang){
	$userObj=new b_automnp('',$mysqlObj,$lang);
	echo json_encode($userObj->getNetworkList());	
}

function callProcessRecharge($mysqlObj,$lang,$langSMS,$rcType,$rcMobile,$rcOperator,$rcAmount){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//var $bGsObj;//General Settings
	$bGsObj = new b_generalsettings($loggedInUserDetails,$mysqlObj,"");
	$gs = $bGsObj->get();
	$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS,$gs);
	$rObj->RechargeType = $rcType;
	$rObj->Mobile = $rcMobile;
	$rObj->Operator = $rcOperator;
	$rObj->Amount= $rcAmount;
	$bReqObj=new b_request($loggedInUserDetails->user,$mysqlObj,$lang);
	$today =date('Y-m-d H:i:s');
	
	$bReqObj = $bReqObj->giveMeObj($loggedInUserDetails->user->UserID,$rObj->Mobile,$_SERVER["REMOTE_ADDR"],"WEB","000000",$rObj->Amount,"WEB REQUEST","Just Request Came","1",$today,$today,"WEB REQUEST");
	$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"W");
	$reqID = $bReqObj->add($bReqObj);
	//addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs)
	$bReqObj->RequestID = $reqID;
	$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
	return $rObj->processRecharge($bReqObj,$rObj->Operator,$rObj->Mobile,$rObj->Amount);
}
//Prepaid number
function UpsertPrepaid($mysqlObj,$lang,$langSMS){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$_POST['RechargeType'],$_POST['Pre_mobile'],$_POST['Pre_operator'],$_POST['rcAmountPrepaid']);
	echo json_encode($res);
	/*$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->Mobile = $_POST['Pre_mobile'];
	$rObj->Operator = $_POST['Pre_operator'];
	$rObj->Amount= $_POST['rcAmountPrepaid'];
	$bReqObj=new b_request($loggedInUserDetails->user,$mysqlObj,$lang);
		$today =date('Y-m-d H:i:s');*/
		/* $bReqObj->RequesterMobile=$rObj->Mobile;
		//$bReqObj->RequesterID=$loggedInUserDetails->user->UserID;//Don't use this. Instead use UserID
		$bReqObj->UserID=$loggedInUserDetails->user->UserID;
		$bReqObj->RequesterIP=$_SERVER["REMOTE_ADDR"];
		$bReqObj->RequesterApp="WEB";
		$bReqObj->TargetNo="000000";
		$bReqObj->TargetAmount=$rObj->Amount;
		$bReqObj->Message="WEB REQUEST";
		$bReqObj->DevInfo="Just Request Came";
		$bReqObj->Status="1";
		$bReqObj->ReqDateTime=($today);
		$bReqObj->ReqReceivedDateTime=$today;
		$bReqObj->InputAsIs="WEB REQUEST"; */
	
	/*$bReqObj = $bReqObj->giveMeObj($loggedInUserDetails->user->UserID,$rObj->Mobile,$_SERVER["REMOTE_ADDR"],"WEB","000000",$rObj->Amount,"WEB REQUEST","Just Request Came","1",$today,$today,"WEB REQUEST");
	$reqID = $bReqObj->add($bReqObj);
	//addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs)
	$bReqObj->RequestID = $reqID;
	$bReqObj->DisplayID = $bReqObj->updateDisplayID($bReqObj,"W");
	echo json_encode($rObj->processRecharge($bReqObj,$rObj->Operator,$rObj->Mobile,$rObj->Amount));*/
}

//Postpaid number
function UpsertPostpaid($mysqlObj,$lang,$langSMS){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$_POST['RechargeType'],$_POST['Post_Mobile'],$_POST['Post_operator'],$_POST['rcAmountPostpaid']);
	echo json_encode($res);
	/*$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->Mobile = $_POST['Post_Mobile'];
	$rObj->Operator = $_POST['Post_operator'];
	$rObj->Amount= $_POST['rcAmountPostpaid'];
	echo json_encode($rObj->processRecharge($rObj->RechargeType,$rObj->Operator,$rObj->Mobile,$rObj->Amount));*/
}

//Recharge DTH
function UpsertDTH($mysqlObj,$lang,$langSMS){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$_POST['RechargeType'],$_POST['dthNumber'],$_POST['dthOperator'],$_POST['dthAmount']);
	echo json_decode($res);
	/* $rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->AccountNumber = $_POST['dthNumber'];
	$rObj->Operator 	= $_POST['dthOperator'];
	$rObj->Amount		= $_POST['dthAmount'];
	echo json_encode($rObj->upsert($rObj)); */
}


//Recharge Datacard
function UpsertDatacard($mysqlObj,$lang,$langSMS){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$_POST['RechargeType'],$_POST['datacardNumber'],$_POST['datacardOperator'],$_POST['datacardAmount']);
	echo json_encode($res);
	/* $rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->AccountNumber = $_POST['datacardNumber'];
	$rObj->Operator 	= $_POST['datacardOperator'];
	$rObj->Amount		= $_POST['datacardAmount'];
	echo json_encode($rObj->upsert($rObj)); */
}

//Recharge Landline
function UpsertLandline($mysqlObj,$lang,$langSMS){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$_POST['RechargeType'],$_POST['landlineNumber'],$_POST['landlineOperator'],$_POST['landlineAmount']);
	echo json_encode($res);
	/* $rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->AccountNumber = $_POST['landlineNumber'];
	$rObj->Operator 	= $_POST['landlineOperator'];
	$rObj->Amount		= $_POST['landlineAmount'];
	echo json_encode($rObj->upsert($rObj)); */
}




?>