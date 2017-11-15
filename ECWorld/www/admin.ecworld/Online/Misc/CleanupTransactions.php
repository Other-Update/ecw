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

//http://localhost/ECWorldBB/ECWorld/www/admin.ecworld/Online/Login.php?Name=1234567890&Password=Ecw!321&RememberMe=0

//http://localhost/ECWorldBB/ECWorld/www/admin.ecworld/Online/Misc/CleanupTransactions.php?DeleteOnAndBefore=2017-11-01&Token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTA3NjE4NDAsImp0aSI6ImNua01GRSt0V1E0YlA4Vmtodmg5Vm10RkhGdnhkRGloaHBxTnVld3pPbjA9IiwiaXNzIjoiRUNXU2VydmVyIiwibmJmIjoxNTEwNzYxODQwLCJleHAiOjE1MTA3OTc4NDAsImRhdGEiOnsidXNlcklkIjoiMSIsInVzZXJOYW1lIjoiQWRtaW4ifX0.Nukan0ie02l05_wqxJJMludRYIdcrajrjxJHO7YFuOk

//To set IST time in all functions by default
//date_default_timezone_set('Asia/Calcutta');
$doCheckToken = false; //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0ODg3MzUxODMsImp0aSI6InFSSWdqRkdibkx4UnJGaXI4cjVEd2ZtZ1FUS3BcL0MzclRSKzM1TnJyNzJjPSIsImlzcyI6IkVDV1NlcnZlciIsIm5iZiI6MTQ4ODczNTE4MywiZXhwIjoxODQ4NzM1MTgzLCJkYXRhIjp7InVzZXJJZCI6IjEiLCJ1c2VyTmFtZSI6IkFkbWluIn19.aftWxYdjc022nEd-7AqEQIY2qivmB4_w1tX7jNWj9bc
//Develper - //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MDA3NTIxMzUsImp0aSI6InBncVZmdVk1NU5lMkFQMFJDSVdmbmp6K1hveFU4ZUxNWnMzY05taE5tM2s9IiwiaXNzIjoiRUNXU2VydmVyIiwibmJmIjoxNTAwNzUyMTM1LCJleHAiOjM2MDE1MDA3NTIxMzUsImRhdGEiOnsidXNlcklkIjoiMTA4IiwidXNlck5hbWUiOiJEZXZlbG9wZXJzIn19.EivZhpiLVCgnVFhAZLaMLWN3vPcE8RcVH-LQ2PSB0rU
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
	if($user->UserID!='1') return false;
	//echo "<br/> userObj=".json_encode($me);
	//echo "<br/> User=".json_encode($user);
	//echo "<br/> Action=".$Action;
	//echo "Ilaiya";
	
	$tObj = new b_transaction($user,$mysqlObj,"");
	$reqObj = new b_request($user,$mysqlObj,"");
	$rcObj = new b_recharge($user,$mysqlObj,"","","","");
	$pObj = new b_payment($user,$mysqlObj,"");
	//$lastMonth=date('Y-m-d',strtotime("3 days"));
	$deleteOnAndBefore = $_GET['DeleteOnAndBefore'];
	$allUsers=$userObj->getAllUsers('1',true,true,'0');
	//echo json_encode($allUsers);
	foreach ($allUsers as $thisuser) {
		DeleteOldData($thisuser->UserID,$deleteOnAndBefore);
	}
	//$tObj->cleanupTransactions($user->UserID,$deleteOnAndBefore);
	
}catch(Exception $ex){
	$resultObj = new httpresult();	
	$resultObj->getHttpResult(false,"Token Tampered 2","");
	echo json_encode($resultObj);
}
?>