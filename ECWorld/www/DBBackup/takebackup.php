<?php
//This script will take backup of schema,data, triggers but not stored procedures
$username ="";
$password ="";
$hostname = "localhost";
$database = "";
$username =escapeshellcmd($username);
$password =escapeshellcmd($password);
$hostname =escapeshellcmd($hostname);
$database =escapeshellcmd($database);
//$backupFile=getcwd()."/".date("Y-m-d-H-i-s").$database.".sql";//Works in godaddy server
$backupFile="C:/ECWorld/DBBackup/".date("Y-m-d-H-i-s").$database.".sql";//Change path according the server
$command = "mysqldump -u $username -p$password $database > $backupFile";
echo $command;echo "\n";
system($command, $result);
echo $result;


?>