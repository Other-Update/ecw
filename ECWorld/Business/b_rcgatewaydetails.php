<?php
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/Database/d_rcgatewaydetails.php';
include_once APPROOT_URL.'/General/general.php';
class b_rcgatewaydetails{
	private $filename='b_rcgatewaydetails';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_rcgatewaydetails($me,$mysqlObj);
	}
	function get_DT($gatewayID){
		return $this->dbObj->get_DT($gatewayID);
	}

	function GetGatewayListIds(){
		return $this->dbObj->GetGatewayListIds();
	}
	
	function upsert($rcgatewayObj){
		try{	
			$resultObj = new httpresult();
			$rcgatewayObj->ModifiedBy = $this->me->UserID;
			$RcGatewayID=0;
			if($rcgatewayObj->RCGatewayDetailsID>0){
				$RcGatewayID = $this->dbObj->update($rcgatewayObj);
			}else{
				$rcgatewayObj->CreatedBy = $this->me->UserID;
				$RcGatewayID = $this->dbObj->add($rcgatewayObj);
			}
			$resultObj->isSuccess=$RcGatewayID;
			if($RcGatewayID){
				$resultObj->message=$this->lang['us_success'];
			}else{
				$resultObj->message=$this->lang['us_failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
}

?>