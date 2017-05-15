<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_recharge.php';
include_once APPROOT_URL.'/Database/d_request.php';
class d_recharge{
	var $fileName="d_recharge.php";
	private $db;
	private $dtObj;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function getRechargeReport_DT_NIU($userId, $mobile, $fromDate, $toDate,$isDataTable){
		//echo json_encode($this->db->executeSP("CALL UpdateWallet(1,1)"));
		$table = 't_recharge';
		$index_column = 'RechargeID';
		$columns = array('RechargeID','UserID', 'ReachargeNo', 'Amount','Status','ReqDateTime','ReqReceivedDateTime', 'OpeningBalance','ClosingBalance','CreatedDate');
		$query="SELECT r.RechargeID,r.CreatedBy AS UserID, r.ReachargeNo,r.Amount,r.Balance,
				r.CreatedDate FROM t_recharge as r 
				left join m_users as u on 
					r.CreatedBy=u.UserID 
				WHERE r.CreatedBy='$userId' AND u.Active=1 ORDER BY r.CreatedDate DESC";
		//echo $query;
		if($isDataTable){
			$arr=$this->db->selectArray($query,'e_recharge');
			return $arr;
		}else{
			return $this->dtObj->get($table, $index_column, $columns,$query);
		}

	}
	
	function getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $limit, $isDataTable){
		$table = 't_recharge';
		$index_column = 'RechargeID';
		$columns = array( 'UserID','UserDisplayID','UserName','RechargeID', 'RequestType', 'ReachargeNo', 'NetworkProviderName', 'Amount', 'Txn_Id', 'Status', 'Balance', 'ReqDateTime' );
		$query="SELECT u.UserID,u.DisplayID as UserDisplayID,u.Name as UserName,req.DisplayID as RechargeID, req.RequestType, r.ReachargeNo, r.NetworkProviderName, r.Amount, r.RcResOpTransID AS Txn_Id, req.Status, trans.ClosingBalance as Balance,r.CreatedDate,req.ReqDateTime   FROM t_recharge as r 
				left join m_users as u on 
					r.CreatedBy=u.UserID
				left join t_request as req on
				    r.RequestID = req.RequestID
				left join t_transaction as trans on trans.TransactionID =(SELECT TransactionID FROM t_transaction WHERE RequestID=r.RequestID ORDER BY TransactionID DESC LIMIT 1) ";
				/* left join t_transaction as trans on
					r.RequestID = trans.RequestID "; */
		
		$where ="WHERE u.Active=1 ";
		if($userId!=''){
			$where .=" AND (u.UserID='$userId' OR u.DisplayID='$userId') ";
		}
		if($mobile!=""){
				$where.=" AND r.ReachargeNo='$mobile' ";
		}
		if($fromDate!="" && $toDate!="")
			$where .="AND DATE(r.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(r.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		
		$orderby = " GROUP BY trans.RequestID ORDER BY r.RechargeID DESC ";
		$qlimit="Limit $limit";
		if($limit=="" || $limit==0)
			$qlimit="";
		$query .= $where.$orderby.$qlimit;
		//echo $query;
		if($isDataTable){
			$arr=$this->db->selectArray($query,'e_recharge');
			return $arr;
		}else{
			return $this->dtObj->get($table, $index_column, $columns,$query);
		}

	}

	function getByRequestID($reqID){
		$q="SELECT * FROM t_recharge WHERE RequestID='$reqID'";
		//echo $q;
		$arr=$this->db->selectArray($q,'e_recharge');
		return $arr;
	}
	function getByMobileAndAmount($mobile,$amount){
		$q="SELECT * FROM t_recharge as r LEFT JOIN t_request as req ON req.RequestID=r.RequestID ";
		if($amount==0 || $amount==""){
			$q.="WHERE r.ReachargeNo='$mobile' AND req.Status='3' ORDER BY r.RcResReceivedDateTime DESC LIMIT 1";
		}else{
			$q.="WHERE r.ReachargeNo='$mobile' AND r.Amount='$amount' AND req.Status='3' ORDER BY r.RcResReceivedDateTime DESC LIMIT 1";
		}
		//echo "<br/>".$q;
		$arr=$this->db->selectArray($q,'e_recharge');
		return $arr;
	}
	function getOpeningBlanceByUserID_NIU($userID){
		$today =date('Y-m-d');
		$q="SELECT IFNULL(ClosingBalance,0.00) AS OpeningBalance FROM t_transaction WHERE UserID='$userID' AND CreatedDate NOT LIKE '%$today%' ORDER BY CreatedDate DESC LIMIT 1";
		//echo $q;
		$arr=$this->db->selectArray($q,'e_recharge');
		return $arr;
	}
	function doRecharge($reqObj,$networkName){
		$createdDate=date('Y-m-d H:i:s');
		$reqObj->ReqDateTime = date("Y-m-d H:i:s", strtotime($reqObj->ReqDateTime));
		/* $q="INSERT INTO t_recharge(ReachargeNo,Amount,Status,NetworkProviderName,RequestID,ReqDateTime,ReqReceivedDateTime,CreatedDate,CreatedBy,ModifiedBy) VALUE('$reqObj->TargetNo','$reqObj->TargetAmount','$reqObj->Status','Getting...','$reqObj->RequestID','$reqObj->ReqDateTime','$reqObj->ReqReceivedDateTime','$createdDate','$reqObj->RequesterID','$reqObj->RequesterID')"; */
		//echo json_encode($reqObj);			
		
		//$q="call AddRecharge('$reqObj->TargetNo','$reqObj->TargetAmount','$reqObj->Status','$networkName','$reqObj->RequestID','$reqObj->ReqDateTime','$reqObj->ReqReceivedDateTime','$createdDate','$reqObj->UserID','$reqObj->TotalAmount')";
	
		$q = "INSERT INTO t_recharge(UserID,ReachargeNo,Amount,NetworkProviderName,RequestID,CreatedDate,CreatedBy,ModifiedBy,Balance,TotalAmount,RcResponse, RcResOpTransID, ResponseAsIs) VALUE('$reqObj->UserID','$reqObj->TargetNo','$reqObj->TargetAmount','$networkName','$reqObj->RequestID',now(),'$reqObj->UserID','$reqObj->UserID',0,0,'','','');";
		
		$qForLog = str_replace(' ', '_', $q);	
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("doRecharge",$qForLog,"Insert into t_recharge","t_recharge",$reqObj->RequestID,"Before calling Insert");
		
		//echo '<br/> Query= '.$q;
		
		//$resLock = $this->db->beginTransaction();
		//$resLock = $this->db->LockExec("LOCK TABLES t_transaction WRITE,t_recharge WRITE");
		//$qTmpSelect = "SELECT t.ClosingBalance FROM t_transaction t WHERE t.UserID='$reqObj->UserID' ORDER BY t.CreatedDate DESC,t.TransactionID DESC LIMIT 1 FOR UPDATE";
		//$res=$this->db->select($qTmpSelect);
		//echo "<br/> qTmpSelect=".json_encode($qTmpSelect);
		//echo "<br/> qTmpSelect res=".json_encode($res);
		//echo "<br/> Table locked";
		//sleep(10);//die;
		//echo "<br/>Res lock=".json_encode($resLock);
		$id=$this->db->insert($q);
		//sleep(3);
		//echo "<br/>t_recharge insert-q Res =".json_encode($id);
		
		//$testQ = "INSERT INTO t_recharge(UserID,ReachargeNo,Amount,NetworkProviderName,RequestID,CreatedDate,CreatedBy,ModifiedBy,Balance,TotalAmount) VALUE(0,0,0,'a',0,now(),0,0,0,0);";
		//echo "<br/> testQ=".$testQ;
		//$testRes=$this->db->insert($testQ);
		//echo "<br/>insert-testQ Res =".json_encode($testRes);
		//$resUnlock = $this->db->LockExec("UNLOCK TABLES");
		//echo "<br/>Res resUnlock=".json_encode($resUnlock);
		
		//$resLock = $this->db->commitTransaction();
		return $id;
	}
	//Don't call this fn. Update t_request table status , intern this will call triger and that will handle this reverting fns bsaed on status change
	function revertRecharge_NIU($reqObj,$ecwStatus){
		$now=date('Y-m-d h:i:s');
		$q="call RevertRecharge('$reqObj->RequesterID','$reqObj->RequestID','$reqObj->TargetAmount','$ecwStatus','$now')";
		//echo '<br/> Query= '.$q;
		$res=$this->db->insert($q);
		return $res;
		//echo "Inserted ID=".$res;
	}
	function getRechargeByRequest($reqObj){
		$today =date('Y-m-d');
		$q="SELECT * FROM t_recharge WHERE RequestID='$reqObj->RequestID' ORDER BY CreatedDate DESC LIMIT 1";
		//echo $q;
		$arr=$this->db->selectArray($q,'e_recharge');
		return $arr;
	}
	function updateRechargeStatus($rcObj){
		$now=date('Y-m-d H:i:s');
		//echo "<br/> Update rc=".json_encode($rcObj);
		$q="call UpdateRecharge('$rcObj->RechargeID','$rcObj->Status','$now','$rcObj->RcResponse','$rcObj->RcResOpTransID','$rcObj->ResponseAsIs')";
		//echo '<br/> Query= '.$q;
		$qForLog = str_replace(' ', '_', $q);
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("updateRechargeStatus",$qForLog,"call UpdateRecharge","t_request",$rcObj->RequestID,"Before updating status");
		$res=$this->db->insert($q);
		return $res;
		//echo "Inserted ID=".$res;
	}
	/* function updateRechargeStatus($rechargeID,$ecwStatus,$responseMsg){
		$now=date('Y-m-d h:i:s');
		$q="call UpdateRecharge('$rechargeID','$ecwStatus','$now','$responseMsg')";
		echo '<br/> Query= '.$q;
		$res=$this->db->insert($q);
		return $res;
		//echo "Inserted ID=".$res;
	} */
	function saveQueryStringTesting($fromWhere,$str){
		//echo "<br/>saveQueryStringTesting in d_recharge";
		//$createdDate=date('Y-m-d h:i:s');
		$q="INSERT INTO z_testing(GUID,Message) VALUE('$fromWhere','$str')";
		//echo '<br/> Query= '.$q;
		$res=$this->db->insert($q);
		//echo "<br/>Inserted ID=".$res;
		return $res;
	}
	
	function addErrorlog($fnName,$message,$deveMsg,$type,$id,$more){
		//echo "test1";
		$this->db->errorlog->addLog("WebserviceProcess",$this->fileName,$fnName,$message,$deveMsg,$type,$id,$more);
	}
	
	
	
	function getCurrentRechargeReport_DT(){
		$table = 't_recharge';
		$index_column = 'RechargeID';
		$columns = array( 'UserID','UserDisplayID','UserName','RechargeID', 'RequestType', 'ReachargeNo', 'NetworkProviderName', 'Amount', 'Txn_Id', 'Status', 'Balance', 'ReqDateTime');
		$query="SELECT u.UserID,u.DisplayID as UserDisplayID,u.Name as UserName,req.DisplayID as RechargeID, req.RequestType, r.ReachargeNo, r.NetworkProviderName, r.Amount, r.RcResOpTransID AS Txn_Id, req.Status, trans.ClosingBalance as Balance,r.CreatedDate, req.ReqDateTime   FROM t_recharge as r 
				left join m_users as u on 
					r.CreatedBy=u.UserID
				left join t_request as req on
				    r.RequestID = req.RequestID
				left join t_transaction as trans on
					r.RequestID = trans.RequestID  WHERE u.Active=1 AND  req.Status='1' or req.Status='2' GROUP BY trans.RequestID ORDER BY r.RechargeID DESC ";

			return $this->dtObj->get($table, $index_column, $columns,$query);
		

	}
}
?>