<?php
include_once APPROOT_URL.'/Database/d_fat.php';
class b_fat{
	var $me;
	var $dbObj;
	function __construct($thisUser,$mysqlObj){
		$this->me=$thisUser;
		$this->dbObj=new d_fat($mysqlObj);
	}
	function add($fatObj){
		return $this->dbObj->add($fatObj);
	}
	function get(){
		try{
			$arr = $this->dbObj->get();
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_fat','Testing');
		}
	}
	
	function getByUserID($userID,$isRole=0){
		$res = $this->dbObj->getByUserID($userID,$isRole);
		return $res;
	}
	function getByParent($parentID){
		$res = $this->dbObj->getByParent($parentID);
		return $res;
	}
	function updateFat($bFatObj){
		$res = $this->dbObj->updateFat($bFatObj);
		return $res;
	}
}
?>