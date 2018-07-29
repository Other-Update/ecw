<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_transaction.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_transaction{
	private $db;
	private $dtObj;
	var $fileName="d_transaction.php";
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	#REGION Add transaction <<<<<<<<<<<<
	function add($type,$requestID,$userID,$amount,$remark,$referenceTable,$referenceID,$createdBy){
	
		$resLock = $this->db->LockExec("LOCK TABLES t_transaction WRITE");

		//$q = "call AddTransaction('$type','$userID','$type','$amount','$requestID','0','$remark','$referenceTable','$referenceID','$createdBy')";	
		$walletBal = $this->getWalletBlanceByUserID($userID);
		//echo "<br/> Wallet balance = ".$walletBal;
		$q = "INSERT INTO t_transaction(TransactionType,RequestID,UserID,Amount,OpeningBalance,ClosingBalance,Remark,ReferenceTable,ReferenceID,CreatedBy) VALUES('$type','$requestID','$userID','$amount','$walletBal','".($walletBal+$amount)."','$remark','$referenceTable','$referenceID','$createdBy');";
		
		$qForLog = str_replace(' ', '_', $q);	
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("doRecharge",$qForLog,"Insert into t_transaction","t_transaction",$requestID,"Before calling Insert");
		//echo "<br/>add t_transaction query=".$q;
		$id = $this->db->insert($q);
		$resUnlock = $this->db->LockExec("UNLOCK TABLES");
		
		//echo "<br/>t_transaction insert-q Res =".json_encode($id);
		return $id;
	}
	function addForRecharge($reqObj,$rechargeID){
		//transaction type 2 is recharge
		//echo "<br/> reqObj=".json_encode($reqObj);
		//echo "<br/> rechargeID=".$rechargeID;
		$transtype = 2;
		return $this->add($transtype,$reqObj->RequestID,$reqObj->UserID,$reqObj->TotalAmount*-1,"Recharge","t_recharge",$rechargeID,$reqObj->UserID);
	}
	#REGION Add transaction >>>>>>>>>>>>
	//TransactionType
	//1-Payment,2-recharge,3-SMS,4-Add Use, 5-System
	function getTransactionReport_DT($userId, $mobile, $network, $requestId, $fromDate, $toDate,$isWebservice){
		$toDate=$fromDate;//This is for testing. remove it after sometime
		//echo json_encode($this->db->executeSP("CALL UpdateWallet(1,1)"));
		$table = 't_transaction';
		$index_column = 'TransactionID';
		$columns = array('TransactionID','UserID','UserName','Request_ID','ReqDateTime','ResponseTime','Trans_ID','MobileNo','TargetAmount','Amount','Network', 'Description', 'Status', 'Balance', 'Type', 'OpeningBalance');
	//rch.`RcResReceivedDateTime`
			$select = "SELECT DISTINCT trans.`TransactionID`, user.`DisplayID` AS UserID, user.Name as UserName,req.`DisplayID` AS 'Request_ID', req.`ReqDateTime`, DATE_FORMAT(req.`ModifiedDate`,'%H:%i:%s') AS ResponseTime, rch.`RcResOpTransID` AS 'Trans_ID', req.`TargetNo` AS 'MobileNo', req.`TargetAmount`, trans.`Amount`, rch.`NetworkProviderName` AS 'Network', trans.`Remark` AS 'Description', req.`Status`, trans.`ClosingBalance` AS 'Balance', trans.`TransactionType` AS 'Type', trans.`OpeningBalance` FROM t_transaction AS trans
		LEFT JOIN t_request AS req ON
			trans.RequestID = req.RequestID
		LEFT JOIN t_recharge AS rch ON
			trans.RequestID = rch.RequestID 
		LEFT JOIN m_users AS user ON
			trans.UserID = user.UserID ";
		//LEFT JOIN t_sms AS sms ON
			//req.RequestID = sms.ReferenceID			";
		//echo "USerID:".$userId."Mobile:".$mobile."requestId:".$requestId."network:".$network;
		//trans.Amount!=0 is to avoid new user first transaction record in the report.
		$where= "WHERE trans.TransactionID!=0 AND (trans.Amount!=0 OR (trans.Amount=0 AND trans.TransactionType='5'))";//sms.IsFirstSMSForReq!=0 ";
		$orderBy =" ORDER BY trans.TransactionID DESC ";
		$groupBy = " GROUP BY req.RequestID ";
		
		$where .=" AND DATE(trans.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(trans.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		if($userId!="")
			$where.="AND trans.UserID='$userId' ";
		if($mobile!="")
			$where.="AND req.`TargetNo`='$mobile' ";
		if($requestId!="")
			$where.="AND req.`DisplayID`='$requestId' ";
		if($network!="")
			$where.="AND rch.`NetworkProviderName`='$network' ";
		/* 
		if($userId =='' &&  $mobile == '' && $requestId == '' && $network == 'null' ) { 
			$where .=" AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		} 
		else if($userId =='' &&  $mobile == '' && $requestId == '' && $network == '' ) { 
			$where .=" AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		} 
		else if($userId !='' &&  $mobile == '' && $requestId == '' && $network == '' ) { 
			$where .=" AND trans.UserID='$userId' AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		}
		else if($userId !='' &&  $mobile == '' && $requestId == '' && $network != '' ) { 
			$where .=" AND trans.UserID='$userId' AND rch.`NetworkProviderName`='$network' AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		} 
		else if($userId =='' &&  $mobile != '' && $requestId == '' ) { 
			$where .=" AND req.`TargetNo`='$mobile' AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		} 
		else if($userId =='' &&  $mobile == '' && $requestId != ''  ) { 
			$where .=" AND req.`DisplayID`='$requestId' ";
		}
		else if($network != '' ) { 
			$where .=" AND rch.`NetworkProviderName`='$network' AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		} else {
			$where .=" AND trans.UserID='$userId' AND req.`TargetNo`='$mobile' AND DATE(trans.`CreatedDate`) >= '$fromDate' AND DATE(trans.`CreatedDate`)<= '$toDate' ";
		}*/
		//$query = $select.$where.$groupBy.$orderBy;
		$query = $select.$where.$orderBy;
		//echo $query;
		if($isWebservice){
			//echo $query;
			return $this->db->selectArray($query,'e_transaction',1);
		}else{
			return $this->dtObj->get($table, $index_column, $columns,$query);
		}

	}
	
	//Get Opening & Closing balance
	function getOpenCloseBalanceByDateUserId($userId, $srchDate){
		//$srchDate = $date.' 23:59:59';
		$q="SELECT OpeningBalance, ClosingBalance FROM t_transaction WHERE UserID='$userId' AND CreatedDate <='$srchDate' ORDER BY TransactionID DESC LIMIT 1";
		//echo $q;
		$arr=$this->db->selectArray($q,'e_transaction');
		return $arr;
	}
	
	
	//Get Today Sales amount
	function getTransferSalesByDate($userID,$date){
		$q = "SELECT SUM(CASE WHEN Amount<0 THEN Amount ELSE 0 END) as TotalSalesAmount, SUM(CASE WHEN Amount>0 THEN Amount ELSE 0 END) as TotalSalesPlusAmount  FROM t_transaction WHERE UserID='$userID' AND CreatedDate like '%$date%'";
		$res=$this->db->selectArray($q,'e_transaction');
		return $res;
	}
	//End here 
	
	
	
	//Today's first transaction record's opening balance is todays' opening balance.
	//If no transaction happened today then last transaction for the users closing balance is toady's opening balance.
	function getOpeningBlanceByUserID($userID){
		$today =date('Y-m-d');
		//$q="SELECT IFNULL((SELECT OpeningBalance FROM t_transaction WHERE UserID='$userID' AND CreatedDate LIKE '%$today%' ORDER BY CreatedDate ASC LIMIT 1),(SELECT ClosingBalance AS OpeningBalance FROM t_transaction WHERE UserID='$userID' ORDER BY CreatedDate DESC LIMIT 1)) AS OpeningBalance";
		$q="SELECT IFNULL(ClosingBalance,0.00) AS OpeningBalance FROM t_transaction WHERE UserID='$userID' AND CreatedDate NOT LIKE '%$today%' ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1";
	//echo $q;
		$arr=$this->db->selectArray($q,'e_transaction');
		return $arr;
	}
	
	//This may be duplicate method. This is being used by Add() method in local.
	function getWalletBlanceByUserID($userID){
		$today =date('Y-m-d');
		$q="SELECT ClosingBalance FROM t_transaction WHERE UserID='$userID' ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1;";
		
		//echo $q;
		$arr=$this->db->selectArray($q,'e_transaction');
		if(count($arr)>0) return $arr[0]->ClosingBalance;
		else return 0;
	}
	function getLatestTransaction($userID,$date,$properties){
		if($properties=="") $properties="*";
		$createdDateCondition = " and CreatedDate > '$date'";
		if($date=="") $createdDateCondition = "";
		$q="SELECT $properties FROM t_transaction WHERE UserID='$userID' $createdDateCondition ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1;";
		
		//echo $q;
		$arr=$this->db->selectArray($q,'e_transaction');
		return $arr;
		/* if(count($arr)>0) return $arr[0]->ClosingBalance;
		else return 0; */
	}
	function DeleteOldTransaction($userID,$date){
		$q="DELETE FROM t_transaction WHERE UserID='$userID' AND CreatedDate <= '$date'";
		//echo $q;
		$res=$this->db->execute($q);
		return $res;
	}
	function addErrorlog($fnName,$message,$deveMsg,$type,$id,$more){
		//echo "test1";
		$this->db->errorlog->addLog("WebserviceProcess",$this->fileName,$fnName,$message,$deveMsg,$type,$id,$more);
	}
}
?>