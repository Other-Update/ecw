<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_fat.php';
class d_fat{
	private $db;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
	}
	function getByID($fatID){
		$q="SELECT * FROM m_fat where FATID='$fatID'";
		$res=$this->db->selectArray($q,'e_fat');
		return $res;
	}
	function getByUserID($userID,$isRole=0){
		$q="SELECT * FROM m_fat where UserID='$userID' AND IsRole='$isRole'";
		//echo $q;
		$res=$this->db->selectArray($q,'e_fat');
		return count($res)>0 ? $res[0] : $res;
	}
	function getByParent($parentID){
		//$q="SELECT * FROM m_fat inner join m_users on m_users.UserID=m_fat.UserID where m_users.ParentID='$parentID' AND IsRole='0'";
		$q="SELECT * FROM m_fat inner join m_users on m_users.UserID=m_fat.UserID where IsRole='0'";
		//echo $q;
		$res=$this->db->selectArray($q,'e_fat');
		//return count($res)>0 ? $res[0] : $res;
		return $res;
	}
	
	function add($fatObj){
		//echo 'D fat-'.$fatObj->FATID;
		$q="INSERT INTO m_fat(Name,UserID,IsRole,UserAccess,ServiceList,RechargeGateway,SMSGateway,GeneralSettings,RechargePermission,DistributorMargin,NetworkManagement,Vendor,VendorPayment,PaymentTransfer,PaymentCollection,BankDetails,MNPSettings,AutoRechargeSettings,ComplaintRequest,PendingRequest,SMSOffer,WebOffer,IncentiveOffer,MoveUser,RechargeAmountSettings,ManageTransaction,LoginSettings,GovernmentHolidays,Recharge,PaymentReport,PaymentCollectionReport,RechargeReport,TransactionReport,CreatedDate,CreatedBy,ModifiedBy) 
		VALUE('$fatObj->Name','$fatObj->UserID','$fatObj->IsRole','$fatObj->UserAccess','$fatObj->ServiceList','$fatObj->RechargeGateway','$fatObj->SMSGateway','$fatObj->GeneralSettings','$fatObj->RechargePermission','$fatObj->DistributorMargin','$fatObj->NetworkManagement','$fatObj->Vendor','$fatObj->VendorPayment','$fatObj->PaymentTransfer','$fatObj->PaymentCollection','$fatObj->BankDetails','$fatObj->MNPSettings','$fatObj->AutoRechargeSettings','$fatObj->ComplaintRequest','$fatObj->PendingRequest','$fatObj->SMSOffer','$fatObj->WebOffer','$fatObj->IncentiveOffer','$fatObj->MoveUser','$fatObj->RechargeAmountSettings','$fatObj->ManageTransaction','$fatObj->LoginSettings','$fatObj->GovernmentHolidays','$fatObj->Recharge','$fatObj->PaymentReport','$fatObj->PaymentCollectionReport','$fatObj->RechargeReport','$fatObj->TransactionReport','$fatObj->CreatedDate','$fatObj->CreatedBy','$fatObj->ModifiedBy')";
		//echo $q;
		$res=$this->db->insert($q);
		//echo 'result='.$res;
		return $res;
	}
	function updateFat($bFat){
		$q="UPDATE m_fat SET UserAccess='$bFat->UserAccess',ServiceList='$bFat->ServiceList',RechargeGateway='$bFat->RechargeGateway',SMSGateway='$bFat->SMSGateway',GeneralSettings='$bFat->GeneralSettings',RechargePermission='$bFat->RechargePermission',DistributorMargin='$bFat->DistributorMargin',NetworkManagement='$bFat->NetworkManagement',Vendor='$bFat->Vendor',VendorPayment='$bFat->VendorPayment',PaymentTransfer='$bFat->PaymentTransfer',PaymentCollection='$bFat->PaymentCollection',BankDetails='$bFat->BankDetails',MNPSettings='$bFat->MNPSettings',AutoRechargeSettings='$bFat->AutoRechargeSettings',ComplaintRequest='$bFat->ComplaintRequest',PendingRequest='$bFat->PendingRequest',SMSOffer='$bFat->SMSOffer',WebOffer='$bFat->WebOffer',IncentiveOffer='$bFat->IncentiveOffer',MoveUser='$bFat->MoveUser',RechargeAmountSettings='$bFat->RechargeAmountSettings',ManageTransaction='$bFat->ManageTransaction',LoginSettings='$bFat->LoginSettings',GovernmentHolidays='$bFat->GovernmentHolidays',Recharge='$bFat->Recharge',PaymentReport='$bFat->PaymentReport',PaymentCollectionReport='$bFat->PaymentCollectionReport',RechargeReport='$bFat->RechargeReport',TransactionReport='$bFat->TransactionReport' WHERE UserID='$bFat->UserID'";
		//echo $q;
		$res=$this->db->execute($q);
		return $res;
	}
}
?>