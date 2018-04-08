<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_payment.php';
//include_once APPROOT_URL.'/Database/d_datatable.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_payment{
	var $fileName="d_payment.php";
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function getBalanceToBePaid($userID,$parentID){
		try{
			$q="SELECT BalanceToBePaid FROM t_payment WHERE UserID='$userID' AND FromOrToUserID='$parentID' ORDER BY CreatedDate DESC LIMIT 1"; 
			
			echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_payment');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Get Today Sales amount
	function getTransferSalesByDate_NIU($userID,$date){
		$q = "SELECT SUM(CASE WHEN amount<0 THEN amount ELSE 0 END) as TotalSalesAmount FROM t_payment WHERE UserID='$userID' AND CreatedDate like '%$date%'";
		$res=$this->db->selectArray($q,'e_payment');
		return $res;
	}
	//End here 
	
	function getTransferByDate($userID,$date){
		//$q="SELECT IFNULL(SUM(Amount),0) as Amount FROM t_payment WHERE UserID='$userID' AND CreatedDate like '%$date%'"; 
		$q = "SELECT IFNULL(ABS(SUM(Amount)),0.00) AS Amount FROM t_payment WHERE UserID='$userID' AND ((Mode<>6 AND Amount>0) OR (Mode=6 AND Amount<0)) AND CreatedDate like '%$date%'";
		//echo $q;
		$res=$this->db->selectArray($q,'e_payment');
		//echo json_encode($res);
		return $res;
		/* if(count($res)>0)
			return $res[0]->TotalAmount;
		else
			return 0; */
	}
	function getBillingByDate($userID,$date){
		$q="SELECT IFNULL(CommissionPercent,0) as CommissionPercent FROM t_payment WHERE UserID='$userID' AND CreatedDate like '%$date%' ORDER By CreatedDate DESC LIMIT 1"; 
		//echo $q;
		$res=$this->db->selectArray($q,'e_payment');
		//echo json_encode($res);
		return $res;
		/* if(count($res)>0)
			return $res[0]->TotalAmount;
		else
			return 0; */
	}
	function addTransfer($obj){
		try{
			//echo '<br /> Add addTransfer';//test								
			$obj->CreatedDate = date('Y-m-d h:i:s');
			$obj->CreatedBy = $this->me->UserID;
			$obj->ModifiedBy = $this->me->UserID;
			$q="call AddPayment('$obj->FromUserID','$obj->ToUserID','$obj->Amount','$obj->CommissionPercent','$obj->Type','$obj->Mode','$obj->Remark','$obj->PaidAmount','$obj->TotalAmount','$obj->CommissionAmountPrevPur','$obj->RequestID')";
			//echo $q;
				
			$qForLog = str_replace(' ', '_', $q);
			$qForLog = str_replace("'", '-', $qForLog);
			$this->addErrorlog("addTransfer",$qForLog,"call addPayment","t_payment","",json_encode($obj));
		
			$res=$this->db->execute($q);
			$res=1;
			//echo 'SP res='.$res;
			/*$q="INSERT INTO m_service(Name,RechargeCode,TopupCode,DefaultType,CreatedDate,CreatedBy,ModifiedBy) 
			VALUE('$serviceObj->Name','$serviceObj->RechargeCode','$serviceObj->TopupCode','$serviceObj->DefaultType','$serviceObj->CreatedDate','$serviceObj->CreatedBy','$serviceObj->ModifiedBy')";
			//echo '<br/> Query= '.$q;
			$res=$this->db->insert($q);*/
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function getTransfers_DT($parentID,$fromDate,$toDate,$isDataTable=false){
		$table = 't_payment';
		$index_column = 'PaymentID';
		$columns = array('PaymentID','CreatedDate', 'UserID','FromOrToUserID','DisplayUserID','Name','Mobile', 'Description', 'Amount','Commission','Type','Wallet','Remark','Mode','TotalAmount');
		
		$commissionAmntCalc="TRUNCATE(ABS(p.TotalAmount-ABS(p.Amount)),2)";
		$whereFromToDateCond="";
		//Filter for today if date is not passwed
		$today =date('Y-m-d');
		if($fromDate=="" && $toDate=="")
		{
			$fromDate=$today;
			$toDate=$today;
		}else if($fromDate=="")
			$fromDate=$toDate;
		else if($toDate=="")
			$toDate=date($fromDate);
		$whereFromToDateCond .="AND DATE(p.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(p.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		$qSelectDisplayUserID = 'SELECT DisplayID FROM m_users WHERE UserID=p.FromOrToUserID';
		$query="SELECT SQL_CALC_FOUND_ROWS p.PaymentID,0 as SNo,p.CreatedDate,p.UserID,IFNULL(($qSelectDisplayUserID),'System') AS FromOrToUserID,u.DisplayID as DisplayUserID,u.Name,u.Mobile, p.Description, p.Amount,$commissionAmntCalc as Commission,p.Type,p.ClosingBalance AS Wallet,p.Remark,p.Mode,p.TotalAmount FROM t_payment as p LEFT JOIN m_users as u ON p.UserID=u.UserID WHERE (p.UserID='$parentID') AND p.TotalAmount>0 AND u.Active=1 $whereFromToDateCond ORDER BY p.PaymentID DESC";
		//echo $query;
		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	function getTransfersByDateRange_DT($parentID,$startDate,$endDate,$onlyMyPayments=false,$isDataTable=false){
		$table = 't_payment';
		$index_column = 'PaymentID';
		$columns = array('PaymentID','CreatedDate', 'UserID','FromOrToUserID','DisplayUserID','Name','Mobile', 'Description', 'Amount','Commission','Type','Wallet','Remark','Mode','TotalAmount');
		
		$commissionAmntCalc="TRUNCATE(ABS(p.TotalAmount-ABS(p.Amount)),2)";
		$dateCond = " AND DATE(p.`CreatedDate`) >= STR_TO_DATE('$startDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(p.`CreatedDate`)<= STR_TO_DATE('$endDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
		$onlyMyPaymentCond = $onlyMyPayments==true ? " AND p.Amount>0 " : "";
		//echo "test".$onlyMyPaymentCond;
		$qSelectDisplayUserID = 'SELECT DisplayID FROM m_users WHERE UserID=p.FromOrToUserID';
		$query="SELECT SQL_CALC_FOUND_ROWS p.PaymentID,0 as SNo,p.CreatedDate,p.UserID,IFNULL(($qSelectDisplayUserID),'System') AS FromOrToUserID,u.DisplayID as DisplayUserID,u.Name,u.Mobile, p.Description, p.Amount,$commissionAmntCalc as Commission,p.Type,p.ClosingBalance AS Wallet,p.Remark,p.Mode,p.TotalAmount FROM t_payment as p LEFT JOIN m_users as u ON p.UserID=u.UserID WHERE (p.UserID='$parentID') AND p.TotalAmount>0 AND u.Active=1 $dateCond $onlyMyPaymentCond ORDER BY p.PaymentID DESC";
		//echo $query;
		if($isDataTable){
			return $this->dtObj->get($table, $index_column, $columns,$query);
		}else{
			return $this->db->selectArray($query,'e_transaction');
		}
	}
	
	function getBalanceToBePaidByParent_DT($parentID){
		$table = 't_payment';
		$index_column = 'PaymentID';
		$columns = array('SNo','DisplayUserID', 'Name','ParentID','BalanceToBePaid');
		
		//$qBalanceToPay="TRUNCATE(((p.CommissionPercent/100)*p.Amount),2)";
		$qBalanceToPay="SELECT BalanceToBePaid FROM t_payment WHERE UserID=u.UserID AND FromOrToUserID='$parentID' ORDER BY CreatedDate DESC LIMIT 1"; 
		$qSelectDisplayUserID = 'SELECT DisplayID FROM m_users WHERE UserID=p.UserID';
		$query="SELECT p.PaymentID AS SNo,u.DisplayID AS DisplayUserID,u.Name,u.ParentID,($qBalanceToPay) AS BalanceToBePaid FROM t_payment as p LEFT JOIN m_users as u ON p.UserID=u.UserID WHERE u.ParentID='$parentID' AND u.Active=1 GROUP BY p.UserID";
		//echo $query;
		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	
	function getCollectionByParent_DT($parentID){
		$table = 't_payment';
		$index_column = 'PaymentID';
		$columns = array('SNo','CreatedDate', 'FromUser','ToUser','PreviousBalance','PaidAmount','CurrentBalance','Mode','Remark');
		
		//$qBalanceToPayPrev="SELECT BalanceToBePaid FROM t_payment WHERE UserID=u.UserID AND FromOrToUserID='$parentID' ORDER BY CreatedDate DESC LIMIT 1,1"; 
		//$qBalanceToPayCur="SELECT BalanceToBePaid FROM t_payment WHERE UserID=u.UserID AND FromOrToUserID='$parentID' ORDER BY CreatedDate DESC LIMIT 1"; 
		$qFromToDisplayUserID = 'SELECT DisplayID FROM m_users WHERE UserID=p.FromOrToUserID';
		$query="SELECT p.PaymentID AS SNo,p.CreatedDate,u.DisplayID AS FromUser,($qFromToDisplayUserID) AS ToUser,(p.BalanceToBePaid+p.PaidAmount) AS PreviousBalance,p.PaidAmount,p.BalanceToBePaid AS CurrentBalance,p.Mode,p.Remark FROM t_payment as p LEFT JOIN m_users as u ON p.UserID=u.UserID WHERE p.FromOrToUserID='$parentID' AND p.PaidAmount>0 AND u.Active=1 ORDER BY p.CreatedDate DESC";
		//echo $query;
		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	
	function DeleteOldPayments($userID,$date){
		$q="DELETE FROM t_payment WHERE UserID='$userID' AND CreatedDate <= '$date'";
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