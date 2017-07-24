<?php
include_once "../../BaseUrl.php";
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/Business/b_webservice.php';
/*include_once APPROOT_URL.'/Business/b_service.php';
include_once APPROOT_URL.'/Business/b_webserviceactions.php'; */
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
include_once APPROOT_URL.'/Business/b_transaction.php';
include_once APPROOT_URL.'/Business/b_payment.php';

//To set IST time in all functions by default
//date_default_timezone_set('Asia/Calcutta');
$doCheckToken = false; //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0ODg3MzUxODMsImp0aSI6InFSSWdqRkdibkx4UnJGaXI4cjVEd2ZtZ1FUS3BcL0MzclRSKzM1TnJyNzJjPSIsImlzcyI6IkVDV1NlcnZlciIsIm5iZiI6MTQ4ODczNTE4MywiZXhwIjoxODQ4NzM1MTgzLCJkYXRhIjp7InVzZXJJZCI6IjEiLCJ1c2VyTmFtZSI6IkFkbWluIn19.aftWxYdjc022nEd-7AqEQIY2qivmB4_w1tX7jNWj9bc
//Develper - //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MDA3NTIxMzUsImp0aSI6InBncVZmdVk1NU5lMkFQMFJDSVdmbmp6K1hveFU4ZUxNWnMzY05taE5tM2s9IiwiaXNzIjoiRUNXU2VydmVyIiwibmJmIjoxNTAwNzUyMTM1LCJleHAiOjM2MDE1MDA3NTIxMzUsImRhdGEiOnsidXNlcklkIjoiMTA4IiwidXNlck5hbWUiOiJEZXZlbG9wZXJzIn19.EivZhpiLVCgnVFhAZLaMLWN3vPcE8RcVH-LQ2PSB0rU

function reports($user,$mysqlObj,$langSMS,$langAPI,$reportName,$startDate,$endDate){
	switch($reportName){
		case "Transaction":
			$userId 	= isset($_GET['UserID'])?$_GET['UserID']:"";
			$mobile 	= isset($_GET['Mobile'])?$_GET['Mobile']:"";
			$network 	= isset($_GET['Network'])?$_GET['Network']:"";
			$requestId 	= isset($_GET['RequestId'])?$_GET['RequestId']:"";
			//$fromDate = $_GET['StarteDate'];
			//$toDate = $_GET['EndDate'];
			$userId = $userId==""?$user->UserID:$userId;
			//echo "userId=".$userId.",";
			$tObj=new b_transaction($user,$mysqlObj,"");
			echo json_encode($tObj->getTransactionReport_DT($userId, $mobile, $network, $requestId, $startDate, $endDate,true));
			break;
		case "Recharge":
			$userId 	= isset($_GET['UserID'])?$_GET['UserID']:"";
			$mobile 	= isset($_GET['Mobile'])?$_GET['Mobile']:"";
			$userId = $userId==""?$user->UserID:$userId;
			//$mobile = $mobile==""?$user->Mobile:$mobile;
			//echo "Recharge report";
			$bGsObj = new b_generalsettings($user,$mysqlObj,"");
			$gs = $bGsObj->get();
			$rcObj = new b_recharge($user,$mysqlObj,"",$langSMS,$langAPI,$gs);
			$res = $rcObj->getRechargeReport_DT($userId,$mobile,$startDate,$endDate,0,true);
			$resultObj = new httpresult();	
			if($res)
				$resultObj->getHttpResult(true,"Success",json_encode($res));
			else 
				$resultObj->getHttpResult(false,"No data found","");
			echo json_encode($resultObj);
			break;
		case "MyPayment":
			$onlyMyPayments = true;
		case "Payment":
			//$onlyMyPayments = isset($onlyMyPayments)==true?$onlyMyPayments==true ? true: false:false;
			$onlyMyPayments = isset($onlyMyPayments)==true?$onlyMyPayments:false;
			$userId =$user->UserID;
			//echo "userId=".$userId.",";die;
			$payObj=new b_payment($user,$mysqlObj,"");
			$res = $payObj->getTransfersByDateRange_DT($userId,$startDate, $endDate,$onlyMyPayments,true);
			$resultObj = new httpresult();	
			if($res)
				$resultObj->getHttpResult(true,"Success",$res);
			else 
				$resultObj->getHttpResult(false,"No data found","");
			echo json_encode($resultObj);
			break;
		default:
			break;
	}
}
function addRequest($user,$mysqlObj,$langSMS,$msg,$mobileNo,$amount,$opCode){
	$bReqObj=new b_request($user,$mysqlObj,"");
	$today =date('Y-m-d H:i:s');
	//echo json_encode($user);
	$bReqObj = $bReqObj->giveMeObj($user->UserID,$user->Mobile,$_SERVER["REMOTE_ADDR"],"Android",$mobileNo,$amount,$msg,"Just Request Came. OpCode=".$opCode,"1",$today,$today,"ANDROID REQUEST","ANDROID");
	$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"A");
	$bReqObj->RequestID = $bReqObj->add($bReqObj);
	$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
	return $bReqObj;
}
function callRecharge($user,$mysqlObj,$bReqObj,$langSMS,$langAPI,$mobileNo,$amount,$opCode){

	//addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs)
	//$bReqObj->RequestID = $reqID;
	$bGsObj = new b_generalsettings($user,$mysqlObj,"");
	$gs = $bGsObj->get();
	$rcObj=new b_recharge($user,$mysqlObj,"",$langSMS,$langAPI,$gs);
	$rcObj->Mobile = $mobileNo;
	$rcObj->Operator = $opCode;
	$rcObj->Amount= $amount;
	return $rcObj->processRecharge($bReqObj,$rcObj->Operator,$rcObj->Mobile,$rcObj->Amount);
}
//TODO:UnknownParam shoudl be removed
function addUser($wsObj,$roleID,$bReqObj,$unknownParam,$mobile,$name,$password){
	$res = $wsObj->createUser($roleID,$bReqObj,$unknownParam,$mobile,$name,$password);
	$resJson = json_decode($res);
	$resultObj = new httpresult();
	$resultObj->isSuccess=$resJson->IsSuccess;
	$resultObj->message=$resJson->FailureReason;
	$resultObj->data='{"NewUserID":'.json_encode($resJson->NewUserID).'}';
	return $resultObj;
}

function getRequestStatus($user,$mysqlObj,$requestID){
	$reqObj = new b_request($user,$mysqlObj,"");
	$reqRes = $reqObj->getRequestStatus($requestID);
	$status = "No request found";
	$statusCode = 0;
	if(count($reqRes)>0){
		$statusCode=$reqRes[0]->Status;
		switch($reqRes[0]->Status){
			case 1:
				$status = "Pending";break;
			case 2:
				$status = "Suspense";break;
			case 3:
				$status = "Success";break;
			case 4:
				$status = "Failed";break;
			default:
				$status = "No request found";break;
		}
	}
	$resultObj = new httpresult();
	$resultObj->isSuccess=$status == "No request found"?0:1;
	$resultObj->message=$status;
	$resultObj->data='{"RequestID":'.$requestID.',"StatusCode":'.$statusCode.',"Status":'.$status.'}';
	echo json_encode($resultObj);
}
try{
	$startTime=round(microtime(true) * 1000);
	
	$tokenGuid="";//$_GET["TokenGUID"];
	$Action = $_GET['Action'];
	$wsObj = new b_webservice('',$mysqlObj,'',$langSMS,$langAPI,"");
	//$res = $wsObj->saveQueryStringTesting("Online/do",$_SERVER['QUERY_STRING']);
	
	$mysqlObj->errorlog->addLog("OnlineRecharge","do.php","Common",$_SERVER['QUERY_STRING'],"QueryString","No Table","0","Log1");
	
	$generalConfig = $mysqlObj->configuration['general'];
	$tokenEnc="";
	//echo $generalConfig["isdev"].",";
	if($generalConfig["isdev"]==1){
		//echo "1 year token";
		//Using 1 year valid admin token for testing.
		$tokenEnc = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0ODg3MzUxODMsImp0aSI6InFSSWdqRkdibkx4UnJGaXI4cjVEd2ZtZ1FUS3BcL0MzclRSKzM1TnJyNzJjPSIsImlzcyI6IkVDV1NlcnZlciIsIm5iZiI6MTQ4ODczNTE4MywiZXhwIjoxODQ4NzM1MTgzLCJkYXRhIjp7InVzZXJJZCI6IjEiLCJ1c2VyTmFtZSI6IkFkbWluIn19.aftWxYdjc022nEd-7AqEQIY2qivmB4_w1tX7jNWj9bc";
	}else{
		//echo "url token";
		$tokenEnc = $_GET['Token'];
	}
	//echo "<br/>Token=".$tokenEnc;
	$token = $ecwToken->decrypt($tokenEnc);
	//If token is broken then exception will be thrown from the above line
	//Controll will not go to the next line
	if(!$ecwToken->isValid($token)){
		$resultObj = new httpresult();	
		$resultObj->getHttpResult(false,"Token Tampered 3","");
		echo json_encode($resultObj);
		return false;
	}
	//echo "<br/>token=".json_encode($token->data->userId);
	
	$userObj = new b_users("",$mysqlObj,"");
	$user = $userObj->getByID($token->data->userId);//TODO: get user id from token
	//echo "<br/> userObj=".json_encode($me);
	//echo "<br/> User=".json_encode($user);
	//echo "<br/> Action=".$Action;
	switch($Action){
		case "Chat":
			$message = $_GET['Message'];
			$bReqObj=addRequest($user,$mysqlObj,$langSMS,$message,"","","");
			$wsObj = new b_webservice($user,$mysqlObj,'',$langSMS,$langAPI);
			$res = $wsObj->processMessageAndroid($bReqObj);
			echo json_decode($res);
			break;
		case "Recharge":
			//echo "Should do rec";
			$opCode = $_GET['OpCode'];
			$mobile = $_GET['Mobile'];
			$amount = $_GET['Amount'];
			$bReqObj=addRequest($user,$mysqlObj,$langSMS,"ANDROID REQUEST",$mobile,$amount,$opCode);
			$res = callRecharge($user,$mysqlObj,$bReqObj,$langSMS,$langAPI,$mobile,$amount,$opCode);
			echo json_decode($res);
			//echo $amount.",";
			break;
		
		case "AddUser":
			//echo "Should do rec";
			$mobile = $_GET['Mobile'];
			$name = $_GET['Name'];
			$bReqObj=addRequest($user,$mysqlObj,$langSMS,"ANDROID REQUEST",$mobile,"",$name);
			$roleID = $_GET['RoleID'];
			$password = $_GET['Password'];
			$bReqObj->TotalAmount = $bReqObj->TargetAmount = "0";
			$bReqObj->RequestType="t_user";
			$bReqObj->TargetNo=$roleID;
			$$password = $password==""?$wsObj->random_string(6):$password;
			$wsObj->me=$user;
			$res = addUser($wsObj,$roleID,$bReqObj,"",$mobile,$name,$password);
			echo json_encode($res);
			//echo $amount.",";
			break;
			
		case "GetUsers":
			$userId =$user->UserID;
			$bUserObj=new b_users($user,$mysqlObj,"");
			$userList = $bUserObj->getAllUsers($user->UserID,false,true,'1');
			$resultObj = new httpresult();	
			if($userList)
				$resultObj->getHttpResult(true,"Success",$userList);
			else 
				$resultObj->getHttpResult(false,"No data found","");
			echo json_encode($resultObj);
			break;
		case "Report":
			//echo "Recharge report";
			$reportName = $_GET['ReportName'];
			$startDate = $_GET['StarteDate'];
			$endDate = $_GET['EndDate'];
			reports($user,$mysqlObj,$langSMS,$langAPI,$reportName,$startDate,$endDate);
			break;
		case "GetRequestStatus":
			//echo "Recharge report";
			$requestID = $_GET['RequestID'];
			getRequestStatus($user,$mysqlObj,$requestID);
			break;
		default:
			echo "Invalid Action Name";
			break;
	}
	
}catch(Exception $ex){
	$resultObj = new httpresult();	
	$resultObj->getHttpResult(false,"Token Tampered 2","");
	echo json_encode($resultObj);
}
?>