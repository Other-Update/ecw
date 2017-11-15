<?php
include_once APPROOT_URL.'/Database/d_transaction.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_request.php';
class b_transaction{
	private $me;
	private $lang;
	var $dObj;
	var $mysql;
	function __construct($thisUser,$mysqlObj,$lang){
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->mysql=$mysqlObj;
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
	function DeleteOldTransactions($userID,$date){
		$latestTrans = $this->dObj->getLatestTransaction($userID,$date,"CreatedDate,ClosingBalance");
		//echo json_encode($latestTrans);
		//echo "\n...";
		$isBalanceBkpInProgress=false;
		$isBalanceBkpCompleted=false;
		if(count($latestTrans)<1) {
			//echo "\n No trans";
			$latestTrans = $this->dObj->getLatestTransaction($userID,"","CreatedDate,ClosingBalance");
			//echo ".\ncount=".count($latestTrans);
			if(count($latestTrans)>=1) {
				$isBalanceBkpInProgress=true;
				$bReqObj=new b_request($this->me,$this->mysql,$this->lang);
				$today =date('Y-m-d H:i:s');

				$bReqObj = $bReqObj->giveMeObj($userID,"00000",$_SERVER["REMOTE_ADDR"],"System","000000","0","System","Backup Balance","3",$today,$today,"Backup balance","WEB");
				$bReqObj->DisplayID = $bReqObj->getDisplayID($bReqObj,"W");
				$reqID = $bReqObj->add($bReqObj);
				//addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs)
				$bReqObj->RequestID = $reqID;
				$bReqObj->DisplayID = $bReqObj->DisplayID.$bReqObj->RequestID;
				//echo "\nReq Obj=".json_encode($bReqObj);
				$this->dObj->add(5,$bReqObj->RequestID,$bReqObj->UserID,"0","System","t_request",$bReqObj->RequestID,$bReqObj->UserID);
				$isBalanceBkpCompleted=true;
			}
			//else echo "\n Again No trans";
		}
		//else 
			//echo "\n TrasCount=".count($latestTrans);
		//echo "\n...end";
		echo ".isBalanceBkpInProgress=".$isBalanceBkpInProgress.",".".isBalanceBkpCompleted=".$isBalanceBkpCompleted;
		if(($isBalanceBkpInProgress==false && $isBalanceBkpCompleted==false) || ($isBalanceBkpInProgress==true && $isBalanceBkpCompleted==true))
			$this->dObj->DeleteOldTransaction($userID,$date);
	}
}
?>