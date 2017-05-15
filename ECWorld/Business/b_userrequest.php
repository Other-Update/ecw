<?php
include_once APPROOT_URL.'/Database/d_userrequest.php';
include_once APPROOT_URL.'/General/general.php';
class b_userrequest{
	private $filename='b_userrequest';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_userrequest($me,$mysqlObj);
	}
	
	//Onchange event function for popup form search request id
	function getUserRequest($SearchRequest){
		return $this->dbObj->getUserRequest($SearchRequest);
	}
	
	function get_DT($userId, $fromTable, $mobile, $requestId, $status, $fromDate, $toDate){
		return $this->dbObj->get_DT($userId, $fromTable, $mobile, $requestId, $status, $fromDate, $toDate);
	}
	
	function upsert($compObj){
		try{	
			$resultObj = new httpresult();
			 if($compObj->ComplaintID==0){  //Add Function
				$complaintID = $this->dbObj->add($compObj);
				if($complaintID>0){	
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['failed'];
				}
				return $resultObj;
			}  else {
				$complaintID = $this->dbObj->update($compObj);
				if($complaintID){	
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