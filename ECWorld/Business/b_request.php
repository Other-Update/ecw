<?php
include_once APPROOT_URL.'/Database/d_request.php';
/*include_once APPROOT_URL.'/Business/b_automnp.php'; */

include_once APPROOT_URL.'/General/general.php';
class b_request{
	var $me;
	var $mysql;
	var $lang;
	var $dObj;//Database Recharge
	function __construct($thisUser,$mysqlObj,$lang){
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->mysql=$mysqlObj;
		$this->dObj=new d_request($mysqlObj);
	}
	function getByID($id){
		return $this->dObj->getByID($id);
	}
	function getByApiRefID($id){
		return $this->dObj->getByApiRefID($id);
	}
	function getDuplicateRequest($mobile,$amount,$sameNoAmountDelay,$sameNoDiffAmountDelay){
		return $this->dObj->getDuplicateRequest($mobile,$amount,$sameNoAmountDelay,$sameNoDiffAmountDelay);
	}
	function add($reqObj){
		//echo "<br/>b add";
		return $this->dObj->add($reqObj);
	}
	function update($reqObj){
		//echo "<br/>b add";
		return $this->dObj->update($reqObj);
	}
	function updateStatus($reqObj){
		//echo "<br/>b add";
		return $this->dObj->updateStatus($reqObj);
	}
	function updateApiImmediateResponse($reqObj){
		//echo "<br/>b add";
		return $this->dObj->updateApiImmediateResponse($reqObj);
	}
	function isDuplicateRequest($mobile,$amount,$sameNoAmountDelay,$sameNoDiffAmountDelay){
		//echo "<br/> b_request isDuplicateRequest";
		$res = $this->getDuplicateRequest($mobile,$amount,$sameNoAmountDelay,$sameNoDiffAmountDelay);
		//echo "<br/> isDuplicateRequest(b_request)=".json_encode($res);
		if(count($res)>0){
			return $res[0]->RequestID;
			//If any same request found with status other than success/filure then 
			// that is called duplicate
				//echo "<br/> Duplicate request = ".json_encode($res);
			/* if($res[0]->Status!=3 && $res[0]->Status!=4)
				return $res[0]->RequestID;
			else  
				return 0;*/
			//}
		}
		//echo "<br/> No request with the message - ".$msg;
		return 0;
	}
	function getMonthInAlphabet($m){
		return chr(64+$m);
	}
	function getDisplayID($reqObj,$server='N'){
		//(ServerNo,17-year,X-Month,19-Day,RequestID-RequestTable)
		//Maximum of 7/8 digit w/o auto inc id
		$displayID=$server;
		$now = new \DateTime('now');
		$month = $this->getMonthInAlphabet($now->format('m'));
	    $year = $now->format('y');//Small y for XX and Capital Y for XXXX format.
		$day = $now->format('d');
		
		$displayID=$server.$year.$month.$day;//.$reqObj->RequestID;
		//echo "<br/><br/> displayID=".$displayID;
		//$res = $this->dObj->updateDisplayID($reqObj->RequestID,$displayID);
		//die;
		return $displayID;
	}
	
	function giveMeObj($userid,$mobile,$ip,$app,$targetNo,$targetAmount,$msg,$devInfo,$status,$reqDT,$receviedDT,$input,$serverNo){
		$bReqObj=new b_request($this->me,$this->mysql,$this->lang);
		$bReqObj->DisplayID="NewID";
		$bReqObj->UserID=$userid;
		$bReqObj->RequesterMobile=$mobile;
		$bReqObj->RequesterIP=$ip;
		$bReqObj->RequesterApp=$app;
		$bReqObj->TargetNo=$targetNo;
		$bReqObj->TargetAmount=$targetAmount;
		$bReqObj->Message=$msg;
		$bReqObj->DevInfo=$devInfo;
		$bReqObj->Status=$status;
		$bReqObj->ReqDateTime=$reqDT;
		$bReqObj->ReqReceivedDateTime=$receviedDT;
		$bReqObj->InputAsIs=$input;
		$bReqObj->ServerNo=$serverNo;
		return $bReqObj;
	}
	function getRequestStatus($reqID){
		return $this->dObj->getRequestStatus($reqID);
	}
	function DeleteOldRequests($userID,$date){
		return $this->dObj->DeleteOldRequests($userID,$date);
	}
	/* Incoming Request */
	function getIncoming_DT($userId, $message, $server_no, $fromDate, $toDate){
		return $this->dObj->getIncoming_DT($userId, $message, $server_no, $fromDate, $toDate);
	}
	/* End */
}
?>