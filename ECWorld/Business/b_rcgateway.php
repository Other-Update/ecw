<?php
include_once APPROOT_URL.'/Resource/RCGateway.php';
include_once APPROOT_URL.'/Database/d_rcgateway.php';
include_once APPROOT_URL.'/Database/d_rcgatewaydetails.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_http.php';
class b_rcgateway{
	private $filename='b_rcgateway';
	private $dbObj;
	private $me;
	private $lang;
	private $RCD;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_rcgateway($me,$mysqlObj);
		$this->RCD=new d_rcgatewaydetails($me,$mysqlObj);
	}
	function get_DT(){
		return $this->dbObj->get_DT();
	}
	function getHttpObjByRcGateway($rcgObj){
		//echo "<br/> rcgObj=".json_encode($rcgObj);die;
		switch($rcgObj->ServerName){
			case "CLOUD"://Cloud API
				return new b_cloudapi();
				break;
			case "MARS"://Mars API
				return new b_marsapi();
				break;
			case "MANUAL"://Manual API
				return new b_manualapi();
				break;
			default: //By default assign manual API
				return new b_manualapi();
				break;
		}
	}
	function isManualApi($rcgObj){
		//echo $rcgObj->Name;
		$position = stripos($rcgObj->Name, "manual");
		//echo "<br/> position=".$position;
		//Note: stripost may return 0 also in case of first match.
		//!== will take care of 0
		if ($position !== false) return true;
		else return false;
	}
	function getByIdAndService($gatewayID,$serviceID){
		return $this->dbObj->getByIdAndService($gatewayID,$serviceID);
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
					$gatewayObj->RcGatewayID=$newGatewayID;
					$res = $this->RCD->assignService($gatewayObj);
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['as_failed'];
				}
				//echo '<br />BS- Result1 = '.json_encode($res);
			}
			else{
				$res = $this->dbObj->update($gatewayObj);
				if($res>0){
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['us_success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['us_failed'];
				}
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