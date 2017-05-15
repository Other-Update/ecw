<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_common.php';
include_once APPROOT_URL.'/Business/b_webservice.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
/* My Code Added By Reghu */
include_once APPROOT_URL.'/Business/b_transaction.php';
include_once APPROOT_URL.'/Business/b_payment.php';
/* End Code */
//echo "test";
$action = $_POST['Action'];
switch($action){
	case 'GetNetwork':
		GetNetwork($mysqlObj,$lang);
		break;
	case 'GetNetworkList':
		GetNetworkList($mysqlObj,$lang);
		break; 
	case 'FundTransfer':
		FundTransfer($mysqlObj,$lang,$langSMS,$langAPI);
		break; 
	case 'UpsertPrepaid':
		UpsertPrepaid($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'UpsertPostpaid':
		UpsertPostpaid($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'UpsertDTH':
		UpsertDTH($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'UpsertDatacard':
		UpsertDatacard($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'UpsertLandline':
		UpsertLandline($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'GetDashboardData':
		GetDashboardData($mysqlObj,$lang);
		break;
	case 'getRechargeReportDashboard':
		getRechargeReportDashboard($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'getRechargeReportRecharge_NIU':
		getRechargeReportRecharge($mysqlObj,$lang,$langSMS,$langAPI);
		break;
	case 'GetUserDetails':
		getUserDetails($mysqlObj,$lang);
		break;
	case 'getCurrentRechargeReport':
		getCurrentRechargeReport($mysqlObj,$lang,$langSMS,$langAPI);
		break;
}

function getUserDetails($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	echo json_encode($userObj->getWalletBalance($_POST['UserID']));
}

/* My Code Added By Reghu */

function getRechargeReportDashboard($mysqlObj,$lang,$langSMS,$langAPI){
	$limit = 8;
	$mobile = '';
	$fromDate = '2017-01-01';
	$toDate = date('Y-m-d');
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$userId = $loggedInUserDetails->user->UserID;
	$bGsObj = new b_generalsettings($loggedInUserDetails,$mysqlObj,"");
	$gs = $bGsObj->get();
	$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS,$langAPI,$gs);
	echo $rObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $limit, false);
}

function getRechargeReportRecharge_NIU($mysqlObj,$lang,$langSMS,$langAPI){
	$limit = 8;
	$mobile = '';
	$fromDate = '2017-01-01';
	$toDate = date('Y-m-d');
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$userId = $loggedInUserDetails->user->UserID;
	$bGsObj = new b_generalsettings($loggedInUserDetails,$mysqlObj,"");
	$gs = $bGsObj->get();
	$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS,$langAPI,$gs);
	echo $rObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $limit, false);
}

function getCurrentRechargeReport($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$userId = $loggedInUserDetails->user->UserID;
	$bGsObj = new b_generalsettings($loggedInUserDetails,$mysqlObj,"");
	$gs = $bGsObj->get();
	$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS,$langAPI,$gs);
	echo $rObj->getCurrentRechargeReport_DT();
}

function GetDashboardData($mysqlObj,$lang){

	$today =date('Y-m-d');
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	
	//Available Balance
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$WalletAmount = json_encode($userObj->getWalletBalance($loggedInUserDetails->user->UserID));
	
	//Opening Balance
	$dObj=new b_transaction($loggedInUserDetails->user,$mysqlObj,$lang);
	$OpeningAmount = $dObj->getOpeningBlanceByUserID($loggedInUserDetails->user->UserID);
	
	//Purchase Balance
	$pObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	$PurchaseTotal = json_encode($pObj->getTransferByDate($loggedInUserDetails->user->UserID,$today));
	
	//Sales Balance
	$SalesTotal = json_encode($dObj->getTransferSalesByDate($loggedInUserDetails->user->UserID,$today));
	//$SalesTotal = json_encode($pObj->getTransferSalesByDate($loggedInUserDetails->user->UserID,$today));
	
	$result='{"WalletAmount":'.$WalletAmount.', "OpeningAmount":'.$OpeningAmount.', "PurchaseTotal":'.$PurchaseTotal.', "SalesTotal":'.$SalesTotal.'}';
	echo $result;
}

/* End Code */

//On Keyup function
function GetNetwork($mysqlObj,$lang){
	$userObj=new b_dashboard('',$mysqlObj,$lang);
	echo json_encode($userObj->getNetwork($_POST['Mobile'], $_POST['RCType']));
}


function GetNetworkList($mysqlObj,$lang){
	$userObj=new b_automnp('',$mysqlObj,$lang);
	echo json_encode($userObj->getNetworkList());	
}

function addRequest($user,$mysqlObj,$lang,$targetNumber,$targetAmount){
	$bReqObj=new b_request($user,$mysqlObj,$lang);
	$today =date('Y-m-d H:i:s');
	
	$bReqObj = $bReqObj->giveMeObj($user->UserID,$targetNumber,$_SERVER["REMOTE_ADDR"],"WEB","000000",$targetAmount,"WEB REQUEST","Just Request Came","1",$today,$today,"WEB REQUEST","WEB");
	$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"W");
	$reqID = $bReqObj->add($bReqObj);
	//addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs)
	$bReqObj->RequestID = $reqID;
	$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
	return $bReqObj;
}
function callProcessRecharge($mysqlObj,$lang,$langSMS,$langAPI,$rcType,$rcMobile,$rcOperator,$rcAmount){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//var $bGsObj;//General Settings
	$bGsObj = new b_generalsettings($loggedInUserDetails,$mysqlObj,"");
	$gs = $bGsObj->get();
	$rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS,$langAPI,$gs);
	$rObj->RechargeType = $rcType;
	$rObj->Mobile = $rcMobile;
	$rObj->Operator = $rcOperator;
	$rObj->Amount= $rcAmount;
	$bReqObj = addRequest($loggedInUserDetails->user,$mysqlObj,$lang,$rObj->Mobile,$rObj->Amount);
	return $rObj->processRecharge($bReqObj,$rObj->Operator,$rObj->Mobile,$rObj->Amount);
}

function FundTransfer($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$toUserID = $_POST['ToUserID'];
	$amount = $_POST['Amount'];
	$bReqObj = addRequest($loggedInUserDetails->user,$mysqlObj,$lang,$toUserID,$amount);
	//echo json_encode($bReqObj);die;
	$bReqObj->Message="FT ".$toUserID." ".$amount;
	$wsObj = new b_webservice($loggedInUserDetails->user,$mysqlObj,'',$langSMS,$langAPI);
	$wsObj->bReqObj=$bReqObj;
	$res = $wsObj->processMessage($bReqObj);
	/* echo "this->resultIsSuccess=".$wsObj->resultIsSuccess;
	echo "this->smsCode=".$wsObj->smsCode;
	echo "this->bReqObj->Status=".$wsObj->bReqObj->Status;
	echo "this->resultSmsMessage=".$wsObj->resultSmsMessage; */
	$resultObj = new httpresult();
	if($wsObj->resultIsSuccess){
		$bReqObj->Status=3;
		$resultObj->isSuccess=true;
		$resultObj->message = "Successfully Transferred";
	}
	else{
		$bReqObj->Status=4;
		$resultObj->isSuccess=false;;
		$resultObj->message = "Error:".$wsObj->resultMessage;
	}
	$bReqObj->updateStatus($bReqObj);
	echo json_encode($resultObj);
}
//Prepaid number
function UpsertPrepaid($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$langAPI,$_POST['RechargeType'],$_POST['Pre_mobile'],$_POST['Pre_operator'],$_POST['rcAmountPrepaid']);
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
function UpsertPostpaid($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$langAPI,$_POST['RechargeType'],$_POST['Post_Mobile'],$_POST['Post_operator'],$_POST['rcAmountPostpaid']);
	echo json_encode($res);
}

//Recharge DTH
function UpsertDTH($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$langAPI,$_POST['RechargeType'],$_POST['dthNumber'],$_POST['dthOperator'],$_POST['dthAmount']);
	echo json_decode($res);
}


//Recharge Datacard
function UpsertDatacard($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$langAPI,$_POST['RechargeType'],$_POST['datacardNumber'],$_POST['datacardOperator'],$_POST['datacardAmount']);
	echo json_encode($res);
	/* $rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->AccountNumber = $_POST['datacardNumber'];
	$rObj->Operator 	= $_POST['datacardOperator'];
	$rObj->Amount		= $_POST['datacardAmount'];
	echo json_encode($rObj->upsert($rObj)); */
}

//Recharge Landline
function UpsertLandline($mysqlObj,$lang,$langSMS,$langAPI){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$res = callProcessRecharge($mysqlObj,$lang,$langSMS,$langAPI,$_POST['RechargeType'],$_POST['landlineNumber'],$_POST['landlineOperator'],$_POST['landlineAmount']);
	echo json_encode($res);
	/* $rObj=new b_recharge($loggedInUserDetails->user,$mysqlObj,$lang,$langSMS);
	$rObj->RechargeType = $_POST['RechargeType'];
	$rObj->AccountNumber = $_POST['landlineNumber'];
	$rObj->Operator 	= $_POST['landlineOperator'];
	$rObj->Amount		= $_POST['landlineAmount'];
	echo json_encode($rObj->upsert($rObj)); */
}




?>