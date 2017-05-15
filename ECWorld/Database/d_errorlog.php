<?php
include_once 'd_mysqldb.php';
class d_errorlog{
	private $db;
	//private $filename='d_errorlog';
	private $tablename='t_errorlog';
	var $islogerror = 0;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		//echo "<br/> islogerror=".$mysqlObj->configuration["general"]["islogerror"];
		$this->islogerror = $mysqlObj->configuration["general"]["islogerror"];
	}
	//Very Simple error log - 1
	function addVS($devmsg,$fileName="NoFile",$functionName="NoFunction"){
		$this->add("-1","-1","NoSource",$fileName,$functionName,"NoError",$devmsg,"NoTable","0","Logged from VerySimpleErrorLog Fn1");
	}
	//Simple error log - 2
	function addS($errormsg,$devmsg,$errortable,$referenceID,$fileName,$functionName){
		$this->add("-1","-1","NoSource",$fileName,$functionName,$errormsg,$devmsg,$errortable,$referenceID,"Logged From SimpleErrorLog Fn2");
	}
	//No created by and No Apply to users will be logged
	function addLog($source,$fileName,$functionName,$errormsg,$devmsg,$errortable,$referenceID,$moredetails){
		$this->add("-1","-1",$source,$fileName,$functionName,$errormsg,$devmsg,$errortable,$referenceID,$moredetails);
	}
	function add($userid,$applyToUserID,$source,$fileName,$functionName,$errormsg,$devmsg,$errortable,$referenceID,$moredetails){
		if($this->islogerror==0)
			return false;
		if(!$this->db){
		 die('Filename='.$this->filename.'Error='.mysql_error());
		}
		//$devmsg = $userid.','.$errortable;
		
		$iquery="INSERT INTO `$this->tablename`(`ApplyToUserID`,`Source`,`FileName`,`FunctionName`,`Type`,`ReferenceID`,`Error`, `DevMessage`, `MoreDetails`,`CreatedBy`) VALUES('$applyToUserID','$source','$fileName','$functionName','$errortable','$referenceID','$errormsg', '$devmsg', '$moredetails','$userid') ";
		//echo "<br/><br/>".$iquery;
		$res = $this->db->execute($iquery);
		//echo $res;
		
		/*$podObj = $this->db->query($iquery);
		if($podObj){
			echo "error inserted";
		} else {
			echo "Something Wrong. Try Again...";
		}*/
	}
}
//$testObj = new errorlog($mysqlObj);
//$testObj->add('error2','1','errorlog','Nothing');
?>