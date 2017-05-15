<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_generalsettings.php';
class d_generalsettings{
	/* private $db;
	private $me;
	function __construct($mysqlObj, $me){
		$this->db = $mysqlObj;
	} */
	private $db;
	var $me;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		//$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function get(){
		$q="SELECT * FROM m_generalsettings where Active='1'";
		//echo $q;
		$res=$this->db->selectArray($q,'e_generalsettings');
		//return $res;
		return count($res)>0 ? $res[0] : $res;
	}
	function updateUserSettings($clientLimit,$distributorFee,$subDistributorFee,$retailerFee){
		$q="UPDATE m_generalsettings SET ClientLimit='$clientLimit', DistributorFee='$distributorFee', SubDistributorFee='$subDistributorFee', RetailerFee='$retailerFee' where Active='1'";
		//echo $q;
		$res=$this->db->execute($q);
		return $res;
	}
	//Update for Fees
	function updateFees($feesObj){
		try{
			$feesObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET DistributorFee='$feesObj->DistributorFees', SubDistributorFee='$feesObj->SubDistributorFees', RetailerFee='$feesObj->RetailerFees', ModifiedBy='$feesObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			//echo '<br/> Query= '.$q;
			$res=$this->db->execute($q);
			//echo '<br/> Result= '.$res;
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	//Update for Recharge Amount
	function updateRCAmt($rcamtObj){
		try{
			$rcamtObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET RA_MinAmt='$rcamtObj->RA_MinAmt', RA_MaxAmt='$rcamtObj->RA_MaxAmt', ModifiedBy='$rcamtObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	//Update for Transfer Amount
	function updateTRAmt($tramtObj){
		try{
			$tramtObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET TA_MinAmt='$tramtObj->TA_MinAmt', TA_MaxAmt='$tramtObj->TA_MaxAmt', ModifiedBy='$tramtObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	//Update for DTH Amount
	function updateDTHAmt($dthamtObj){
		try{
			$dthamtObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET DTH_MinAmt='$dthamtObj->DTH_MinAmt', DTH_MaxAmt='$dthamtObj->DTH_MaxAmt', ModifiedBy='$dthamtObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for User Balance Amount
	function updateUserBalance($userbalanceObj){
		try{
			$userbalanceObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET UB_Distributor_AlertEnable='$userbalanceObj->UB_Distributor_AlertEnable', UB_Distributor_MinAmt='$userbalanceObj->UB_Distributor_MinAmt', UB_Distributor_MaxAmt='$userbalanceObj->UB_Distributor_MaxAmt', UB_SubDistributor_AlertEnable='$userbalanceObj->UB_SubDistributor_AlertEnable', UB_SubDistributor_MinAmt='$userbalanceObj->UB_SubDistributor_MinAmt',UB_SubDistributor_MaxAmt='$userbalanceObj->UB_SubDistributor_MaxAmt',UB_Retailer_AlertEnable='$userbalanceObj->UB_Retailer_AlertEnable', UB_Retailer_MinAmt='$userbalanceObj->UB_Retailer_MinAmt', UB_Retailer_MaxAmt='$userbalanceObj->UB_Retailer_MaxAmt', ModifiedBy='$userbalanceObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for SMS Cost
	function updateSMSCost($smscostObj){
		try{
			$smscostObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET SC_FirstSMS_Cost='$smscostObj->SC_FirstSMS_Cost', SC_FirstSMS_Enable='$smscostObj->SC_FirstSMS_Enable', SC_FailedRecharge_Cnt='$smscostObj->SC_FailedRecharge_Cnt', SC_FailedRecharge_Cost='$smscostObj->SC_FailedRecharge_Cost', SC_OfferSMS_Cnt='$smscostObj->SC_OfferSMS_Cnt', SC_OfferSMS_Cost='$smscostObj->SC_OfferSMS_Cost', SC_OTP_Cnt='$smscostObj->SC_OTP_Cnt', SC_OTP_Cost='$smscostObj->SC_OTP_Cost', ModifiedBy='$smscostObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for SMS Setting
	function updateSMSSetting($smssettingObj){
		try{
			$smssettingObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET SS_Success_Msg='$smssettingObj->SS_Success_Msg', SS_Failed_Msg='$smssettingObj->SS_Failed_Msg', SS_Suspense_Msg='$smssettingObj->SS_Suspense_Msg', SS_AfterSuspence_Msg='$smssettingObj->SS_AfterSuspence_Msg', SS_Time_Delay='$smssettingObj->SS_Time_Delay',ModifiedBy='$smssettingObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for Recharge Setting
	function updateRCSetting($rcsettingObj){
		try{
			$rcsettingObj->ModifiedBy = $this->me->UserID;
			$q="UPDATE m_generalsettings SET RS_SmNo_SmAmt_Delay='$rcsettingObj->RS_SmNo_SmAmt_Delay', RS_SmNo_DiffAmt_Delay='$rcsettingObj->RS_SmNo_DiffAmt_Delay', RS_MNP_AutoRC_Enable='$rcsettingObj->RS_MNP_AutoRC_Enable', RS_OTPRC_Enable='$rcsettingObj->RS_OTPRC_Enable', ModifiedBy='$rcsettingObj->ModifiedBy' WHERE `GeneralSettingsID`=1 ";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	function updateServiceProblem($serviceProblemMsgCur){
		$q="UPDATE m_generalsettings SET ServiceProblemMsgPrev=ServiceProblemMsgCur, ServiceProblemMsgCur='$serviceProblemMsgCur' where Active='1'";
		//echo $q;
		$res=$this->db->execute($q);
		return $res;
	}
}
?>