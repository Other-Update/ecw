<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Resource/User.php';
include_once APPROOT_URL.'/Business/b_distmargin.php';
$action = $_POST['Action'];	
switch($action){
	case 'GetDistMarginUsers_DT':
		getUsers_DT($mysqlObj,$lang);
		break;
	case 'GetMarginByUser':
		getMarginByUser($mysqlObj,$lang);
		break;
	case 'UpdateMargins':
		updateMargins($mysqlObj,$lang);
		break;
	/* case 'GetTodayOpeningBalance':
		getTodayOpeningBalance($mysqlObj,$lang);
		break; */
}
/*function getOpeningBalanceByDate($mysqlObj,$lang){
	$httpObj = new httpresult();
	$userDetails = s_GetUserDetails();
	$dmObj=new b_distmargin($userDetails->user,$mysqlObj,$lang);
	$httpResult=$httpObj->getHttpResultObj(true,"Success",$res);
	$yesterday=date('Y-m-d',strtotime("-1 days"));
	//To get today opening balance , get yesterdays closing balance
	echo json_decode($dmObj->getOpeningBalanceByDate($yesterday));
} */
function updateMargins($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$dmObj=new b_distmargin($userDetails->user,$mysqlObj,$lang);
	$dmObj->deleteByUser($_POST["UserID"]);
	
	$dmObj->updateOpenBalance($_POST["UserID"],$_POST["idPopOpeningBalance"]);
	
	//echo $_POST["UserID"];
	$FromAmount = isset($_POST['FromAmount'])?$_POST['FromAmount']:"";
	$ToAmount = isset($_POST['ToAmount'])?$_POST['ToAmount']:"";
	$NormalBilling = isset($_POST['NormalBilling'])?$_POST['NormalBilling']:"";
	$RegularBilling = isset($_POST['RegularBilling'])?$_POST['RegularBilling']:"";
	//echo count($FromAmount);
	
	for($i=0;$i<count($FromAmount) && isset($_POST['FromAmount']);$i++){
		$dmObj->UserID = $_POST["UserID"];
		$dmObj->FromAmount = $FromAmount[$i];
		$dmObj->ToAmount = $ToAmount[$i];
		$dmObj->NormalBilling = $NormalBilling[$i];
		$dmObj->RegularBilling = $RegularBilling[$i];
		$result = $dmObj->add($dmObj);
	}
	$httpObj = new httpresult();
	$httpResult=$httpObj->getHttpResultObj(true,"Success","");
	echo json_encode($httpResult);
}
function getUsers_DT($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$excludeRoleIDs="";
	if(isset($_POST['ExcludeRoleIDs']))
		$excludeRoleIDs=$_POST['ExcludeRoleIDs'];
	$dmObj=new b_distmargin($userDetails->user,$mysqlObj,$lang);
	echo $dmObj->getUsers_DT($_POST["ParentID"],$excludeRoleIDs);
}
function getMarginByUser($mysqlObj,$lang){
	$httpObj = new httpresult();
	$userDetails = s_GetUserDetails();
	$dmObj=new b_distmargin($userDetails->user,$mysqlObj,$lang);
	$marginData = $dmObj->getByUserID($_POST["UserID"]);
	$openBalance = $dmObj->getUserMinOpeningBalance($_POST["UserID"]);
	/* //To get today opening balance , get yesterdays closing balance
	$yesterday=date('Y-m-d',strtotime("-1 days"));
	$todayOpeningBal = $dmObj->getClosingBalanceByDate($yesterday)); */
	
	//$httpResult=$httpObj->getHttpResultObj($marginData);
	//$httpResult2=$httpObj->getHttpResultObj($openBalance);
	
	$httpObj->isSuccess=true;
	$httpObj->message='success';
	$httpObj->data='{"MarginData":'.json_encode($marginData).',"OpenBalance":'.$openBalance.'}';
	
	echo json_encode($httpObj);
}
?>