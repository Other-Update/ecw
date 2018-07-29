<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_service.php';
//include_once APPROOT_URL.'/Database/d_datatable.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_service{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function get_DT(){
		//echo json_encode($this->db->executeSP("CALL UpdateWallet(1,1)"));
		$table = 'm_service';
		$index_column = 'ServiceID';
		$columns = array('ServiceID','Name', 'RechargeCode', 'TopupCode', 'DefaultType','DefaultTypeName','NetworkProviderID', 'NetworkMode' );
		$query='SELECT SQL_CALC_FOUND_ROWS s.ServiceID,s.Name, s.RechargeCode,s.TopupCode,s.DefaultType,r.Name as DefaultTypeName, s.NetworkProviderID, s.NetworkMode
		FROM m_service as s left join m_rechargetype as r on s.DefaultType=r.RechargeTypeID WHERE s.Active=1 ORDER BY s.ServiceID ASC';
		return $this->dtObj->get($table, $index_column, $columns,$query);

	}
    
	//Manage Network
	function getAll(){
		try{
			$q="SELECT * FROM m_service WHERE Active='1' "; 
			/* $q="SELECT s.ServiceID, s.Name, s.RechargeCode, s.TopupCode, t.TypeName FROM m_service AS s 
			LEFT JOIN m_rechargetype AS t ON 
				s.DefaultType = t.TypeID
			where s.Active='1' "; */
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_service');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function add($serviceObj){
		try{
			//echo '<br /> Add service';								
			$serviceObj->CreatedDate = date('Y-m-d h:i:s');
			$serviceObj->CreatedBy = $this->me->UserID;
			$serviceObj->ModifiedBy = $this->me->UserID;
			$q="INSERT INTO m_service(Name, RechargeCode, TopupCode, DefaultType, NetworkProviderID, NetworkMode, CreatedDate, CreatedBy, ModifiedBy) 
			VALUE('$serviceObj->Name','$serviceObj->RechargeCode','$serviceObj->TopupCode','$serviceObj->DefaultType', '$serviceObj->NetworkProviderID', '$serviceObj->NetworkMode', '$serviceObj->CreatedDate','$serviceObj->CreatedBy','$serviceObj->ModifiedBy')";
			//echo '<br/> Query= '.$q;
			$res=$this->db->insert($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function update($serviceObj){
		try{
			//echo '<br /> db-Update service';				
			$serviceObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_service SET Name='$serviceObj->Name',RechargeCode='$serviceObj->RechargeCode',TopupCode='$serviceObj->TopupCode',NetworkProviderID='$serviceObj->NetworkProviderID', NetworkMode='$serviceObj->NetworkMode', DefaultType='$serviceObj->DefaultType', ModifiedBy='$serviceObj->ModifiedBy' WHERE ServiceID='$serviceObj->ServiceID'";
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
	
	function updateServiceProblem($serviceID,$isProblem){
		try{
			//$serviceObj->ModifiedBy = $this->me->UserID;
			$userID=$this->me->UserID;
			$q="UPDATE m_service SET IsProblem='$isProblem', ModifiedBy='$userID' WHERE ServiceID='$serviceID'";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	/* function isExist($serviceID,$value, $field, $code, $mode){
		try{
			if($code == 'NCode'){
				$q ="SELECT Count(*) As FieldCount FROM m_service WHERE $field='$value' AND ServiceID!='$serviceID' AND Active=1";
			}else if($value == ''){
				return 0;
			}
			else if($code == 'RCode'){
				 $q ="SELECT Count(*) As FieldCount FROM m_service WHERE ($field='$value' OR  TopupCode='$value') AND ServiceID!='$serviceID' AND NetworkMode='$mode' AND Active=1";
			} else {
				  $q ="SELECT Count(*) As FieldCount FROM m_service WHERE ($field='$value' OR RechargeCode='$value') AND ServiceID!='$serviceID' AND NetworkMode='$mode' AND Active=1";
			}
			//echo $q;
			$res=$this->db->selectArray($q,'e_service');
			//echo '<br/> Result= '.$res[0]->NameCount;
			return $res[0]->FieldCount;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		} 
	}
	*/
	
	
	function isExist($serviceID, $value, $field, $code){
		try{
			if($code == 'NCode'){
				$q ="SELECT Count(*) As FieldCount FROM m_service WHERE $field='$value' AND ServiceID!='$serviceID' AND Active=1";
			}else if($value == ''){
				return 0;
			}
			else if($code == 'RCode'){
				 $q ="SELECT Count(*) As FieldCount FROM m_service WHERE ($field='$value' OR  TopupCode='$value') AND ServiceID!='$serviceID'  AND Active=1";
			} else {
				  $q ="SELECT Count(*) As FieldCount FROM m_service WHERE ($field='$value' OR RechargeCode='$value') AND ServiceID!='$serviceID' AND Active=1";
			}
			//echo $q;
			$res=$this->db->selectArray($q,'e_service');
			//echo '<br/> Result= '.$res[0]->NameCount;
			return $res[0]->FieldCount;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		} 
	}
	
	function delete($serviceID){
		try{
			//$serviceObj->ModifiedBy = $this->me->UserID;
			$userID=$this->me->UserID;
			$q="UPDATE m_service SET Active='0', ModifiedBy='$userID' WHERE ServiceID='$serviceID'";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function getServiceList(){
		try{
			$q="SELECT ServiceID,Name,RechargeCode,NetworkProviderID,NetworkMode,TopupCode FROM m_service where Active='1'";
			$res=$this->db->selectArray($q,'e_service');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	//New parameter(mode = 1-prepaid. 2-postaid) has been added
	function getByCode($code,$mode){
		try{
			$q="SELECT * FROM m_service where (RechargeCode='$code' OR TopupCode='$code') AND Active='1'";
			if($mode)
			$q="SELECT * FROM m_service where (RechargeCode='$code' OR TopupCode='$code') AND NetworkMode='$mode' AND Active='1'";
			//echo $q;
			$res=$this->db->selectArray($q,'e_service');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	function getByNetworkProvider($networkproviderID,$networkMode){
		try{
			$q="SELECT * FROM m_service where NetworkProviderID='$networkproviderID' AND NetworkMode='$networkMode' AND Active='1'";
			$res=$this->db->selectArray($q,'e_service');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	//search operator in dashboard page
	function getServiceOperator($mobileno,$type){
		$q="SELECT s.ServiceID, s.Name, s.RechargeCode,s.TopupCode FROM m_service AS s 
			LEFT JOIN m_auto_mnp AS am ON
				s.NetworkProviderID = am.NetworkName 
			LEFT JOIN m_networkprovider AS net ON
				am.NetworkName = net.NetworkProviderID
			WHERE am.MobileNo LIKE '".$mobileno."%' AND s.NetworkMode='$type' AND am.Active=1 "; 
		//echo $q;
		$arr=$this->db->selectArray($q,'e_service',1);
		return $arr;
	}
	
}
?>