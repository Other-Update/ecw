<?php
include_once APPROOT_URL.'/Database/d_sms.php';
include_once APPROOT_URL.'/Database/d_request.php';
class b_sms{
	private $me;
	private $lang;
	var $dObj;
	var $smsURL;
	var $smsQueryStr;
	var $bHttpObj;
	var $bUserObj;
	var $mysqlObj;
	var $langSMS;
	//var $isSendSMS;
	var $footerMsg;
	var $customerInputSMS="";
	var $action = 0;//1-UserCreation,0-Others,
	var $dReqObj;
	function __construct($thisUser,$mysqlObj,$bUserObj,$lang,$langSMS,$httpObj){
		//$this->isSendSMS="1";//Enable disable SMS sending feature
		$this->me=$thisUser;
		$this->mysqlObj=$mysqlObj;
		$this->lang=$lang;
		$this->langSMS=$langSMS;
		$this->bUserObj=$bUserObj;
		$this->bHttpObj=$httpObj;
		$this->bReqObj=new d_request($mysqlObj);
		//echo "<br/><hr/> user =".json_encode($thisUser);
		$this->dObj=new d_sms($mysqlObj,$thisUser);
		$this->setSMSApiForAction();
		$this->footerMsg="www.ecworld.co.in, Thanks";
	}
	//General functions start
	function updateUserMe($user){
		$this->me=$user;
		$this->dObj=new d_sms($this->mysqlObj,$this->me);
	}
	function add($to,$msg,$resID,$refTable,$devInfo){
		$res = $this->dObj->add($to,$msg,$resID,$refTable,$devInfo);
		return $res;
	}
	
	function sendSMS($to,$rcMobile,$msg,$gatewayID,$refID,$refTable,$devInfo){
		//$mobile="9740074855";
		//$message = $this->bHttpObj->getEncodedString($message);
		//echo $message;
		$msg = $this->updateCommonMsg($msg);
		$qsWithParam = str_replace("[MOB]",$to,$this->smsQueryStr);
		$qsWithParam = str_replace("[MSG]",$msg,$qsWithParam);
		//echo "<br/> Encoded Msg=".$this->bHttpObj->getEncodedString($message);
		$qsWithParam = $this->bHttpObj->getEncodedString($qsWithParam);
		//echo "<br/>qsWithParam=".$qsWithParam;
		$urlWithParam = $this->smsURL.$qsWithParam;
		$smsConfig = $this->mysqlObj->configuration['sms'];
		//echo '<br/><br/>sending sms ='.$smsConfig['isenabled'];
		//echo '<br/> SMS Message='.$msg.'<br/><br/>';
		if($smsConfig['isenabled']){
			//$urlWithParam = str_replace("9043210003","9740074855",$urlWithParam);
			//echo "<br/> SMS sending url =".$urlWithParam;
			$respCode = $this->bHttpObj->HTTPGet($urlWithParam);
			//echo "<br/> SMS response=".$respCode.".";
			//Returning some unecessory new lines. so need to remove those.
			$respCode = preg_replace( "/\r|\n/", "", $respCode );
			$res = $this->dObj->add($gatewayID,$refID,$refTable,$to,$msg,$devInfo,$respCode,$urlWithParam);
			return $respCode;
		}else{
			//echo "<br/><br/> Sending SMS is disabled<br/> Msg=".$msg;
			//echo "<br/><br/>";
		}
	}
	function replace($original,$new,$msg){
		return str_replace($original,$new,$msg);
	}
	function updateCommonMsg($msg){
		//echo "<br/> b_sms-me=".json_encode($this->me);
		//echo "<br/> updateCommonMsg.msg(Before)=".$msg;
		if($this->me){
			$msg = $this->replace("[CUSTOMERNAME]",$this->me->Name,$msg);
			//echo "<br/> updateCommonMsg.msg(After)=".$msg;
			$wallet = $this->bUserObj->getWalletBalance($this->me->UserID);
			$walletJson = json_decode(json_encode($wallet));
			$msg = $this->replace("[WALLETBALANCE]",$walletJson->Wallet,$msg);
		}else{
			$msg = $this->replace("[CUSTOMERNAME]","User",$msg);
			//echo "<br/> updateCommonMsg.msg(After)=".$msg;
			$msg = $this->replace("[WALLETBALANCE]","Not Available Now",$msg);
		}
		//echo "<br/> this->customerInputSMS=".$this->customerInputSMS;
		if($this->customerInputSMS!=""){
			$msg = $this->replace("[CUSTOMERMESSAGE]",$this->customerInputSMS,$msg);
		}else{
			$msg = $this->replace("[[CUSTOMERMESSAGE]].","",$msg);
		}
		//echo "<br/> msg=".$msg;
		$msg = $this->replace("[FOOTERMESSAGE]",$this->footerMsg,$msg);
		return $msg;
	}
	function setSMSApiForAction($isRepeated=false){
		//$this->smsURL = "http://117.218.212.48:8086/";
		
		switch($this->action){
			case 1: //1-UserCreation
				$smsserver1 = $this->mysqlObj->configuration['sms']['smsserver1'];
				$this->smsURL = $smsserver1['ip'];
				$this->smsQueryStr=$smsserver1['querystring'];
				break;
			default: //0-Others,
				$smsserver2 = $this->mysqlObj->configuration['sms']['smsserver2'];
				$this->smsURL = $smsserver2['ip'];
				$this->smsQueryStr=$smsserver2['querystring'];
				break;
		}
		
		// there is situation where SMS may not be delivered.User createion 
		// is an important SMS to user.
		// So in those situations 
		// we need to send sms from opposite gateway also.
		if($isRepeated==true){
			$smsserver2 = $this->mysqlObj->configuration['sms']['smsserver2'];
			$this->smsURL = $smsserver2['ip'];
			$this->smsQueryStr=$smsserver2['querystring'];
		}
	}
	//general functions end
	
	//SMS request - Need to send sms to parent and new user
	//Android and Web - No need to send SMS to parent
	function userCreation($isSuccess,$reqObj,$newUserID,$newUserMobile,$newUserName,$newUserRoleName,$rndPassword,$failureReason){
		$this->action = 1;//1 means user creation
		$this->setSMSApiForAction();
		
		//Send to Parent user
		//echo "<br/> b_sms -> userCreation. reqObj=".json_encode($reqObj);
		$msg = $this->langSMS['NewUserToParent_s'];
		//echo "<br/> userCreation -msg to parent - raw=".$msg;
		if(!$isSuccess)
			$msg=$this->langSMS['NewUserToParent_f'];
		//echo "<br/> userCreation -msg to parent - raw=".$msg;
		$msg = $this->updateCommonMsg($msg);
		$msg = $this->replace("[NEWUSERNAME]",$newUserName,$msg);
		
		$msg = $this->replace("[NEWUSERID]",$newUserID,$msg);
		$msg = $this->replace("[USERROLE]",$newUserRoleName,$msg);
		$msg = $this->replace("[FAILUREREASON]",$failureReason,$msg);
		//echo "<br/> userCreation -msg to parent=".$msg;
		$isSMSReq = $this->bReqObj->isSMSRequest($reqObj);
		//echo "<br/> Req disp id=".$reqObj->DisplayID;die;
		//if($isSMSReq)
		$this->sendSMS($this->me->Mobile,$reqObj->TargetNo,$msg,"0",$reqObj->RequestID,"t_request","Unknown");
		
		//Send to New UserID
		if($isSuccess){
			$msg=$this->langSMS['NewUserToNewUser_s'];
			$msg = $this->replace("[NEWUSERNAME]",$newUserName,$msg);			
			$msg = $this->replace("[NEWUSERID]",$newUserID,$msg);
			$msg = $this->replace("[NEWUSERMOBILE]",$newUserMobile,$msg);
			
			$msg = $this->replace("[PASSWORD]",$rndPassword,$msg);
			$msg = $this->replace("[PINNUMBER]","No yet created",$msg);
			$msg = $this->replace("[FOOTERMESSAGE]",$this->footerMsg,$msg);
			//echo "<br/> userCreation -msg(To new User)=".$msg;
			
			$this->sendSMS($newUserMobile,'',$msg,"0",$reqObj->RequestID,"t_request","Unknown",0);
			//Send SMS from other gateway
			$this->setSMSApiForAction(true);
			$this->sendSMS($newUserMobile,'',$msg,"0",$reqObj->RequestID,"t_request","Unknown",0);
		}
		
	}
	
	function paymentTransfer($isSuccess,$smsCode,$toUserID,$toUser,$amount,$minAmount,$maxAmount,$reqObj){
		/* $msg = $this->langSMS['Payment_s'];
		if(!$isSuccess) */
		$msg_sender=$this->langSMS[$smsCode];
		//echo json_encode($reqObj);
		$msg_sender = $this->updateCommonMsg($msg_sender);
		$msg_sender = $this->replace("[AMOUNT]",$reqObj->TotalAmount,$msg_sender);
		$msg_sender = $this->replace("[RECEIVERUSERID]",$toUserID,$msg_sender);
		if($toUser){
		$msg_sender = $this->replace("[RECEIVERUSERNAME]",$toUser->Name,$msg_sender);
		$msg_sender = $this->replace("[RECEIVERROLE]",$this->bUserObj->getRoleName($toUser->RoleID),$msg_sender);
		$msg_sender = $this->replace("[RECEIVERMOBILE]",$toUser->Mobile,$msg_sender);
		}
		if($isSuccess){
			$msg_sender = $this->replace("[TRANSREQUESTID]",$reqObj->DisplayID,$msg_sender);
			
			$msg_receiver=$this->langSMS["Payment_Receiver_s"];
			$msg_receiver = $this->replace("[AMOUNT]",$reqObj->TotalAmount,$msg_receiver);
			$msg_receiver = $this->replace("[RECEIVERUSERID]",$toUserID,$msg_receiver);
			$msg_receiver = $this->replace("[RECEIVERNAME]",$toUser->Name,$msg_receiver);
			$msg_receiver = $this->replace("[SENDERID]",$this->me->DisplayID,$msg_receiver);
			$msg_receiver = $this->replace("[SENDENAME]",$this->me->Name,$msg_receiver);
			$msg_receiver = $this->replace("[TRANSREQUESTID]",$reqObj->DisplayID,$msg_receiver);
			$wallet = $this->bUserObj->getWalletBalance($toUserID);
			$walletJson = json_decode(json_encode($wallet));
			$msg_receiver = $this->replace("[RECEIVERBALANCE]",$walletJson->Wallet,$msg_receiver);
			$this->sendSMS($toUser->Mobile,'',$msg_receiver,"0",$reqObj->RequestID,"t_request","Unknown");
			//echo "<br/> msg_receiver".$msg_receiver;
		}
		if(!$isSuccess){ 
			$msg_sender = $this->replace("[MINIMUMTRANSFERAMOUNT]",$minAmount,$msg_sender);
			$msg_sender = $this->replace("[MAXIMUMTRANSFERAMOUNT]",$maxAmount,$msg_sender);
		}
		
		$this->sendSMS($this->me->Mobile,'',$msg_sender,"0",$reqObj->RequestID,"t_request","Unknown");
	}
	
	//Both users need SMS all the time
	function revertPaymentTransfer($isSuccess,$smsCode,$amount,$currentReqObj,$reqObjToRevert,$reason,$orignalReceiver){
		//echo "<br/>=".$smsCode;
		$msg_original_sender=$this->langSMS[$smsCode];
		$msg_original_sender = $this->updateCommonMsg($msg_original_sender);
		$msg_original_sender = $this->replace("[AMOUNT]",$amount,$msg_original_sender);
		$msg_original_sender = $this->replace("[TRANSREQUESTID]",$currentReqObj->DisplayID,$msg_original_sender);
		$msg_original_sender = $this->replace("[REASON]",$reason,$msg_original_sender);
		$msg_original_sender = $this->replace("[ORIGINALRECEIVERID]",$orignalReceiver==""?"":$orignalReceiver->DisplayID,$msg_original_sender);
		$msg_original_sender = $this->replace("[ORIGINALRECEIVERNAME]",$orignalReceiver==""?"":$orignalReceiver->Name,$msg_original_sender);
		if($isSuccess){
			$msg_original_receiver = $this->langSMS["Payment_Rev_Receiver_s"];
			$msg_original_receiver = $this->replace("[AMOUNT]",$amount,$msg_original_receiver);
			$msg_original_receiver = $this->replace("[TRANSREQUESTID]",$currentReqObj->DisplayID,$msg_original_receiver);
			$msg_original_receiver = $this->replace("[ORIGINALRECEIVERNAME]",$orignalReceiver->Name,$msg_original_receiver);
			$msg_original_receiver = $this->replace("[ORIGINALSENDERID]",$this->me->DisplayID,$msg_original_receiver);
			$msg_original_receiver = $this->replace("[ORIGINALSENDERNAME]",$this->me->Name,$msg_original_receiver);
			$wallet = $this->bUserObj->getWalletBalance($orignalReceiver->UserID);
			$walletJson = json_decode(json_encode($wallet));
			$msg_original_receiver = $this->replace("[ORIGINALRECEIVERBALANCE]",$walletJson->Wallet,$msg_original_receiver);
			$this->sendSMS($orignalReceiver->Mobile,'',$msg_original_receiver,"0",$currentReqObj->RequestID,"t_request","Unknown");
		}
		$this->sendSMS($this->me->Mobile,'',$msg_original_sender,"0",$currentReqObj->RequestID,"t_request","Unknown");
	}
	
	function balanceDetails($reqObj,$user,$walletBal,$purchaseAmnt,$salesAmnt,$smsCode){
		$msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langSMS[$smsCode]);
		$msg = str_replace("[WALLETBALANCE]",$walletBal,$msg);
		$msg = str_replace("[TODAYPURCHASE]",$purchaseAmnt,$msg);
		$msg = str_replace("[TODAYSALES]",abs($salesAmnt),$msg);
		$isSMSReq = $this->bReqObj->isSMSRequest($reqObj);
		if($isSMSReq)
			$this->sendSMS($this->me->Mobile,'',$msg,"0",$this->me->UserID,"t_users","Unknown");
	}
	function subAcBalanceDetails($reqObj,$user,$subAcUserID,$subAcUserName,$walletBal,$smsCode){
		//$msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langSMS[$smsCode]);
		$msg = $this->langSMS[$smsCode];
		$msg = str_replace("[SUBACCOUNTID]",$subAcUserID,$msg);
		$msg = str_replace("[SUBACCOUNTNAME]",$subAcUserName,$msg);
		$msg = str_replace("[WALLETBALANCE]",$walletBal,$msg);
		$isSMSReq = $this->bReqObj->isSMSRequest($reqObj);
		if($isSMSReq)
			$this->sendSMS($this->me->Mobile,'',$msg,"0",$subAcUserID,"t_users","Unknown");
	}
	
	function enableDisableUser($user,$subAcUserID,$subAcUserName,$action,$smsCode){
		//$msg = str_replace("[CUSTOMERNAME]",$this->me->Name,$this->langSMS[$smsCode]);
		$msg = $this->langSMS[$smsCode];
		$msg = str_replace("[SUBACCOUNTID]",$subAcUserID,$msg);
		$msg = str_replace("[SUBACCOUNTNAME]",$subAcUserName,$msg);
		$msg = str_replace("[ACTION]",$action,$msg);
		
		$isSMSReq = $this->bReqObj->isSMSRequest($reqObj);
		if($isSMSReq)
			$this->sendSMS($this->me->Mobile,'',$msg,"0",$subAcUserID,"t_users","Unknown");
	}
	
	function Recharge($reqObj,$msg,$refID,$autoMnpObj,$refTable){
		//$msg=$this->langSMS[$smsCode];
		$msg = $this->updateCommonMsg($msg);
		if($autoMnpObj){
			$msg = $this->replace("[NETWORKNAME]",$autoMnpObj->Name,$msg);
		}
		$isSMSReq = $this->bReqObj->isSMSRequest($reqObj);
		if($isSMSReq)
			$this->sendSMS($this->me->Mobile,'',$msg,"0",$refID,$refTable,"Unknown");
	}
	
	/* Get Outgoing Request */
	function getOutgoing_DT($userId, $message, $api_name, $fromDate, $toDate){
		return $this->dObj->getOutgoing_DT($userId, $message, $api_name, $fromDate, $toDate);
	}
	/* End */
}

//Over all notes
////isFirstSMSForReq is not being used.
/*Action
0-Others
1-User creation

*/
?>
