<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_payment.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_paycollectionreport{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	
	function getPayCollectionReport_DT($userId, $mobile, $fromDate, $toDate){
		$table = 't_payment';
		$index_column = 'PaymentID';
		$columns = array('PaymentID', 'CreatedDate', 'Name', 'OpeningBalance', 'ClosingBalance', 'PaidAmount', 'Mode', 'Remark', 'Type');
		if($userId !='' AND $mobile !=''){
		$query ="SELECT SQL_CALC_FOUND_ROWS pay.PaymentID, pay.`CreatedDate`, user.Name, pay.OpeningBalance, pay.`ClosingBalance`, pay.PaidAmount, pay.`Mode`, pay.`Remark`, pay.`Type` 
		FROM `t_payment` AS pay
		LEFT JOIN m_users AS user ON
			  pay.UserId = user.UserId 
		WHERE pay.PaidAmount > 0 AND user.Active=1 AND pay.UserId='$userId' AND pay.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ORDER BY pay.`PaymentID` ASC ";
		} else if($userId =='' AND $mobile !=''){
		$query ="SELECT SQL_CALC_FOUND_ROWS pay.PaymentID, pay.`CreatedDate`, user.Name, pay.OpeningBalance, pay.`ClosingBalance`, pay.PaidAmount, pay.`Mode`, pay.`Remark`, pay.`Type` 
		FROM `t_payment` AS pay
		LEFT JOIN m_users AS user ON
			  pay.UserId = user.UserId 
		WHERE pay.PaidAmount > 0 AND  user.Active=1 AND user.mobile='$mobile' AND pay.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ORDER BY pay.`PaymentID` ASC ";
		} else {
		$query ="SELECT SQL_CALC_FOUND_ROWS pay.PaymentID, pay.`CreatedDate`, user.Name, pay.OpeningBalance, pay.`ClosingBalance`, pay.PaidAmount, pay.`Mode`, pay.`Remark`, pay.`Type` 
		FROM `t_payment` AS pay
		LEFT JOIN m_users AS user ON
			  pay.UserId = user.UserId 
		WHERE pay.PaidAmount > 0 AND  user.Active=1 AND pay.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ORDER BY pay.`PaymentID` ASC ";
		}
		return $this->dtObj->get($table, $index_column, $columns,$query);

	}
	
	
}
?>