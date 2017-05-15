<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_rcamountsetting.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_rcamountsetting{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function getByServiceID($serviceID){
		try{
			$q="SELECT * FROM m_rcamount_setting WHERE ServiceID='$serviceID' AND Active='1' ";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_rcamountsetting');
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	function get_DT(){
		$table = 'm_rcamount_setting';
		$index_column = 'RcAmountID';
		$columns = array('RcAmountID', 'ServiceName', 'TypeName', 'RCDenomination', 'TPDenomination', 'InvalidAmount', 'ServiceID', 'DefaultType');
		$query="SELECT r.`RcAmountID`, s.`Name` AS ServiceName,  t.`Name` AS TypeName, r.`RCDenomination`,
				r.`TPDenomination`, r.`InvalidAmount`, s.`ServiceID`, s.`DefaultType` FROM `m_service` AS s
				LEFT JOIN `m_rcamount_setting` AS r ON 
				   s.`ServiceID`  = r.`ServiceID`
				LEFT JOIN `m_rechargetype` AS t ON
				   s.`DefaultType` = t.`RechargeTypeID` 
			WHERE s.Active=1 ";
		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	function add($amtObj){
		try{ 							
			$amtObj->ModifiedDate = date('Y-m-d h:i:s');
			$amtObj->CreatedBy = $this->me->UserID;
			$amtObj->ModifiedBy = $this->me->UserID;
			
			$ser="UPDATE `m_service` SET  `DefaultType`='$amtObj->RechargeTypeID', `ModifiedDate`='$amtObj->ModifiedDate', `ModifiedBy`='$amtObj->ModifiedBy' WHERE `ServiceID`='$amtObj->ServiceID'";
			$update=$this->db->execute($ser);
			
			$RCDenomination = str_replace(",", "#",$amtObj->RCDenomination);
			$TPDenomination = str_replace(",", "#",$amtObj->TPDenomination);
			$InvalidAmount = str_replace(",", "#",$amtObj->InvalidAmount);
			
			$q="INSERT INTO `m_rcamount_setting`(`ServiceID`, `RCDenomination`, `TPDenomination`, `InvalidAmount`,  `CreatedBy`, `ModifiedDate`, `ModifiedBy`) VALUE('$amtObj->ServiceID', '$RCDenomination', '$TPDenomination', '$InvalidAmount','$amtObj->CreatedBy','$amtObj->ModifiedDate','$amtObj->ModifiedBy')";
			$res=$this->db->insert($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	
	function update($amtObj){
		try{ 							
			$amtObj->ModifiedDate = date('Y-m-d h:i:s');
			$amtObj->ModifiedBy = $this->me->UserID;
			
			$ser="UPDATE `m_service` SET  `DefaultType`='$amtObj->RechargeTypeID', `ModifiedDate`='$amtObj->ModifiedDate', `ModifiedBy`='$amtObj->ModifiedBy' WHERE `ServiceID`='$amtObj->ServiceID'";
			$update=$this->db->execute($ser);
			
			$RCDenomination = str_replace(",", "#",$amtObj->RCDenomination);
			$TPDenomination = str_replace(",", "#",$amtObj->TPDenomination);
			$InvalidAmount = str_replace(",", "#",$amtObj->InvalidAmount);
			
			$q="UPDATE `m_rcamount_setting` SET `RCDenomination`='$RCDenomination', `TPDenomination`='$TPDenomination', `InvalidAmount`='$InvalidAmount', `ModifiedDate`='$amtObj->ModifiedDate', `ModifiedBy`='$amtObj->ModifiedBy' WHERE `RcAmountID`='$amtObj->RcAmountID'";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	
}
?>