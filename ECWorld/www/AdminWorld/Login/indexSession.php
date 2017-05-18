<?php
include "../../../BaseUrl.php";
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/Resource/User.php';
include_once APPROOT_URL.'/Business/b_users.php';
$username = $_POST['Name'];
$password = $_POST['Password'];
//die;
$userObj=new b_users('',$mysqlObj,$lang);
$loginResult = $userObj->login($username,$password);
$loginResultJson = json_decode($loginResult);
//echo json_encode($loginResultJson->data);
$_SESSION['me']=json_encode($loginResultJson->data);
echo $loginResult;
?>