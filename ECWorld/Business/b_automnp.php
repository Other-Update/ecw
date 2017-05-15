<?php
//include_once APPROOT_URL.'/Resource/AutoMnpRecharge.php';
include_once APPROOT_URL.'/Database/d_automnp.php';
include_once APPROOT_URL.'/General/general.php';
class b_automnp{
	private $filename='b_automnp';
	private $dbObj;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dbObj=new d_automnp($me,$mysqlObj);
	}
	
	function get_DT($rcType){
		return $this->dbObj->get_DT($rcType);
	}
	function getByID($id){
		$res = $this->dbObj->getByID($id);
		if(count($res)>0) return $res[0];
		else return null;
	}
	
	function getNetwork($mobileNo,$type){
		//echo "Reghu1:".$mobileNo;
		return $this->dbObj->getNetwork($mobileNo,$type);
	}
	
	function getNetworkList(){
		return $this->dbObj->getNetworkList();
	}
	
	function upsert($autoObj){
		try{	
			$resultObj = new httpresult();
			 if($autoObj->NeworkID==0){  //Add Function
				$newNeworkID = $this->dbObj->add($autoObj);
				if($newNeworkID>0){	
					$resultObj->isSuccess=true;
					$resultObj->message=$this->lang['success'];
				}else{
					$resultObj->isSuccess=false;
					$resultObj->message=$this->lang['failed'];
				}
				return $resultObj;
			}  else {
				$newNeworkID = $this->dbObj->update($autoObj);
				if($newNeworkID){	
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