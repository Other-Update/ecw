<?php
include_once "../../BaseUrl.php";
include_once APPROOT_URL.'/Resource/User.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/Business/b_service.php';
include_once APPROOT_URL.'/Business/b_webservice.php';
include_once APPROOT_URL.'/Business/b_webserviceactions.php';
include_once APPROOT_URL.'/General/general.php';
//To set IST time in all functions by default
//date_default_timezone_set('Asia/Calcutta');
/* $now=date('Y-m-d H:i:s');
echo "<br/> Now=".$now;
die; */
//$resultObj = new httpresult();	

function renewToken_NIU($token,$ecwTokenObj){
	GLOBAL $renewedTokenEnc;
	$renewedToken = $ecwTokenObj->renew($token);
	$renewedTokenEnc = $ecwTokenObj->encrypt($token);
}
function getTokenByGuid_NIU($guid){
	//TODO: Get encrypted token value from DB against the given GUID
	return "";
}
try{
	$startTime=round(microtime(true) * 1000);
	
	$tokenGuid="";//$_GET["TokenGUID"];
	$reqMobile = $_GET['mobile'];
	$msg = $_GET['msg'];
	$serverNo = $_GET['ServerNumber'];
	//echo '<br/>mysqlObj='.json_encode($mysqlObj);
	$wsObj = new b_webservice('',$mysqlObj,'',$langSMS,$langAPI);
	$reqDate = $_GET["date"];
	$reqTime = $_GET["time"];
	$mysqlObj->errorlog->addLog("OfflineRecharge","do.php","Common",$_SERVER['QUERY_STRING'],"QueryString","No Table","0","Log1");
	//$mysqlObj->errorlog->addVS("Testing message1");
	//$res = $wsObj->saveQueryStringTesting("Offline/do",$_SERVER['QUERY_STRING']);
	
	$res = $wsObj->processOfflineRequest($tokenGuid,$reqMobile,$msg,$reqDate,$reqTime,$_SERVER['QUERY_STRING'],$serverNo);
	
	$endTime=round(microtime(true) * 1000);
	$jsonRes=json_decode(json_decode($res));
	echo json_encode($jsonRes);
	/* echo "<br/><hr/> Do.php";
	echo "<br/> Final json=".$res;
	echo "<br/><br/>Final message=".$jsonRes->Message;
	//echo "<br/><br/>Final SMS message=".$langSMS[$jsonRes->SmsCode];
	*/
	echo '<br/><b>startTime(ms)='.$startTime."</b>";
	echo '<br/><br/><b>endTime(ms)='.$endTime;
	$diffTime=$endTime-$startTime;
	echo '<br/>Time taken in milliseconds='.$diffTime."</b>";
	
}catch(Exception $ex){
	$message = "Token Tampered 2";
}
?>