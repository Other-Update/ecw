<?php
include_once APPROOT_URL.'/Business/b_users.php';
include_once APPROOT_URL.'/Database/d_request.php';
include_once APPROOT_URL.'/Database/d_recharge.php';
include_once APPROOT_URL.'/Database/d_transaction.php';
include_once APPROOT_URL.'/Business/b_automnp.php';
include_once APPROOT_URL.'/Business/b_service.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
include_once APPROOT_URL.'/Business/b_servicepermission.php';
include_once APPROOT_URL.'/Business/b_servicepermissionassign.php';
include_once APPROOT_URL.'/Business/b_rcamountsetting.php';
include_once APPROOT_URL.'/Business/b_rcusergateway.php';
include_once APPROOT_URL.'/Business/b_rcgateway.php';
include_once APPROOT_URL.'/Business/b_http.php';
include_once APPROOT_URL.'/Business/b_sms.php';

include_once APPROOT_URL.'/General/general.php';
class b_recharge{
	var $fileName="b_recharge.php";
	var $me;
	var $mysql;
	var $lang;
	var $langSMS;
	var $langAPI;
	var $dObj;//Database Recharge
	var $bUserObj;
	var $bGsObj;//General Settings
	var $bServiceObj;//Service
	var $bSPObj;//Service Permission
	var $bSPAObj;//Service Permission Assign
	var $bRASObj;//Recharge Amount Settings
	var $bRCUGObj;//Recharge User Gateway 
	var $bRCGObj;//Recharge Gateway 
	var $bHttpObj;//for gateway request
	var $bSMSObj;//Sending sms
	var $com;//General.php
	var $bReqObj;//Request Obj
	var $bTransObj;//Transaction object
	var $dReqObj;
	
	var $service;
	var $serviceCodeType;//0-None,1-Recharge,2-Topup
	var $serviceCode;
	var $generalSettings;
	var $rechargeGateway;//Final rc gateway
	var $automnp;//For network provider name
	
	var $rcDeductionAmnt;
	
	var $resultIsSuccess;
	var $resultMessage="";
	var $resultSmsMessage="";
	var $resultStatus;
	var $resultCode;
	var $isSendSms=1;
	function __construct($thisUser,$mysqlObj,$lang,$langSMS,$langAPI,$gs){
		//echo "<br/> b_recharge constructor. thisUser=".json_encode($thisUser);
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->langSMS=$langSMS;
		$this->langAPI=$langAPI;
		$this->mysql=$mysqlObj;
		$this->dObj=new d_recharge($mysqlObj);
		$this->bUserObj = new b_users($thisUser,$mysqlObj,"");
		//$this->bGsObj = new b_generalsettings($thisUser,$mysqlObj,"");
		$this->generalSettings = $gs;
		$this->bServiceObj =new b_service($this->me,$this->mysql,"");
		$this->bSPObj = new b_servicepermission($thisUser,$mysqlObj,"");
		$this->bSPAObj = new b_servicepermissionassign($thisUser,$mysqlObj,"");
		$this->bRASObj = new b_rcamountsetting($thisUser,$mysqlObj,"");
		$this->bRCUGObj = new b_rcusergateway($thisUser,$mysqlObj,"");
		$this->bRCGObj = new b_rcgateway($thisUser,$mysqlObj,"");
		$this->bHttpObj = new b_http($thisUser,$mysqlObj,"");
		$this->bSMSObj = new b_sms($thisUser,$mysqlObj,$this->bUserObj,"","",$this->bHttpObj);
		$this->bTransObj = new d_transaction($mysqlObj);
		$this->com = new common();
		$this->bReqObj = null;
		$this->dReqObj = new d_request($mysqlObj);
		
		$this->loadData();
	}
	function getByRequestID($reqID){
		$res = $this->dObj->getByRequestID($reqID);
		if(count($res)>0) return $res[0];
		else return null;
	}
	function getRechargeObjByValues($respRcID,$respOpTransID,$respOpMsg,$respBal,$inputAsIs,$receivedDT){
		$this->RechargeID = $respRcID;
		$this->RequestID = $respRcID;
		$this->ResponseAsIs = $inputAsIs;
		$this->RcResponse = $respOpMsg;
		$this->RcResReceivedDateTime = $receivedDT;
		$this->RcResOpTransID = $respOpTransID;
		return $this;
	}
	function getRechargeReport_DT_NIU($userId, $mobile, $fromDate, $toDate,$isWebservice){
		return $this->dObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate,$isWebservice);
	}
	
	function getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $limit, $isDataTable){
		return $this->dObj->getRechargeReport_DT($userId, $mobile, $fromDate, $toDate, $limit, $isDataTable);
	}
	
	function getCurrentRechargeReport_DT(){
		return $this->dObj->getCurrentRechargeReport_DT();
	}

	function loadData(){
		//$this->generalSettings = $this->bGsObj->get();
		//echo json_encode($generalSettings);
		//echo json_encode($this->generalSettings);
	}
	function updateMessageVariables($replaceTo,$replaceWith){
		$this->resultSmsMessage = str_replace($replaceTo,$replaceWith,$this->resultSmsMessage);
		$this->resultMessage = str_replace($replaceTo,$replaceWith,$this->resultMessage);
	}
	function findDTHCode($targetNumber,$codeGiven,$isForAutoSelect=false){
		//echo "<br/> processRecharge. targetNumber=".$targetNumber.", codeGiven=".$codeGiven;
		
		//No need to check starting with condition if code is given.
		//This is to accept new number format when Network provider adds new format.
		function isStartingWith($number,$startsWith){
			return (strpos($number,$startsWith) === 0);
		}
		function isAIRTELDTH($number,$codeGiven){
			//echo "<br/> This is airtel DTH";
			if($codeGiven!="") return strlen($number)==10;
			else return isStartingWith($number,'3') && strlen($number)==10;
		}
		function isBIGTV($number,$codeGiven){
			if($codeGiven!="") return strlen($number)==12;
			else return ((isStartingWith($number,'200') || isStartingWith($number,'201')) && strlen($number)==12);
		}
		function isDISHTV($number,$codeGiven){
			if($codeGiven!="") return strlen($number)==11;
			else return ((isStartingWith($number,'01') || isStartingWith($number,'02') || isStartingWith($number,'03')) && strlen($number)==11);
		}
		function isSUNDIRECT($number,$codeGiven){
			if($codeGiven!="") return strlen($number)==11;
			else return ((isStartingWith($number,'4') || isStartingWith($number,'1')) && strlen($number)==11);
		}
		function isTATASKY($number,$codeGiven){
			if($codeGiven!="") return strlen($number)==10;
			else return ((isStartingWith($number,'1')) && strlen($number)==10);
		}
		function isVIDEOCOND2H($number,$codeGiven){
			$isStartsWith1to9=(isStartingWith($number,'1') || isStartingWith($number,'2') || isStartingWith($number,'3') || isStartingWith($number,'4') || isStartingWith($number,'5') || isStartingWith($number,'6') || isStartingWith($number,'7') || isStartingWith($number,'8') || isStartingWith($number,'9') );
			$isLengthIs6789 = (strlen($number)==6 || strlen($number)==7 || strlen($number)==8 || strlen($number)==9);
			//echo "<br/>isStartsWith1to9=".$isStartsWith1to9;
			//echo "<br/>isEndsWith6789=".$isEndsWith6789;
			
			if($codeGiven!="") return $isLengthIs6789;
			else return $isStartsWith1to9 && $isLengthIs6789;
		}
		$code ="NoDTHCode";
		if($codeGiven!=""){
			$code = $codeGiven;
			//Only check number of digits in case of code is given.
			switch($codeGiven){
				case "DV":
					if(!isVIDEOCOND2H($targetNumber,$codeGiven)) $code="InvalidCode";
					break;
				case "DA":
					if(!isAIRTELDTH($targetNumber,$codeGiven)) $code="InvalidCode";
					break;
				case "DS":
					if(!isSUNDIRECT($targetNumber,$codeGiven)) $code="InvalidCode";
					break;
				case "DT":
					if(!isTATASKY($targetNumber,$codeGiven)) $code="InvalidCode";
					break;
				case "DB":
					if(!isBIGTV($targetNumber,$codeGiven)) $code="InvalidCode";
					break;
				case "DD":
					if(!isDISHTV($targetNumber,$codeGiven)) $code="InvalidCode";
					break;
				default:
					$code ="NoDTHCode";
					break;
			}
		}
		else if(isVIDEOCOND2H($targetNumber,$codeGiven)) $code = "DV";
		else if(isAIRTELDTH($targetNumber,$codeGiven)) $code = "DA";
		else if(isSUNDIRECT($targetNumber,$codeGiven)) $code = "DS";
		else if(isTATASKY($targetNumber,$codeGiven)) $code = "DT";
		else if(isBIGTV($targetNumber,$codeGiven)) $code = "DB";
		else if(isDISHTV($targetNumber,$codeGiven)) $code = "DD";
		//else $code = "Mobile";
		//echo ",$code=".$code;
		
		$this->serviceCode = $code;
		$isSuccess = true;
		//If not mobile the get DTH Service by code
		if($code != "NoDTHCode" && $code != "InvalidCode"){
			//echo "<br/> DTH code=".$code;
			$this->service = $this->bServiceObj->getByCode($code,3);// 3-is DTH
			if(!$this->service){
				$this->resultIsSuccess=false;
				$this->resultSmsMessage=$this->langSMS["Recharge_DTH_Not_Found"];
				$this->resultMessage=$this->langSMS["Recharge_DTH_Not_Found"];
				$this->serviceCode = "ServiceNotFound";
				$isSuccess = false;
			}
		}
		else {//if($code=="NoDTHCode" || $code=="InvalidCode"){
			$this->resultIsSuccess=false;
			$this->resultSmsMessage="No DTH code match. It may be mobile";
			$this->resultMessage="No DTH code match. It may be mobile";
			$isSuccess = false;
		}
		//$this->serviceCode = $code;
		//echo '<br/>->service='.json_encode($this->service);
		if($isForAutoSelect)
			return $this->serviceCode;
		else return $isSuccess;
	}
	function findMobileRCCode($targetNumber,$amount){
		$amObj=new b_automnp($this->me,$this->mysql,"");
		$res = $amObj->getNetwork($targetNumber,0);//0 is MNP
		//echo "b_automnp-0-=".json_encode($res);
		if(count($res)<=0){
			//echo "b_automnp-1-=".json_encode($res);
			$res = $amObj->getNetwork(substr($targetNumber,0,4),1);//1 is Auto
		}
		if(count($res)>0){
			//echo "<br/> Network=".json_encode($res[0]->Name);
			$this->automnp=$res[0];
			$serviceObj =new b_service($this->me,$this->mysql,"");
			$this->service = $serviceObj->getByNetworkProvider($res[0]->NetworkProviderID,1);// 1-is prepaid
			
			if($this->service==null){
				$this->resultIsSuccess=false;
				$this->resultCode="22";
				$this->resultSmsMessage=$this->langSMS["22"];
				$this->resultMessage=$this->langAPI["22"];
				return "NotFound";//This is a keyword
			}
			else{
				$rcORtp = $this->decideRechargeOrTopup($amount,$this->service);
				if($rcORtp==1){//1 is "Recharge"
					return $this->service->RechargeCode;
				}else if($rcORtp==2){//2 is "Topup"
					return $this->service->TopupCode;
				}else{
					$this->resultIsSuccess=false;
					// $this->resultSmsMessage=$rcORtp;//If it is not Recharge & Topup then it is a message
					return "InvalidAmount";
					//return $this->returnMessage();
				}
				//return $serviceObj->getApplicableCode($this->service);
				/* if($this->service->DefaultType==1)
					return $this->service->RechargeCode;
				else
					return $this->service->TopupCode; */
			}
			//echo json_encode($res);
		}else{
			//echo "<br/>New operator. User operator code.<br/>";
			$this->resultIsSuccess=false;
			$this->resultCode="23";
			$this->resultSmsMessage=$this->langSMS["23"];
			$this->resultMessage=$this->langAPI["23"];
			return "NewOperator";//This is a keyword
		}
	}
	function hasEnoughWallet($rechargeAmount,$deductionAmount){
		//echo "<br /><br /><u>enoughWallet</u><br />";
		$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
		$walletJson = json_decode(json_encode($wallet));
		//echo "<br/>6- Wallet=".$walletJson->Wallet.",BalanceLevel=".$walletJson->BalanceLevel;
		//echo "<br/> BL+RC amnt+ deduction=".($walletJson->BalanceLevel+$rechargeAmount+$deductionAmount);
		//echo json_encode($walletJson);
		return ($walletJson->Wallet)>=($walletJson->BalanceLevel+$rechargeAmount+$deductionAmount);
	}
	function isNetworkProblem(){
		//echo json_encode($this->service);
		return $this->service->IsProblem;
	}
	//Returning -1 indicates problem
	function getRechargeDeductions($amount){
		//echo json_encode($this->service);
		//echo "<br/><hr>getRechargeDeductions";
		$sp = $this->bSPObj->getByUserID($this->me->UserID);
		if($sp){
			//echo "<br/> this->service=".json_encode($this->service);
			//echo "<br/> this->Sp=".json_encode($sp);
			$spa = $this->bSPAObj->getBySpAndServiceID($sp->ServicePermissionID,$this->service->ServiceID);
			 /* echo $this->service->ServiceID;
			echo ",".$sp->ServicePermissionID;*/
			//echo json_encode($spa); 
			if(count($spa)<=0 || !$spa[0]->IsEnabled){
				$this->resultIsSuccess=false;
				$this->resultCode="24";
				$this->resultSmsMessage=$this->langSMS["24"];
				$this->resultMessage=$this->langAPI["24"];
				$this->updateMessageVariables("[NETWORKNAME]",$this->service->Name);
				//echo '3';
				return -1;
			}
			/* echo '<br/>,amount='.$amount;
			echo '<br/>,MinCharge='.$spa[0]->MinCharge;
			echo '<br/>,Commission='.$spa[0]->Commission.'%'; */
			$commissionRs = ($amount/100)*$spa[0]->Commission;
			$commissionRs = $commissionRs > $spa[0]->MinCharge ? $commissionRs : $spa[0]->MinCharge;
			//echo "<br/> commission=(".$commissionRs." rs)";
			return $commissionRs;
		}else{
			$this->resultIsSuccess=false;
			$this->resultCode="24";
			$this->resultSmsMessage=$this->langSMS["24"];
			$this->resultMessage=$this->langAPI["24"];
			$this->updateMessageVariables("[NETWORKNAME]",$this->service->Name);
			return -1;
		}
		return -1;
	}
	function isWithinMinMaxAmount($amount,$isDTH){
		$minAmount = $isDTH ? $this->generalSettings->DTH_MinAmt:$this->generalSettings->RA_MinAmt;
		$maxAmount = $isDTH ? $this->generalSettings->DTH_MaxAmt:$this->generalSettings->RA_MaxAmt;
		//echo "<br/>7-isWithinMinMaxAmount(Rs.".$minAmount." AND Rs.".$maxAmount.") = Yes";
		if($amount < $minAmount){
			$this->resultIsSuccess=false;
			$this->resultCode="25";
			$this->resultSmsMessage=$this->langSMS["25"];
			$this->resultSmsMessage = str_replace("[MAXMINAMOUNT]",$minAmount,$this->resultSmsMessage);
			$this->resultMessage=$this->langAPI["25"];
			$this->resultMessage = str_replace("[MAXMINAMOUNT]",$minAmount,$this->resultMessage);
			return false;
		}
		if($amount > $maxAmount){
			$this->resultIsSuccess=false;
			$this->resultCode="26";
			$this->resultSmsMessage=$this->langSMS["26"];
			$this->resultSmsMessage = str_replace("[MAXMINAMOUNT]",$maxAmount,$this->resultSmsMessage);
			$this->resultMessage=$this->langAPI["26"];
			$this->resultMessage = str_replace("[MAXMINAMOUNT]",$maxAmount,$this->resultMessage);
			return false;
		}
		return true;
	}
	function decideRechargeOrTopup($amount,$service){
		//echo "<br/>8-";
		$res=$this->bRASObj->getByServiceID($service->ServiceID);
		//echo json_encode($res);
		$this->serviceCodeType = 0;
		$serachString = ",".$amount.",";
		if(count($res)>0){
			//strpos returns false for not exists or it's position (may be zero)
			if (strpos($res[0]->InvalidAmount, $serachString) !== false) {
				$this->serviceCodeType = 0;
				$this->resultCode="27";
				$this->resultSmsMessage=$this->langSMS["27"];
				$this->resultMessage=$this->langAPI["27"];
				return "Invalid Amount";
			}
			if (strpos($res[0]->RCDenomination, $serachString) !== false) {
				$this->serviceCodeType = 1;
				return 1;//"Recharge";
			}
			if (strpos($res[0]->TPDenomination, $serachString) !== false) {
				$this->serviceCodeType = 2;
				return 2;//"Topup";
			}
		}
		//echo "2222222=".$service->ServiceID;
		//If nothing mathces then continue with the default from service
		if($service->DefaultType==2){//2 is Topup
			$this->serviceCodeType = 2;
			return 2;//"Topup";
		}
		else{//1 is Recharge
			$this->serviceCodeType = 1;
			return 1;//"Recharge";
		}
		/* if($rcORtp=="Error")
			 $this->resultSmsMessage="Invalid amount1";
		else
			 $this->resultSmsMessage="No Recahrge or Topup settings found"; */
	}
	function getRechargeGateway($userID,$serviceID,$amount){
		//bRCUGObj
		//$serviceID=12;
		//echo "<br/>getRechargeGateway serviceID=".$serviceID.", userID=".$userID.",amount=".$amount;
		$res = $this->bRCUGObj->getAssignedDetailsByUserAndService($userID,$serviceID);
		//echo "<br/>9-RechargeGateway = ".json_encode($res);
		if(count($res)<=0){
			$this->resultIsSuccess=false;
			$this->resultCode="28";
			$this->resultSmsMessage=$this->langSMS["28"];
			$this->resultMessage=$this->langAPI["28"];
			return false;
		}
		$gatewayID = $res[0]->PrimaryGateway;
		//If amount is in Amount column then it is invalid for primary gateway. So choose secondary gateway
		//echo "<br/>strpos=".strpos($res[0]->Amount, $amount);
		if (strpos($res[0]->Amount, $amount) !== false)
			$gatewayID = $res[0]->SecondaryGateway;
		//echo "<br/> selected gateway id=".$gatewayID;
		//echo "<br/> selected serviceID=".$serviceID;
		$gatewayRes = $this->bRCGObj->getByIdAndService($gatewayID,$serviceID);
		
		//echo "<br/>gatewayRes = ".json_encode($gatewayRes);
		if(count($gatewayRes)<=0){
			$this->resultIsSuccess=false;
			$this->resultCode="29";
			$this->resultSmsMessage=$this->langSMS["29"];
			$this->resultMessage=$this->langAPI["29"];
			return false;
		}
		$this->rechargeGateway = json_decode(json_encode($gatewayRes[0]));//IDK why we need to encode and decode. But it is working in this way only
		//echo "<br/>9-".json_encode($this->rechargeGateway);
		//echo "<br/>9- rc gateway =".$this->rechargeGateway->URL." , ".$this->rechargeGateway->Code;
		return true;
		//$this->service->ServiceID
	}
	////$this->sendResponseSMS($userMobile,$rcObj->ReachargeNo,$rcObj->Status,$rcObj->RechargeID,"t_request");
	//function sendResponseSMS($userMobile,$targetMobile,$ecwStatus,$refID,$refTable){
	function sendResponseSMS($userMobile,$rcObj,$refTable){
		//echo "<br/><hr/>sendResponseSMS SMS<br/> ";
		//$isSendSms = 0;
		//$this->resultStatus = $rcObj->Status = 2;
		//echo "<br/>,ecwStatus=".$rcObj->Status;
		switch($rcObj->Status){
			case 1://Pending
				//No need to send sms in case of Pending response
				$this->isSendSms = 0;
				break;
			case 2://Suspense
				//If control is coming here means it is API response. So conside rit as success
				$rcObj->Status=3;
				if($this->generalSettings->SS_Success_Msg){
					/* $this->resultCode="210";
					 $this->resultSmsMessage = $this->langSMS["210"].$rcObj->ReachargeNo.". Amount rs.".$rcObj->Amount; */
					
					$this->resultCode="211";
					$this->resultSmsMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langSMS["211"]);
					$this->resultMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langAPI["211"]);
					 $this->updateMessageVariables("[RECHARGEAMOUNT]",$rcObj->Amount);
					 $this->updateMessageVariables("[NETWORKNAME]",$rcObj->NetworkProviderName);
					 $this->updateMessageVariables("[RECHARGENUMBER]",$rcObj->ReachargeNo);
					 $this->updateMessageVariables("[TRANSACTIONID]",$rcObj->RequestID);
					
					$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
					//echo "<br/> wallet=".json_encode($wallet->Wallet);
					$walletJson = json_decode(json_encode($wallet));
					 $this->updateMessageVariables("[WALLETBALANCE]",$walletJson->Wallet);
					//$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
					//$this->resultSmsMessage = $msg;
				}else{
					$this->resultIsSuccess=true;
					$this->isSendSms="0";
				}
				break;
			case 3://Success
				if($this->generalSettings->SS_Success_Msg){
					$this->resultCode="211";
					// $this->resultSmsMessage = $this->langSMS["211"].$rcObj->ReachargeNo.". Amount rs.".$rcObj->Amount;
					$this->resultSmsMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langSMS["211"]);
					$this->resultMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langAPI["211"]);
					 $this->updateMessageVariables("[NETWORKNAME]",$rcObj->NetworkProviderName);
					 $this->updateMessageVariables("[RECHARGENUMBER]",$rcObj->ReachargeNo);
					 $this->updateMessageVariables("[TRANSACTIONID]",$rcObj->RequestID);
					
					$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
					//echo "<br/> wallet=".json_encode($wallet);
					$walletJson = json_decode(json_encode($wallet));
					 $this->updateMessageVariables("[WALLETBALANCE]",$walletJson->Wallet);
					//echo $this->resultMessage;
					//$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
					//$this->resultSmsMessage = $msg;
				}else{
					$this->resultIsSuccess=true;
					$this->isSendSms="0";
				}
				break;
			case 4://Failure
				if($this->generalSettings->SS_Failed_Msg){
					$this->resultCode="212";
					// $this->resultSmsMessage = $this->langSMS["212"].$rcObj->ReachargeNo.". Amount rs.".$rcObj->Amount;
					$this->resultSmsMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langSMS["212"]);
					$this->resultMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langAPI["212"]);
					 $this->updateMessageVariables("[NETWORKNAME]",$rcObj->NetworkProviderName);
					 $this->updateMessageVariables("[RECHARGENUMBER]",$rcObj->ReachargeNo);
					 $this->updateMessageVariables("[TRANSACTIONID]",$rcObj->RequestID);
					
					//echo "<br/> User id=".$this->me->UserID;
					$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
					$walletJson = json_decode(json_encode($wallet));
					//echo "<br/> wallet=".json_encode($wallet);
					 $this->updateMessageVariables("[WALLETBALANCE]",$walletJson->Wallet);
					//$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
					//$this->resultSmsMessage = $msg;
				}else{
					$this->resultIsSuccess=true;
					$this->isSendSms="0";
				}
				break;
			default:
				//echo "<br/> No matching status";
				break;
		}
		$res = $this->dObj->updateRechargeStatus($rcObj);
		/* echo "<br/>isSendSms=".$this->isSendSms;
		echo "<br/>resultSmsMessage=". $this->resultSmsMessage;
		echo "<br/>message=". $this->resultMessage;
		echo "<br/>targetMobile=".$rcObj->ReachargeNo;
		echo "<br/>userMobile=".$userMobile; */
		/*if($isSendSms){
			$smsRes = $this->bSMSObj->sendSMS($userMobile,$targetMobile,$message,"0",$refID,$refTable,"Recharge");
			
			echo "<br/>Final smsRes=".json_encode($smsRes);
		}else 
			echo "<br/>Not sending SMS";*/
	}
	function completeRecharge($userMobile,$rcObj,$isImmediateUpdate){
		//echo "<br/>completeRecharge=".json_encode($rcObj);
		//$rcObj->RechargeID this should be request ID
		/* echo "<br/>isImmediateUpdate=".$isImmediateUpdate;
		echo "<br/> before sending sms call status=".$rcObj->Status; */
		if(($isImmediateUpdate && $rcObj->Status==4) || (!$isImmediateUpdate)){	//$this->sendResponseSMS($userMobile,$rcObj->ReachargeNo,$rcObj->Status,$rcObj->RechargeID,"t_request");
			//if(!$isImmediateUpdate){
				//echo "<br/> a1111".json_encode($this->bReqObj);
				$this->bReqObj->Status = $rcObj->Status;
				//echo "<br/> s1111".json_encode($this->bReqObj);
				$this->dReqObj->updateStatus($this->bReqObj);
			$this->sendResponseSMS($userMobile,$rcObj,"t_request");
			//}
		}
		else{
			//echo "Test;";
			$res = $this->dObj->updateRechargeStatus($rcObj);
			//This is a pending/suspense of immediate response
			//Don't need to send SMS now. SMS will be sent afte getting original response
			$this->resultCode="210";
			$this->resultIsSuccess=true;
			$this->isSendSms=0;
			
			$this->resultSmsMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langSMS[$this->resultCode]);
			$this->resultMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langAPI[$this->resultCode]);
			 $this->updateMessageVariables("[NETWORKNAME]",$rcObj->NetworkProviderName);
			 $this->updateMessageVariables("[RECHARGENUMBER]",$rcObj->ReachargeNo);
			 $this->updateMessageVariables("[TRANSACTIONID]",$rcObj->RequestID);
					 
			//echo "<br/> 432=".json_encode($rcObj);
			// $this->resultSmsMessage = $this->langSMS["210"].$rcObj->ReachargeNo.". Amount is ".$rcObj.Amount;
		}
	}
	/*function completeRecharge($rechargeID,$requestID,$respMsg,$respStatus,$ecwStatus,$targetMobile){
		//TODO : Send SMS
		//echo "<br/> completeRecharge. requestID=".$requestID;
		//TODO:RequesterID should be with proper RechargeID once Stored procedure returns the proper inserted ID
		$res = $this->dObj->updateRechargeStatus($requestID,$ecwStatus,$respMsg);
		//echo "<br/> updateRechargeStatus=".json_encode($res);
		//$this->sendResponseSMS($targetMobile,$ecwStatus);
	}*/
	//First update after insert with all proper details
	function updateRequest($reqObj){
		$reqObj->Remark = "Recharge for ".$reqObj->RequesterMobile;
		$reqObj->RequestType = "RECHARGE";
		$res = $reqObj->update($reqObj);
		//echo "<br/>".json_encode($reqObj);
		//echo "<br/> Updated=".$res;
	}
	//Immediate after submission to API
	function updateApiImmediateResponse($rechargeID,$reqObj,$respCode,$respDetails){
		//First reply to user in case of immediate fail
		/* echo "<br/><hr/><updateApiImmediateResponse<br/> ";
		echo "<br/>1updateApiImmediateResponse=".$respCode;
		echo "<br/>2ecwStatus=".$respDetails->ECWStatus; */
		$rcObj=$this->getRechargeObjByValues($reqObj->RequestID,"Not received","Not received","",$respCode,"");
		//echo "<br/><hr/> reqObj obj=".$reqObj->RequesterMobile;
		
		$this->addErrorlog("updateApiImmediateResponse",$respDetails->ECWStatus,"ECWStatus","t_request",$reqObj->RequestID,"Before updating status");
		
		$rcObj->Status = $respDetails->ECWStatus;
		$rcObj->RcResponse = $respDetails->Description;
		$rcObj->ResponseAsIs = $respCode;
		$rcObj->ReachargeNo = $reqObj->TargetNo;
		$rcObj->Amount = $reqObj->TargetAmount;
		//echo "<br/> reqOj=".json_encode($reqObj);
		$rcObj->NetworkProviderName = $this->automnp==null? $this->service->Name : $this->automnp->Name;
		$this->completeRecharge($reqObj->RequesterMobile,$rcObj,true);
		//echo "start.".strlen($rcObj->NetworkProviderName).".end";
		//$respDetails->ECWStatus=4;
		if($respDetails->ECWStatus==4){
			//echo "<br/>completeRecharge-Rever wallet";
			//getRechargeObjByValues($respRcID,$respOpTransID,$respOpMsg,$respBal,$inputAsIs,$receivedDT){
			//$this->completeRecharge($reqObj->TargetNo,$rcObj,true);
			$this->resultIsSuccess=false;
			$this->resultCode="212";
			$this->isSendSms="1";
			
			$this->resultSmsMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langSMS["212"]);
			$this->resultMessage = str_replace("[RECHARGEAMOUNT]",$rcObj->Amount,$this->langAPI["212"]);
			 $this->updateMessageVariables("[NETWORKNAME]",$rcObj->NetworkProviderName);
			 $this->updateMessageVariables("[RECHARGENUMBER]",$rcObj->ReachargeNo);
			 $this->updateMessageVariables("[OPERATORTRANSACTIONID]",$rcObj->RequestID);
			
			$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
			$walletJson = json_decode(json_encode($wallet));
			 $this->updateMessageVariables("[WALLETBALANCE]",$walletJson->Wallet);
			return $this->returnMessage();
		}else{
			//For immediate update dont send sms for statuses other than failure
			$this->isSendSms=0;
			$this->resultIsSuccess=true;
			$this->resultSmsMessage=$this->langSMS["210"];
			$this->resultMessage=$this->langAPI["210"];
			//This is to update only the DevInfo
			//Actually this update is not required. Avoid this update to improve performance
			/* $reqObj->DevInfo = "Immediate API response is ".$respCode;
			$res = $reqObj->updateApiImmediateResponse($reqObj,$respCode); */
			return $this->returnMessage();
		}
		
		//echo "<br/><hr/><updateApiImmediateResponse ><br/>";
		/* $this->resultIsSuccess=true;
		$this->isSendSms=0;
		$this->resultSmsMessage=$this->langSMS["210"];
		$this->resultMessage=$this->langAPI["210"];
		return $this->returnMessage();*/
		//echo "<br/>".json_encode($reqObj);
		//echo "<br/> Updated=".$res;
	}
	function doRecharge($reqObj,$amount,$decutionAmount,$tryCount){
		//echo "test";
		$this->addErrorlog("doRecharge",json_encode($reqObj),"ReqObj","t_request",$reqObj->RequestID,"Before recharge - amount=$amount,decutionAmount=$decutionAmount");
		
		//echo "<hr/><br/> doRecharge.reqObj=".json_encode($reqObj);
		if(!$this->hasEnoughWallet($amount,$decutionAmount)){
			$this->resultIsSuccess=false;
			$this->resultCode="217";
			$this->resultSmsMessage=$this->langSMS["217"];
			$this->resultMessage=$this->langAPI["217"];
			return $this->returnMessage();
		}
		//echo "<br/>Updating the request";
		$res = $this->updateRequest($reqObj);
		//echo "<br/><br/> <hr/>";
		//$reqObj->Status=2;
		//$networkName = $this->automnp==null? $this->service->Name : $this->automnp->Name;
		$networkName = $this->service->Name;
		
		$callRechargeAPI = false;
		$rcInsertedID = 0;
		$retryAddRecharge = true;
		$retryCount=0;
		while($retryAddRecharge==true && $retryCount<3){
			$retryAddRecharge=false;
			$rcInsertedID = $this->dObj->doRecharge($reqObj,$networkName);
			$currentRCObj = $this->dObj->getRechargeByRequest($reqObj);
			
			$this->addErrorlog("doRecharge",json_encode($currentRCObj),"RechargeObj","t_request",$reqObj->RequestID,"After Recharge table insert. RCObject count=".count($currentRCObj));
			
			//echo "<br/> currentRCObj=".json_encode($currentRCObj);
			//TODO: For some reason record is not getting inserted to t_recharge
			//So prevent calling RC api when failed to insert to t_recharge table
			if(count($currentRCObj)>0){
				$rcInsertedID = $currentRCObj[0]->RechargeID;
				$callRechargeAPI = true;
			}else{
				$retryAddRecharge=true;
				$retryCount++;
			}
		}
		$this->addErrorlog("doRecharge",json_encode($currentRCObj),"RechargeObj","t_request",$reqObj->RequestID,"After Recharge table insert. callRechargeAPI=".$callRechargeAPI);
		
		$transactionID = 0;
		if($rcInsertedID>0){
			$transactionID = $this->bTransObj->addForRecharge($reqObj,$rcInsertedID);
		}
		
		$this->addErrorlog("doRecharge",json_encode($currentRCObj),"RechargeObj","t_request",$reqObj->RequestID,"After Transaction table insert. Trans id =".$transactionID);
		
		//if($rcInsertedID>0) $callRechargeAPI=true;
		//echo "<br/> Recharge Req ID=".$reqObj->RequestID;
		//echo "<br/>transactionID.".$transactionID;
		//echo "<br/>callRechargeAPI.".$callRechargeAPI;
		
		/* if($reqObj->TargetAmount>10){
			
			$this->resultIsSuccess=false;
			 $this->resultSmsMessage="Temporary check. Enter less than 10rs";
			echo "<br/><u style='color:red;'><b>". $this->resultSmsMessage."</b></u>";
			return $this->returnMessage();
		} */
		//$this->rechargeGateway->Code;
		//http://ecworld.co/recharge.ashx?uid=[UID]&pwd=[PWD]&mobileno=[MOBILENO]&amt=[AMT]&rcode=[RCODE]&transid=[TRANSID]
		
		$isManual = $this->bRCGObj->isManualApi($this->rechargeGateway);
		//echo "<br/>isManual=".$isManual;
		if($isManual==true) $callRechargeAPI=false;
		//echo "<br/>callRechargeAPI=".$callRechargeAPI;
		$urlWithParam = $this->rechargeGateway->URL;
		//echo "<br/> Calling URL=".$urlWithParam;
		$rechargeServer = $this->mysql->configuration['rechargeserver'];
		$urlWithParam = str_replace("[UID]",$rechargeServer['uid'],$urlWithParam);
		$urlWithParam = str_replace("[PWD]",$rechargeServer['pwd'],$urlWithParam);
		$urlWithParam = str_replace("[MOBILENO]",$reqObj->TargetNo,$urlWithParam);
		$urlWithParam = str_replace("[AMT]",$reqObj->TargetAmount,$urlWithParam);
		//echo "<br/> serviceCodeType=".$this->serviceCodeType;
		$apiCode = $this->rechargeGateway->RechargeCode;
		if($this->serviceCodeType==2){
			$apiCode = $this->rechargeGateway->TopupCode;
		}
		$urlWithParam = str_replace("[RCODE]",$apiCode,$urlWithParam);
		$urlWithParam = str_replace("[TRANSID]",$reqObj->RequestID,$urlWithParam);
		//echo "<br/><br/> urlWithParam=".$urlWithParam;die;
		//echo "<br/> this->rechargeGateway=".json_encode($this->rechargeGateway);
		$retry =true;
		$respCode = 2000;
		$respDetails = "";
		$urlObj = new b_cloudapi();
		$urlObj = $this->bRCGObj->getHttpObjByRcGateway($this->rechargeGateway);
		//echo "<br/> httpObj=".json_encode($httpObj);
		
		$this->addErrorlog("doRecharge",$urlWithParam,"urlWithParam","t_request",$reqObj->RequestID,"Before while. retry=".$retry.",callRechargeAPI=".$callRechargeAPI);
		
		while($retry==true && $callRechargeAPI==true && $transactionID>0){
			
			$this->addErrorlog("doRecharge",$urlWithParam,"urlWithParam","t_request",$reqObj->RequestID,"Inside while. retry=".$retry);
			//echo "<br/>inside while";
			$start = time();// record time
			$respCode = $this->bHttpObj->HTTPGet($urlWithParam);
			//echo $respCode;
			$timeTaken = (time() - $start);
			$this->addErrorlog("doRecharge",$urlWithParam,"urlWithParam","t_request",$reqObj->RequestID,"RC API RespCode=".$respCode.",retry=$retry,timeTaken=$timeTaken");
			
			//echo "<br/>TimeTaken by HTTP(In Sec)=".$timeTaken;
			//echo "<br/>B_Recharge=".json_encode($respCode);
			//echo ($res==1206);
			$respDetails = $urlObj->getDetailsByImmediateResponseCode($respCode);
			/* echo "<br/>".json_encode($respDetails);
			echo "<br/>Description=".$respDetails->Description;
			echo "<br/>Retry=".$respDetails->Retry;
			echo "<br/>maxRetryCount=".$urlObj->maxRetryCount; */
			
			$retry=false;
			//Try agin 3 times if failed and Retry is enabled.
			if($respDetails->ECWStatus==4 && $respDetails->Retry==1 && $tryCount<3){
				$retry=true;
				$tryCount+=1;
				
				//$this->doRecharge($reqObj,$amount,$decutionAmount,$tryCount);
			}
		}
		//echo "out while";
		//echo "respCode=".$respCode;
		if($respDetails=="")
			$respDetails = $urlObj->getDetailsByImmediateResponseCode($respCode);
		//echo "<br/>Total tries=".$tryCount.". All retries completed";
		/* echo "<br/> Before updateApiImmediateResponse.";
		echo "<br/> respCode=".$respCode;
		echo "<br/> respDetails=".json_encode($respDetails); */
		//$respDetails->ECWStatus=2;
		return $this->updateApiImmediateResponse($rcInsertedID,$reqObj,$respCode,$respDetails);//$respDetails->ECWStatus);
	}
	function checkDelayWithPrevRecharge($mobile,$amount){
		//echo "<br/> Same Number Same Amount";
		$lastRecharge = $this->dObj->getByMobileAndAmount($mobile,$amount);
		//echo "<br/> checkDelayWithPrevRecharge last recharge for mobile(".$mobile.") amount(".$amount.") is =".json_encode($lastRecharge);
		$today =date('Y-m-d H:i:s');
		//echo "<br/> today=".$today;
		if(count($lastRecharge)>0){
			//echo "<br/> RcResReceivedDateTime=".$lastRecharge[0]->RcResReceivedDateTime;
			$today = new DateTime($today);
			$lastRcDateTime = new DateTime($lastRecharge[0]->RcResReceivedDateTime);
			$interval = $today->diff($lastRcDateTime);
			//echo "<br/> interval=".json_encode($interval);
			$diffDays = $interval->d;
			$diffHours = ($diffDays*24)+($interval->h);
			$diffMins = ($diffHours*60)+($interval->i);
			if($interval->s > 30) $diffMins+=1;
			//echo "<br/> diffMins=".$diffMins;
			//echo "<br/> Allowed delay mins=".json_encode($this->generalSettings->RS_SmNo_SmAmt_Delay);
			if($diffMins < $this->generalSettings->RS_SmNo_SmAmt_Delay){
				$this->resultIsSuccess=false;
				$this->resultCode="Recharge_F_SameNo_SameAmnt";
				$this->resultSmsMessage=$this->langSMS[$this->resultCode];
				$this->resultMessage=$this->langAPI[$this->resultCode];
				$this->updateMessageVariables("[DENIEDMINSDURATION]",$this->generalSettings->RS_SmNo_SmAmt_Delay);
				//$this->updateMessageVariables("[CUSTOMERMESSAGE]",$this->bReqObj->Message);
				return false;
			}
		}else{
			//echo "<br/> Same Number Diff Amount";
			$lastRecharge = $this->dObj->getByMobileAndAmount($mobile,"");
			//echo "res count=".count($lastRecharge);
			if(count($lastRecharge)>0){
				//echo "<br/> RcResReceivedDateTime=".$lastRecharge[0]->RcResReceivedDateTime;
				$today =date('Y-m-d H:i:s');
				$today = new DateTime($today);
				$lastRcDateTime = new DateTime($lastRecharge[0]->RcResReceivedDateTime);
				$interval = $today->diff($lastRcDateTime);
				//echo "<br/> interval=".json_encode($interval);
				$diffMins = ($interval->h*60)+($interval->i);
				if($interval->s > 30) $diffMins+=1;
				//echo "<br/> diffMins1=".$diffMins;
				//echo "<br/> Allowed delay mins=".json_encode($this->generalSettings->RS_SmNo_SmAmt_Delay);
				if($diffMins < $this->generalSettings->RS_SmNo_DiffAmt_Delay){
					$this->resultIsSuccess=false;
					$this->resultCode="Recharge_F_SameNo_DiffAmnt";
					$this->resultSmsMessage=$this->langSMS[$this->resultCode];
					$this->resultMessage=$this->langAPI[$this->resultCode];
					 $this->updateMessageVariables("[DENIEDMINSDURATION]",$this->generalSettings->RS_SmNo_DiffAmt_Delay);
					return false;
				}
			}
		}
		return true;
	}
	function processRecharge($reqObj,$code,$targetNumber,$amount){
	
		$amount = intval($amount);
		$this->bReqObj=$reqObj;
		$this->bSMSObj->customerInputSMS=$this->bReqObj->Message;
		//Check. Same no same amount
		//$genSettings = $bGsObj->get();
		//echo "<br/>b_recharge -processRecharge -genSettings=".json_encode($this->generalSettings);
		$isWithinWindowMins = $this->checkDelayWithPrevRecharge($targetNumber,$amount);
		if(!$isWithinWindowMins){
			return $this->returnMessage();
		}
		//Find DTH/Mobile Service code
		$isTargetDTH = false;
		if($code==""){
			$isDthFound = $this->findDTHCode($targetNumber,$code);
			//echo "<br/> serviceCode=".$this->serviceCode;
			//echo "<br/>4-is DTH=".(($isDthFound || $this->serviceCode.""=="ServiceNotFound") ?"Yes":"No");
			//echo '<br/>DTH code ='.$this->serviceCode;
			//If DTH not found then it could be mobile
			//Sometime isDthFound will be false when this->rechargeCode is NoCode. So we need to check for both
			//if(!$isDthFound && $this->serviceCode.""=="NoCode"){
			if($this->serviceCode.""=="NoDTHCode" || $this->serviceCode.""=="InvalidCode"){
				if(strlen($targetNumber)!=10){
					$this->resultIsSuccess=false;
					$this->resultCode="213";
					 $this->resultSmsMessage=$this->langSMS["213"];
					 $this->resultMessage=$this->langAPI["213"];
					return $this->returnMessage();
				}
				$this->serviceCode = $this->findMobileRCCode($targetNumber,$amount);
				
				//Convert to str and then check
				if($this->serviceCode.""=="InvalidAmount"){
					$this->resultCode="recharge_f_invalidamount";
					$this->resultIsSuccess=false;
					$this->resultSmsMessage=$this->langSMS["recharge_f_invalidamount"];
					$this->resultMessage=$this->langAPI["recharge_f_invalidamount"];
					return $this->returnMessage();
				}
				else if($this->serviceCode.""=="InvalidAmount" ||$this->serviceCode.""=="NotFound" || $this->serviceCode.""=="NewOperator"){
					$this->resultCode="23";
					$this->resultIsSuccess=false;
					$this->resultSmsMessage=$this->langSMS["23"];
					$this->resultMessage=$this->langAPI["23"];
					return $this->returnMessage();
				}
			}else if($this->serviceCode == "ServiceNotFound"){
				//$this->serviceCode is "ServiceNotFound"				
				$this->resultIsSuccess=false;
				$this->resultCode='214';
				 $this->resultSmsMessage=$this->langSMS["214"];
				 $this->resultMessage=$this->langAPI["214"];
				return $this->returnMessage();
			}
			//echo "<br/> Final RC code=".$this->serviceCode;
			//die;
			//echo "<br/>5-Mobile code=".$this->serviceCode;
		}else{
			$this->serviceCode = $code;
			$serviceObj =new b_service($this->me,$this->mysql,"");
			$this->service = $serviceObj->getByCode($code,0);//0 means no mode
			//echo "<br/> this->service=".json_encode($this->service);
			
			if($this->service==null){
				$this->resultIsSuccess=false;
				$this->resultCode="214";
				$this->resultSmsMessage=$this->langSMS["214"];
				$this->resultMessage=$this->langAPI["214"];
				return $this->returnMessage();
			}
			//echo "<br/> code=".$code;
			if($code == $this->service->TopupCode){
				$this->serviceCodeType = 2;//1 - is topup
			}else if($code == $this->service->RechargeCode){
				$this->serviceCodeType = 1;//1 - is recharge
			}else{			
				//This else case if un reachable. 
				//Bcoz we are getting service by Recharge code/ Topup code given.,
				//And checking eith the same. So this else is unreachable code.
				$this->resultIsSuccess=false;
				$this->resultCode='214';
				 $this->resultSmsMessage=$this->langSMS["214"];
				 $this->resultMessage=$this->langAPI["214"];
				return $this->returnMessage();
			}
			
			//Start - Special case for BSNL - Identification - Both Recharge and Topup code is present in service
			if($this->service->TopupCode!="" && $this->service->RechargeCode!="")
			{
				//In case of BSNL we need to decide even if they gave code to recharge
				
				$rcORtp = $this->decideRechargeOrTopup($amount,$this->service);
				if($rcORtp==1){//1 is "Recharge"
					$this->serviceCode = $this->service->RechargeCode;
				}else if($rcORtp==2){//2 is "Topup"
					$this->serviceCode = $this->service->TopupCode;
				}else{
					$this->resultIsSuccess=false;
					$this->resultCode="recharge_f_invalidamount";
					$this->resultIsSuccess=false;
					$this->resultSmsMessage=$this->langSMS["recharge_f_invalidamount"];
					$this->resultMessage=$this->langAPI["recharge_f_invalidamount"];
					return $this->returnMessage();
				}
			}
				//echo "1111111111111111";
			//End - BSML special case
			
			//echo "<br/> this->service->NetworkMode=".$this->service->NetworkMode;
			$isDthFound = $this->service->NetworkMode==3;//3 is DTH
			if($isDthFound){
				$dthCodeVerified = $this->findDTHCode($targetNumber,$code);
				//echo "<br/> dthCodeVerified=".$dthCodeVerified;
				if(!$dthCodeVerified){
					$this->resultIsSuccess=false;
					$this->resultCode="215";
					$this->resultSmsMessage=$this->langSMS["215"];
					$this->resultMessage=$this->langAPI["215"];
					return $this->returnMessage();
				}
			}
			$amObj=new b_automnp($this->me,$this->mysql,"");
			$this->automnp = $amObj->getByID($this->service->NetworkProviderID);
			//echo "<br/> this->N/W provider=".json_encode($this->automnp);
			//$this->automnp=null;
			/* if($this->automnp==null){
				$this->resultIsSuccess=false;
					$this->resultCode="216";
				 $this->resultSmsMessage=$this->langSMS["216"];
				return $this->returnMessage();
			} */
		}
		//echo "<br/> Service=".json_encode($this->service);
		//Calculate the extra amount to be deducted from wallet for the recharge
		$rcDeductionAmnt = $this->getRechargeDeductions($amount);
		//echo ".,rcDeductionAmnt=".$rcDeductionAmnt;
		if($rcDeductionAmnt<0){
			//echo "See final msg. Req Obj=".json_encode($this->automnp);
			return $this->returnMessage();
		}
		$this->rcDeductionAmnt=$rcDeductionAmnt;
		//echo ",RC deduction=".$this->rcDeductionAmnt;
		
		/* //Check wallet has enough balance by including BalanceLevel
		if(!$this->hasEnoughWallet($amount,$this->rcDeductionAmnt)){
			$this->resultIsSuccess=false;
			$this->resultCode="217";
			 $this->resultSmsMessage=$this->langSMS["217"];
			return $this->returnMessage();
		} */
		//Check for network problem for the found network for the given mobile/service code
		if($this->isNetworkProblem()){
			$this->resultIsSuccess=false;
			$this->resultCode="218";
			$this->resultSmsMessage=$this->langSMS["218"];
			$this->resultMessage=$this->langAPI["218"];
			
			$this->updateMessageVariables("[MESSAGE]",$this->generalSettings->ServiceProblemMsgCur);
			return $this->returnMessage();
		}
		
		if(!$this->isWithinMinMaxAmount($amount,$isDthFound))
			return $this->returnMessage();
		/*  */
		if(!$this->getRechargeGateway($this->me->UserID,$this->service->ServiceID,$amount)){
			return $this->returnMessage();
		}
		
		/* $this->com->put(1,"******************************");
		$this->com->put(1,"Recharge gateway =".$this->rechargeGateway->URL);
		$this->com->put(1,"Recahrge gateway code=".$this->rechargeGateway->Code);
		$this->com->put(1,"Recahrge Mobile/Dth=".$targetNumber);
		$this->com->put(1,"Recahrge Amount=".$amount);
		$this->com->put(1,"******************************"); */
		$reqObj->TargetNo=$targetNumber;
		$reqObj->TargetAmount=$amount;
		$reqObj->TotalAmount=$amount+$this->rcDeductionAmnt;
		$res = $this->doRecharge($reqObj,$amount,$this->rcDeductionAmnt,1);
		$res = json_decode(json_decode($res));
		//echo "<br/> After doRecharge res=".json_encode($res);
		//echo "Message=".$res->Message;
		$this->resultIsSuccess=$res->IsSuccess;
		$this->resultSmsMessage=$res->SmsMessage;
		$this->resultMessage=$res->Message;
		return $this->returnMessage();
	}
	function processResponse($reqObj,$rcObj,$respStatus,$apiType){	
		$this->bReqObj = $reqObj;
		//echo "<br/><hr/>< processResponse ><br/>";
		//echo "<br/> rcObj=".json_encode($rcObj); 
		//echo "<br/> reqObj=".json_encode($this->bReqObj); 
		$urlObj = '';//b_http.php
		if($apiType=="Mars"){
			$urlObj = new b_marsapi();
		}else{
			$urlObj = new b_cloudapi();//b_http.php
		}
		$respDetails = $urlObj->getDetailsByResponseCode($respStatus);
		$rcObj->Status = $respDetails->ECWStatus;
		//echo "<br/> respDetails=".json_encode($respDetails);die;
		//$targetMob = $urlObj->getTargetMobileFromResponse($rcObj->RcResponse);
		//$targetAmount = $urlObj->getTargetAmountFromResponse($rcObj->RcResponse);
		$targetMob = $this->bReqObj->TargetNo;
		$targetAmount = $this->bReqObj->TargetAmount;
		//echo "<br/> Resp targetn amnt=".$targetAmount;die;
		$rcObj->ReachargeNo=$targetMob;
		$rcObj->Amount=$targetAmount;
		//echo "<br/>Me=".json_encode($this->me);
		$this->completeRecharge($rcObj->me->Mobile,$rcObj,false);
		//echo "<br/> completeRecharge completed";
		//Once SP is returning proper ID then 2nd param will not be required
		//$this->completeRecharge($respRcID,$respRcID,$rcObj->RcResponse,$respStatus,$respDetails->ECWStatus,$targetMob);
		//echo "<br/> RcResOpTransID=".$rcObj->RcResOpTransID;
		
		$this->updateMessageVariables("[OPERATORTRANSACTIONID]",$rcObj->RcResOpTransID);
		//$this->sendResponseSMS($targetMob,$respDetails->ECWStatus,$rcObj->RechargeID,"t_recharge");
		return $this->returnMessage();
	}
	/*
	function processResponse($respRcID,$respStatus,$respOpTransID,$respOpMsg,$respBal,$inputAsIs,$receivedDT){	
		$urlObj = new b_cloudapi();//b_http.php
		$respDetails = $urlObj->getDetailsByResponseCode($respStatus);
		//echo "<br/> respDetails=".json_encode($respDetails);
		$targetMob = $urlObj->getTargetMobileFromResponse($respOpMsg);
		//Once SP is returning proper ID then 2nd param will not be required
		$this->completeRecharge($respRcID,$respRcID,$respOpMsg,$respStatus,$respDetails->ECWStatus,$targetMob);
		//echo "<br/> Response msg received=".$respOpMsg;
		$this->sendResponseSMS($targetMob,$respDetails->ECWStatus,$respRcID,"t_recharge");
	}
	*/
	function returnMessage(){
		$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
		$walletJson = json_decode(json_encode($wallet));
		//$walletJson->Wallet;
		//echo json_encode($this->bReqObj);
		if($this->isSendSms){
			//echo "<br/> Request=".json_encode($this->bReqObj);
			$this->bSMSObj->Recharge($this->bReqObj,$this->resultSmsMessage,$this->bReqObj->RequestID,$this->automnp,"t_request");
		}
		
		//Generally replace Networkname
		if($this->automnp)
			$this->resultMessage = str_replace("[NETWORKNAME]",$this->automnp->Name,$this->resultMessage);
		else
			$this->resultMessage = str_replace("[NETWORKNAME]","",$this->resultMessage);
		$this->isSendSms=0;
		$jsonStr='{"IsSuccess":'.json_encode($this->resultIsSuccess).',"Code":'.json_encode($this->resultCode).',"IsSendSMS":'.json_encode($this->isSendSms).',"Message":'.json_encode( $this->resultMessage).',"AvailableBalance":'.json_encode($walletJson->Wallet).',"SmsMessage":'.json_encode( $this->resultSmsMessage).',"RequestID":'.json_encode( $this->bReqObj->DisplayID).',"Status":'.json_encode( $this->bReqObj->Status).'}';
		
		//echo "<br/> Final JSON str=".$jsonStr;
		return json_encode($jsonStr);
	}
	
	function DeleteOldRecharges($userID,$date){
		return $this->dObj->DeleteOldRecharges($userID,$date);
	}
	function addErrorlog($fnName,$message,$deveMsg,$type,$id,$more){
		//echo "test1";
		$this->mysql->errorlog->addLog("WebserviceProcess",$this->fileName,$fnName,$message,$deveMsg,$type,$id,$more);
	}
	function echoMessage(){
		echo "<br/>resultSmsMessage=". $this->resultSmsMessage;
		echo "<br/>resultIsSuccess=".$this->resultIsSuccess;
		echo "<br/>";
	}
	function saveQueryStringTesting($fromWhere,$str){
		//echo "<br/>ssaveQueryStringTesting in b_recharge{";
		$this->dObj->saveQueryStringTesting($fromWhere,$str);
	}
}
?>