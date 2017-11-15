<?php
include_once 'd_mysqldb.php';
class d_monitor{
	private $db;
	private $filename='t_monitor.php';
	private $tablename='t_monitor';
	
	var $logEnabledForLoginAttempt = false;
	var $logEnabledForUrlAccess = false;
	
	public $enumLoginAttemptName = "LoginAttempt";
	public $enumUrlAccessName = "UrlAccess";
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		//echo "<br/> islogerror=".$mysqlObj->configuration["general"]["islogerror"];
	}
	function add($userID,$type,$isSuccess,$otherInfo){
		//echo "d_monitor add.<<<<<<<<";
		if($type=="LoginAttempt" && $this->logEnabledForLoginAttempt==false )
			return false;
		if($type=="URL" && $this->logEnabledForUrlAccess==false )
			return false;
		if(!$this->db){
		 die('Filename='.$this->filename.'Error='.mysql_error());
		};
		$ip = $_SERVER["REMOTE_ADDR"];
		$headers = apache_request_headers();
		$url = isset($headers['Referer'])?$headers['Referer']:"".$_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'];
		//$url = $headers['Referer'].'?'.$_SERVER['QUERY_STRING'];
		$get = json_encode($_GET);
		$post = json_encode($_POST);
		
		$iquery="INSERT INTO `$this->tablename`(`IPAddress`,`UserID`,`Type`,`IsSuccess`,`GET`,`POST`,`URL`,`OtherInfo`) VALUES('$ip','$userID','$type','$isSuccess','$get','$post','$url','$otherInfo') ";
		//echo "<br/><br/>".$iquery;
		//$res = $this->db->addMonitor($ip,$userID,$type,$isSuccess,$get,$post,$url,$otherInfo,'e_monitor');
		//$res = $this->db->execute($iquery);
		//echo "d_monitor add.>>>>>>>>>>>>>>>";
		
        try{
	   $today =date('Y-m-d H:i:s');
        $myFile =$_SERVER["DOCUMENT_ROOT"].'/t_monitor.txt';
        $fh = fopen($myFile, 'a+') or die("can't open file");
        $stringData = "\n".$today." || \n";
        $stringData.="".$iquery;
        fwrite($fh, $stringData);
        fclose($fh);
        }catch(Exception $ex){
            echo "Exception = ".json_encode($ex);
        }
		$res = $this->db->execute($iquery);
	}
}
?>