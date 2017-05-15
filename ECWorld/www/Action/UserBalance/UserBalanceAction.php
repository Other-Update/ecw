<?php
//session_start();
include_once "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/User.php';
include_once APPROOT_URL.'/Resource/Sms.php';
include_once APPROOT_URL.'/Business//b_users.php';
include_once APPROOT_URL.'/Business/b_role.php';
include_once APPROOT_URL.'/Business/b_fat.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_sms.php';
include_once APPROOT_URL.'/Business/b_http.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
$action = $_POST['Action'];
switch($action){

	case 'GetUsersByParent_DT':
		GetUsersByParent_DT($mysqlObj,$lang);
		break; 
	case 'GetLoggedInUser':
		GetLoggedInUser($mysqlObj);
		break;
}
function GetLoggedInUser($mysqlObj){
	echo $_SESSION['me'];
}

//Get all users for datatable
function GetUsersByParent_DT($mysqlObj,$lang){
	$parentID = $_POST['ParentID'];
	$roleID="0";
	if(isset($_POST['RoleID']))
	$roleID=$_POST['RoleID'];

	$me = s_GetUserDetails();
	$includeParent=$me->user->UserID==1?true:false;
	if($parentID=="LoggedInUser" || $parentID==0)
		$parentID=$me->user->UserID;
	$userObj=new b_users($me->user,$mysqlObj,$lang);
	$res = $userObj->getUsersByParent_DT($parentID,$includeParent,$roleID);
	//echo "<br/> res=".$res;
	$val = json_decode($res);
	//echo "<br/> res=".json_encode($val->aaData[0][""]);
	$finalArr = array();
	for($i=0;$i<count($val->aaData);$i++){
		//echo "<br/> key=".json_encode($i).",value=".json_encode($val->aaData[$i]);
		$wallet= $val->aaData[$i][8];
		$balanceLevel=$val->aaData[$i][11];
		//echo "<br/> user=".$val->aaData[$i][0].",wallet=".$val->aaData[$i][8].", bl=".$val->aaData[$i][11];
		if($wallet<=$balanceLevel){
			$finalArr[count($finalArr)] = $val->aaData[$i];
		}

	}
	//echo "<br/> finalArr=".json_encode($finalArr);
	$val->aaData = $finalArr;
	//echo "<br/> finalArr=".json_encode($val);
	/*while(json_encode($val->aaData)){

	}*/

	echo json_encode($val);
	//echo $res;
}

