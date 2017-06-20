<?php
include_once "../../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Payment.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_payment.php';
include_once APPROOT_URL.'/Business/b_distmargin.php';
include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/Business/b_transaction.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_sms.php';
include_once APPROOT_URL.'/Business/b_http.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
$action = $_POST['Action'];
//echo $lang['s_no'];
switch($action){
	case 'AddTransfer':
		addTransfer($mysqlObj,$lang,$langSMS);
		break;
	case 'AddCollection':
		addCollection($mysqlObj,$lang);
		break;
	case 'GetBalanceToBePaid_NIU':
		getBalanceToBePaid_NIU($mysqlObj,$lang);
		break;
	case 'GetTransfers_DT':
		if($_POST['ParentID']!='null')
			getTransfers_DT($mysqlObj,$lang,$_POST['ParentID']);
		else
			getTransfers_DT($mysqlObj,$lang,0);
		break;
	case 'GetBalanceToBePaidByParent_DT':
		if($_POST['SelectedUserID']!='null')
			getBalanceToBePaidByParent_DT($mysqlObj,$lang);
		break;
	case 'GetCollectionByParent_DT':
		if($_POST['SelectedUserID']!='null')
			getCollectionByParent_DT($mysqlObj,$lang);
		break;
	case 'GetUserDetailsForTranser':
		getUserDetailsForTranser($mysqlObj,$lang);
		break;
		
}
function getCollectionByParent_DT($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$pObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	echo $pObj->getCollectionByParent_DT($_POST["SelectedUserID"]);
}
function getBalanceToBePaidByParent_DT($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$pObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	echo $pObj->getBalanceToBePaidByParent_DT($_POST["SelectedUserID"]);
}
function getUserDetailsForTranser($mysqlObj,$lang){
	$resultObj = new httpresult();
	$userDetails = s_GetUserDetails();
	
	$pObj=new b_payment($userDetails->user,$mysqlObj,$lang);
	
	//date_default_timezone_set('Asia/Calcutta');
	$yesterday =date('Y-m-d',strtotime("-1 days"));
	$yestPurchase = $pObj->getTransferByDate($_POST['UserID'],$yesterday);
	$yestPurchaseAmnt = count($yestPurchase)>0?$yestPurchase[0]->Amount:0;
	$yestBilling = $pObj->getBillingByDate($_POST['UserID'],$yesterday);
	$yestBillingPer = count($yestBilling)>0?$yestBilling[0]->CommissionPercent:0;
	$yestBillingPer = $yestBillingPer?$yestBillingPer:0;
	//echo json_encode($yestBilling);
	$today =date('Y-m-d');
	//echo $today;
	$todayPurchase = $pObj->getTransferByDate($_POST['UserID'],$today);
	$todayPurchaseAmnt = count($todayPurchase)>0?$todayPurchase[0]->Amount:0;
	$todayBilling = $pObj->getBillingByDate($_POST['UserID'],$today);
	$todayBillingPer = count($todayBilling)>0?$todayBilling[0]->CommissionPercent:0;
	$todayBillingPer = $todayBillingPer?$todayBillingPer:0;
	//echo "<br /> todayPurchaseAmnt="+$todayPurchaseAmnt;
	$dObj=new b_distmargin($userDetails->user,$mysqlObj,$lang);
	$margin = $dObj->getByUserID($_POST['UserID']);
	//$todayMargin = $dObj->getByUserID($_POST['UserID'],$todayPurchaseAmnt);
	
	$minOpeningBlance = $dObj->getUserMinOpeningBalance($_POST['UserID']);
	$dObj=new b_transaction($userDetails->user,$mysqlObj,$lang);
	$openingBalance = $dObj->getOpeningBlanceByUserID($_POST['UserID']);
	//echo 'minOpeningBlance='.$minOpeningBlance.',openingBalance='.$openingBalance;
	$isOpeningBalReached = $minOpeningBlance<=$openingBalance?1:0;
	//echo 'isOpeningBalReached='.$isOpeningBalReached;
	
	$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
	$wallet = $userObj->getWalletBalance($_POST['UserID']);
	if(isset($_POST['ParentID']))
		$balanceToBePaid = $pObj->getBalanceToBePaid($_POST['UserID'],$_POST['ParentID']);
	else
		$balanceToBePaid=0;

	$resultObj->isSuccess=true;
	$resultObj->message='success';
	/* echo $yestBillingPer;
	echo "<br />";
	echo $todayBillingPer; */
	$resultObj->data='{"IsOpeningBalReached":'.$isOpeningBalReached.',"YesterdayPurchase":'.$yestPurchaseAmnt.',"YesterdayBilling":'.$yestBillingPer.',"TodayPurchase":'.$todayPurchaseAmnt.',"TodayBilling":'.$todayBillingPer.',"Margin":'.json_encode($margin).',"BalanceToBePaid":'.json_encode($balanceToBePaid).',"UserWallet":'.json_encode($wallet).'}';
	
	echo json_encode($resultObj);
}
function getBalanceToBePaid_NIU($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$pObj=new b_payment($userDetails->user,$mysqlObj,$lang);
	$balanceToBePaid = $pObj->getBalanceToBePaid($_POST['UserID']);
	$resultObj = new httpresult();
	echo json_encode($resultObj->getHttpResultObj(true,$this->lang['success'],'"BalanceToBePaid":'.json_encode($balanceToBePaid).'}'));
}
function addCollection($mysqlObj,$lang){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$pObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	$pObj->FromUserID = $_POST['FromUserID'];
	$pObj->ToUserID = $_POST['ToUserID'];
	$pObj->Amount = 0;
	$pObj->CommissionPercent = 0;
	$pObj->Type = 1;
	$pObj->Mode = $_POST['Mode'];
	$pObj->Remark = $_POST['Remark'];
	$pObj->PaidAmount = $_POST['PaidAmount'];
	$pObj->CommissionAmountPrevPur = 0;
	
	//$commAmnt = ($pObj->CommissionPercent/100) * $pObj->Amount;
	$pObj->TotalAmount = 0;
	
	//$pObj->TotalAmount = $_POST['TotalAmount'];
	echo json_encode($pObj->addTransfer($pObj));
}
function addTransfer($mysqlObj,$lang,$langSMS){	
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//Adding request
	$bReqObj=new b_request($loggedInUserDetails->user,$mysqlObj,$lang);
	$today =date('Y-m-d H:i:s');
	$bReqObj = $bReqObj->giveMeObj($loggedInUserDetails->user->UserID,$loggedInUserDetails->user->Mobile,$_SERVER["REMOTE_ADDR"],"WEB","000000","0","WEB REQUEST","Add Payment","1",$today,$today,"WEB REQUEST","WEB");
	
	$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"W");
	$bReqObj->RequestID = $bReqObj->add($bReqObj);
	$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
	
	$pObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	$pObj->FromUserID = $_POST['FromUserID'];
	$pObj->ToUserID = $_POST['ToUserID'];
	$pObj->RequestID = 0;//TODO: Add request and send that ID.
	$pObj->Amount = $_POST['Amount'];
	$pObj->CommissionPercent = $_POST['CommissionPercent'];
	$pObj->Type = $_POST['Type'];
	$pObj->Mode = $_POST['Mode'];
	$pObj->Remark = $_POST['Remark'];
	$pObj->PaidAmount = $_POST['PaidAmount'];
	$pObj->CommissionAmountPrevPur = $_POST['CommissionAmountPrevPur'];
	$commAmnt = ($pObj->CommissionPercent/100) * $pObj->Amount;
	$pObj->TotalAmount = $pObj->Amount+$commAmnt+$pObj->CommissionAmountPrevPur;
	
	//This is to make up next purchase calculation. When 5k is transfered with 4.2% then 2k is debited. So final 3k purchase is applied with 3.7% only. So in this case this 2k debit should be mentioned that this has 3.7% margin.
	if($pObj->Type==2)//Debit
		$pObj->CommissionPercent = $pObj->CommissionPercent-$_POST['ExtraCommForDebit'];
	/* echo $commAmnt;
	echo ",";
	echo $pObj->CommissionAmountPrevPur;
	echo ",";
	echo $pObj->TotalAmount; */
	$res = $pObj->addTransfer($pObj,$bReqObj);
	//Updating Request
	$bReqObj->UserID = $loggedInUserDetails->user->UserID;
	$bReqObj->TotalAmount = $pObj->TotalAmount;
	$bReqObj->TargetAmount= $pObj->Amount;
	$bReqObj->RequestType = "t_payment";
	$bReqObj->TargetNo = "0";
	
	//SMS data
	$bHttpObj = new b_http($loggedInUserDetails->user,$mysqlObj,"");
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$bSMSObj = new b_sms($loggedInUserDetails->user,$mysqlObj,$userObj,$lang,$langSMS,$bHttpObj);
	$toUser = $userObj->getByID($pObj->ToUserID);
	if($res->isSuccess){
		$bReqObj->Status="3";
		$bReqObj->Remark="Successfully Transfered";
		$bSMSObj->paymentTransfer(true,"Payment_s",$pObj->ToUserID,$toUser,$pObj->Amount,0,0,$bReqObj);
	}else{
		$bReqObj->Status="4";
		$bReqObj->Remark="Failed to transfer";
		$bSMSObj->paymentTransfer(true,"Payment_f",$pObj->ToUserID,$toUser,$pObj->Amount,0,0,$bReqObj);
	}
	$bReqObj->update($bReqObj);
	
	echo json_encode($res);
}
function getTransfers_DT($mysqlObj,$lang,$parentID){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	echo $sObj->getTransfers_DT($parentID);//$_POST["ParentID"]);
}
?>