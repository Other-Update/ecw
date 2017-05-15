<?php
session_start();
function Session_GetLoggedInUser($mysqlObj){
	echo $_SESSION['me'];
}
//this fn is copy of Session_GetLoggedInUser. and it is being called from header.php
function Session_ReturnLoggedInUser(){
	return $_SESSION['me'];
}
function Session_IsUserLoggedIn(){
	$isLoggedIn=false;
	if(isset($_SESSION['me'])){
		$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
		if($loggedInUserDetails)
			if($loggedInUserDetails->user)
				$isLoggedIn=true;
	}
	if(!$isLoggedIn) Session_Signout();
	return $isLoggedIn;
}
function Session_Signout(){
	session_destroy();
	return true;
}
//echo 'IsUserLoggedIn='.(Session_IsUserLoggedIn()==true?'YES':'NO');

function session_redirect($url){
	if (headers_sent()){
	  die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
	}else{
	  header('Location: ' . $url);
	  die();
	}    
}
if(!Session_IsUserLoggedIn()) {
	//echo "INSIDE";
	if(strpos($_SERVER['REQUEST_URI'], 'AdminWorld') >0)
		session_redirect("..\..\AdminWorld\Login\index.php");
		//header('location:..\..\AdminWorld\Login\index.php');
	else
		session_redirect("..\..\Distributor\Login\index.php");
		//header('location:..\..\Distributor\Login\index.php');
	exit;
}
/* else{
	echo "ELSE";
} */
?>