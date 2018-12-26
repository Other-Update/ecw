<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/Business/Token/b_token.php';
include_once APPROOT_URL.'/Business/b_users.php';;
include_once APPROOT_URL.'/Business/b_webservice.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Resource/User.php';
$resultJson = new httpresult();		
try{
   	
	$json = file_get_contents('php://input');
	$obj = json_decode($json);
	$userId=$obj->userId;
	$password=$obj->password;
	$oldpassword=$obj->oldpassword;


	$userObj=new b_users('',$mysqlObj,$lang);
	$resultStr = $userObj->changePass($userId,$password, $oldpassword);
	$resultJson = json_decode($resultStr);
	
	if($resultJson->isSuccess){
		$resultUser=$resultJson->isSuccess;
		$resultUser=$resultJson->message;
		$resultJson->data=json_encode($resultUser);
	}
	//echo $tokenEnc;
}catch(Exception $ex){
	$resultJson->isSuccess=true;
	$resultJson->message="Exception Occured";
}
echo json_encode($resultJson);
?>