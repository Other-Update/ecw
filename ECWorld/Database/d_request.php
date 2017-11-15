<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_request.php';
include_once APPROOT_URL.'/Database/d_recharge.php';
include_once APPROOT_URL.'/Database/d_transaction.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_request{
	var $fileName="d_request.php";
	private $db;
	private $dtObj;
	private $dRcObj;
	private $dTransObj;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
		$this->dTransObj = new d_transaction($mysqlObj);
		$this->dRcObj = new d_recharge($mysqlObj);
	}
	function getByID($id){
		$q= "SELECT * FROM t_request WHERE RequestID='$id' OR DisplayID='$id'";
		$res=$this->db->selectArray($q,'e_request');
		return $res;
	}
	function getByApiRefID($id){
		$q= "SELECT * FROM t_request WHERE RcApiRefID='$id'";
		$res=$this->db->selectArray($q,'e_request');
		return $res;
	}
	
	function getDuplicateRequest($mobile,$amount,$sameNoAmountDelay,$sameNoDiffAmountDelay){
		$q= "SELECT * FROM t_request WHERE TargetNo='$mobile' AND ((TargetAmount='$amount' AND TIMESTAMPDIFF(MINUTE, CreatedDate, now())<'$sameNoAmountDelay') OR (TargetAmount!='$amount' AND TIMESTAMPDIFF(MINUTE, CreatedDate, now())<'$sameNoDiffAmountDelay'))  AND Status!=4 AND Status!=3 ORDER BY CreatedDate DESC LIMIT 1";
		//echo $q;
		$res=$this->db->selectArray($q,'e_request');
		return $res;
	}
	function add($reqObj){
		$now=date('Y-m-d H:i:s');
		//echo "<br/> reqObj=".json_encode($reqObj);
		$reqObj->TargetNo = $reqObj->TargetNo ? $reqObj->TargetNo : "Identifying From MSG";
		$reqObj->TargetAmount = $reqObj->TargetAmount ? $reqObj->TargetAmount : 0.00;
		//echo "<br/>RequesterMobiles=".$reqObj->RequesterMobile;die;
		//echo "<br/>ReqReceivedDateTime=".$reqObj->ReqReceivedDateTime;
		$reqObj->ReqDateTime = date("Y-m-d H:i:s", strtotime($reqObj->ReqDateTime));
		$reqObj->ReqReceivedDateTime = date("Y-m-d H:i:s", strtotime($reqObj->ReqReceivedDateTime));
		//echo "<br/>ReqReceivedDateTime=".$reqObj->ReqReceivedDateTime;
		$reqObj->Remark = "Prcessing";
		$q="INSERT INTO t_request(DisplayID,RequesterIP,RequesterApp,TargetNo,TargetAmount,Message,DevInfo,Status,ReqDateTime,ReqReceivedDateTime,RequestAsIs,Remark,ServerNo,CreatedDate) VALUE('$reqObj->DisplayID','$reqObj->RequesterIP','$reqObj->RequesterApp','$reqObj->TargetNo','$reqObj->TargetAmount','$reqObj->Message','$reqObj->DevInfo','$reqObj->Status','$reqObj->ReqDateTime','$reqObj->ReqReceivedDateTime','$reqObj->InputAsIs','$reqObj->Remark','$reqObj->ServerNo','$now')";
		//echo '<br/> Query= '.$q;die;
		$qForLog = str_replace(' ', '_', $q);
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("add",$qForLog,"Insert request","t_request","","No-Amount. Before adding request");
		
		$res=$this->db->insert($q);
		return $res;
	}
	
	function addTransactionByRcReqStatus($oldReqObj,$newReqObj){
		//echo "<br/> addTransactionByReqStatus oldReqObj=".$oldReqObj->Status;
		//echo "<br/> addTransactionByReqStatus newReqObj=".$newReqObj->Status;
		$rc = $this->dRcObj->getByRequestID($newReqObj->RequestID);
		//echo "<br/> count(rc)=".count($rc);
		//Add transaction only for recharge requests. And it should have recharge record in t_rechrge table(Otherwise somethign wrong happened. It was happening when SP was used).
		
		$remark = "Recharge Failed Revert";
		$referenceTable = "t_request";
		$multiplyBy = 1;
		
		if(($oldReqObj->Status==1 || $oldReqObj->Status==2 || $oldReqObj->Status==3) && count($rc)>0 && $newReqObj->Status==4 && $newReqObj->RequestType=='RECHARGE'){
			$remark = "Recharge Failed Revert";
			$referenceTable = "t_request";
			$multiplyBy = 1;		
			
			$this->dTransObj->add(2,$newReqObj->RequestID,$newReqObj->UserID,$newReqObj->TotalAmount*$multiplyBy,$remark,$referenceTable,$newReqObj->RequestID,$newReqObj->UserID);
		}else if($oldReqObj->Status==4 && $newReqObj->Status!=4 && $newReqObj->RequestType=='RECHARGE'){
			$remark = "Recharge Failed to Sucess";
			$referenceTable = "t_request";
			$multiplyBy = -1;
			$this->dTransObj->add(2,$newReqObj->RequestID,$newReqObj->UserID,$newReqObj->TotalAmount*$multiplyBy,$remark,$referenceTable,$newReqObj->RequestID,$newReqObj->UserID);
		}
		//echo "<br/> newReqObj->UserID=".$newReqObj->UserID;
	}
	
	//Fir time updae with proper values like user id mobile, rc amount
	function update($reqObj){
		$now=date('Y-m-d H:i:s');
		//echo "<br/> Req update=".json_encode($reqObj);
		$reqObj->DevInfo = "Update-2. Updating Proper User&Input details";
		if($reqObj->Status!=3 && $reqObj->Status!=4)
			$reqObj->Status = "2";//Suspense
		//CreatedBy cannot be updated while creating the request. Adding request should happen before any calculation including user verification
		$q="UPDATE t_request SET UserID='$reqObj->UserID',TargetNo='$reqObj->TargetNo',TargetAmount='$reqObj->TargetAmount',TotalAmount='$reqObj->TotalAmount',DevInfo='$reqObj->DevInfo',RequestType='$reqObj->RequestType',Status='$reqObj->Status',ModifiedBy='$reqObj->UserID',Remark='$reqObj->Remark',ModifiedDate='$now',CreatedBy='$reqObj->UserID',ModifiedBy='$reqObj->UserID' WHERE RequestID='$reqObj->RequestID'";
		
		$qForLog = str_replace(' ', '_', $q);
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("update",$qForLog,"Update2 request","t_request",$reqObj->RequestID,"Before updating request");
		//echo "<br/> Q=".$q;
		
		$oldReqObj = $this->getByID($reqObj->RequestID);
		if(count($oldReqObj)>0){
			$res=$this->db->insert($q);
			//echo "<br/> update Request status updated result =".json_encode($res);
			$this->addTransactionByRcReqStatus($oldReqObj[0],$reqObj);
			return $res;
		}else return null;
	}
	
	function updateStatusByApiResp($reqObj){
	}
	function updateStatus($reqObj){
		$now=date('Y-m-d H:i:s');
		//echo "<br/> Req update=".json_encode($reqObj);
		$reqObj->DevInfo = "Updating status";
		$userID=$reqObj->UserID?$reqObj->UserID:-1;
		$q="UPDATE t_request SET Status='$reqObj->Status',ModifiedDate='$now',ModifiedBy='$userID'";
		if($reqObj->Remark!="") $q.=" ,Remark='$reqObj->Remark'";
		if($reqObj->DevInfo!="") $q.=" ,DevInfo='$reqObj->DevInfo'";
		$q.=" WHERE RequestID='$reqObj->RequestID'";
		//echo "<br/> Q=".$q;
		
		$qForLog = str_replace(' ', '_', $q);
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("updateStatus",$qForLog,"Update3 request","t_request",$reqObj->RequestID,"Before updating request");
		
		$oldReqObj = $this->getByID($reqObj->RequestID);
		
		if(count($oldReqObj)>0){
			$res=$this->db->insert($q);
			//echo "<br/>updateStatus Request status updated result =".json_encode($res);
			$this->addTransactionByRcReqStatus($oldReqObj[0],$reqObj);
			return $res;
		}else return null;
	}
	function updateApiImmediateResponse($reqObj){
		//echo "updateApiImmediateResponse";
		$now=date('Y-m-d H:i:s');
		$q="UPDATE t_request SET DevInfo='$reqObj->DevInfo' WHERE RequestID='$reqObj->RequestID'";
		//echo $q;
		$qForLog = str_replace(' ', '_', $q);
		$qForLog = str_replace("'", '-', $qForLog);
		$this->addErrorlog("updateApiImmediateResponse",$qForLog,"Update3 request","t_request",$reqObj->RequestID,"Before updating request");
		$res=$this->db->insert($q);
		return $res;
	}
	function updateDisplayID($reqID,$displayID){
		$now=date('Y-m-d H:i:s');
		$q="UPDATE t_request SET DisplayID='$displayID' WHERE RequestID='$reqID'";
		
		$res=$this->db->insert($q);
		return $res;
	}
	
	function getRequestStatus($reqID){
		$q="SELECT Status FROM t_request WHERE RequestID='$reqID'";
		
		$res=$this->db->selectArray($q,'e_request');
		return $res;
	}
	function addErrorlog($fnName,$message,$deveMsg,$type,$id,$more){
		//echo "d_request test1";
		$this->db->errorlog->addLog("WebserviceProcess",$this->fileName,$fnName,$message,$deveMsg,$type,$id,$more);
	}
	//Send SMS only if request is from SMS or if request is FAILED.
	function isSMSRequest($req){
		//S means SMS
		if ((strpos($req->DisplayID, 'S') !== false) || $req->Status==4)
			return true;
		else return false;
	}
	
	function DeleteOldRequests($userID,$date){
		$q="DELETE FROM t_request WHERE UserID='$userID' AND CreatedDate <= '$date'";
		//echo $q;
		$res=$this->db->execute($q);
		return $res;
	}
	/* Incoming Request */
	function getIncoming_DT($userId, $message, $server_no, $fromDate, $toDate){
		$table = 't_request';
		$index_column = 'RequestID';
		$columns = array('RequestID', 'UserDisplayID', 'Name', 'TargetNo', 'Message','RequestType', 'Remark', 'ReqDateTime', 'ServerNo');
		$select="SELECT SQL_CALC_FOUND_ROWS r.RequestID, u.DisplayID AS UserDisplayID, u.Name,  r.TargetNo,  r.Message, r.RequestType,  r.Remark, r.ReqDateTime, r.ServerNo
			FROM t_request as r 
			LEFT JOIN m_users as u on 
			     r.UserID =u.UserID"; 
			$where=" WHERE DATE(r.`ReqDateTime`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(r.`ReqDateTime`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
			if($userId!=""){
				$where.="AND r.UserID='$userId' ";
			}
			if($server_no!=""){
				$where.="AND r.`ServerNo`='$server_no' ";
			}
			if($message!=""){
				$where.="AND r.`Message` LIKE '%$message%' ";
			}
			$orderBy="AND u.Active=1 ORDER BY r.RequestID DESC ";

			$query = $select.$where.$orderBy;
			
		return $this->dtObj->get($table, $index_column, $columns,$query);

	}
	/* End */
}
?>