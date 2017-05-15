<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_servicepermission.php';
class d_servicepermission{
	private $db;
	var $me;
	function __construct($me,$mysqlObj){
		$this->me=$me;
		$this->db = $mysqlObj;
	}
	//TODO: Not refering from anywhere -15/10
	function getAll(){
		try{
			$q="SELECT * FROM m_servicepermission where Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermission');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
	function get($spID){
		try{
			$q="SELECT * FROM m_servicepermission where ServicePermissionID='$spID' Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermission');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
	function getByUserID($userID){
		try{
			$q="SELECT * FROM m_servicepermission where UserID='$userID' and Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermission');
			//echo 'Count='.(count($res)==0);
			if(count($res)==0)
				return $res;
			else
				return $res[0];
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	function copy($fromUserID,$toUserID){
		try{
			$q="SELECT * FROM m_servicepermission where UserID='$fromUserID' and Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermission');
			//echo '<br />DB- Query Res sp id= '.$res[0]->ServicePermissionID;
			//echo '<br />DB- Query Res isOTFCommission= '.$res[0]->isOTFCommission;
			if(count($res)>0)
			{
				$res[0]->UserID=$toUserID;
				$this->add($res[0]);
				return true;
			}
			else return false;
			//return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
	function add($obj){
		try{
			//echo 'D servicepermission name-'.$obj->Name;
			$q="INSERT INTO m_servicepermission(Name,UserID,IsOTFMinCharge,OTFMinCharge,IsOTFCommission,IsFirstSMSCost,FirstSMSCost,IsAppliedForGroup,IsAppliedForSubGroup,CreatedDate,CreatedBy,ModifiedBy) 
			VALUE('$obj->Name','$obj->UserID','$obj->IsOTFMinCharge','$obj->OTFMinCharge','$obj->IsOTFCommission','$obj->IsFirstSMSCost','$obj->FirstSMSCost','$obj->IsAppliedForGroup','$obj->IsAppliedForSubGroup','$obj->CreatedDate','$obj->CreatedBy','$obj->ModifiedBy')";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	function update($obj){
		try{
			//echo 'D servicepermission name-'.$obj->Name;
			$q="UPDATE m_servicepermission SET IsOTFMinCharge='$obj->IsOTFMinCharge',OTFMinCharge='$obj->OTFMinCharge',IsOTFCommission='$obj->IsOTFCommission',IsFirstSMSCost='$obj->IsFirstSMSCost',FirstSMSCost='$obj->FirstSMSCost',IsAppliedForGroup='$obj->IsAppliedForGroup',IsAppliedForSubGroup='$obj->IsAppliedForSubGroup' WHERE ServicePermissionID='$obj->ServicePermissionID'";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	
		//Reghu code
	function deleteParentIDtoSubUser($userID){
		
		$user ="SELECT UserID FROM m_users WHERE ParentID='$userID' AND Active=1";
		$result=$this->db->selectArray($user,'e_users');
		$i=0;
		while($i < count($result)){
			$parentID = $result[$i]->UserID;
			$serPer ="SELECT ServicePermissionID FROM m_servicepermission WHERE UserID='$parentID' AND Active=1";
			$sPermis=$this->db->selectArray($serPer,'e_servicepermission');
				$ServicePermissionID = $sPermis[0]->ServicePermissionID;
				$q="DELETE FROM m_servicepermissionassign WHERE ServicePermissionID='$ServicePermissionID' ";
				$res = $this->db->execute($q);
			$i++;
		} 
		return $res;
	}
	//End code
}
?>