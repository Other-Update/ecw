<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/Payment.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/Resource/Common.php';
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
		addTransfer($mysqlObj,$lang,$langSMS,$langCommon);
		break;
	case 'AddCollection':
		addCollection($mysqlObj,$lang);
		break;
	case 'GetBalanceToBePaid_NIU':
		getBalanceToBePaid_NIU($mysqlObj,$lang);
		break;
	case 'GetTransfers_DT':
		if($_POST['ParentID']!='null' || $_POST['SearchStr']!='null')
			getTransfers_DT($mysqlObj,$lang);
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
	
	$userObj = new b_users($userDetails->user,$mysqlObj,$lang);
	$pObj=new b_payment($userDetails->user,$mysqlObj,$lang);
	
	//Search user by search string
	$searchStr= $_POST["SearchStr"];
	$userId= $_POST["UserID"];
	$user=null;
	//If searchStr and userId are equal then that means user has been loaded already. No need to load the user again
	//if($searchStr != $userId)
	//{
		$user=$userObj->getBySearchStr($searchStr,1);
		//echo "Userid=".$userObj->UserID;
		if($user!=null) $userId=$user->UserID;
		else $userId = -1;
	//}	
	//echo "111";
	$response="";
	if($userId==null || $userId==-1 || $userId==""){
			
		$response='{"data":"","ecwIsUserFound":"0","ecwMessage":"* User Not Found","ecwUser":""}';
		
		$response = json_decode($response);
	}else{
	
		//date_default_timezone_set('Asia/Calcutta');
		$yesterday =date('Y-m-d',strtotime("-1 days"));
		$yestPurchase = $pObj->getTransferByDate($userId,$yesterday);
		$yestPurchaseAmnt = count($yestPurchase)>0?$yestPurchase[0]->Amount:0;
		$yestBilling = $pObj->getBillingByDate($userId,$yesterday);
		$yestBillingPer = count($yestBilling)>0?$yestBilling[0]->CommissionPercent:0;
		$yestBillingPer = $yestBillingPer?$yestBillingPer:0;
		//echo json_encode($yestBilling);
		$today =date('Y-m-d');
		//echo $today;
		$todayPurchase = $pObj->getTransferByDate($userId,$today);
		$todayPurchaseAmnt = count($todayPurchase)>0?$todayPurchase[0]->Amount:0;
		$todayBilling = $pObj->getBillingByDate($userId,$today);
		$todayBillingPer = count($todayBilling)>0?$todayBilling[0]->CommissionPercent:0;
		$todayBillingPer = $todayBillingPer?$todayBillingPer:0;
		//echo "<br /> todayPurchaseAmnt="+$todayPurchaseAmnt;
		$dObj=new b_distmargin($userDetails->user,$mysqlObj,$lang);
		$margin = $dObj->getByUserID($userId);
		//$todayMargin = $dObj->getByUserID($_POST['UserID'],$todayPurchaseAmnt);
		
		$minOpeningBlance = $dObj->getUserMinOpeningBalance($userId);
		$dObj=new b_transaction($userDetails->user,$mysqlObj,$lang);
		$openingBalance = $dObj->getOpeningBlanceByUserID($userId);
		//echo 'minOpeningBlance='.$minOpeningBlance.',openingBalance='.$openingBalance;
		$isOpeningBalReached = $minOpeningBlance<=$openingBalance?1:0;
		//echo 'isOpeningBalReached='.$isOpeningBalReached;
		
		$userObj=new b_users($userDetails->user,$mysqlObj,$lang);
		$wallet = $userObj->getWalletBalance($userId);
		if(isset($_POST['ParentID']))
			$balanceToBePaid = $pObj->getBalanceToBePaid($userId,$_POST['ParentID']);
		else
			$balanceToBePaid=0;

		$resultObj->isSuccess=true;
		$resultObj->message='success';
		/* echo $yestBillingPer;
		echo "<br />";
		echo $todayBillingPer; */
		$resultObj->data='{"IsOpeningBalReached":'.$isOpeningBalReached.',"YesterdayPurchase":'.$yestPurchaseAmnt.',"YesterdayBilling":'.$yestBillingPer.',"TodayPurchase":'.$todayPurchaseAmnt.',"TodayBilling":'.$todayBillingPer.',"Margin":'.json_encode($margin).',"BalanceToBePaid":'.json_encode($balanceToBePaid).',"UserWallet":'.json_encode($wallet).'}';
		$response=$resultObj;
		
		$response->ecwIsUserFound="1";
		$response->ecwMessage="User found";
		$response->ecwUser=$user;
		//User wouldn't have loaded fo admin. so say it is admin
		//if($userId==1)
			//$response->ecwUser='{"Name":"Admin"}';
	}
	echo  json_encode($response);
	
	//echo json_encode($resultObj);
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
function addTransfer($mysqlObj,$lang,$langSMS,$langCommon){	
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
	$res = "";//Declare
	$userObj=new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$toUser = $userObj->getByID($pObj->ToUserID);
	//echo json_encode($toUser);
	if(strpos($toUser->Ancestors,$pObj->FromUserID)===false && $pObj->ToUserID!=1 && $pObj->FromUserID!=1)//Wrong child
	{
		$resultObj = new httpresult();	
		$resultObj->getHttpResult(false,$langCommon['InvalidUserSelected'],"");
		$resultObj->isSuccess=$resultObj->IsSuccess;
		$resultObj->message=$resultObj->Message;
		echo json_encode($resultObj);
	}else{
	
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
		$bSMSObj = new b_sms($loggedInUserDetails->user,$mysqlObj,$userObj,$lang,$langSMS,$bHttpObj);
		if($res->isSuccess){
			$bReqObj->Status="3";
			$bReqObj->Remark="Successfully Transfered";
			if($pObj->Type=="1")
			$bSMSObj->paymentTransfer(true,"Payment_s",$pObj->ToUserID,$toUser,$pObj->Amount,0,0,$bReqObj,0);
			else
			$bSMSObj->revertPaymentTransfer(true,"Payment_Rev_s",$pObj->Amount,$bReqObj,"","",$toUser);
			//echo "<br/>Message code=".$pObj->Amount;
		}else{
			$bReqObj->Status="4";
			$bReqObj->Remark="Failed to transfer";
			if($pObj->Type=="1"){
				if($res->otherInfo!=""){
					$otherInfoArr = explode(" ", $res->otherInfo);
					$otherInfoParam1=$otherInfoArr[0];//SMS Code
					$otherInfoParam2=$otherInfoArr[1];//Reject within minutes
					$res->message="You are not allowed to send the same amount to same user within ".$otherInfoParam2." minutes";
					$bSMSObj->paymentTransfer(true,$otherInfoParam1,$pObj->ToUserID,$toUser,$pObj->Amount,0,0,$bReqObj,$otherInfoParam2);
				}else{
					$bSMSObj->paymentTransfer(true,"Payment_f",$pObj->ToUserID,$toUser,$pObj->Amount,0,0,$bReqObj,0);
				}
			}
			else
			$bSMSObj->revertPaymentTransfer(true,"Payment_Rev_f",$pObj->Amount,$bReqObj,"","",$toUser);
			//echo "<br/>Message code=".$pObj->Amount;
		}
		//echo "<br/>Amount=".$pObj->Amount;
		$bReqObj->update($bReqObj);
		
		echo json_encode($res);
	}
}
function getTransfers_DT($mysqlObj,$lang){
	$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	$sObj=new b_payment($loggedInUserDetails->user,$mysqlObj,$lang);
	$userObj = new b_users($loggedInUserDetails->user,$mysqlObj,$lang);
	$searchStr= $_POST["SearchStr"];
	$fromDate 	= $_POST['fromDate'];
	$toDate 	= $_POST['toDate'];
	$parentId= $_POST["ParentID"];
	$parentUer=null;
	//If searchStr and ParentID are equal then that means parent has been loaded already. No need to load the user again
	if($searchStr != $parentId)
	{
		$parentUer=$userObj->getBySearchStr($searchStr,1);
		//echo "Userid=".$userObj->UserID;
		if($parentUer!=null) $parentId=$parentUer->UserID;
		else $parentId = -1;
	}
	//$parentId=$searchStr;
	// echo "searchstr=".$searchStr;
	//echo "Parent=".$parentId; 
	$response="";
	if($parentId==null || $parentId==-1 || $parentId==""){
		//echo "a";
		$response='{"sEcho":3,"iTotalRecords":"2","iTotalDisplayRecords":"0","aaData":[],"ecwIsUserFound":"0","ecwMessage":"* User Not Found","ecwUser":""}';
		
		$response = json_decode($response);
		//echo $response;
	}else{
		//echo "b";
		$response= $sObj->getTransfers_DT($parentId,$fromDate,$toDate);
		$response = json_decode($response);
		//echo "type=".gettype($response);
		//$response["ecwIsUserFound"]="1";
		$response->ecwIsUserFound="1";
		$response->ecwMessage="User found";
		$response->ecwUser=$parentUer;
	}
	echo  json_encode($response);
}
?>