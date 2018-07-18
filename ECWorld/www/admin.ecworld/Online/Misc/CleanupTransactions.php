<?php
include_once "../../../../BaseUrl.php";
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

//Get Token using the following url
//http://localhost/ECWorldBB/ECWorld/www/admin.ecworld/Online/Login.php?Name=&Password=&RememberMe=0

//DeleteOnAndBefore - YYYY-MM-DD
//http://admin.ecworld.info/Online/Misc/CleanupTransactions.php?DeleteOnAndBefore=2017-11-01&Token=
function DeleteOldData($userid,$deleteOnAndBefore){
	echo "<br/>".$thisuser->DisplayID."-".$thisuser->Name.",";
	$tObj->DeleteOldTransactions($userid,$deleteOnAndBefore);
	$pObj->DeleteOldPayments($userid,$deleteOnAndBefore);
	$rcObj->DeleteOldRecharges($userid,$deleteOnAndBefore);
	$reqObj->DeleteOldRequests($userid,$deleteOnAndBefore);
}
try{
	$startTime=round(microtime(true) * 1000);
	
	$tokenGuid="";//$_GET["TokenGUID"];
	//$Action = $_GET['Action'];
	$wsObj = new b_webservice('',$mysqlObj,'',$langSMS,$langAPI,"");
	//$res = $wsObj->saveQueryStringTesting("Online/do",$_SERVER['QUERY_STRING']);
	
	$mysqlObj->errorlog->addLog("OnlineRecharge","do.php","Common",$_SERVER['QUERY_STRING'],"QueryString","No Table","0","Log1");
	
	$generalConfig = $mysqlObj->configuration['general'];
	$tokenEnc="";
	//echo $generalConfig["isdev"].",";
	if($generalConfig["isdev"]==1){
		$tokenEnc = "";
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
	
	$userObj = new b_users("",$mysqlObj,"");
	$user = $userObj->getByID($token->data->userId);//TODO: get user id from token
	if($user->UserID!='1') return false;
	
	$tObj = new b_transaction($user,$mysqlObj,"");
	$reqObj = new b_request($user,$mysqlObj,"");
	$rcObj = new b_recharge($user,$mysqlObj,"","","","");
	$pObj = new b_payment($user,$mysqlObj,"");
	//$lastMonth=date('Y-m-d',strtotime("3 days"));
	$deleteOnAndBefore = $_GET['DeleteOnAndBefore'];
	$allUsers=$userObj->getAllUsers('1',true,true,'0');
	//echo json_encode($allUsers);
	foreach ($allUsers as $thisuser) {
    	echo "<br/>".$thisuser->DisplayID."-".$thisuser->Name.",";
    	$tObj->DeleteOldTransactions($thisuser->UserID,$deleteOnAndBefore);
    	$pObj->DeleteOldPayments($thisuser->UserID,$deleteOnAndBefore);
    	$rcObj->DeleteOldRecharges($thisuser->UserID,$deleteOnAndBefore);
    	$reqObj->DeleteOldRequests($thisuser->UserID,$deleteOnAndBefore);
	}
	
}catch(Exception $ex){
	$resultObj = new httpresult();	
	$resultObj->getHttpResult(false,"Token Tampered 2","");
	echo json_encode($resultObj);
}
?>