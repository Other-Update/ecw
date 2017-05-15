<?php
include_once APPROOT_URL.'/Resource/Service.php';
include_once APPROOT_URL.'/Database/d_service.php';
include_once APPROOT_URL.'/General/general.php';
class b_service{
	private $filename='b_service';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_service($me,$mysqlObj);
	}
	function get_DT(){
		return $this->dbObj->get_DT();
	}
	
	//Manage Network
	function getAll(){
		try{
			$arr = $this->dbObj->getAll();
			//cho '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function upsert($serviceObj){
		try{	
			$resultObj = new httpresult();
			$checkServiceName = $this->dbObj->isExist($serviceObj->ServiceID,$serviceObj->Name, 'Name', 'NCode');
			$checkServiceRCode = $this->dbObj->isExist($serviceObj->ServiceID,$serviceObj->RechargeCode, 'RechargeCode', 'RCode');
			$checkServiceTCode = $this->dbObj->isExist($serviceObj->ServiceID,$serviceObj->TopupCode, 'TopupCode', 'TCode'); 
			if($checkServiceName != 0 OR $checkServiceRCode != 0 OR $checkServiceTCode != 0){
				$resultObj->isSuccess=false;
				$resultObj->data='{"Name":'.json_encode($checkServiceName).',"RCode":'.json_encode($checkServiceRCode).',"TCode":'.json_encode($checkServiceTCode).'}';
				return $resultObj;
			}  else {
			
			 if($serviceObj->ServiceID==0){
				//echo 'Add';
				$newServiceID = $this->dbObj->add($serviceObj);
				if($newServiceID>0){	
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['as_success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['as_failed'];
				}
				//echo '<br />BS- Result1 = '.json_encode($res);
				return $resultObj;
				}else{
					//echo 'Update';
					$res = $this->dbObj->update($serviceObj);
					$resultObj->isSuccess=$res;
					if($res){
						$resultObj->message=$this->lang['us_success'];
					}else{
						$resultObj->message=$this->lang['us_failed'];
					}
					return $resultObj;
				} 
			}
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function updateServiceProblem($serviceID,$isPorblem){
		try{
			$arr = $this->dbObj->updateServiceProblem($serviceID,$isPorblem);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function delete($serviceID){
		try{
			$res = $this->dbObj->delete($serviceID);
			//echo '<br />BS- Result = '.json_encode($arr);
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Get service list for dashboard page 
	function getServiceList(){
		try{
			$arr = $this->dbObj->getServiceList();
			return $arr;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	function getApplicableCode($serviceObj){
		if($serviceObj->DefaultType==1)//1- is recharge . 2 is topup
			return $serviceObj->RechargeCode;
		else
			return $serviceObj->TopupCode;
	}
	//New parameter(Type = 1-prepaid. 2-postaid) has been added
	function getByCode($code,$mode){
		$arr = $this->dbObj->getByCode($code,$mode);
		if(count($arr)>0)
			return $arr[0];
		else
			return null;
	}
	function getByNetworkProvider($networkproviderID,$networkMode){
		$arr = $this->dbObj->getByNetworkProvider($networkproviderID,$networkMode);
		if(count($arr)>0)
			return $arr[0];
		else
			return null;
	}
	//search operator in dashboard page
	function getServiceOperator($mobileNo,$type){
		return $this->dbObj->getServiceOperator($mobileNo,$type);
	}
	
}
?>