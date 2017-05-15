<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rcusergateway.php';
/* include_once APPROOT_URL.'/Entity/e_service.php'; */
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
include_once APPROOT_URL.'/Database/d_users.php';
class d_rcusergateway{
	private $db;
	var $me;
	var $userObj ;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
		$this->userObj = new d_users($mysqlObj);
	}
	
	function get_DT($userID){
		$table = 'm_rcusergateway';
		$index_column = 'RCUserGatewayID';
		$columns = array('RCUserGatewayID','UserID','ServiceID','Network','PrimaryGateway','SecondaryGateway','Amount','PrimaryGatewayId', 'SecondaryGatewayId');
		//$query="SELECT SQL_CALC_FOUND_ROWS ug.RCUserGatewayID,ug.UserID,s.ServiceID,s.Name as Network,g.Name as PrimaryGateway,g2.name as SecondaryGateway,ug.Amount FROM m_service as s left join m_rcusergateway as ug on ug.ServiceID=s.ServiceID left join m_rcgateway as g on g.RCGatewayID=ug.PrimaryGateway left join m_rcgateway as g2 on g2.RCGatewayID=ug.SecondaryGateway WHERE (ug.UserID='$userID' OR ug.UserID IS NULL) AND s.Active=1 ORDER BY ug.RCUserGatewayID ASC";
		$query="SELECT SQL_CALC_FOUND_ROWS ug.RCUserGatewayID,ug.UserID,s.ServiceID,s.Name as Network,g.Name as PrimaryGateway,g2.name as SecondaryGateway,ug.Amount,ug.`PrimaryGateway` AS PrimaryGatewayId,ug.`SecondaryGateway` as SecondaryGatewayId FROM m_service as s left join m_rcusergateway as ug on ug.ServiceID=s.ServiceID AND ug.UserID='$userID' AND ug.Active=1 left join m_rcgateway as g on g.RCGatewayID=ug.PrimaryGateway left join m_rcgateway as g2 on g2.RCGatewayID=ug.SecondaryGateway WHERE s.Active=1 ORDER BY s.ServiceID ASC";
		//echo $query;
		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	function getByUserAndService($userID,$ancestorID,$serviceID){
		try{
			$q="SELECT * FROM m_rcusergateway where ServiceID='$serviceID' AND Active='1' AND UserID=";
			$qGateway=$q.$userID;
			//echo '<br/> Query= '.$qGateway;
			$res=$this->db->selectArray($qGateway,'e_rcusergateway');
			$rcusergatewayRes = 0;
			if(count($res)==0){
				//$ancestorID = $this->userObj->getAncestorIDByUserID($userID);
				$qGateway=$q.$ancestorID;
				//echo '<br/> Query= '.$qGateway;
				$res=$this->db->selectArray($qGateway,'e_rcusergateway');
			}
			//echo '<br/> getByUserAndService=Result= '.json_encode($res);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function add($ugObj,$isCopy){
		try{
			//echo '<br /> rcusergatewau user-'.json_encode($this->me);										
			$ugObj->CreatedDate = date('Y-m-d h:i:s');
			//echo "<br/> d_rcusergateway me =".json_encode($this->me);
			//echo "userid=".$this->me->UserID;
			//echo "ugObj->CreatedBy".$ugObj->CreatedBy;
			$ugObj->CreatedBy = $this->me->UserID;
			$ugObj->ModifiedBy = $this->me->UserID;
			$q="INSERT INTO m_rcusergateway(UserID,ServiceID,Amount,CreatedDate,CreatedBy,ModifiedBy) VALUES('$ugObj->UserID','$ugObj->ServiceID','$ugObj->Amount','$ugObj->CreatedDate','$ugObj->CreatedBy','$ugObj->ModifiedBy') ";
			if($isCopy)
				$q="INSERT INTO m_rcusergateway(UserID,ServiceID,PrimaryGateway,SecondaryGateway,Amount,CreatedDate,CreatedBy,ModifiedBy) VALUES('$ugObj->UserID','$ugObj->ServiceID','$ugObj->PrimaryGateway','$ugObj->SecondaryGateway','$ugObj->Amount','$ugObj->CreatedDate','$ugObj->CreatedBy','$ugObj->ModifiedBy') ";
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
	function updateAmount($ugObj){
		try{
			//echo '<br /> db-Update service';				
			//$rcgatewayObj->ModifiedBy = $this->me->UserID;
			$userID = $this->me->UserID;
			$Amount = str_replace(",", "#",$ugObj->Amount);
			$q="UPDATE m_rcusergateway SET Amount='$Amount', ModifiedBy='$userID' WHERE RCUserGatewayID='$ugObj->RCUserGatewayID'";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			/* $errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing'); */
		}
	}
	
	function delete($id){
		try{
			//echo '<br /> db-Update service';				
			$userID = $this->me->UserID;
			$q="UPDATE m_rcusergateway SET Active='0', ModifiedBy='$userID' WHERE RCUserGatewayID='$id'";
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
	
	function copy($fromUserID,$toUserID){
		try{
			$q="SELECT * FROM m_rcusergateway where UserID='$fromUserID' and Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_rcusergateway');
			//echo '<br />DB- Query Res sp id= '.$res[0]->ServicePermissionID;
			//echo '<br />DB- Query Res isOTFCommission= '.$res[0]->isOTFCommission;
			if(count($res)>0)
			{
				$i=0;
				//echo '<br/> count($res)='.json_encode($res);
				while($i<count($res))
				{
					$res[$i]->UserID=$toUserID;
					//ServicePermissionID
					$this->add($res[$i],true);
					$i++;
				}
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
	
	
	//Reghu -- 20-12-2016 for update multiple gateway
	function updateGeneralApi($ugObj){
		try{
			$userID = $this->me->UserID;
			$ugObj->CreatedDate = date('Y-m-d h:i:s');
			$ServiceID = explode(",",$ugObj->ServiceID); 
			$serIdCount = count($ServiceID);
			$RCUserGatewayID = explode(",",$ugObj->RCUserGatewayID); 
			for($i=0; $i<$serIdCount; $i++){
				if($RCUserGatewayID[$i] == ''){
					$q1="INSERT INTO `m_rcusergateway`(`UserID`, `ServiceID`, `PrimaryGateway`, `SecondaryGateway`, `CreatedDate`, `CreatedBy`, `ModifiedBy`) VALUES ('$ugObj->UserID','$ServiceID[$i]', '$ugObj->PrimaryGateway', '$ugObj->SecondaryGateway', '$ugObj->CreatedDate', '$userID', '$userID')";
					$res=$this->db->execute($q1);
				} else {
					$q="UPDATE m_rcusergateway SET PrimaryGateway='$ugObj->PrimaryGateway', SecondaryGateway='$ugObj->SecondaryGateway', ModifiedBy='$userID' WHERE RCUserGatewayID='$RCUserGatewayID[$i]' ";
					$res=$this->db->execute($q);
				} 
			}
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
}
?>