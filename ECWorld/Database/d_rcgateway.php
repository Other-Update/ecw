<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rcgateway.php';
include_once APPROOT_URL.'/Database/d_datatable.php';
class d_rcgateway{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new DataTable();
	}
	function get_DT(){
		$q='SELECT * FROM m_rcgateway WHERE Active=1';

		//if(count($roles)>0) $q.=" AND Active='1'";
		//echo "Gateway:".$q;
		$arr=$this->db->selectArray($q,'e_rcgateway');
		return $arr;
	}
	function getByIdAndService($gatewayID,$serviceID){
		$q="SELECT rcg.RCGatewayID,rcg.Name,rcg.ServerName,rcg.URL,rcgd.RCGatewayDetailsID,rcgd.ServiceID,rcgd.RechargeCode,rcgd.TopupCode FROM m_rcgateway AS rcg LEFT JOIN m_rcgatewaydetails AS rcgd ON rcg.RCGatewayID=rcgd.RCGatewayID WHERE rcg.RCGatewayID='$gatewayID' AND rcgd.ServiceID='$serviceID' AND rcg.Active=1";

		//echo "Gateway:".$q;
		$arr=$this->db->selectArray($q,'e_rcgateway');
		return $arr;
	}
	function add($gatewayObj){
		try{
			//echo '<br /> Add Gateway';								
			$gatewayObj->CreatedDate = date('Y-m-d h:i:s');
			$gatewayObj->CreatedBy = $this->me->UserID;
			$gatewayObj->ModifiedBy = $this->me->UserID;
			$q="INSERT INTO m_rcgateway(Name,URL,CreatedDate,CreatedBy,ModifiedBy) 
			VALUE('$gatewayObj->Name','$gatewayObj->URL','$gatewayObj->CreatedDate','$gatewayObj->CreatedBy','$gatewayObj->ModifiedBy')";
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
	
	function update($gatewayObj){
		try{
			//echo '<br /> Add Gateway';				
			$gatewayObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_rcgateway SET Name='$gatewayObj->Name',URL='$gatewayObj->URL',ModifiedBy='$gatewayObj->ModifiedBy' WHERE RCGatewayID='$gatewayObj->RCGatewayID'";
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