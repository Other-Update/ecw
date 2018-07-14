<?php 
$config = '';//require '../../../Settings/config.php';
//echo 'report='.$report;
if(isset($report) && $report==true)
	$config = require '../../../../Settings/config.php';
else if(isset($mainfolder) && $mainfolder==true)
	$config = require '../../Settings/config.php';
else
	$config = require '../../../Settings/config.php';
$WebsiteUrl=$config['general']['websiteurl'];
//$WebsiteUrl="http://vm2017jan.cloudapp.net/test/ECWorldGit/ECWorld/www";
//$WebsiteUrl="http://localhost/ECWorld/www"; 
//$WebsiteUrl="http://ecworld.co.in/";
//$WebsiteUrl="http://192.168.126.30/ECWorldGit/ECWorld/www"; 
?>