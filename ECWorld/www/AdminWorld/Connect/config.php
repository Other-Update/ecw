<?php
date_default_timezone_set("Asia/Kolkata");
$driver = 'mysql';
$host = 'localhost';
$database = 'ecworld1_live';
$user = 'ecworld1_ecworld';
$pass = '_F9N@_{QtN05';

global $connect;
$connect = connect($driver, $host, $database, $user, $pass);
function connect($driver, $host, $database, $user, $pass){
	try {
		  return new PDO("$driver:host=$host; dbname=$database", $user, $pass);
		} catch (PDOException $e) {
		  return $e->getMessage();
		  die();
		}
}
?>