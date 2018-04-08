<?php
include_once APPROOT_URL.'/Resource/Payment.php';
include_once APPROOT_URL.'/Database/d_payment.php';
include_once APPROOT_URL.'/General/general.php';
class b_payment{
	private $filename='b_payment';
	private $dbObj;
	private $me;
	private $lang;
	private $mysqlObj;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->mysqlObj=$mysqlObj;
		$this->lang=$lang;
		$this->dbObj=new d_payment($me,$mysqlObj);
	}
	function getBalanceToBePaid($userID,$parentID){
		$res = $this->dbObj->getBalanceToBePaid($userID,$parentID);
		if(count($res)>0)
			return $res[0]->BalanceToBePaid;
		else
			return 0;
	}
	
	function getCollectionByParent_DT($parentID){
		return $this->dbObj->getCollectionByParent_DT($parentID);
	}
	function getBalanceToBePaidByParent_DT($parentID){
		return $this->dbObj->getBalanceToBePaidByParent_DT($parentID);
	}
	function getTransfers_DT($parentID,$fromDate,$toDate){
		return $this->dbObj->getTransfers_DT($parentID,$fromDate,$toDate);
	}
	function getTransfersByDateRange_DT($parentID,$startDate,$endDate,$onlyMyPayments=false,$isDataTable=false){
		return $this->dbObj->getTransfersByDateRange_DT($parentID,$startDate,$endDate,$onlyMyPayments,$isDataTable=false);
	}
	function getTransferByDate($userID,$date){
		return $this->dbObj->getTransferByDate($userID,$date);
	}
	
	function getBillingByDate($userID,$date){
		return $this->dbObj->getBillingByDate($userID,$date);
	}
	
	
	function getTransferSalesByDate_NIU($userID,$date){
		return $this->dbObj->getTransferSalesByDate($userID,$date);
	}
	
	function addTransferWebservice($loggedInUser,$fromUser,$toUser,$amount,$remark,$requestObj){
		$this->me=$loggedInUser;
		$payObj = new b_payment($this->me,$this->mysqlObj,$this->lang);
		//echo "<br/> b_payment. me-=".json_encode($this->me);
		$payObj->FromUserID=$fromUser->UserID;
		$payObj->ToUserID=$toUser->UserID;
		$payObj->RequestID = $requestObj->RequestID;
		$payObj->Amount=$amount;
		$payObj->CommissionPercent="0";
		$payObj->Type="1";//1-Credit, 2-Debit
		$payObj->Mode="1";//1-Normal
		$payObj->Remark=$remark;
		$payObj->PaidAmount="0";
		$payObj->TotalAmount=$amount;
		$payObj->CommissionAmountPrevPur="0";
		$this->dbObj=new d_payment($this->me,$this->mysqlObj);
		return $this->addTransfer($payObj,$requestObj);
	}
	function addTransfer($obj,$bReqObj){
		$obj->RequestID = $bReqObj->RequestID;
		try{	
			$resultObj = new httpresult();
			//echo 'Add';
			$insertedID = $this->dbObj->addTransfer($obj);
			if($insertedID>0){	
				$resultObj->isSuccess=true;
				$resultObj->message=$this->lang?$this->lang['success']:"Success";
				$resultObj->otherInfo = $insertedID;
			}else{
				$resultObj->isSuccess=false;
				$resultObj->message=$this->lang?$this->lang['failed']:"Failed";
				$resultObj->otherInfo = 0;
			}
			//echo '<br />BS- Result1 = '.json_encode($res);
			return $resultObj;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function DeleteOldPayments($userID,$date){
		return $this->dbObj->DeleteOldPayments($userID,$date);
	}
}
?>