<?php
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/Database/d_gateway.php';
include_once APPROOT_URL.'/General/general.php';
class b_gateway{
	private $filename='b_gateway';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_gateway($me,$mysqlObj);
	}
	function get_DT(){
		return $this->dbObj->get_DT();
	}
	
	function upsert($gatewayObj){
		try{	
			$resultObj = new httpresult();
			 if($gatewayObj->RCGatewayID==0){
				//echo 'Add';
				$newGatewayID = $this->dbObj->add($gatewayObj);
				if($newGatewayID>0){	
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['as_success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['as_failed'];
				}
				//echo '<br />BS- Result1 = '.json_encode($res);
				return $resultObj;
				} 
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function GetGatewayListIds(){
		return $this->dbObj->GetGatewayListIds();
	}
	

}

?>