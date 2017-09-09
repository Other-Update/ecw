<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/Business/Token/b_token.php';
include_once APPROOT_URL.'/Business/b_users.php';;
include_once APPROOT_URL.'/Business/b_webservice.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Resource/User.php';
$resultJson = new httpresult();		
try{

	//$wsObj = new b_webservice('',$mysqlObj,'');
	//$wsObj->saveQueryStringTesting('Login',$_SERVER['QUERY_STRING']);
        //$wsObj->saveQueryStringTesting('Login',$obj->Name);
	//echo "Empty".$obj->Name;
	//return false;
        
	$username = "Name";
	$password = "Pass";
    $rememberme = "0";
    if(isset($_GET['Name'])){
		$username = $_GET['Name'];
		$password = $_GET['Password'];
		$rememberme = $_GET['RememberMe'];
    }else{
         $json = file_get_contents('php://input');
         $obj = json_decode($json);
         $username=$obj->Name;
         $password=$obj->Password;
         $rememberme=$obj->RememberMe;
    }
	$userObj=new b_users('',$mysqlObj,$lang);
	$resultStr = $userObj->login($username,$password);
	//echo $resultStr;
	$resultJson = json_decode($resultStr);
	$tokenEnc="";
	
	if($resultJson->isSuccess){
		$resultData=json_decode($resultJson->data);
		$tokenEnc = $ecwToken->getToken($resultData->user);//->user->UserID,$resultData->user->Name);
		$resultUser=$resultData->user;
		$userRole=$resultData->role;
		$userFat=$resultData->fat;
		$resultJson->data='{"user":'.json_encode($resultUser).',"role":'.json_encode($userRole).',"fat":'.json_encode($userFat).',"token":'.$tokenEnc.'}';
	}
	//echo $tokenEnc;
}catch(Exception $ex){
	$resultJson->isSuccess=true;
	$resultJson->message="Exception Occured";
}
echo json_encode($resultJson);
?>