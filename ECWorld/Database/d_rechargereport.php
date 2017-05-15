<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_transaction.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_rechargereport{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	
	function getNetworkList(){
		$q="SELECT Name FROM m_networkprovider WHERE Active=1 ";
		$arr=$this->db->selectArray($q,'e_transaction');
		return $arr;
	}
	
/*	function getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $requestId, $network){
		$table = 't_transaction';
		$index_column = 'TransactionID';
		$columns = array('TransactionID', 'CreatedDate', 'Name', 'OpeningBalance', 'ClosingBalance', 'PaidAmount', 'Mode', 'Remark', 'Type');
		if($userId !='' AND $mobile !=''){
		$query ="SELECT SQL_CALC_FOUND_ROWS t.`UserID`, t.`ReferenceTable`, t.`ReferenceID`, r.`Amount`, r.`Status`, r.`NetworkProviderName` FROM `t_transaction` AS t 
			LEFT JOIN t_recharge AS r ON 
			t.`ReferenceID`   = r.`RechargeID`
			WHERE r.`NetworkProviderName`='$network' AND t.`CreatedDate` BETWEEN '$fromDate' AND '$toDate' ORDER BY t.`TransactionID` ASC ";
		} 
		return $this->dtObj->get($table, $index_column, $columns,$query);

	}  */
	
	
	
	
	
}
?>