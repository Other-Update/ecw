<?php
//session_start();
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/User.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/Business//b_users.php';
include_once APPROOT_URL.'/Business/b_role.php';
include_once APPROOT_URL.'/Business/b_fat.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_sms.php';
include_once APPROOT_URL.'/Business/b_http.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];

switch($action){
	case 'Upsert':
		//$mysqlObj - deifned in mysql file,$lang-defined in resource file
		Upsert($mysqlObj,$lang,$langSMS);
		break;
	/* case 'GetParents':
		GetParents($mysqlObj,$lang);
		break; */
	case 'GetUsers_DT':
		GetUsers_DT($mysqlObj,$lang);
		break; 
	case 'GetUsersByParent_DT':
		GetUsersByParent_DT($mysqlObj,$lang);
		break; 
	case 'GetUsersByRoles':
		GetUsersByRoleIDs($mysqlObj,$lang);
		break; 
	case 'GetUsersByParent':
		getUsersByParent($mysqlObj,$lang);
		break;
	case 'GetAllUsers':
		GetAllUsers($mysqlObj,$lang);
		break;
	case 'GetRoles':
		GetRoles($mysqlObj);
		break;
	case 'GetGeneralSettings':
		GetGeneralSettings($mysqlObj,$lang);
		break;
	case 'IsMobileExists':
		IsMobileExists($mysqlObj,$lang);
		break;
	case 'GetLoggedInUser':
		GetLoggedInUser($mysqlObj);
		break;
	case 'DeleteUser':
		DeleteUser($mysqlObj,$lang);
		break;
	case 'UpdateUserStatus':
		UpdateUserStatus($mysqlObj,$lang);
		break;
	case 'GetByID':
		GetByID($mysqlObj,$lang);
		break;
	case 'GetWalletBalance':
		getWalletBalance($mysqlObj,$lang);
		break;
	case 'UpdateFat':
		UpdateFat($mysqlObj,$lang);
		break;
	case 'Signout':
		echo Session_Signout();
		break;
}
function GetLoggedInUser($mysqlObj){
	echo $_SESSION['me'];
}
//Get all users who can be parent(This will return users whose role is not AdminUser and Retailer)
/* function GetParents($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$roles = ['Admin','StateDistributor','Distributor','SubDistributor'];
	echo json_encode($userObj->getUsersByRoles($roles));
} */

function getWalletBalance($mysqlObj,$lang){
	$me = s_GetUserDetails();
	$userObj=new b_users($me->user,$mysqlObj,$lang);
	echo json_encode($userObj->getWalletBalance($_POST['UserID']));
}
//Get all users for datatable
function GetUsers_DT($mysqlObj,$lang){
	$me = s_GetUserDetails();
	$userObj=new b_users($me->user,$mysqlObj,$lang);
	echo $userObj->getUsers_DT();
}
//Get all users for datatable
function GetUsersByParent_DT($mysqlObj,$lang){
	$parentID = $_POST['ParentID'];
	$roleID="0";
	if(isset($_POST['RoleID']))
	$roleID=$_POST['RoleID'];
	/* if(isset($_POST['IncludeParent']))
		if($_POST['IncludeParent'])
			$includeParent=true; */
	$me = s_GetUserDetails();
	$includeParent=$me->user->UserID==1?true:false;
	//echo json_encode($me);
	if($parentID=="LoggedInUser" || $parentID==0)
		$parentID=$me->user->UserID;
	$userObj=new b_users($me->user,$mysqlObj,$lang);
	echo $userObj->getUsersByParent_DT($parentID,$includeParent,$roleID);
}
//Get all users whose role is matching the given role
function GetUsersByRoleIDs($mysqlObj,$lang){
	$roleIDs = $_POST['RoleIDs'];
	//echo 'a';
	$userDetails = s_GetUserDetails();
	//$userDetails = json_decode(json_decode($_SESSION['me']));
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	$roles = explode(',',$roleIDs);
	echo json_encode($userObj->getUsersByRoleIDs($roles,$userDetails->user->UserID));
}

function getUsersByParent($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$parentID = $_POST['ParentID'];
	$includeAllSubUsers = 0;
	if(isset($_POST['IncludeAllSubUsers']))
		$includeAllSubUsers=$_POST['IncludeAllSubUsers'];
	$excludeRoleIDs="";
	if(isset($_POST['ExcludeRoleIDs']))
		$excludeRoleIDs=$_POST['ExcludeRoleIDs'];
	$excludeRoleIDs = explode(',',$_POST['ExcludeRoleIDs']);
	
	$includeParent=false;
	if(isset($_POST['IncludeParent']))
		if($_POST['IncludeParent'])
			$includeParent=true;
	if($parentID=="LoggedInUser" || $parentID==0)
		$parentID=$userDetails->user->UserID;
	//echo 'a';
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	//$roles = explode(',',$roleIDs);
	echo json_encode($userObj->getUsersByParentID($parentID,$includeParent,$excludeRoleIDs,$includeAllSubUsers));
}
function GetAllUsers($mysqlObj,$lang){
	$today =date('Y-m-d H:i:s');
	//echo ",Call started=".$today;
	$parentID = $_POST['ParentID'];
	$includeAllSubUsers = $_POST['IncludeAllSubUsers'];
	//echo "<br/> includeAllSubUsers".$includeAllSubUsers;
	$excludeRoleIDs = explode(',',$_POST['ExcludeRoleIDs']);
	$includeParent=false;
	if(isset($_POST['IncludeParent']))
		if($_POST['IncludeParent'])
			$includeParent=true;
	$includeFeatureAccess=false;
	if(isset($_POST['IncludeFeatureAccess']))
		if($_POST['IncludeFeatureAccess'])
			$includeFeatureAccess=true;
	$userDetails = s_GetUserDetails();
	$parentID = $parentID==0?$userDetails->user->UserID:$parentID;
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	//$roles = explode(',',$roleIDs);
	//$fatObj = new b_fat($userDetails->user,$mysqlObj);
	//$fatRes = $fatObj->getByParent($parentID);
	
	$today =date('Y-m-d H:i:s');
	//echo ",Before get users=".$today;
	$userRes = $userObj->getAllUsers($parentID,$includeParent,$includeAllSubUsers,$excludeRoleIDs);
	$today =date('Y-m-d H:i:s');
	//echo ",After get users=".$today;
	//echo "UsersCount=".count($userRes);
	$returnRes = '{"fat":[],"users":'.json_encode($userRes).'}';
	echo json_encode($returnRes);
}

//Returns roles whose role seniority is lesser than this.
function GetRoles($mysqlObj){
	$roleID = $_POST['RoleID'];
	//echo $roleID.',';
	$userDetails = s_GetUserDetails();
	$roleID = $roleID==0?$userDetails->user->RoleID:$roleID;//0 means logged in user role id
	//echo $roleID;
	$roleObj=new b_role($mysqlObj);
	//$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//echo json_encode($roleObj->getRolesBelowRole($loggedInUserDetails->user->RoleID));
	echo json_encode($roleObj->getRolesBelowRole($roleID));
}
function GetGeneralSettings($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$gsObj=new b_generalsettings($userDetails->user,$mysqlObj,$lang);
	$gs = $gsObj->get();
	echo json_encode($gs);
}

function IsMobileExists($mysqlObj,$lang){
	$userObj=new b_users('',$mysqlObj,$lang);
	//$userObj->UserID = $_POST['UserID'];
	echo $userObj->isMobileNoExists($_POST['Mobile'], $_POST['UserId']);
	
}

function DeleteUser($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	echo json_encode($userObj->deleteUser($_POST['UserID']));
}
function UpdateUserStatus($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	//echo $_POST['NewStatus']=="1"?true:false;die;
	echo json_encode($userObj->enableDisableByUserID($_POST['UserID'],$_POST['NewStatus']=="1"?false:true));
}

function GetByID($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	//echo $_POST['UserID'];
	echo json_encode($userObj->getByID($_POST['UserID']));
}
function Upsert($mysqlObj,$lang,$langSMS){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//Adding request
	$bReqObj=new b_request($loggedInUserDetails->user,$mysqlObj,$lang);
	$today =date('Y-m-d H:i:s');
	$bReqObj = $bReqObj->giveMeObj($loggedInUserDetails->user->UserID,$loggedInUserDetails->user->Mobile,$_SERVER["REMOTE_ADDR"],"WEB","000000","0","WEB REQUEST","Add/Update User","1",$today,$today,"WEB REQUEST","WEB");
	if($_POST['UserID']==0){
		$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"W");
		$bReqObj->RequestID = $bReqObj->add($bReqObj);
		$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
		/* 
		$bReqObj->RequestID = $bReqObj->add($bReqObj);
		$bReqObj->DisplayID = $bReqObj->updateDisplayID($bReqObj,"W"); */
	}
	
	//Adding user
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$userObj->UserID = $_POST['UserID'];
	$userObj->ParentID = $_POST['ParentID'];
	$userObj->Ancestors = $userObj->ParentID."/";
	$userObj->Name = $_POST['Name'];
	$userObj->Mobile = $_POST['Mobile'];
	$userObj->DOB = $_POST['DOB'];
	$userObj->Email = $_POST['Email'];
	$userObj->Address = $_POST['Address'];
	$userObj->ClientLimit = $_POST['ClientLimit'];
	$userObj->BalanceLevel = $_POST['BalanceLevel'];
	$userObj->DistributorFee = $_POST['DistributorFee'];
	$userObj->MandalFee = $_POST['MandalFee'];
	$userObj->RetailerFee = $_POST['RetailerFee'];
	$userObj->Deposit = $_POST['Deposit'];
	$userObj->Remarks = $_POST['Remarks'];
	$userObj->PAN = $_POST['PAN'];
	//Somehow encrypted password is being updated to the user obj. May be bcoz of userobj is a class obj it is reference type. So saving it in seperate variable just to send it to user via SMS
	$userEnteredPassword = $userObj->Password = $_POST['Password'];
	$userObj->RoleID = $_POST['RoleID'];
	
	if($_POST['RoleID'] != 2 ){ $userObj->Refundable = $_POST['Refundable']; } else { $userObj->Refundable = 0;  }
	$userObj->Gender = isset($_POST['Gender'])?$_POST['Gender']:"Male";
	$userObj->MinOpenBalanceMargin = 0;
	$userObj->AllowedIPs = isset($_POST['AllowedIPs'])?$_POST['AllowedIPs']:"0.0.0.0";
	$res = $userObj->upsert($userObj,$bReqObj);
	//echo "<br/> Upsert res=".json_encode($res);	
	
	//SMS data
	$bHttpObj = new b_http($loggedInUserDetails->user,$mysqlObj,"");
	//$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$bSMSObj = new b_sms($loggedInUserDetails->user,$mysqlObj,$userObj,$lang,$langSMS,$bHttpObj);
	//Updating Request
	if($_POST['UserID']==0){
		$bReqObj->UserID = $loggedInUserDetails->user->UserID;
		$bReqObj->TotalAmount =$bReqObj->TargetAmount= "0";
		$bReqObj->RequestType = "t_user";
		$bReqObj->TargetNo = '';//$userObj->RoleID;
		if($res->isSuccess && $res->InsertedUserID!="0"){
			$bReqObj->Status="3";
			$bReqObj->Remark="Successfully added user";
			$bSMSObj->userCreation(1,$bReqObj,$res->InsertedUserID,$userObj->Mobile,$userObj->Name,$userObj->getRoleName($userObj->RoleID),$userEnteredPassword,$bReqObj->Remark);
		}else{
			$bReqObj->Status="4";
			$bReqObj->Remark="Failed to add user";
			$bSMSObj->userCreation(0,$bReqObj,"",$userObj->Mobile,$userObj->Name,$userObj->getRoleName($userObj->RoleID),$userEnteredPassword,$bReqObj->Remark);
		}
		$bReqObj->update($bReqObj);
	}
	echo json_encode($res);
}
function UpdateFat($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$bFatObj = new b_fat($loggedInUserDetails->user,$mysqlObj);
	$bFatObj->UserID = $_POST['UserID'];
	
	$userAccess_r = isset($_POST['UserAccess_r']) ? "1" : "0" ;
	$userAccess_au = isset($_POST['UserAccess_au']) ? "1" : "0" ;
	$bFatObj->UserAccess = $userAccess_r.",".$userAccess_au;
	
	$serviceList_r = isset($_POST['ServiceList_r']) ? "1" : "0" ;
	$serviceList_au = isset($_POST['ServiceList_au']) ? "1" : "0" ;
	$bFatObj->ServiceList = $serviceList_r.",".$serviceList_au;
	
	$rechargeGateway_r = isset($_POST['RechargeGateway_r']) ? "1" : "0" ;
	$rechargeGateway_au = isset($_POST['RechargeGateway_au']) ? "1" : "0" ;
	$bFatObj->RechargeGateway = $rechargeGateway_r.",".$rechargeGateway_au;
	
	$smsGateway_r = isset($_POST['SMSGateway_r']) ? "1" : "0" ;
	$smsGateway_au = isset($_POST['SMSGateway_au']) ? "1" : "0" ;
	$bFatObj->SMSGateway = $smsGateway_r.",".$smsGateway_au;
	
	$generalSettings_r = isset($_POST['GeneralSettings_r']) ? "1" : "0" ;
	$generalSettings_au = isset($_POST['GeneralSettings_au']) ? "1" : "0" ;
	$bFatObj->GeneralSettings = $generalSettings_r.",".$generalSettings_au;
	
	$rechargePermission_r = isset($_POST['RechargePermission_r']) ? "1" : "0" ;
	$rechargePermission_au = isset($_POST['RechargePermission_au']) ? "1" : "0" ;
	$bFatObj->RechargePermission = $rechargePermission_r.",".$rechargePermission_au;
	
	$distributorMargin_r = isset($_POST['DistributorMargin_r']) ? "1" : "0" ;
	$distributorMargin_au = isset($_POST['DistributorMargin_au']) ? "1" : "0" ;
	$bFatObj->DistributorMargin = $distributorMargin_r.",".$distributorMargin_au;
	
	$networkManagement_r = isset($_POST['NetworkManagement_r']) ? "1" : "0" ;
	$networkManagement_au = isset($_POST['NetworkManagement_au']) ? "1" : "0" ;
	$bFatObj->NetworkManagement = $networkManagement_r.",".$networkManagement_au;
	
	$vendor_r = isset($_POST['Vendor_r']) ? "1" : "0" ;
	$vendor_au = isset($_POST['Vendor_au']) ? "1" : "0" ;
	$bFatObj->Vendor = $vendor_r.",".$vendor_au;
	
	$vendorPayment_r = isset($_POST['VendorPayment_r']) ? "1" : "0" ;
	$vendorPayment_au = isset($_POST['VendorPayment_au']) ? "1" : "0" ;
	$bFatObj->VendorPayment = $vendorPayment_r.",".$vendorPayment_au;
	
	$paymentTransfer_r = isset($_POST['PaymentTransfer_r']) ? "1" : "0" ;
	$paymentTransfer_au = isset($_POST['PaymentTransfer_au']) ? "1" : "0" ;
	$bFatObj->PaymentTransfer = $paymentTransfer_r.",".$paymentTransfer_au;
	
	$paymentCollection_r = isset($_POST['PaymentCollection_r']) ? "1" : "0" ;
	$paymentCollection_au = isset($_POST['PaymentCollection_au']) ? "1" : "0" ;
	$bFatObj->PaymentCollection = $paymentCollection_r.",".$paymentCollection_au;
	
	$bankDetails_r = isset($_POST['BankDetails_r']) ? "1" : "0" ;
	$bankDetails_au = isset($_POST['BankDetails_au']) ? "1" : "0" ;
	$bFatObj->BankDetails = $bankDetails_r.",".$bankDetails_au;
	
	$mnpSettings_r = isset($_POST['MNPSettings_r']) ? "1" : "0" ;
	$mnpSettings_au = isset($_POST['MNPSettings_au']) ? "1" : "0" ;
	$bFatObj->MNPSettings = $mnpSettings_r.",".$mnpSettings_au;
	
	$autoRechargeSettings_r = isset($_POST['AutoRechargeSettings_r']) ? "1" : "0" ;
	$autoRechargeSettings_au = isset($_POST['AutoRechargeSettings_au']) ? "1" : "0" ;
	$bFatObj->AutoRechargeSettings = $autoRechargeSettings_r.",".$autoRechargeSettings_au;
	
	$complaintRequest_r = isset($_POST['ComplaintRequest_r']) ? "1" : "0" ;
	$complaintRequest_au = isset($_POST['ComplaintRequest_au']) ? "1" : "0" ;
	$bFatObj->ComplaintRequest = $complaintRequest_r.",".$complaintRequest_au;
	
	$pendingRequest_r = isset($_POST['PendingRequest_r']) ? "1" : "0" ;
	$pendingRequest_au = isset($_POST['PendingRequest_au']) ? "1" : "0" ;
	$bFatObj->PendingRequest = $pendingRequest_r.",".$pendingRequest_au;
	
	$smsOffer_r = isset($_POST['SMSOffer_r']) ? "1" : "0" ;
	$smsOffer_au = isset($_POST['SMSOffer_au']) ? "1" : "0" ;
	$bFatObj->SMSOffer = $smsOffer_r.",".$smsOffer_au;
	
	$webOffer_r = isset($_POST['WebOffer_r']) ? "1" : "0" ;
	$webOffer_au = isset($_POST['WebOffer_au']) ? "1" : "0" ;
	$bFatObj->WebOffer = $webOffer_r.",".$webOffer_au;
	
	$incentiveOffer_r = isset($_POST['IncentiveOffer_r']) ? "1" : "0" ;
	$incentiveOffer_au = isset($_POST['IncentiveOffer_au']) ? "1" : "0" ;
	$bFatObj->IncentiveOffer = $incentiveOffer_r.",".$incentiveOffer_au;
	
	$moveUser_r = isset($_POST['MoveUser_r']) ? "1" : "0" ;
	$moveUser_au = isset($_POST['MoveUser_au']) ? "1" : "0" ;
	$bFatObj->MoveUser = $moveUser_r.",".$moveUser_au;
	
	$rechargeAmountSettings_r = isset($_POST['RechargeAmountSettings_r']) ? "1" : "0" ;
	$rechargeAmountSettings_au = isset($_POST['RechargeAmountSettings_au']) ? "1" : "0" ;
	$bFatObj->RechargeAmountSettings = $rechargeAmountSettings_r.",".$rechargeAmountSettings_au;
	
	$manageTransaction_r = isset($_POST['ManageTransaction_r']) ? "1" : "0" ;
	$manageTransaction_au = isset($_POST['ManageTransaction_au']) ? "1" : "0" ;
	$bFatObj->ManageTransaction = $manageTransaction_r.",".$manageTransaction_au;
	
	$loginSettings_r = isset($_POST['LoginSettings_r']) ? "1" : "0" ;
	$loginSettings_au = isset($_POST['LoginSettings_au']) ? "1" : "0" ;
	$bFatObj->LoginSettings = $loginSettings_r.",".$loginSettings_au;
	
	$governmentHolidays_r = isset($_POST['GovernmentHolidays_r']) ? "1" : "0" ;
	$governmentHolidays_au = isset($_POST['GovernmentHolidays_au']) ? "1" : "0" ;
	$bFatObj->GovernmentHolidays = $governmentHolidays_r.",".$governmentHolidays_au;
	
	$recharge_r = isset($_POST['Recharge_r']) ? "1" : "0" ;
	$recharge_au = isset($_POST['Recharge_au']) ? "1" : "0" ;
	$bFatObj->Recharge = $recharge_r.",".$recharge_au;
	
	$paymentReport_r = isset($_POST['PaymentReport_r']) ? "1" : "0" ;
	$paymentReport_au = isset($_POST['PaymentReport_au']) ? "1" : "0" ;
	$bFatObj->PaymentReport = $paymentReport_r.",".$paymentReport_au;
	
	$paymentCollectionReport_r = isset($_POST['PaymentCollectionReport_r']) ? "1" : "0" ;
	$paymentCollectionReport_au = isset($_POST['PaymentCollectionReport_au']) ? "1" : "0" ;
	$bFatObj->PaymentCollectionReport = $paymentCollectionReport_r.",".$paymentCollectionReport_au;
	
	$rechargeReport_r = isset($_POST['RechargeReport_r']) ? "1" : "0" ;
	$rechargeReport_au = isset($_POST['RechargeReport_au']) ? "1" : "0" ;
	$bFatObj->RechargeReport = $rechargeReport_r.",".$rechargeReport_au;
	
	$transactionReport_r = isset($_POST['TransactionReport_r']) ? "1" : "0" ;
	$transactionReport_au = isset($_POST['TransactionReport_au']) ? "1" : "0" ;
	$bFatObj->TransactionReport = $transactionReport_r.",".$transactionReport_au;
	
	$res = $bFatObj->updateFat($bFatObj);
	if($res)
		echo json_encode('{"IsSuccess":"1"}');
	else
		echo json_encode('{"IsSuccess":"0"}');
	//echo "<br /> userAccess=".$userAccess;
}
?>