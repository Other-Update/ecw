<?php
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Business/b_service.php';
include_once APPROOT_URL.'/Resource/GeneralSettings.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
/* include_once APPROOT_URL.'/Resource/service.php';
include_once APPROOT_URL.'/Business/b_servicepermission.php';
include_once APPROOT_URL.'/Database/d_servicepermission.php';
include_once APPROOT_URL.'/General/general.php'; */

$action = $_POST['Action'];
switch($action){
	case 'LoadPage':
		loadPage($mysqlObj,$lang);
		break;
	case 'UpdateManageNetwork':
		updateManageNetwork($mysqlObj,$lang);
		break;
}
function loadPage($mysqlObj,$lang){
	$userDetails = s_GetUserDetails();
	$sObj=new b_service($userDetails->user,$mysqlObj,$lang);
	$resServiceList = $sObj->getAll();
	$gsObj=new b_generalsettings($userDetails->user,$mysqlObj,$lang);
	$resGSettings=$gsObj->get();
	
	$httpObj = new httpresult();
	$resultData='{"Services":'.json_encode($resServiceList).',"ServiceProblemMsgCur":'.json_encode($resGSettings->ServiceProblemMsgCur).',"ServiceProblemMsgPrev":'.json_encode($resGSettings->ServiceProblemMsgPrev).'}';
	$httpResult=$httpObj->getHttpResultObj(true,"Success",$resultData);
	echo json_encode($httpResult);
}

function updateManageNetwork($mysqlObj,$lang){
	$loggedInUserDetails = s_GetUserDetails();
	$gsObj=new b_generalsettings($loggedInUserDetails->user,$mysqlObj,$lang);
	$gsObj->ServiceProblemMsgCur=$_POST['ServiceProblemMsgCur'];
	$gsObj->ServiceProblemMsgPrev=$_POST['ServiceProblemMsgPrev'];
	$gsRes = $gsObj->updateServiceProblem($gsObj->ServiceProblemMsgCur);
	
	
	$sObj=new b_service($loggedInUserDetails->user,$mysqlObj,$lang);
	$serviceIDArr = $_POST['ServiceID'];
	$serviceCheckArr = $_POST['CheckSequence'];
	//echo count($serviceIDArr);
	for($i=0;$i<count($serviceIDArr);$i++){
		//echo '<br/>'.$i.'='.$serviceCheckArr[$i];
		//$sObj->ServiceID = $serviceIDArr[$i];
		//$sObj->IsProblem = $serviceCheckArr[$i];
		$result = $sObj->updateServiceProblem($serviceIDArr[$i],$serviceCheckArr[$i]);
	}
	echo json_encode($gsRes);
}
?>