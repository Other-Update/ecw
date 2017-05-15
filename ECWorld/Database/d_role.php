<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_role.php';
class d_role{
	private $db;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
	}
	function getByID($roleID){
		$q="SELECT RoleID,Name,Priority FROM m_role where RoleID='$roleID' AND Active='1'";
		//echo $q;
		$res=$this->db->selectArray($q,'e_role');
		return $res[0];
	}
	
	function getRolesBelowPriority($priority){
		$q="SELECT RoleID,Name,Priority FROM m_role WHERE Priority>$priority AND Active='1' ";
		$res=$this->db->selectArray($q,'e_role');		
		return $res;
	}
	
	function getAllExcept($roles){
		//$q="SELECT RoleID,Name FROM m_role where Active='1'";
		//echo $q;
		$q="SELECT RoleID,Name FROM m_role where Active='1'";
		$i=0;
		while($i<count($roles)){
			if($i==0) $q.=" AND ( Name<>'$roles[$i]'";
			else $q.=" AND Name<>'$roles[$i]'";
			$i++;
			//echo '<br/>'.$q;
		}
		$q.=")";
		$res=$this->db->selectArray($q,'e_role');		
		return $res;
	}
	
	function getAllByRoleNames($roles){
		try{
			//echo 'aaa'.json_encode($roles);
			$q="SELECT RoleID,Name FROM m_role where Active='1'";
			$i=0;
			while($i<count($roles)){
				//echo $roles[$i];
				if($i==0) $q.=" AND ( Name='$roles[$i]'";
				else if($i+1==count($roles)) $q.=" OR Name='$roles[$i]')";
				else $q.=" OR Name='$roles[$i]'";
				$i++;
				//echo '<br/>'.$q;
			}
			//$q.=")";
			//echo '<br/>Query='.$q;
			$res=$this->db->selectArray($q,'e_role');
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_role','Testing');
		}
	}
	function getRoleByIDs($roleIDs){
		try{
			//echo 'aaa'.json_encode($roles);
			$q="SELECT RoleID,Name FROM m_role where Active='1'";
			$i=0;
			while($i<count($roleIDs)){
				//echo $roles[$i];
				if($i==0) $q.=" AND ( RoleID='$roles[$i]'";
				else if($i+1==count($roles)) $q.=" OR RoleID='$roles[$i]')";
				else $q.=" OR RoleID='$roles[$i]'";
				$i++;
				//echo '<br/>'.$q;
			}
			//$q.=")";
			//echo '<br/>Query='.$q;
			$res=$this->db->selectArray($q,'e_role');
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','b_role','Testing');
		}
	}
}
?>