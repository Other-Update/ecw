<?php
include_once APPROOT_URL.'/Database/d_rcamountsetting.php';
include_once APPROOT_URL.'/General/general.php';
class b_rcamountsetting{
	private $filename='b_rcamountsetting';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_rcamountsetting($me,$mysqlObj);
	}
	
	function get_DT(){
		return $this->dbObj->get_DT();
	}
	
	function getByServiceID($serviceID){
		return $this->dbObj->getByServiceID($serviceID);
	}
	
	function upsert($amtObj){
		try{	
			$resultObj = new httpresult();
			 if($amtObj->RcAmountID==''){  //Add Function
				$newRcAmountID = $this->dbObj->add($amtObj);
				if($newRcAmountID>0){	
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['failed'];
				}
				return $resultObj;
			}  else {
				$newRcAmountID = $this->dbObj->update($amtObj);
				if($newRcAmountID){	
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['failed'];
				}
				return $resultObj;
			}
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
}
?>