<?php
//include_once APPROOT_URL.'/Resource/Generalsettings.php';
include_once APPROOT_URL.'/Database/d_generalsettings.php';
include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/General/general.php';
class b_generalsettings{
	private $filename='b_generalsettings';
	var $dGsObj;
	private $me;
	private $lang;
	private $bUserObj;
	function __construct($me,$mysqlObj,$lang){
		$this->me=$me;
		$this->lang=$lang;
		$this->dGsObj=new d_generalsettings($me, $mysqlObj);
		$this->bUserObj = new b_users($me,$mysqlObj,"");
	}
	
	function get(){
		$gsArr = $this->dGsObj->get();
		//Override general settings by user values
		//echo "<br/> logged in userDetails=".json_encode($this->me);
		//echo "<br/ gs->DistributorFee(Before)=".$gsArr->DistributorFee;
		
		$admin = $this->bUserObj->getByID(1);
		if($admin){
			$gsArr->DistributorFee=$admin->DistributorFee;
			$gsArr->SubDistributorFee=$admin->MandalFee;
			$gsArr->RetailerFee=$admin->RetailerFee;
		}
		//echo "<br/ ->me->DistributorFee=".$this->me->DistributorFee;
		//echo "<br/ gs->DistributorFee(After)=".$gsArr->DistributorFee;
		return $gsArr;
	}
	function updateUserSettings($clientLimit,$distributorFee,$subDistributorFee,$retailerFee){
		$res = $this->dGsObj->updateUserSettings($clientLimit,$distributorFee,$subDistributorFee,$retailerFee);
		return $res;
	}
	function updateServiceProblem($serviceProblemMsgCur){
		$res = $this->dGsObj->updateServiceProblem($serviceProblemMsgCur);
		return $res;
	}
	
	//Update for Fees
	function UpsertFees($feesObj){
		try{	
			$resultObj = new httpresult();
			//$res = $this->dGsObj->updateFees($feesObj);
			$res = $this->bUserObj->updateAdminUserFee($feesObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for Recharge Amount
	function UpsertRCAmt($rcamtObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateRCAmt($rcamtObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	//Update for Transfer Amount
	function UpsertTRAmt($tramtObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateTRAmt($tramtObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for DTH Amount
	function UpsertDTHAmt($dthamtObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateDTHAmt($dthamtObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	//Update for Payment Amount
	function UpsertPAYAmt($payamtObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updatePAYAmt($payamtObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for User Balance Amount
	function UpsertUserBalance($userbalanceObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateUserBalance($userbalanceObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update for SMS Cost
	function UpsertSMSCost($smscostObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateSMSCost($smscostObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;

		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update SMS SEtting 
	function UpsertSMSSetting($smssettingObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateSMSSetting($smssettingObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;
			
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
	//Update Recharge SEtting 
	function UpsertRCSetting($rcsettingObj){
		try{	
			$resultObj = new httpresult();
			$res = $this->dGsObj->updateRCSetting($rcsettingObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->message=$this->lang['success'];
			}else{
				$resultObj->message=$this->lang['failed'];
			}
			return $resultObj;
			
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	
}
/* $obj=new b_generalsettings($mysqlObj);
$r = $obj->get();
if(count($r)==1)
echo json_encode($r);
else echo 'smnt wrng'; */
?>