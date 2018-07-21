<?php
class e_generalsettings{
	var $GeneralSettingsID;
	var $Name;
	var $ClientLimit;
	var $DistributorFee;
	var $SubDistributorFee;
	var $RetailerFee;
	var $ServiceProblemMsgCur;
	var $ServiceProblemMsgPrev;
	//User Balance
	var $UB_Distributor_AlertEnable;
	var $UB_Distributor_MinAmt;
	var $UB_Distributor_MaxAmt;
	var $UB_SubDistributor_AlertEnable;
	var $UB_SubDistributor_MinAmt;
	var $UB_SubDistributor_MaxAmt;
	var $UB_Retailer_AlertEnable;
	var $UB_Retailer_MinAmt;
	var $UB_Retailer_MaxAmt;
	
	//SMS Cost 
	var $SC_FirstSMS_Cost;
	var $SC_FirstSMS_Enable;
	var $SC_FailedRecharge_Cnt;
	var $SC_FailedRecharge_Cost;
	var $SC_OfferSMS_Cnt;
	var $SC_OfferSMS_Cost;
	var $SC_OTP_Cnt;
	var $SC_OTP_Cost;
	
	//Recharge Setting
	var $RS_SmNo_SmAmt_Delay;
	var $RS_SmNo_DiffAmt_Delay;
	var $RS_MNP_AutoRC_Enable;
	var $RS_OTPRC_Enable; 
	
	//SMS Setting
	var $SS_Success_Msg;
	var $SS_Failed_Msg;
	var $SS_Suspense_Msg;
	var $SS_AfterSuspence_Msg;
	var $SS_Time_Delay;
	
	var $RA_MinAmt;
	var $RA_MaxAmt;
	
	var $TA_MinAmt;
	var $TA_MaxAmt;
	
	var $DTH_MinAmt;
	var $DTH_MaxAmt;
	
	var $PAY_MinAmt;
	var $PAY_MaxAmt;
	
	var $CreatedDate;
	var $CreatedBy;
	var $ModifiedDate;
	var $ModifiedBy;
	var $Active;
}?>