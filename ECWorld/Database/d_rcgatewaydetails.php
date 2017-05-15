<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rcgatewaydetails.php';
include_once APPROOT_URL.'/Entity/e_service.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_rcgatewaydetails{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}	
	function get_DT($gatewayID){
		$table = 'm_rcgatewaydetails';
		$index_column = 'RCGatewayDetailsID';
		$columns = array('RCGatewayDetailsID','RcGatewayID','ServiceID','Name','RechargeCode','TopupCode');
		//$query="SELECT SQL_CALC_FOUND_ROWS r.RCGatewayDetailsID,r.RcGatewayID,r.ServiceID,s.Name,r.Code FROM m_rcgatewaydetails as r left join m_service as s on r.ServiceID=s.ServiceID  WHERE r.RcGatewayID='$gatewayID' AND s.Active=1 ORDER BY r.RCGatewayDetailsID ASC";
		$query="SELECT SQL_CALC_FOUND_ROWS r.RCGatewayDetailsID,r.RcGatewayID,s.ServiceID,s.Name,r.RechargeCode,r.TopupCode FROM m_service as s left join m_rcgatewaydetails as r on r.ServiceID=s.ServiceID AND r.RcGatewayID='$gatewayID' WHERE s.Active=1 ORDER BY r.RCGatewayDetailsID ASC";
		return $this->dtObj->get($table, $index_column, $columns,$query);
		//return $this->dtObj->get('m_service', 'ServiceID', array('ServiceID','Name', 'RechargeCode', 'TopupCode', 'DefaultType')," Active='1'");
	}
	
	function assignService($rcgObj){
		try{
			$q='SELECT ServiceID FROM m_service WHERE Active=1';
			$res=$this->db->selectArray($q,'e_service');
			$i=0;
			while($i < count($res))
			{
				$rcgObj->ServiceID = $res[$i]->ServiceID;
				$rcgObj->RechargeCode = '';
				$this->add($rcgObj);
				$i++;
			}
				

		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function add($obj){
		try{
			$obj->CreatedDate = date('Y-m-d h:i:s');
			$q="INSERT INTO m_rcgatewaydetails(RcGatewayID,ServiceID,RechargeCode,TopupCode,CreatedDate,CreatedBy,ModifiedBy) VALUE('$obj->RcGatewayID','$obj->ServiceID','$obj->RechargeCode','$obj->TopupCode','$obj->CreatedDate','$obj->CreatedBy','$obj->ModifiedBy')";
			
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
	function update($rcgatewayObj){
		try{
			//echo '<br /> db-Update service';				
			$rcgatewayObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_rcgatewaydetails SET RechargeCode='$rcgatewayObj->RechargeCode', TopupCode='$rcgatewayObj->TopupCode', ModifiedBy='$rcgatewayObj->ModifiedBy' WHERE RCGatewayDetailsID='$rcgatewayObj->RCGatewayDetailsID'";
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
}
?>