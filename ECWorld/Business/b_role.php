<?php
include_once APPROOT_URL.'/Database/d_role.php';
class b_role{
	private $filename='b_role';
	var $dbObj;
	function __construct($mysqlObj){
		$this->dbObj=new d_role($mysqlObj);
	}
	function getByID($roleID){
		$res = $this->dbObj->getByID($roleID);
		return $res;
	}
	function getRolesBelowRole($roleID){
		try{
			$role = $this->dbObj->getByID($roleID);
			//return $role->Priority;
			$res = $this->dbObj->getRolesBelowPriority($role->Priority);
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_role','Testing');
		}
	}
	function getAllExcept($roles){
		try{
			$res = $this->dbObj->getAllExcept($roles);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_role','Testing');
		}
	}
	
	//This fn is being converted to getRolesByIDs
	function getAllByRoleNames($roleNames){
		try{
			$arr = $this->dbObj->getAllByRoleNames($roleNames);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_role','Testing');
		}
	}
	function getRoleByIDs($roleIDs){
		try{
			$arr = $this->dbObj->getRoleByIDs($roleIDs);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_role','Testing');
		}
	}
}
/*
$arr = ['Admin','StateDistributor','Distributor','SubDistributor'];
$obj=new b_role($mysqlObj);
$r = $obj->getAllByRoleNames($arr);
echo json_encode($r);*/

/*$obj=new b_role($mysqlObj);
$r = $obj->getByID(1);
echo json_encode($r);*/
?>
