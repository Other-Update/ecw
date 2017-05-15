<?php
include_once 'd_mysqldb.php';
class d_monitor{
	private $db;
	private $filename='t_monitor.php';
	private $tablename='t_monitor';
	
	var $logEnabledForLoginAttempt = true;
	var $logEnabledForUrlAccess = true;
	
	public $enumLoginAttemptName = "LoginAttempt";
	public $enumUrlAccessName = "UrlAccess";
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		//echo "<br/> islogerror=".$mysqlObj->configuration["general"]["islogerror"];
	}
	function add($userID,$type,$isSuccess,$otherInfo){
		if($type=="LoginAttempt" && $this->logEnabledForLoginAttempt==false )
			return false;
		if($type=="URL" && $this->logEnabledForUrlAccess==false )
			return false;
		if(!$this->db){
		 die('Filename='.$this->filename.'Error='.mysql_error());
		};
		$ip = $_SERVER["REMOTE_ADDR"];
		$url = $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'];
		$get = json_encode($_GET);
		$post = json_encode($_POST);
		
		$iquery="INSERT INTO `$this->tablename`(`IPAddress`,`UserID`,`Type`,`IsSuccess`,`GET`,`POST`,`URL`,`OtherInfo`) VALUES('$ip','$userID','$type','$isSuccess','$get','$post','$url','$otherInfo') ";
		//echo "<br/><br/>".$iquery;
		$res = $this->db->execute($iquery);
	}
}
?>