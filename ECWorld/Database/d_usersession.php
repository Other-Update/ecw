<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_usersession.php';
class d_usersession{
	private $db;
	private $filename='t_usersession.php';
	private $tablename='t_usersession';
	var $logEnabledForUserSession = false;
	
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		//echo "<br/> islogerror=".$mysqlObj->configuration["general"]["islogerror"];
	}
	function add($userID,$ecwToken){
		if($this->logEnabledForUserSession==false )
			return false;
		$phpSessionID = md5(session_id()).'_'.md5(md5($userID.'PreventHack')).'_'.session_id();//Do not store PHPSESSID anywhere w/o encryption. it is like a password.
		if($ecwToken=="") $ecwToken="NotIssued";
		if(!$this->db){
		 die('Filename='.$this->filename.'Error='.mysql_error());
		};
		$ip = $_SERVER["REMOTE_ADDR"];
		
		$iquery="INSERT INTO `$this->tablename`(`UserID`,`IPAddress`,`PHPSESSID`,`EcwToken`,`LastAccessed`,`CreatedOn`) VALUES('$userID','$ip','$phpSessionID','$ecwToken',now(),now()) ";
		//echo "<br/><br/>".$iquery;
		$res = $this->db->execute($iquery);
	}
	function updateSessionAccess($us){
		if($this->logEnabledForUserSession==false )
			return false;
		$q = "UPDATE `$this->tablename` SET LastAccessed=now() WHERE UserSessionID='$us->UserSessionID'";
		$res=$this->db->execute($q);
	}
	function getLastSessionByUserID($userID){
		//$phpSessionID = md5(session_id());
		$phpSessionID = md5(session_id()).'_'.md5(md5($userID.'PreventHack')).'_'.session_id();
		$ip = $_SERVER["REMOTE_ADDR"];
		$q="SELECT UserSessionID,UserID,IPAddress,PHPSESSID,EcwToken,LastAccessed FROM `$this->tablename` WHERE UserID='$userID' AND IPAddress='$ip' AND PHPSESSID='$phpSessionID' ORDER BY LastAccessed DESC LIMIT 1";
		$res=$this->db->selectArray($q,'e_usersession');
		//echo $json;
		if(count($res)>0) $this->updateSessionAccess($res[0]);
		return $res;
	}
}
?>