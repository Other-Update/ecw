<?php
include_once APPROOT_URL.'/Database/d_transaction.php';
include_once APPROOT_URL.'/General/general.php';
class b_transaction{
	private $me;
	private $lang;
	var $dObj;
	function __construct($thisUser,$mysqlObj,$lang){
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->dObj=new d_transaction($mysqlObj);
	}
	function getTransactionReport_DT($userId, $mobile, $network, $requestId, $fromDate, $toDate,$isWebservice=false){
		return $this->dObj->getTransactionReport_DT($userId, $mobile, $network, $requestId, $fromDate, $toDate,$isWebservice);
	}
	
	
	//Get Opening & Closing balance
	function getTransactionOpenCloseBalance($userId, $fromDate, $toDate){
		$getFromdateBalnce = $this->dObj->getOpenCloseBalanceByDateUserId($userId, $fromDate.' 00:00:00',1);
		$getTodateBalnce = $this->dObj->getOpenCloseBalanceByDateUserId($userId, $toDate.' 23:59:59',0);
		if(count($getFromdateBalnce)>0) { $from = $getFromdateBalnce[0]; } else {$from = '0.00'; }
		if(count($getTodateBalnce)>0) { $to = $getTodateBalnce[0]; } else {$to = '0.00'; }
		$result ='{"OpeningBalance":'.json_encode($from).',"ClosingBalance":'.json_encode($to).'}';
		return json_encode($result);
	}
	
	function getOpeningBlanceByUserID($userID){
		$res = $this->dObj->getOpeningBlanceByUserID($userID);
		if(count($res)>0)
			return $res[0]->OpeningBalance;
		else 
			return 0;
	}
	
	function getTransferSalesByDate($userID,$date){
		return $this->dObj->getTransferSalesByDate($userID,$date);
	}
}
?>