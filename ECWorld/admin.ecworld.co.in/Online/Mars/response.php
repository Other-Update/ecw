<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'../Resource/User.php';
include_once APPROOT_URL.'../Resource/Sms.php';
include_once APPROOT_URL.'../Business/Token/b_token.php';
include_once APPROOT_URL.'../Business/b_webservice.php';
/*include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/Business/b_service.php';
include_once APPROOT_URL.'/Business/b_webserviceactions.php';
include_once APPROOT_URL.'/General/general.php'; */

//To set IST time in all functions by default
//date_default_timezone_set('Asia/Calcutta');

$message = "Token Tampered";
$isSuccess = false;
$renewedTokenEnc = "";

function isTokenValid($token,$ecwTokenObj){
	GLOBAL $message,$isSuccess;//TO access global variable inside function
	//TODO: Once getTokenByGuid() is proper then enable the below logic to verify the token	
	$isSuccess = true;//Temp
	$message = "Processing recharge";//Temp
	return true;//Temp			
			
	//echo "<br/>.Decoded = ".$token->iat;
	if($token==null){
		$message = "Token is null";
		return false;
	}
	else{
		if($ecwTokenObj->isValid($token)){
			$isSuccess = true;
			$message = "Processing recharge";
			return true;
		}
		else{
			$message = "Token expired/invalid";
			return false;
		}
	}
}
function renewToken($token,$ecwTokenObj){
	GLOBAL $renewedTokenEnc;
	$renewedToken = $ecwTokenObj->renew($token);
	$renewedTokenEnc = $ecwTokenObj->encrypt($token);
}
function getTokenByGuid($guid){
	//TODO: Get encrypted token value from DB against the given GUID
	return "";
}
try{
	$startTime=round(microtime(true) * 1000);
	//echo '<br/><b>startTime(ms)='.$startTime."</b>";
	$tokenEnc="";//getTokenByGuid($_GET["Token"]);
	$token = "";//$ecwToken->decrypt($tokenEnc);
	if(isTokenValid($token,$ecwToken)){
		$respRcID = $_GET["yourref"];
		$respStatus = $_GET["status"];
		$respOpTransID = $_GET["transid"];
		$respOpMsg = $_GET["message"];
		$respBal = $_GET["balance"];	
		//echo $respOpMsg;
		//$wsObj = new b_webservice('',$mysqlObj,'',$langSMS);
		$wsObj = new b_webservice('',$mysqlObj,'',$langSMS,$langAPI);
		
		$mysqlObj->errorlog->addLog("RechargeResponse","APIResponse.php","Common",$_SERVER['QUERY_STRING'],"QueryString","t_request",$respRcID,$respStatus);
		//$wsObj->saveQueryStringTesting('APIResponse',$_SERVER['QUERY_STRING']);
		$res = $wsObj->processRCApiResponse($respRcID,$respStatus,$respOpTransID,$respOpMsg,$respBal,$_SERVER['QUERY_STRING'],"Mars");
		
		$jsonRes=json_decode(json_decode($res));
		echo json_encode($jsonRes);
	}else{
		//Given token is invalid
	}
	/* $endTime=round(microtime(true) * 1000);
	echo '<br/><br/><b>endTime(ms)='.$endTime;
	$diffTime=$endTime-$startTime;
	echo '<br/>Time taken in milliseconds='.$diffTime."</b>"; */
	//echo $tokenEnc;
	//sleep(11);
}catch(Exception $ex){
	//echo $ex;
	$message = "Token Tampered 2";
}
//echo json_encode($resultObj->getHttpResultObj($isSuccess,$message,'{"RenewedToken":'.$renewedTokenEnc.'}'));
?>