<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rcgateway.php';
include_once APPROOT_URL.'/Database/d_datatable.php';
class d_gateway{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new DataTable();
	}
	function get_DT(){
		return $this->dtObj->get('m_rcgateway', 'RCGatewayID', array('RCGatewayID','Name', 'URL')," Active='1'");
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
	
	function GetGatewayListIds(){
		$q='SELECT * FROM m_rcgateway WHERE Active=1';

		//if(count($roles)>0) $q.=" AND Active='1'";
		//echo "Gateway:".$q;
		$arr=$this->db->selectArray($q,'e_rcgateway');
		return $arr;
	}
	

	

}
?>