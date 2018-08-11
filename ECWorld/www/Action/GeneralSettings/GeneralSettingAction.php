<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/GeneralSettings.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
switch($action){
	case 'UpsertFees':
		UpsertFees($mysqlObj,$lang);
		break;
	case 'GetGeneralSettings':
		GetGeneralSettings($mysqlObj,$lang);
		break;
	case 'UpsertRCAmt':
		UpsertRCAmt($mysqlObj,$lang);
		break;
	case 'UpsertTRAmt':
		UpsertTRAmt($mysqlObj,$lang);
		break;
	case 'UpsertDTHAmt':
		UpsertDTHAmt($mysqlObj,$lang);
		break;
	/* case 'UpsertPAYAmt':
		UpsertPAYAmt($mysqlObj,$lang);
		break; */
	case 'UpsertUserBalance':
		UpsertUserBalance($mysqlObj,$lang);
		break;
	case 'UpsertSMSCost':
		UpsertSMSCost($mysqlObj,$lang);
		break;
	case 'UpsertSMSSetting':
		UpsertSMSSetting($mysqlObj,$lang);
		break;
	case 'UpsertRCSetting':
		UpsertRCSetting($mysqlObj,$lang);
		break;
}


function GetGeneralSettings($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$gsObj=new b_generalsettings($userDetails->user,$mysqlObj,$lang);
	//$objGS=new b_generalsettings($mysqlObj);
	echo json_encode($gsObj->get());
}

//Fees Amount
function UpsertFees($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->DistributorFees = $_POST['DistributorFees'];
	$sObj->SubDistributorFees = $_POST['SubDistributorFees'];
	$sObj->RetailerFees = $_POST['RetailerFees'];
	echo json_encode($sObj->UpsertFees($sObj));
}
//Recharge Amount
function UpsertRCAmt($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->RA_MinAmt = $_POST['RA_MinAmt'];
	$sObj->RA_MaxAmt = $_POST['RA_MaxAmt'];
	echo json_encode($sObj->UpsertRCAmt($sObj));
}
//Transfer Amount
function UpsertTRAmt($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->TA_MinAmt = $_POST['TA_MinAmt'];
	$sObj->TA_MaxAmt = $_POST['TA_MaxAmt'];
	$sObj->TA_RejectDuration = $_POST['TA_RejectDuration'];
	echo json_encode($sObj->UpsertTRAmt($sObj));
}
//DTH Amount
function UpsertDTHAmt($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->DTH_MinAmt = $_POST['DTH_MinAmt'];
	$sObj->DTH_MaxAmt = $_POST['DTH_MaxAmt'];
	echo json_encode($sObj->UpsertDTHAmt($sObj));
}

//User Balance Amount
function UpsertUserBalance($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->UB_Distributor_AlertEnable = isset($_POST['UB_Distributor_AlertEnable']) ? '1' : '0';
	$sObj->UB_Distributor_MinAmt = $_POST['UB_Distributor_MinAmt'];
	$sObj->UB_Distributor_MaxAmt = $_POST['UB_Distributor_MaxAmt'];
	
	$sObj->UB_SubDistributor_AlertEnable = isset($_POST['UB_SubDistributor_AlertEnable']) ? '1' : '0';
	$sObj->UB_SubDistributor_MinAmt = $_POST['UB_SubDistributor_MinAmt'];
	$sObj->UB_SubDistributor_MaxAmt = $_POST['UB_SubDistributor_MaxAmt'];
	
	$sObj->UB_Retailer_AlertEnable = isset($_POST['UB_Retailer_AlertEnable']) ? '1' : '0';
	$sObj->UB_Retailer_MinAmt = $_POST['UB_Retailer_MinAmt'];
	$sObj->UB_Retailer_MaxAmt = $_POST['UB_Retailer_MaxAmt'];
	echo json_encode($sObj->UpsertUserBalance($sObj));
}


//SMS Cost
function UpsertSMSCost($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->SC_FirstSMS_Enable = isset($_POST['SC_FirstSMS_Enable']) ? '1' : '0';
	$sObj->SC_FirstSMS_Cost = $_POST['SC_FirstSMS_Cost'];
	$sObj->SC_FailedRecharge_Cnt = $_POST['SC_FailedRecharge_Cnt'];
	$sObj->SC_FailedRecharge_Cost = $_POST['SC_FailedRecharge_Cost'];
	$sObj->SC_OfferSMS_Cnt = $_POST['SC_OfferSMS_Cnt'];
	$sObj->SC_OfferSMS_Cost = $_POST['SC_OfferSMS_Cost'];
	$sObj->SC_OTP_Cnt = $_POST['SC_OTP_Cnt'];
	$sObj->SC_OTP_Cost = $_POST['SC_OTP_Cost'];
	echo json_encode($sObj->UpsertSMSCost($sObj));
}


// SMS Setting
function UpsertSMSSetting($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->SS_Success_Msg = isset($_POST['SS_Success_Msg']) ? '1' : '0';
	$sObj->SS_Failed_Msg = isset($_POST['SS_Failed_Msg']) ? '1' : '0';
	$sObj->SS_AfterSuspence_Msg = isset($_POST['SS_AfterSuspence_Msg']) ? '1' : '0';
	$sObj->SS_Suspense_Msg = isset($_POST['SS_Suspense_Msg']) ? '1' : '0';
	$sObj->SS_Time_Delay = $_POST['SS_Time_Delay'];
	echo json_encode($sObj->UpsertSMSSetting($sObj));
}

// Recharge Setting
function UpsertRCSetting($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$sObj->RS_SmNo_SmAmt_Delay = $_POST['RS_SmNo_SmAmt_Delay'];
	$sObj->RS_SmNo_DiffAmt_Delay = $_POST['RS_SmNo_DiffAmt_Delay'];
	$sObj->RS_MNP_AutoRC_Enable = isset($_POST['RS_MNP_AutoRC_Enable']) ? '1' : '0';
	$sObj->RS_OTPRC_Enable = isset($_POST['RS_OTPRC_Enable']) ? '1' : '0';
	echo json_encode($sObj->UpsertRCSetting($sObj));
}
?>