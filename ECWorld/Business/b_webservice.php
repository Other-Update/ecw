<?php
//include_once APPROOT_URL.'/Database/d_recharge.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/b_recharge.php';
include_once APPROOT_URL.'/Business/b_request.php';
include_once APPROOT_URL.'/Business/b_payment.php';
include_once APPROOT_URL.'/Business/b_transaction.php';
include_once APPROOT_URL.'/Business/b_generalsettings.php';
class b_webserviceOffline{
	function __construct(){}
}
class b_webserviceOnline{
	function __construct(){}
}
class b_webservice{
	var $fileName="b_webservice";
	var $me;
	var $mysql;
	var $lang;
	var $langSMS;
	var $langAPI;
	var $dObj;
	
	var $bReqObj;
	var $bUserObj;
	var $bHttpObj;//for gateway request
	var $bSMSObj;//Sending sms
	var $bPayObj;//Payment. I think it is not being used in this file - ilaiya on Apr/23
	var $bTransObj;
	var $bGsObj;//General Settings
	
	var $service;
	var $webserviceaction;
	var $resultSmsMessage="";
	var $resultMessage="";
	var $resultIsSuccess=false;
	var $smsCode = "0";//Failed to Add user
	var $isSendSMS = "1";//Send SMS by default
	var $notes = "Nothing";
	var $isDuplicateRequest=false;
	var $requestType="";//CSDAC,CDAC,EAC,RC.....
	var $processedSMSmssage = "NOTPROCESSED";
	var $allowedReqDelayInSec = 300;// 5 mins
	function __construct($thisUser,$mysqlObj,$lang,$langSMS,$langAPI){
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->langSMS=$langSMS;
		$this->langAPI=$langAPI;
		$this->mysql=$mysqlObj;
		$this->bUserObj = new b_users($thisUser,$mysqlObj,"");
		$this->bReqObj=new b_request($thisUser,$mysqlObj,$lang);
		$this->bHttpObj = new b_http($thisUser,$mysqlObj,"");
		$this->bSMSObj = new b_sms($thisUser,$mysqlObj,$this->bUserObj,$lang,$langSMS,$this->bHttpObj);
		$this->bPayObj = new b_payment($thisUser,$mysqlObj,"");
		$this->bTransObj = new b_transaction($thisUser,$mysqlObj,"");
		$this->bGsObj = new b_generalsettings($thisUser,$mysqlObj,"");
		
	}
	
	function updateMessageVariables($replaceTo,$replaceWith){
		$this->resultSmsMessage = str_replace($replaceTo,$replaceWith,$this->resultSmsMessage);
		$this->resultMessage = str_replace($replaceTo,$replaceWith,$this->resultMessage);
	}
	function getMessageFormatCode_NIU($paramCount,$param1,$param2,$param3,$param4){
			
		switch($param1){
			case "BAL"://Get Balance
				return "D1";
				break;
			case "MINI"://Get Mini Statement
				return "D2";
				break;
			case "SR"://Get Particular Mobile Number Last 3 Recharge
				return "D3";
				break;
			case "CSAC"://Create SubDistributor Account
				return "D4";
				break;
			case "CRAC"://Create Retailer Account
				return "D5";
				break;
			case "EAC"://Enable Sub Account
				return "D6";
				break;
			case "DAC"://Disable Sub Account
				return "D7";
				break;
			case "SBAL"://Get Sub Account Balance
				return "D8";
				break;
			case "FT"://Fund Transfer
				return "D10";
				break;
			case "CPN"://Change MOB Number
				return "D11";
				break;
			case "OFD"://Today Offer
				return "D12";
				break;
			case "CBAC"://CREATE COLLECTION BOY
				return "D13";
				break;
			case "CDAC"://CREATE DISTRIBUTOR
				return "D14";
				break;
			case "REV"://FUND REVERSAL
				return "D15";
				break;
			case "LOGIN"://USER DETAILS
				return "D16";
				break;
			default:
				//echo "Default SMS deduct=".$param1;
				//Some SMS formats are "<Mobile/DTM> <Amount>"
				if(is_numeric($param1)){
					//If code is not given, then there should be only two params(Mobile and amount). 3rd param shoud be empty
					if(!is_numeric($param2) || $param3!=""){
						$this->resultIsSuccess=false;
						$this->smsCode="21";
						$this->resultSmsMessage="Invalid Message Format";
						return 0;
					}
					return "RC1";
				}else{
					return "RC2";
				}
				break;
				//return 0;
		}
		$this->smsCode="21";
		echo "<br/> No format identified. Dead. getMessageFormatCode()";
		//die;
	}
	//$param2-Mobile
	//$param3-Name
	function createUser($role,$reqObj,$param1,$param2,$param3,$rndPassword){
		//echo "<br/> b_webservice. me=".json_encode($this->me);
		$newUser=new b_users($this->me,$this->mysql,"");
		$res = $newUser->addUserFromWebservice($this->me,$newUser,$reqObj,$param1,$param2,$param3,$role,$rndPassword);
		//$res = json_encode($res);
		//echo "<br/> Webservice(createUser)=".json_encode($res);
		//InsertedUserID
		$this->resultMessage = $this->resultSmsMessage="Failed to add user";
		$this->resultIsSuccess=false;
		$this->smsCode="15";
		$this->isSendSMS="1";
		$newUserID = 0;
		$failurereason = "Unknown Reason";
		if($res){
			$this->resultIsSuccess=$res->isSuccess;
			$this->smsCode=$res->code;
			$this->resultSmsMessage=$this->langSMS[$this->smsCode];//$res->message;
			$this->resultMessage = $this->langAPI[$this->smsCode];
			$this->updateMessageVariables("[MOBILENUMBER]",$param2);
			if($res->isSuccess && $res->InsertedUserID!="0"){
				$newUserID = $res->InsertedUserID;
			}
		}
		return '{"IsSuccess":"'.$res->isSuccess.'","NewUserID":"'.$newUserID.'","FailureReason":"'.$this->resultMessage.'"}';
	}
	
	function random_string($length) {
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));

		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}

		return $key;
	}
	
	function processMessageAndroid($reqObj){
		$this->bSMSObj->customerInputSMS=$reqObj->Message;
		$this->bReqObj = $reqObj;
		$isProcessed = $this->processMessage($reqObj);
		return $this->returnMessage();
	}
	function processMessage($reqObj){
		
		$param1="";//action
		$param2="";//mobile 
		$param3="";//amount 
		$param4="";//amount
		$msgArr = explode(" ", $reqObj->Message);
		if(count($msgArr)>=1) $param1=$msgArr[0];//Code
		if(count($msgArr)>=2) $param2=$msgArr[1];//Recharge
		if(count($msgArr)>=3) $param3=$msgArr[2];//Amount
		if(count($msgArr)>=4) $param4=$msgArr[3];//Amount
		//$messageFormat = $this->getMessageFormatCode(count($msgArr),$param1,$param2,$param3,$param4);;	
		//echo "<br/>message=".$reqObj->Message;
		$param1 = strtoupper($param1);
		$this->bSMSObj->updateUserMe($this->me);
		//$this->bUserObj->updateUserMe($this->me);
		switch($param1){
			case "CSDAC":
			case "CDAC":
			case "CSAC":
			case "CRAC":
				$roleID=6;
				$roleName="Retailer";
				switch($param1){
					case "CSDAC"://State Distributor(3)
						$roleID=3;
						$roleName="State Distributor";break;
					case "CDAC"://Distributor(4)
						$roleID=4;
						$roleName="Distributor";break;
					case "CSAC"://Sub-Distributor(5)
						$roleID=5;
						$roleName="Sub Distributor";break;
					case "CRAC"://Retailer (6)
						$roleID=6;
						$roleName="Retailer";break;
				}
				$this->bReqObj->TotalAmount = $this->bReqObj->TargetAmount = "0";
				$this->bReqObj->RequestType="t_user";
				$this->bReqObj->TargetNo='';//$roleID;
				$failureReason="";
				//Inc case of SMS request $reqObj->me will be empty. we cannot use that. So get user from DB always. 
				$subUser = $this->bUserObj->getByID($reqObj->UserID);
				//echo json_encode($subUser);
				if($subUser->RoleID >= $roleID){
					$failureReason = "You cannot create user with this role";
				}
				if($param3==""){
					$failureReason = "Username cannot be empty";
				}
				if($failureReason!=""){
					$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
					$walletJson = json_decode(json_encode($wallet));
					$this->smsCode="NewUserToParent_f";
					$msg = $this->langAPI[$this->smsCode];
					//echo "<br/> Create user res.=".json_encode($res);
					$msg = str_replace("[USERROLE]",$roleName,$msg);
					$msg = str_replace("[NEWUSERNAME]",$param3,$msg);
					$msg = str_replace("[FAILUREREASON]",$failureReason,$msg);
					$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
					
					$this->resultIsSuccess=false;
					$this->isSendSMS="0";
					$this->resultSmsMessage=$msg;
					$this->processedSMSmssage = $msg;
					
					$this->bSMSObj->userCreation(0,$this->bReqObj,"",$param2,$param3,$roleName,"",$failureReason);
					return false;
				}
				$rndPassword = $this->random_string(6);
				//echo "<br/> New password=".$rndPassword;
				$res = $this->createUser($roleID,$reqObj,$param1,$param2,$param3,$rndPassword);
				//echo "<br/> New password=".$rndPassword;
				//echo "<br/> Message format code CRAC. Add retailer";
				$resJson = json_decode($res);
				//echo "<br/> Create user res.=".json_encode($res);
				//echo "<br/> Create user res.=".$resJson->FailureReason;
				$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
				$walletJson = json_decode(json_encode($wallet));
				if($resJson->NewUserID){
					$this->smsCode="NewUserToParent_s";
					$msg = $this->langAPI[$this->smsCode];
					
					$msg = str_replace("[NEWUSERNAME]",$param3,$msg);
					$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
					$msg = str_replace("[NEWUSERID]",$resJson->NewUserID,$msg);
					$msg = str_replace("[USERROLE]",$roleName,$msg);
					
					//$this->resultSmsMessage=$msg;
					$this->resultIsSuccess=true;
					$this->isSendSMS="0";
					$this->resultSmsMessage=$msg;
					$this->processedSMSmssage = $msg;
					//$this->bSMSObj->sendSMS($this->bReqObj->RequesterMobile,$this->bReqObj->TargetNo,$this->bReqObj->Remark,"0","0","-","Unknown");
					$this->bSMSObj->userCreation(1,$this->bReqObj,$resJson->NewUserID,$param2,$param3,$roleName,$rndPassword,"");
					return true;
				}else{
					$this->smsCode="NewUserToParent_f";
					$msg = $this->langAPI[$this->smsCode];
					//echo "<br/> Create user res.=".json_encode($res);
					$msg = str_replace("[USERROLE]",$roleName,$msg);
					$msg = str_replace("[NEWUSERNAME]",$param3,$msg);
					$msg = str_replace("[FAILUREREASON]",$resJson->FailureReason,$msg);
					$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
					
					$this->resultIsSuccess=false;
					$this->isSendSMS="0";
					$this->resultSmsMessage=$msg;
					$this->processedSMSmssage = $msg;
					
					$this->bSMSObj->userCreation(0,$this->bReqObj,"",$param2,$param3,$roleName,$rndPassword,$resJson->FailureReason);
					return false;
				}
				break;
			case "BAL"://Balance
				$this->bReqObj->TotalAmount = $this->bReqObj->TargetAmount = "0";
				$this->bReqObj->RequestType="t_user";
				$this->bReqObj->TargetNo=$param1;
				$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
				$walletJson = json_decode(json_encode($wallet));
				
				$msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langAPI["UserBalanceDetails"]);
				$msg = str_replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
				
				//$pObj=new b_payment($userDetails->user,$mysqlObj,$lang);
				//$this->bPayObj
				$today =date('Y-m-d');
				$todayPurchase = $this->bPayObj->getTransferByDate($this->me->UserID,$today);
				$todayPurchaseAmnt = count($todayPurchase)>0?$todayPurchase[0]->Amount:0;
				//echo "<br/> todayPurchase=".json_encode($todayPurchase);
				$msg = str_replace("[TODAYPURCHASE]",$todayPurchaseAmnt,$msg);
				
				$todaySales = $this->bTransObj->getTransferSalesByDate($this->me->UserID,$today);
				//echo "<br/> todaySales=".json_encode($todaySales);
				$todaySalesAmount = count($todaySales)>0?$todaySales[0]->TotalSalesAmount:"0.00";
				$todaySalesAmount = $todaySalesAmount==null?"0.00":$todaySalesAmount;
				//echo "<br/> todaySalesAmount=".$todaySalesAmount;
				//die;
				$msg = str_replace("[TODAYSALES]",abs($todaySalesAmount),$msg);
				
				$this->resultIsSuccess = true;
				$this->smsCode="UserBalanceDetails";
				$this->isSendSMS="0";
				$this->resultSmsMessage=$msg;
				$this->processedSMSmssage = $msg;
				$this->bSMSObj->balanceDetails($this->bReqObj,$this->me,$walletJson->Wallet,$todayPurchaseAmnt,$todaySalesAmount,"UserBalanceDetails");
				return true;
				break;
			case "SBAL"://SUB ACCOUNT Balance
				$this->bReqObj->TotalAmount = $this->bReqObj->TargetAmount = "0";
				$this->bReqObj->RequestType="t_user";
				$this->bReqObj->TargetNo=$param2;
				$walletBal = 0;
				$subUserName="";
				$subUser = $this->bUserObj->getByDisplayIDByParent($this->me->UserID,$param2);
				//echo "<br/> Sub User details=".json_encode($subUser);
				if($subUser){
					$subUserName = $subUser->Name;
					$wallet = $this->bUserObj->getWalletBalance($param2,$this->me->UserID);
					$walletJson = json_decode(json_encode($wallet));
					$msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langAPI["Balance_Sub_Account"]);
					$msg = str_replace("[SUBACCOUNTID]",$param2,$msg);
					$msg = str_replace("[SUBACCOUNTNAME]",$subUserName,$msg);
					$walletBal = $walletJson->Wallet;
					$msg = str_replace("[WALLETBALANCE]",$walletBal,$msg);
					$this->resultIsSuccess = true;
					$this->smsCode="Balance_Sub_Account";
				}else{
					$msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langAPI["Sub_Account_Invalid"]);
					$msg = str_replace("[SUBACCOUNTID]",$param2,$msg);
					$msg = str_replace("[CUSTOMERMESSAGE]",$this->bSMSObj->customerInputSMS,$msg);
					$this->resultIsSuccess = true;
					$this->smsCode="Sub_Account_Invalid";
				}
				$this->bSMSObj->subAcBalanceDetails($this->bReqObj,$this->me,$param2,$subUserName,$walletBal,$this->smsCode);
				$this->isSendSMS="0";
				$this->processedSMSmssage = $msg;
				$this->resultSmsMessage=$msg;
				return true;
				break;
			case "EAC":
			case "DAC"://"DAC [UID]"="Param1 Param2"
				$this->bReqObj->TotalAmount = $this->bReqObj->TargetAmount = "0";
				$this->bReqObj->RequestType="t_user";
				$this->bReqObj->TargetNo=$param2;
				$isDisable = $param1=="DAC"?true:false;
				$subUser = $this->bUserObj->getByDisplayIDByParent($this->me->UserID,$param2);
				$msg="";
				$actionName="";
				$subUserID=$param2;
				$subUserName="";
				if($subUser){
					$subUserName=$subUser->Name;
					$res = $this->bUserObj->enableDisableByUserID($param2,$isDisable);
					$this->smsCode="EnableDisableAccount";
					if($res){
						$actionName = $param1=="DAC"?"disabled":"enabled";
						$this->resultIsSuccess = true;
						//return true;
					}else{
						$actionName = " unable to ";
						$actionName .= $param1=="DAC"?"disable":"enable";
						$this->resultIsSuccess = false;
						//return false;
					}
					//echo $actionName;
					$msg = str_replace("[SUBACCOUNTID]",$param2,$this->langAPI[$this->smsCode]);
					$msg = str_replace("[SUBACCOUNTNAME]",$subUserName,$msg);
					$msg = str_replace("[ACTION]",$actionName,$msg);
				}else{
					$this->smsCode="Sub_Account_Invalid";
					$msg = str_replace("[SUBACCOUNTID]",$param2,$this->langAPI[$this->smsCode]);
					$this->resultIsSuccess = false;
				}
				$this->isSendSMS="0";
				$this->resultSmsMessage=$msg;
				$this->processedSMSmssage = $msg;
				$this->bSMSObj->enableDisableUser($this->me,$param2,$subUserName,$actionName,$this->smsCode);
				//echo "<br/ Enable Disable UserID=".$param2;
				//echo "<br/> Res=".json_encode($res);
				return $this->resultIsSuccess;
				break;
			case "FT"://FT [UID] [AAMT]
				$this->bReqObj->TotalAmount = $this->bReqObj->TargetAmount = $param3;
				$this->bReqObj->RequestType="t_payment";
				$this->bReqObj->TargetNo=$param2;
				//echo "<br/>FT request";
				//echo "<br/> Req Obj =".json_encode($this->bReqObj);die;
				$userToTransfer = $this->bUserObj->getByDisplayIDByAncestor($this->me->UserID,$param2);
				$fromUserWallet = $this->bUserObj->getWalletBalance($this->me->UserID);
				$fromUserWalletJson = json_decode(json_encode($fromUserWallet));
				if(!$userToTransfer){	
					$this->resultIsSuccess=false;
					$this->isSendSMS="0";
					$this->smsCode="Payment_f_NotAuth";
					$this->bReqObj->Status = 4;
					$this->resultMessage=$this->resultSmsMessage = $this->langAPI[$this->smsCode];
					$this->bSMSObj->paymentTransfer(0,$this->smsCode,$param2,"",$param3,0,0,$this->bReqObj);
					return false;
				}
				$enoughBalance = $this->bUserObj->enoughBalanceInWallet($this->me,$param3);
				if(!$enoughBalance){	
					$this->resultIsSuccess=false;
					$this->smsCode="Payment_f_NoBalance";
					$this->bReqObj->Status = 4;
					$this->isSendSMS="0";
					
					$msg = str_replace("[WALLETBALANCE]",$fromUserWalletJson->Wallet,$this->langAPI[$this->smsCode]);
					$this->resultMessage=$this->resultSmsMessage = $msg;
					$this->bSMSObj->paymentTransfer(0,$this->smsCode,$param2,$userToTransfer,$param3,0,0,$this->bReqObj);
					return false;
				}
				$gs = $this->bGsObj->get();
				/* echo "<br/> GS=".$param3;
				echo "<br/> GS=".$gs->TA_MinAmt;
				if((double) $param3 < (double) $gs->TA_MinAmt)
					echo "<br/> Not allowed";
				else echo "<br/> Allowed";
					die; */
				if(((double) $param3 < (double) $gs->TA_MinAmt) || ((double)$param3>(double) $gs->TA_MaxAmt)){
					$this->resultIsSuccess=false;
					$this->resultMessage=$this->resultSmsMessage = "Min/Max Transfer Amount";
					$this->isSendSMS="0";
					$this->bReqObj->Status = 4;
					$this->bSMSObj->paymentTransfer(0,"Payment_f_MinMaxTrans",$param2,$userToTransfer,$param3,$gs->TA_MinAmt,$gs->TA_MaxAmt,$this->bReqObj);
					return false;
				}
				//echo "<br/><br/> Request Obj=".json_encode($this->bReqObj);die;
				//echo "<br/> FT =".json_encode($userToTransfer);
				$remark="SMS FUND TRANSFER ";
				//echo "<br/> Me=".json_encode($this->me);
				$res = $this->bPayObj->addTransferWebservice($this->me,$this->me,$userToTransfer,$param3,$remark,$this->bReqObj);
				//echo "<br/> FT isSuccess=".$res->isSuccess;
				if($res->isSuccess){
					/* $msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langSMS["31"]);
					$msg = str_replace("[AMOUNT]",$param3,$msg);
					$msg = str_replace("[DISPLAYUSERID]",$param2,$msg); */
					$this->resultIsSuccess = true;
					$this->smsCode="Payment_s";
					$this->isSendSMS="0";
					$this->bReqObj->Status =3;
					$msg = str_replace("[AMOUNT]",$this->bReqObj->TotalAmount,$this->langAPI[$this->smsCode]);
					$msg = str_replace("[RECEIVERROLE]",$this->bUserObj->getRoleName($userToTransfer->RoleID),$msg);
					$msg = str_replace("[RECEIVERUSERNAME]",$userToTransfer->Name,$msg);
					$msg = str_replace("[RECEIVERUSERID]",$userToTransfer->DisplayID,$msg);
					$msg = str_replace("[RECEIVERMOBILE]",$userToTransfer->Mobile,$msg);
					$msg = str_replace("[TRANSREQUESTID]",$this->bReqObj->DisplayID,$msg);
					//$msg = str_replace("[WALLETBALANCE]",$fromUserWalletJson->Wallet,$msg);
					$this->resultMessage=$this->resultSmsMessage=$msg;
					$this->bReqObj->Status = 3;
					$this->bSMSObj->paymentTransfer(true,"Payment_s",$param2,$userToTransfer,$this->bReqObj->TotalAmount,0,0,$this->bReqObj);
					return true;
				}else{
					/* $msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langSMS["32"]);
					$msg = str_replace("[AMOUNT]",$param3,$msg);
					$msg = str_replace("[DISPLAYUSERID]",$param2,$msg); */
					$this->resultIsSuccess = false;
					
					$this->smsCode="Payment_f";
					$this->isSendSMS="0";
					$msg = str_replace("[AMOUNT]",$param3,$this->langAPI[$this->smsCode]);
					$msg = str_replace("[RECEIVERUSERID]",$userToTransfer->DisplayID,$msg);
					$msg = str_replace("[WALLETBALANCE]",$fromUserWalletJson->Wallet,$msg);
					$this->resultMessage=$this->resultSmsMessage=$msg;
					$this->bReqObj->Status = 4;
					$this->bSMSObj->paymentTransfer(0,"Payment_f",$param2,$userToTransfer,$param3,0,0,$this->bReqObj);
					return false;
				}
				break;
			case "REV"://REV [REQ_ID]
				$this->resultIsSuccess=false;
				$this->isSendSMS=false;
				$this->smsCode="0";
				
				$this->bReqObj->RequestType="t_payment_r";
				$this->bReqObj->TargetNo=$param2;
				$this->bReqObj->TargetAmount = $this->bReqObj->TotalAmount = 0;
					
				//echo "Revertuing FT for req=".$param2;
				$reqObjToRevert = $this->bReqObj->getByID($param2);
				//echo "<br/> Req obj =".json_encode($reqObj);
				if(count($reqObjToRevert)<=0){
					$this->resultSmsMessage = "Invalid Reference ID";
					$this->bReqObj->Status = 4;
					$this->bSMSObj->revertPaymentTransfer(0,"Payment_Rev_f",0,$this->bReqObj,$reqObjToRevert,$this->resultSmsMessage,$transferedUser);
					return false;					
				}
				$reqObjToRevert = $reqObjToRevert[0];
				$transferedUser = $this->bUserObj->getByID($reqObjToRevert->UserID);
				//echo "<br/> transferedUser=".json_encode($transferedUser);
				if(!$transferedUser){
					$this->resultSmsMessage="User not found to revert transfer";
					$this->bReqObj->Status = 4;
					$this->bSMSObj->revertPaymentTransfer(0,"Payment_Rev_f",0,$this->bReqObj,$reqObjToRevert,$this->resultSmsMessage,$transferedUser);
					return false;
				}
				$enoughBalance = $this->bUserObj->enoughBalanceInWallet($transferedUser,$reqObjToRevert->TotalAmount);
				//echo "<br/> enoughBalance=".json_encode($enoughBalance);
				if(!$enoughBalance){	
					$this->resultSmsMessage="User Doesn't have enough balance to revert";
					$this->bReqObj->Status = 4;
					$this->bSMSObj->revertPaymentTransfer(0,"Payment_Rev_f",0,$this->bReqObj,$reqObjToRevert,$this->resultSmsMessage,$transferedUser);
					return false;
				}
				$remark="SMS FUND REVERSE ";
				//echo "<br/> Me=".json_encode($this->me);
				//echo "<br/> transferedUser=".json_encode($transferedUser);
				
				$res = $this->bPayObj->addTransferWebservice($this->me,$transferedUser,$this->me,$reqObjToRevert->TotalAmount,$remark,$this->bReqObj);
				//echo "<br/> FT  REV isSuccess=".$res->isSuccess;
				$origFromUserWallet = $this->bUserObj->getWalletBalance($this->me->UserID);
				$origFromUserWalletJson = json_decode(json_encode($origFromUserWallet));
				if($res->isSuccess){
					$this->resultIsSuccess = true;
					$this->smsCode="Payment_Rev_s";
					$this->isSendSMS="0";
					$msg = str_replace("[AMOUNT]",$reqObjToRevert->TotalAmount,$this->langAPI[$this->smsCode]);
					$msg = str_replace("[ORIGINALRECEIVERID]",$transferedUser->DisplayID,$msg);
					$msg = str_replace("[ORIGINALRECEIVERNAME]",$transferedUser->Name,$msg);
					$msg = str_replace("[TRANSREQUESTID]",$this->bReqObj->RequestID,$msg);
					$msg = str_replace("[WALLETBALANCE]",$origFromUserWalletJson->Wallet,$msg);
					$this->resultSmsMessage=$msg;
					$this->bReqObj->Status = 3;
					$this->bSMSObj->revertPaymentTransfer(true,"Payment_Rev_s",$reqObjToRevert->TotalAmount,$this->bReqObj,$reqObjToRevert,$this->resultSmsMessage,$transferedUser);
					return true;
				}else{
					$this->resultIsSuccess = false;
					$this->smsCode="Payment_Rev_f";
					$this->isSendSMS="0";
					$msg = str_replace("[TRANSREQUESTID]",$reqObjToRevert->DisplayID,$this->langAPI[$this->smsCode]);
					$this->resultSmsMessage=$msg;
					$this->bReqObj->Status = 4;
					$this->bSMSObj->revertPaymentTransfer(0,$this->smsCode,$reqObjToRevert->TotalAmount,$this->bReqObj,$reqObjToRevert,$this->resultSmsMessage,$transferedUser);
					return false;
				}
				break;
			default:
				$this->requestType = "RC";
				//Duplicate request checking is only for recharge - So moved to b_recharge
				
				$rcCode = $param1;
				$rcMobileNo = $param2;
				$rcAmount = $param3;
				if(is_numeric($param1)){
					//If code is not given, then there should be only two params(Mobile and amount). 3rd param shoud be empty
					if(!is_numeric($param2) || $param3!=""){
						$this->resultIsSuccess=false;
						$this->smsCode="Incorrect_Message_Format";
						$this->resultSmsMessage="Invalid Message Format1";
						return 0;
					}
					$rcCode = "";
					$rcMobileNo = $param1;
					$rcAmount = $param2;
				}else{
					//If code is given then it should be <Code Number Amount>
					if(!is_numeric($param2) || $param3==""){
						$this->resultIsSuccess=false;
						$this->smsCode="Incorrect_Message_Format";
						$this->resultSmsMessage="Invalid Message Format2";
						$msg = str_replace("[CUSTOMERMESSAGE]",$this->bSMSObj->customerInputSMS,$this->langAPI[$this->smsCode]);
						$this->resultSmsMessage=$msg;
						$this->processedSMSmssage = $msg;
						return 0;
					}
					$rcCode = $param1;
					$rcMobileNo = $param2;
					$rcAmount = $param3;
				}
		
				$gs = $this->bGsObj->get();
				//echo '<br/> this->bGsObj= '.json_encode($gs);
				//echo "<br/>$rcMobileNo,$rcAmount,$gs->RS_SmNo_SmAmt_Delay,$gs->RS_SmNo_DiffAmt_Delay<br/>";
				/*$duplicateReqID = $this->bReqObj->isDuplicateRequest($rcMobileNo,$rcAmount,$gs->RS_SmNo_SmAmt_Delay,$gs->RS_SmNo_DiffAmt_Delay);
				//echo "<br/> duplicateReqID=".$duplicateReqID;
				
				if($duplicateReqID){
					$this->resultSmsMessage="NEW Duplicate request - ";
					$this->resultIsSuccess=false;
					$this->smsCode="Duplicate_Request";
					return 0;
				}*/
				$rcObj = new b_recharge($this->me,$this->mysql,"",$this->langSMS,$this->langAPI,$gs);
				$res = $rcObj->processRecharge($reqObj,$rcCode,$rcMobileNo,$rcAmount); 
				//echo "<br/> processRecharge=".$res;
					
				$resJson = json_decode(json_decode($res));
				//echo $res;
				$this->resultSmsMessage=$resJson->Message;
				$this->resultMessage=$resJson->Message;
				
				$this->smsCode=$resJson->Code;
				$this->isSendSMS=$resJson->IsSendSMS;
				$this->resultIsSuccess=$resJson->IsSuccess;
				return true;
				break;
			/*case "RC1_NIU"://"<Mobile> <Amount>"
				$rcObj = new b_recharge($this->me,$this->mysql,"",$this->langSMS);
				$reqObj->TargetAmount=$param2;//Amount
				$reqObj->TargetNo=$param1;//RC mobile no
				//echo "<br/> Re obj=".json_encode($reqObj);
				$res = $rcObj->processRecharge($reqObj,"",$param1,$param2);
				
				$resJson = json_decode(json_decode($res));
				//echo $res;
				$this->resultSmsMessage=$resJson->Message;
				$this->smsCode=$resJson->Code;
				$this->isSendSMS=$resJson->IsSendSMS;
				$this->resultIsSuccess=$resJson->IsSuccess;
				return true;
				break;
			case "RC2_NIU"://<CODE> <MOBILE> <AMOUNT>
				//echo "<br/> Message format code RC2";
				$rcObj = new b_recharge($this->me,$this->mysql,"",$this->langSMS);
				$res = $rcObj->processRecharge($reqObj,$param1,$param2,$param3);
				//echo "<br/> processRecharge=".$res;
					
				$resJson = json_decode(json_decode($res));
				//echo $res;
				$this->resultSmsMessage=$resJson->Message;
				$this->smsCode=$resJson->Code;
				$this->isSendSMS=$resJson->IsSendSMS;
				$this->resultIsSuccess=$resJson->IsSuccess;
				return true;
				break;
			default:
				$this->resultIsSuccess = false;
				$this->smsCode="21";
				$this->isSendSMS="1";
				$this->resultSmsMessage="Invalid message format";
				return false;
			*/
		}
	}
	function isUserVerified($requesterMobile){//Call-5
		$userObj=new b_users('',$this->mysql,$this->lang);
		$this->me = $userObj->getByMobile($requesterMobile);
		//echo json_encode($this->me);
		return $this->me ? true : false;
	}
	function isDelayedRequest($reqDT,$reqReceivedDT){//Call-3
		//echo "<br />Req Diff in sec=".($reqReceivedDT - $reqDT);
		return (($reqReceivedDT - $reqDT) <= $this->allowedReqDelayInSec)?false:true;
	}
	function processRequest($reqObj){//Call-2 
		if($this->isDelayedRequest($reqObj->ReqDateTime,$reqObj->ReqReceivedDateTime)){
			$this->resultSmsMessage="Delayed Request(It is more than 5 mins)";
			$this->resultIsSuccess=false;
			$this->smsCode="01";
			return false;
		}
		if(!$this->isUserVerified($reqObj->RequesterMobile)){
			$this->resultSmsMessage="Unable to verify user";
			$this->resultIsSuccess=false;
			$this->smsCode="18";
			return false;
		}
		$reqObj->UserID=$this->me->UserID;
		//echo "<br/> reqObj1=".json_encode($reqObj);
		return $this->processMessage($reqObj);
	}
	function offlineTokenValidation(){//Call-4
		//TODO
	}
	function onlineTokenValidation(){//Call-4
		//TODO
	}
	function addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs,$serverNo){//call-1
		//$this->bReqObj->DisplayID="Not Yet Done...";
		$this->bReqObj->RequesterMobile=$reqMobile;
		$this->bReqObj->RequesterID="-1";//Don't use this. Instead use UserID
		$this->bReqObj->UserID="-1";
		$this->bReqObj->RequesterIP=$_SERVER["REMOTE_ADDR"];
		$this->bReqObj->RequesterApp="SMS";
		$this->bReqObj->TargetNo="000000";
		$this->bReqObj->TargetAmount=0;
		$this->bReqObj->Message=$msg;
		$this->bReqObj->DevInfo="Just Request Came";
		$this->bReqObj->Status="1";
		$this->bReqObj->ReqDateTime=($reqDate." ".$reqTime);
		$this->bReqObj->ReqReceivedDateTime=$receivedDT;
		$this->bReqObj->InputAsIs=$inputAsIs;
		$this->bReqObj->ServerNo=$serverNo;
		
		/* echo "<br/> (reqDate reqTime)=".($reqDate." ".$reqTime);
		echo "<br/> receivedDT=".$receivedDT;
		echo "<br/> bReqObj=".json_encode($this->bReqObj); */
		/*$duplicateReqID = $this->bReqObj->isDuplicateRequest($this->bReqObj->Message);
		if($duplicateReqID>0){
			$this->isDuplicateRequest=true;
			//$this->bReqObj->Status="4";
			$this->resultSmsMessage="Duplicate request - ".$duplicateReqID;
			$this->bReqObj->DevInfo=$this->resultSmsMessage;
			//$this->resultIsSuccess=false;
			$this->smsCode="Duplicate_Request";
			//return 0;
		}*/
		//Add request after checking for duplicate record
		$this->bReqObj->DisplayID = $this->bReqObj->getDisplayID($this->bReqObj,"S");
		$this->bReqObj->RequestID = $this->bReqObj->add($this->bReqObj);
		$this->bReqObj->DisplayID = $this->bReqObj->DisplayID.$this->bReqObj->RequestID;
		//echo " New DISPLAY iD=".$this->bReqObj->DisplayID;
		//$this->bReqObj->DisplayID = "willchange".$this->bReqObj->RequestID;
		/* if($duplicateReqID>0) 
			return 0;
		else */
		return $this->bReqObj->RequestID;
	}
	function processOfflineRequest($tokenGuid,$reqMobile,$msg,$reqDate,$reqTime,$inputAsIs,$serverNo){//Starting Fn
		$this->bSMSObj->customerInputSMS=$msg;
		//Set TotalAmount and RequestType to 0 - meaning unknown.
		$this->bReqObj->TotalAmount=0;
		$this->bReqObj->RequestType=0;
		$this->bReqObj->ServerNo=$serverNo;
		$this->addErrorlog("processOfflineRequest",$reqMobile."-".$msg,"reqMobile-msg","No table","0","Before Adding request");
		
		//First thing is to record the requested time
		$receivedDT =date('m/d/Y H:i:s');
		
		//Add new request and maintain the request ID all over the recharge process
		$reqID = $this->addRequest($reqMobile,$msg,$reqDate,$reqTime,$receivedDT,$inputAsIs,$serverNo);
		
		$this->addErrorlog("processOfflineRequest",$reqMobile."-".$msg,"reqMobile-msg","t_request",$reqID,"Just request added");
		
		//echo "<br/> req obj=".json_encode($this->bReqObj);
		//echo "<br/> New Request ID=".$reqID;
		if($reqID<=0){
			if($this->smsCode!="Duplicate_Request"){
				$this->resultSmsMessage="Unable to create request";
				$this->resultIsSuccess=false;
				$this->smsCode="02";
				return $this->returnMessage();
			}
		}
		
		//TODO: Verify the token
		
		$isProcessed = $this->processRequest($this->bReqObj);
		/* echo "<br/> isProcessed=".$isProcessed;
		echo "<br/>".$this->resultSmsMessage;
		echo "<br/>".$this->resultIsSuccess; */
		//echo "<br/> <hr/>";
		return $this->returnMessage();
	}
	function processOnlineRequest(){
		//TODO
	}
	
	function processRCApiResponse($respRcID,$respStatus,$respOpTransID,$respOpMsg,$respBal,$inputAsIs,$apiType){
		//First thing is to record the response time
		$receivedDT =date('d/M/Y H:i:s');
		$reqObj = $this->bReqObj->getByID($respRcID);
		//echo "<br/> reqObj=".json_encode($reqObj);
		if(count($reqObj)<=0){
			$this->resultSmsMessage=$this->langSMS["03"].$respRcID;
			$this->smsCode="03";
			$this->resultIsSuccess=false;
			return $this->returnMessage();
		}
		$this->bReqObj->RequestID = $reqObj[0]->RequestID;
		$this->bReqObj->UserID = $reqObj[0]->UserID;
		$this->bReqObj->Remark=$reqObj[0]->Remark;
		$this->bReqObj->DevInfo = $reqObj[0]->DevInfo;
		$this->bReqObj->TotalAmount = $reqObj[0]->TotalAmount;
		$this->bReqObj->TargetAmount = $reqObj[0]->TargetAmount;
		$this->bReqObj->RequestType = $reqObj[0]->RequestType;
		$this->bReqObj->Status = $reqObj[0]->Status;
		$this->requestType = "RC"; // RC - Recharge. Just to avoidupdating request before returning message(returnMessage());
		//$this->bReqObj->RequesterMobile = $reqObj[0]->RequesterMobile;
		$this->bReqObj->TargetNo = $reqObj[0]->TargetNo;
		$this->me = $this->bUserObj->getByID($reqObj[0]->CreatedBy);
		$userID = $this->me ? $this->me->UserID : -1;//-1 indicates user not found
		//echo "<br/><hr/> reqObj1=".json_encode($reqObj[0]);
		//echo "<br/>Me(Webservice)=".json_encode($this->me);
		$gs = $this->bGsObj->get();
		$rcObj = new b_recharge($this->me,$this->mysql,"",$this->langSMS,$this->langAPI,$gs);
		$rcObj = $rcObj->getRechargeObjByValues($respRcID,$respOpTransID,$respOpMsg,$respBal,$inputAsIs,$receivedDT);
		$res = $rcObj->getByRequestID($rcObj->RequestID);
		//echo "<br/> res=".json_encode($res);
		$rcObj->NetworkProviderName = $res->NetworkProviderName;
		$res = $rcObj->processResponse($reqObj[0],$rcObj,$respStatus,$apiType);
		//echo "<br/> processResponse=".$res;
		$this->bReqObj->RequesterMobile=$this->me->Mobile;
		
		$res = json_decode(json_decode($res));
		//echo $res->IsSuccess;
		$this->resultIsSuccess=$res->IsSuccess;
		$this->isSendSMS=$res->IsSendSMS;
		$this->resultSmsMessage=$res->Message;
		return $this->returnMessage();
		//$res = $rcObj->processResponse($reqObj,$respRcID,$respStatus,$respOpTransID,$respOpMsg,$respBal,$inputAsIs,$receivedDT);
		//echo "<br/> Updated Response=".json_encode($res);
		/* $isUpdated = $this->bReqObj->updateResponse($this->bReqObj);
		if($reqID<=0){
			$this->resultSmsMessage="Unable to create request";
			$this->resultIsSuccess=false;
			return $this->returnMessage();
		} */
		
		//TODO: Verify the token
	}
	function updateRequestStatus(){
		$this->bReqObj->updateStatus($this->bReqObj);
	}
	function returnMessage(){
		/* echo "<br/> this->resultMessage=".$this->resultMessage;
		echo "<br/><hr/> b_webservice returnMessage=".json_encode($this->bReqObj);
		echo "<br/>resultIsSuccess=".$this->resultIsSuccess;
		echo "<br/>resultSmsMessage=".$this->resultSmsMessage;
		echo "<br/>bReqObj->RequestID=".$this->bReqObj->RequestID;
		echo "<br/>this->isSendSMS=".$this->isSendSMS;
		echo "<br/> requestType=".$this->requestType; */
		//echo "<br/> this->bReqObj=".$this->bReqObj->RequestType;
		//if($this->resultIsSuccess){
			if($this->bReqObj->RequestID>0){
			
				//If it is not recharge then change the status to success if result is success. If it is recharge then send the request status as it is.
				if(!$this->resultIsSuccess)
					$this->bReqObj->Status=4;
				else if($this->requestType!='RC'){
					$this->bReqObj->Status=3;
					//Updating status from here only for non-recharge requests.
					//Bcoz recharge request status would have been updated inside recharge process
					$this->updateRequestStatus();
				}
					/* if($this->resultIsSuccess)
						$this->bReqObj->Status=3;
					else 
						$this->bReqObj->Status=4; */
					
				//echo "<br/> reqObj=".json_encode($reqObj);
				$this->bReqObj->Remark=$this->resultMessage;
				$this->bReqObj->DevInfo .= ". isSendSMS".$this->isSendSMS;
				//Recharge will update request inside b_recharge process
				if($this->requestType!="RC")
					$this->bReqObj->update($this->bReqObj);
				//$this->updateRequestStatus($this->bReqObj);
				//$this->isSendSMS="0";
				if($this->isSendSMS){
					//$smsMessage = $this->langSMS[$this->smsCode];
					//echo "<br/> Sending SMS from b_webservice...";
					if($this->processedSMSmssage == "NOTPROCESSED")
						$this->resultSmsMessage = $this->bReqObj->Remark =$this->processedSMSmssage=$this->langSMS[$this->smsCode];
					else
						$this->resultSmsMessage = $this->bReqObj->Remark =$this->processedSMSmssage;;
					//echo "<br/> Req be4 sms=".json_encode($this->bReqObj);
					$this->bSMSObj->sendSMS($this->bReqObj->RequesterMobile,$this->bReqObj->TargetNo,$this->processedSMSmssage,"0",$this->bReqObj->RequestID,"t_request",$this->notes);
				}//else
					//echo "<br/>Not sending SMS";
			}
		//}
		$this->resultSmsMessage = str_replace("[CUSTOMERMESSAGE]",$this->bSMSObj->customerInputSMS,$this->resultSmsMessage);
		//$reqDBobj = $this->bReqObj->getByID($this->bReqObj->RequestID);
		//echo json_encode($reqDBobj);
		$jsonStr='{"IsSuccess":'.json_encode($this->resultIsSuccess).',"SmsCode":'.json_encode($this->smsCode).',"IsSendSMS":'.json_encode($this->isSendSMS).',"Status":'.$this->bReqObj->Status.',"RequestID":'.$this->bReqObj->RequestID.',"Message":'.json_encode($this->resultSmsMessage).'}';
		
		$this->addErrorlog("returnMessage",json_encode($jsonStr),"OutputMessage","t_request",$this->bReqObj->RequestID,"Finishing process");
		
		return json_encode($jsonStr);
	}
	function addErrorlog($fnName,$message,$deveMsg,$type,$id,$more){
		$this->mysql->errorlog->addLog("WebserviceProcess",$this->fileName,$fnName,$message,$deveMsg,$type,$id,$more);
	}
	function echoMessage(){
		echo "<br/>resultSmsMessage=".$this->resultSmsMessage;
		echo "<br/>resultIsSuccess=".$this->resultIsSuccess;
		echo "<br/>";
	}
	function saveQueryStringTesting_NIU($fromWhere,$str){
		//echo "<br/>saveQueryStringTesting in webservice";
		$rcObj = new b_recharge($this->me,$this->mysql,"",$this->langSMS,$this->langAPI,"");
		$res = $rcObj->saveQueryStringTesting($fromWhere,$str);
	}
}
?>