<?php
include_once "../../BaseUrl.php";
include_once APPROOT_URL.'/Business/z_testing.php';
$testObj = new z_b_testing($mysqlObj);
$res = $testObj->upsert($_GET["Name"],$_SERVER["REMOTE_ADDR"]);
echo "Your IP ".$_SERVER["REMOTE_ADDR"]." has been saved at ID ".$res;
?>