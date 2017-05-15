<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_servicepermissionassign.php';
class d_servicepermissionassign{
	private $db;
	private $me;
	function __construct($me,$mysqlObj){
		//echo '<br/>db='.$mysqlObj->test_returnThis('aa');
		$this->me=$me;
		$this->db = $mysqlObj;
	}
	function getBySpID($spID){
		try{
			//$q="SELECT * FROM m_servicepermissionassign where ServicePermissionID='$spID' and Active='1'";
			$q="select s.Name,s.ServiceID,sp.ServicePermissionAssignID,sp.ServicePermissionID,sp.IsEnabled,sp.MinCharge,sp.Commission from m_service s left join m_servicepermissionassign as sp on sp.ServiceID=s.ServiceID and sp.ServicePermissionID='$spID' WHERE s.Active='1' ";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermissionassign');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	function getBySpAndServiceID($spID,$serviceID){
		try{
			//$q="SELECT * FROM m_servicepermissionassign where ServicePermissionID='$spID' and Active='1'";
			$q="select s.Name,s.ServiceID,sp.ServicePermissionAssignID,sp.ServicePermissionID,sp.IsEnabled,sp.MinCharge,sp.Commission from m_service s left join m_servicepermissionassign as sp on sp.ServiceID=s.ServiceID and sp.ServicePermissionID='$spID' WHERE s.Active='1'  AND sp.ServiceID='$serviceID'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermissionassign');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	
	function copy($fromRpID,$toRpID){
		try{
			$q="SELECT * FROM m_servicepermissionassign where ServicePermissionID='$fromRpID' and Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_servicepermissionassign');
			//echo '<br />DB- Query Res= '.$res[0]->ServicePermissionID;
			if(count($res)>0)
			{
				$i=0;
				//echo '<br/> count($res)='.count($res);
				while($i<count($res))
				{
					$res[$i]->ServicePermissionID=$toRpID;
					//ServicePermissionID
					$this->add($res[$i]);
					$i++;
				}
				return true;
			}
			else return false;
			//return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','m_servicepermissionassign','Testing');
		}
	}
	function add($obj){
		try{
			//echo 'D servicepermissionassign name-'.$obj->Name;
			$q="INSERT INTO m_servicepermissionassign(ServiceID,ServicePermissionID,MinCharge,Commission,IsEnabled,CreatedDate,CreatedBy,ModifiedBy) 
			VALUE('$obj->ServiceID','$obj->ServicePermissionID','$obj->MinCharge','$obj->Commission','$obj->IsEnabled','$obj->CreatedDate','$obj->CreatedBy','$obj->ModifiedBy')";
			//echo '<br/> insert= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
	function update($obj){
		try{
			//echo 'D servicepermissionassign enabled-'.$obj->Name;
			$q="UPDATE m_servicepermissionassign SET IsEnabled='$obj->IsEnabled',MinCharge='$obj->MinCharge',Commission='$obj->Commission' WHERE ServicePermissionAssignID='$obj->ServicePermissionAssignID'";
			//echo '<br/> update= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermissionassign','Testing');
		}
	}
}
?>