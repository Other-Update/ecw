<?php
error_reporting(0);
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_userrequest.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_userrequest{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	
	function get_DT($userId, $fromTable, $mobile, $requestId, $status, $fromDate, $toDate) {
		$table = 't_complaint';
		$index_column = 'ComplaintID';
		$columns = array('ComplaintID', 'UserID', 'Recharge date', 'DisplayID', 'TransactionID', 'MobileNo', 'Amount', 'Network', 'Request Date', 'Status', 'Remark', 'TakenBy', 'RequestID' );
		
		$select = "SELECT SQL_CALC_FOUND_ROWS c.`ComplaintID`,req.`DisplayID`,c.`Status`,c.`Remark`,c.`CreatedDate` AS 'Request Date', req.`RequestID`, rc.`RcResOpTransID` AS 'TransactionID', rc.`ReachargeNo` AS 'MobileNo', rc.Amount, rc.`NetworkProviderName` AS 'Network',req.`ReqDateTime` AS 'Recharge date', u.DisplayID AS 'UserID', u2.Name AS 'TakenBy' FROM `t_complaint` AS c
		LEFT JOIN t_request AS req ON c.`RequestID`= req.`RequestID`
		LEFT JOIN t_recharge AS rc ON req.`RequestID` = rc.`RequestID`
		LEFT JOIN m_users AS u ON rc.`UserID` = u.`UserID`
		LEFT JOIN m_users AS u2 ON c.`CreatedBy` = u2.`UserID` ";
		
		$where= "WHERE c.Active=1 ";
		$orderBy ="ORDER BY c.ComplaintID DESC";
		
		//echo "USerID:".$userId."Mobile:".$mobile."requestId:".$requestId."Status:".$status;
		if($userId =='null' &&  $mobile == '' && $requestId == '' && $status == 1 ) { 
			$where .="AND c.FromTable='$fromTable' AND c.Status='$status' ";
		} 
		else if($userId =='' &&  $mobile == '' && $requestId == '' && $status == 1 ) { 
			$where .="AND c.FromTable='$fromTable' AND c.Status='$status' ";
		} 
		else if($userId =='' &&  $mobile == '' && $requestId == '' && $status != 1 && $status != 0 ){
			$where .="AND c.FromTable='$fromTable' AND c.Status='$status' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		} 
		else if($userId =='' &&  $mobile == '' && $requestId == '' &&  $status == 0 ){
			$where .="AND c.FromTable='$fromTable' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		} //User ID
		else if($userId !='' &&  $mobile == '' && $requestId == '' && $status != 1 && $status != 0 ){
			$where .="AND r.UserID='$userId' AND c.FromTable='$fromTable' AND c.Status='$status' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		} 
		else if($userId !='' &&  $mobile == '' && $requestId == '' &&  $status == 0 ){
			$where .="AND rc.UserID='$userId' AND c.FromTable='$fromTable' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		} //Mobile
		else if($userId =='' &&  $mobile != '' && $requestId == '' &&  $status != 0 ){
			//$where .="AND rc.`ReachargeNo`='$mobile' AND c.FromTable='$fromTable' AND c.Status='$status' AND c.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ";
			$where .="AND rc.`ReachargeNo`='$mobile' AND c.FromTable='$fromTable' AND c.Status='$status' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		}
		else if($userId =='' &&  $mobile != '' && $requestId == '' &&  $status == 0 ){
			//$where .="AND rc.`ReachargeNo`='$mobile' AND c.FromTable='$fromTable' AND c.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ";
			$where .="AND rc.`ReachargeNo`='$mobile' AND c.FromTable='$fromTable' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		}//Request ID
		else if($userId =='' &&  $mobile == '' && $requestId != '' &&  $status != 0 ){
			//$where .="AND req.`DisplayID`='$requestId' AND c.FromTable='$fromTable' AND c.Status='$status' AND c.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ";
			$where .="AND req.`DisplayID`='$requestId' AND c.FromTable='$fromTable' AND c.Status='$status' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s')";
		}
		else if($userId =='' &&  $mobile == '' && $requestId != '' &&  $status == 0 ){
			//$where .="AND req.`DisplayID`='$requestId' AND c.FromTable='$fromTable' AND c.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ";
			$where .="AND req.`DisplayID`='$requestId' AND c.FromTable='$fromTable' AND DATE(c.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(c.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		}
		$query = $select.$where.$orderBy;
		return $this->dtObj->get($table, $index_column, $columns,$query);

	}
	
	function getUserRequest($SearchRequest){
		//$q="SELECT rc.RechargeID,rc.ReachargeNo,rc.Amount,rc.RcResOpTransID from t_recharge WHERE RequestID='$SearchRequest' "; 
		$q = "SELECT t.RequestID,rc.ReachargeNo,rc.Amount,rc.RcResOpTransID FROM t_request AS t 
				LEFT JOIN t_recharge AS rc ON
					t.RequestID = rc.RequestID
			  WHERE t.DisplayID='$SearchRequest' ";
		$arr=$this->db->selectArray($q,'e_userrequest');
		return $arr;
	}
	

	function add($compObj){
		try{ 							
			$compObj->CreatedDate = date('Y-m-d h:i:s');
			$compObj->CreatedBy = $this->me->UserID;
			
			$q="INSERT INTO t_complaint( `RequestID`, `Status`, `Remark`, `FromTable`, `CreatedDate`, `CreatedBy`, `ModifiedDate`, `ModifiedBy`) VALUE('$compObj->RequestID','$compObj->Status','$compObj->Remark','$compObj->FromTable','$compObj->CreatedDate','$compObj->CreatedBy','$compObj->CreatedDate','$compObj->CreatedBy')";
			$res=$this->db->insert($q);
			
			//Send SMS
			/* if(!empty($compObj->SendSms)){
				echo "Regu".$compObj->SendSms;
			} else {
				echo "Nathan".$compObj->SendSms;
			}  */
				
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	
	
function update($compObj){
		try{ 							
			$compObj->ModifiedDate = date('Y-m-d h:i:s');
			$compObj->ModifiedBy = $this->me->UserID;
			
			$q = "UPDATE t_complaint SET `Status`='$compObj->Status', `Remark`='$compObj->Remark', `ModifiedDate`= '$compObj->ModifiedDate', `ModifiedBy`='$compObj->ModifiedBy' WHERE `ComplaintID`='$compObj->ComplaintID' ";
			$res=$this->db->execute($q);
			

			//Pending --> 1, Success --> 3,  Failed --> 4
			if($compObj->Status != 1 AND $compObj->PrevStatus != $compObj->Status){
			
				$req = $this->bReqObj->getByID($compObj->RequestID);
				$ddd=  json_encode($req);

				$req[0]->DisplayID; 
				$req[0]->Status = $compObj->Status;
				$req[0]->DevInfo;
				$req[0]->Remark;
				$req[0]->UserID;
				updateStatus($req[0]);

			} 
				
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	
}
?>