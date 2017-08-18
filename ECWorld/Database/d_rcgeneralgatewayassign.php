<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rcusergateway.php';
/* include_once APPROOT_URL.'/Entity/e_service.php'; */
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_rcgeneralgatewayassign{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	
	function get(){
		$q="SELECT ga.RCUserGatewayUD,ga.IsAssigned,u.UserID,u.Name FROM m_users as u LEFT JOIN  m_rcgeneralgatewayassign as ga ON (u.UserID=ga.UserID OR ga.UserID IS NULL) WHERE u.UserID!=1 AND u.ParentID=1 AND u.RoleID!=2 AND u.Active=1";
		//echo $q;
		$res=$this->db->selectArray($q,'e_rcgeneralgatewayassign');
		return $res;
	}
	function isUserAssigned($userID,$ancestorID){
		$q="SELECT * from m_rcgeneralgatewayassign WHERE Active=1 AND IsAssigned=1 AND UserID=";
		$qGenGateway = $q.$userID;
		//echo $q;	
		$res=$this->db->selectArray($qGenGateway,'e_rcgeneralgatewayassign');
		if(count($res)<=0){
			$qGenGateway = $q.$ancestorID;
			$res=$this->db->selectArray($qGenGateway,'e_rcgeneralgatewayassign');
			if(count($res)<=0)
				return false;
			else 
				return true;
		}
		return true;
	}
	function add($userID){
		try{
			//echo '<br /> db-Update service';								
			$rcUserGatewayUD = 1;//Admin GatewayID;
			$createdDate = date('Y-m-d h:i:s');
			$createdBy = $this->me->UserID;
			$modifiedBy = $this->me->UserID;
			$q="INSERT INTO m_rcgeneralgatewayassign(RCUserGatewayUD,UserID,CreatedDate,CreatedBy,ModifiedBy) VALUES('$rcUserGatewayUD','$userID','$createdDate','$createdBy','$modifiedBy') ";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function update($userID,$isAssigned){
		try{
			$modifiedBy = $this->me->UserID;
			$q="UPDATE m_rcgeneralgatewayassign SET IsAssigned='$isAssigned' WHERE UserID='$userID' ";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function upsert($checkedUsersArr){
		try{
			//echo '<br /> db-Update service';				
			$userID = $this->me->UserID;
			//Blindly unassign all users from General gateway
			$qUnAssignAll="UPDATE m_rcgeneralgatewayassign SET IsAssigned='0', ModifiedBy='$userID'";// WHERE RCGenralGatewayAssignID='$id'";
			$res=$this->db->execute($qUnAssignAll);
			for($i=0;$i<count($checkedUsersArr);$i++){
				$q="SELECT UserID FROM m_rcgeneralgatewayassign WHERE UserID='$checkedUsersArr[$i]'";
				$res=$this->db->selectArray($q,'e_rcgeneralgatewayassign');
				if(count($res)==0)
					$this->add($checkedUsersArr[$i]);
				else
					$this->update($checkedUsersArr[$i],1);
				//echo $checkedUsersArr[$i]."=".count($res);
			}
			//echo '<br/> Query= '.$q;
			//$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return true;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
		return false;
	}
	
}
?>