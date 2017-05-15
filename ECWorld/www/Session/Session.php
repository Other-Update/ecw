<?php
include_once APPROOT_URL.'/Database/d_monitor.php';
include_once APPROOT_URL.'/Database/d_usersession.php';
include_once APPROOT_URL.'/General/general.php';

session_start();
$GLOBALS['tempMysqlObj'] = $mysqlObj;
function addMonitorLogs($isSuccess,$info){
	$meObj =json_decode($_SESSION['me']);
	$monitorObj = new d_monitor($GLOBALS['tempMysqlObj']);
	$user =json_decode($meObj);
	$monitorObj->add($user?$user->user->UserID:-1,$monitorObj->enumUrlAccessName,$isSuccess,$info);
}
function verifyUserSession(){
	$sessionValidDur = 3600;//1 hour in secs
	$meObj =json_decode($_SESSION['me']);
	$usObj = new d_userSession($GLOBALS['tempMysqlObj']);
	$user =json_decode($meObj);
	$lastUserSession = $usObj->getLastSessionByUserID($user?$user->user->UserID:-1);
	if(count($lastUserSession)==1){
		$lastAccessed = $lastUserSession[0]->LastAccessed;
		$today =date('Y-m-d H:i:s');
		//echo $today;
		$today = new DateTime($today);
		$lastAccessed = new DateTime($lastAccessed);
		$interval = $today->diff($lastAccessed);
		$diffDays = $interval->d;
		$diffHours = ($diffDays*24)+($interval->h);
		$diffMins = ($diffHours*60)+($interval->i);
		$diffSecs = ($diffMins*60)+($interval->s);
		//if($interval->s > 30) $diffMins+=1;
		if($diffSecs<$sessionValidDur){
			//echo "Valid";
			return 1;
		}
		else{
			//echo "Invalid";
			return 0;
		}
		//echo ",diffSecs=".$diffSecs;die;
	}else{
		//echo "Something wrong . Destroy the session";
		return 0;
	}
}
function verifyToken(){
	$tokenEnc = "";
	$isValid = false;
	try{	
		if(isset($_GET['Token'])){
			$tokenEnc = $_GET['Token'];
		}else if(isset($_POST['Token'])){
			$tokenEnc = $_POST['Token'];
		}else{
			 $json = file_get_contents('php://input');
			 $obj = json_decode($json);
			 if($obj!=""){
				//echo ",obj=".$obj;
				$tokenEnc=$obj->Token;
			 }
		}
		//echo "tokenEnc=".$tokenEnc;
		$ecwToken =$GLOBALS['EcwToken'];//Get global object
		
		$token = $ecwToken->decrypt($tokenEnc);
		//If token is broken then exception will be thrown from the above line
		//Controll will not go to the next line
		
		if($ecwToken->isValid($token))
			$isValid = true;
		//else
			//echo "Token expired";
	}catch(Exception $ex){
		echo "Token Tambered-2.Token=".$tokenEnc;
	}
	return $isValid;
	//echo ",Token = ".$tokenEnc;die;
}
function s_GetUserDetails(){
	if(Session_IsUserLoggedIn()){
		$meObj =json_decode($_SESSION['me']);
		return json_decode($meObj);
	}else{
		$resultObj = new httpresult();
		$resultObj->isSuccess=false;
		$resultObj->message="InvalidSession";
		echo json_encode($resultObj);
		die;
	}
	/* $meObj =json_decode($_SESSION['me']);
	return json_decode($meObj); */
}
function s_RefreshUserDetails(){
	return json_decode(json_decode($_SESSION['me']));
}
function Session_IsUserLoggedIn(){
	//$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
	//return isset($_SESSION['me']);
	$isLoggedIn = false;
	if(isset($_SESSION['me'])){
		$isUserSesionVerified = verifyUserSession();
		$isTokenVerified = verifyToken();
		//echo ",isUserSesionVerified=".$isUserSesionVerified;
		if($isUserSesionVerified==1){
			addMonitorLogs(1,"SessionVerified");
			if($isTokenVerified==1){
				$isLoggedIn = true;
				addMonitorLogs(1,"TokenVerified");
			}else{
				addMonitorLogs(0,"TokenVerificationFailed");
			}
		}
		else{
			addMonitorLogs(0,"SessionVerificationFailed");
		}
	}
	if(!$isLoggedIn) Session_Signout();
	return isset($_SESSION['me']);
}
function Session_Signout(){
	$_SESSION=array();
    session_regenerate_id(); 
    session_destroy();
	return true;
}
function s_redirect($url){
	if (headers_sent()){
	  die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
	}else{
	  header('Location: ' . $url);
	  die();
	}    
}
/* if(!Session_IsUserLoggedIn()) {
	header('location:..\..\index.php');
	exit;
} */
?>