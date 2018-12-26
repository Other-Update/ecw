<?php
include_once APPROOT_URL.'/Database/d_users.php';
include_once 'b_role.php';
include_once 'b_fat.php';
include_once 'b_servicepermission.php';
include_once 'b_distmargin.php';
include_once 'b_rcusergateway.php';
include_once 'b_generalsettings.php';
include_once APPROOT_URL.'/Database/d_monitor.php';
include_once APPROOT_URL.'/Database/d_usersession.php';
include_once APPROOT_URL.'/www/Session/Session.php';
include_once APPROOT_URL.'/General/general.php';
include_once APPROOT_URL.'/Business/Token/b_token.php';
class b_users{
	private $filename='b_users';
	private $me;
	private $mysqlObj;
	private $dUserObj;
	private $bRoleObj;
	private $bFatObj;
	private $bSpObj;
	private $rcgObj;
	private $gsObj;
	private $lang;
	private $monitorObj;
	private $usObj;
	function __construct($thisUser,$mysqlObj,$lang){
		//echo "<br/>__construct=".json_encode($thisUser);
		$this->me=$thisUser;
		$this->mysqlObj=$mysqlObj;
		$this->lang=$lang;
		$this->dUserObj=new d_users($mysqlObj);
		$this->bRoleObj=new b_role($mysqlObj);
		$this->bFatObj=new b_fat($thisUser,$mysqlObj);
		$this->bSpObj=new b_servicepermission($thisUser,$mysqlObj,$lang);
		$this->dmObj=new b_distmargin($thisUser,$mysqlObj,$lang);
		$this->rcgObj=new b_rcusergateway($thisUser,$mysqlObj,$lang);
		$this->monitorObj = new d_monitor($mysqlObj);
		$this->usObj = new d_usersession($mysqlObj);
		
		//$this->gsObj=new b_generalsettings($thisUser,$mysqlObj,$lang);//Making agin call to B_user constructor
	}
	
	function updateUserMe($user){
		$this->me=$user;
		$this->bSpObj=new b_servicepermission($this->me,$this->mysqlObj,$this->lang);
		$this->dmObj=new b_distmargin($this->me,$this->mysqlObj,$this->lang);
		$this->rcgObj=new b_rcusergateway($this->me,$this->mysqlObj,$this->lang);
	}
	//update password -- reghu
	function changePass($userId, $password, $oldpassword) {
		$userArr = $this->dUserObj->isPasswordMatch($userId,$oldpassword);
		$resultObj = new httpresult();
		if($userArr == 1){
			$res = $this->dUserObj->changePass($userId,$password);
			if($res){
				$resultObj->isSuccess = true;
				$resultObj->message = "Password updated successfully.";
			} else {
				$resultObj->isSuccess=false;
				$resultObj->message = "Something went wrong. Try again...";
			}
		}
		else{
			$resultObj->isSuccess = false;
			$resultObj->message = "Old password does not match";
		}
		return json_encode($resultObj);
	}
	//update password end -- reghu

	function login($username,$password){
		$userArr = $this->dUserObj->login($username,$password);
		//echo "Active=".json_encode($userArr[0]).",";
		////echo count($result);
		$this->monitorObj->add(-1,$this->monitorObj->enumLoginAttemptName,0,"BeforeLogin");
		$resultObj = new httpresult();
		$tokenEnc = "";
		/* echo "<br/> Allowed IPS=".$userArr[0]->AllowedIPs;
		echo "<br/>contais ip=0.0.0.0=".(strpos($userArr[0]->AllowedIPs, '0.0.0.0'));
		echo "<br/>contais ip=".$_SERVER["REMOTE_ADDR"]."=".(strpos($userArr[0]->AllowedIPs, $_SERVER["REMOTE_ADDR"])); */
		
		if(count($userArr)==0){
			$resultObj->isSuccess=false;
			$resultObj->message=$this->lang['login_failed'];
		}
		else if(!$userArr[0]->Active){
			$resultObj->isSuccess=false;
			$resultObj->message=$this->lang['login_disabled'];
		}
		else if((strpos($userArr[0]->AllowedIPs, $_SERVER["REMOTE_ADDR"])===FALSE) && (strpos($userArr[0]->AllowedIPs, '0.0.0.0')===FALSE)){
			$resultObj->isSuccess=false;
			$resultObj->message=$this->lang['login_ip_not_allowed'];
		}
		else if ((strpos($_SERVER['REQUEST_URI'], 'AdminWorld') >0) && $userArr[0]->RoleID!='1' && $userArr[0]->RoleID!='2'){
			//Deny any user tries to login from Admin login
			$resultObj->isSuccess=false;
			$resultObj->message=$this->lang['login_failed'];
		}else{
			//echo "<br/> Login Active=".$userArr[0]->Active;
			//echo 'RoleID='.$userArr[0]->RoleID;
			$roleData = $this->bRoleObj->getByID($userArr[0]->RoleID);
			$fatData = $this->bFatObj->getByUserID($userArr[0]->UserID);
			//echo 'Role='.$roleData[0]->Name;
			$resultObj->isSuccess=true;
			$resultObj->message=$this->lang['login_success'];			
			$ecwToken =$GLOBALS['EcwToken'];//Get global object
			$tokenEnc = $ecwToken->getToken($userArr[0]);
			$resultObj->data='{"user":'.json_encode($userArr[0]).',"role":'.json_encode($roleData).',"fat":'.json_encode($fatData).',"token":'.json_encode($tokenEnc).'}';
		}
		$this->monitorObj->add(count($userArr)==0?-1:$userArr[0]->UserID,$this->monitorObj->enumLoginAttemptName,$resultObj->isSuccess==true?1:0,$tokenEnc);//json_encode($resultObj));
		$this->usObj->add(count($userArr)==0?-1:$userArr[0]->UserID,$tokenEnc);
		return json_encode($resultObj);
	}
	function getByID($userID)
	{
		$userArr = $this->dUserObj->getByID($userID);
		if(count($userArr)>0)
			return $userArr[0];
		else
			return null;
	}
	function getByDisplayID($userDisplayID)
	{
		$userArr = $this->dUserObj->getByDisplayID($userDisplayID);
		if(count($userArr)>0)
			return $userArr[0];
		else
			return null;
	}
	function getByDisplayIDByParent($parentID,$userDisplayID)
	{
		$userArr = $this->dUserObj->getByDisplayIDByParent($parentID,$userDisplayID);
		if(count($userArr)>0)
			return $userArr[0];
		else
			return null;
	}
	function getByDisplayIDByAncestor($parentID,$userDisplayID)
	{
		$userArr = $this->dUserObj->getByDisplayIDByAncestor($parentID,$userDisplayID);
		if(count($userArr)>0)
			return $userArr[0];
		else
			return null;
	}
	function getByMobile($mobileNo)
	{
		$userArr = $this->dUserObj->getByMobile($mobileNo);
		if(count($userArr)>0)
			return $userArr[0];
		else
			return null;
	}
	//$noOfUsers =0 means unlimited
	function getBySearchStr($searchStr,$noOfUsers)
	{
		$userArr = $this->dUserObj->getBySearchStr($searchStr,"");
		if(count($userArr)>0)
			if($noOfUsers==1)
				return $userArr[0];
			else
				return $userArr;
		else
			return null;
	}
	
	function isClientLimitReached($userObj){
		$clientCount = $this->dUserObj->getClientCount($userObj->UserID);
		/* echo $userObj->UserID.',';
		echo $clientCount.',';
		echo $userObj->ClientLimit; */
		if($userObj->ClientLimit!=-1 && $clientCount>=$userObj->ClientLimit)
			return true;
		else
			return false;
	}
	function getUserFee($userObj,$newUserRoleID){
		$userFee = $userObj->RetailerFee;
		if($newUserRoleID==4)//Distributor
			$userFee=$userObj->DistributorFee;
		else if($newUserRoleID==5)//SubDistibutor
			$userFee=$userObj->MandalFee;
		return $userFee;
	}
	function enoughBalanceInWallet($userObj,$amountToUse){
		$userForWallet = $this->getWalletBalance($userObj->UserID);
		if(($userForWallet[0] - $userObj->BalanceLevel - $amountToUse) >= 0)
			return true;
		else
			return false;
	}
	function haveEnoughBalance($userObj,$newUserRoleID){
		//echo "<br/> haveEnoughBalance =userObj=".json_encode($userObj);
		$userFee = $this->getUserFee($userObj,$newUserRoleID);
		$userForWallet = $this->getWalletBalance($userObj->UserID);
		//$userForWallet = json_encode($userForWallet);
		/* echo "<br/> userForWallet->Wallet=".json_encode($userForWallet[0]);
		echo "<br/> Wallet=".$userForWallet[0].',';
		echo "<br/> BalanceLevel=".$userObj->BalanceLevel.',';
		echo "<br/> userFee=".$userFee.',';
		echo "<br/> Final=".($userForWallet[0] - $userObj->BalanceLevel - $userFee) */;
		if(($userForWallet[0] - $userObj->BalanceLevel - $userFee) >= 0)
			return true;
		else
			return false;
	}
	function isEligibleToAdd($userObj,$newUserRoleID){
		$result = 2;//User Limit Reached
		$isClientLimitOK = !$this->isClientLimitReached($userObj);
		if($isClientLimitOK)
		{
			$isWalletOK = $this->haveEnoughBalance($userObj,$newUserRoleID);
			//echo '<br/>isWalletOK='.$isWalletOK;
			if($isWalletOK)
				$result = 1;
			else
				$result = 3;//Not enough balanc
		}
		return $result;
	}
	function updateWallet($userID,$amount,$logMsg){
		return $res = $this->dUserObj->updateWallet($this->me,$userID,$amount,$logMsg);
	}
	function addUserFromWebservice($user,$newUser,$reqObj,$param1,$param2,$param3,$roleID,$rndPassword){
		$this->me=$user;
		/* echo "<br/>addUserFromWebservice reqObj=".json_encode($reqObj);
		echo "<br/>param1=".$param1;//Code
		echo "<br/>param2=".$param2;//Mobile
		echo "<br/>param3=".$param3;//Name */
		$newUser->UserID = 0;
		$newUser->ParentID = $user->UserID;
		$newUser->RoleID = $roleID;
		$newUser->Name = $param3;
		$newUser->Mobile = $param2;
		$newUser->Gender = "";
		$newUser->DOB = "";
		$newUser->Email = "";
		$newUser->Address = "";		
		$newUser->ClientLimit = "0";
		$newUser->BalanceLevel = "0";
		/*
		//CAUTION: We can't take ClientLimit from Admin. Since admin can create any number of users we are indicating -1 for admin as unlimited.
		//Role-6 is retailer. Retailer doesn't have any child
		if($roleID<6){
			//Load ClientLimit and BalanceLevel from admin details. 
			$adminObjs = $this->dUserObj->getByID(1);
			if(count($adminObjs)>0){
				$newUser->ClientLimit = $adminObjs[0]->ClientLimit;
				$newUser->BalanceLevel = $adminObjs[0]->BalanceLevel;
			}else{
				$newUser->ClientLimit = "25";
				$newUser->BalanceLevel = "100";
			}
		} */
		
		$this->gsObj=new b_generalsettings($this->me,$this->mysqlObj,$this->lang);
		$gs = $this->gsObj->get();
		$newUser->DistributorFee = $gs->DistributorFee;//TODO:Take it from general
		$newUser->MandalFee = $gs->SubDistributorFee;
		$newUser->RetailerFee = $gs->RetailerFee;
		$newUser->Deposit = 0;
		$newUser->Remarks = "Added from Webservice";
		$newUser->PAN = "";
		$newUser->Password = $rndPassword;
		$newUser->Refundable = "0";
		$newUser->CreatedDate = date('Y-m-d H:i:s');
		$newUser->CreatedBy = $user->UserID;
		$newUser->ModifiedBy = $user->UserID;
		$newUser->AllowedIPs = '0.0.0.0';
		//echo "<br/>newUserObj=".json_encode($newUser);
		$isExists = $this->isMobileNoExists($newUser->Mobile,$newUser->UserID);
		if(!$isExists){
			$res = $this->upsert($newUser,$reqObj);
			return $res;
		}
		else{
			$res = new httpresult();
			$res->isSuccess=true;
			$res->code="111";
			$res->InsertedUserID="0";
			$res->message="Mobile Number Exists,";
			return $res;
		}
		//echo "<br/> Adding new user Result=".json_encode($res);
	}
	function addUserRelatedSettings($userObj){
		$resultObj = new httpresult();
		$fatData = $this->bFatObj->getByUserID($userObj->RoleID,1);
		if(count($fatData)!=1){
			$resultObj->isSuccess=false;
			$resultObj->code="00";
			$resultObj->message="Failed to get FAT data for Role=".$userObj->RoleID;
			return $resultObj;
		}
		
		$fatData->UserID=$userObj->UserID;
		$fatData->Name="For UserID-".$userObj->UserID;
		$fatData->IsRole=0;
		$insertedFatdID = $this->bFatObj->add($fatData);
		//Copy Service permission from default(user id is -1) incase of parent is admin. otherwise copy from parent.
		$spCopyFromID = $userObj->ParentID==1?-1:$userObj->ParentID;
		//echo 'parent='.$userObj->ParentID;
		$isSpAssigned = $this->bSpObj->copy($spCopyFromID,$userObj->UserID);
		
		//Copy distributorMargin/RcGateway in case of admin child
		$isDistMarginCopied = true;
		$isDistRCGatewayCopied = true;
		if($userObj->ParentID==1){
			$isDistMarginCopied = false;
			$isDistMarginCopied = $this->dmObj->copy($userObj->ParentID,$userObj->UserID);
			$isDistRCGatewayCopied=false;
			//echo "<br/> Copying started for RC gateway. parent=".$userObj->ParentID.", userid=".$userObj->UserID;
			$isDistRCGatewayCopied = $this->rcgObj->copy($userObj->ParentID,$userObj->UserID);
		}
		if($isDistRCGatewayCopied==false || $isDistMarginCopied == false || $isSpAssigned == false || $insertedFatdID==0){
			$resultObj->isSuccess=false;
			$resultObj->code="12";
			$resultObj->message=$this->lang!=null?$this->lang['au_sp_unassign']:"Service Permission Unassigned";
			return $resultObj;//Failed. Client limit reached
		}
		$resultObj->isSuccess=true;
		$resultObj->code="00";
		$resultObj->message="Success";
		return $resultObj;//Failed. Client limit reached
	}
	function upsert($userObj,$reqObj){
		$resultObj = new httpresult();
		//echo "<br/> userObj=".json_encode($userObj);
		if($userObj->UserID==0){
			if($userObj->DOB=='')					
				$userObj->DOB = date('Y-m-d');
			if($userObj->ClientLimit=='')					
				$userObj->ClientLimit = 0;
			$parentObj = $this->dUserObj->getByID($userObj->ParentID);
			//echo count($parentObj);
			if(count($parentObj)<=0)
			{		
				$resultObj->isSuccess=false;
				$resultObj->code="11";
				$resultObj->message="Parent not found";
				return $resultObj;
			}
			$userObj->Ancestors = $parentObj[0]->Ancestors.$parentObj[0]->UserID."/";
			//echo "<br/> parentObj=".json_encode($parentObj);
			//echo "<br/> userObj=".json_encode($userObj);
			$userObj->MinOpenBalanceMargin=$parentObj[0]->MinOpenBalanceMargin;
			$isEligible = $this->isEligibleToAdd($parentObj[0],$userObj->RoleID);
			if($isEligible!=1){		
				$resultObj->isSuccess=false;
				//$this->lang?$this->lang['au_limit_reached']
				if($isEligible==2){
					$resultObj->code="16";
					$resultObj->message=$this->lang?$this->lang['au_limit_reached']:"User Limit Reached";
				}else{
					$resultObj->code="17";
					$resultObj->message=$this->lang?$this->lang['au_no_balance']:"Not enough balance";
				}
				return $resultObj;
			}
			/* $resultObj->isSuccess=false;
			$resultObj->message="Eliblie to Add";
			return $resultObj;  */ 
			
			$userObj->CreatedDate = date('Y-m-d H:i:s');
			//echo $userObj->CreatedBy;
			$userObj->CreatedBy = $this->me->UserID;
			$userObj->ModifiedBy = $this->me->UserID;
			$insertedUserID=$this->dUserObj->add($userObj);
			$insertedUserDisplayID = $this->updateDisplayID($insertedUserID,$userObj->RoleID);
			//$insertedUserID=0;//TODO:This is stop inserting unwanted fat data
			//echo '<br />inserted user id='.$insertedUserID;
			if($insertedUserID>0)
			{
				$newUserRoleObj = $this->bRoleObj->getByID($userObj->RoleID);
				$logMsg = "created new ($newUserRoleObj->Name) id $insertedUserDisplayID ($userObj->Name)";
				//echo $parentObj[0]->userID;
				//return false;
				$this->dUserObj->updateWallet($this->me,$userObj->ParentID,-1*$this->getUserFee($parentObj[0],$userObj->RoleID),$reqObj->RequestID,$logMsg);
				//echo '<br/> User inserted:'.$insertedUserID;
				//echo "<br/> user obj=".json_encode($userObj);
				$fatData = $this->bFatObj->getByUserID($userObj->RoleID,1);
				//return 'count'.count($fatData);
				if(count($fatData)==1){
					//echo '<br/> FAT inserted for this user';
					$fatData->UserID=$insertedUserID;
					$fatData->Name='For New User';
					$fatData->IsRole=0;
					$insertedFatdID = $this->bFatObj->add($fatData);
					
					//Copy Service permission from default(user id is -1) incase of parent is admin. otherwise copy from parent.
					$spCopyFromID = $userObj->ParentID==1?-1:$userObj->ParentID;
					//echo 'parent='.$userObj->ParentID;
					$isSpAssigned = $this->bSpObj->copy($spCopyFromID,$insertedUserID);
					
					//Copy distributorMargin/RcGateway in case of admin child
					$isDistMarginCopied = true;
					$isDistRCGatewayCopied = true;
					if($userObj->ParentID==1){
						$isDistMarginCopied = false;
						$isDistMarginCopied = $this->dmObj->copy($userObj->ParentID,$insertedUserID);
						$isDistRCGatewayCopied=false;
						//echo "<br/> Copying started for RC gateway";
						$isDistRCGatewayCopied = $this->rcgObj->copy($userObj->ParentID,$insertedUserID);
					}
					/*echo 'isDistRCGatewayCopied='.$isDistRCGatewayCopied;
					echo ',isDistMarginCopied='.$isDistMarginCopied;
					echo ',isSpAssigned='.$isSpAssigned;
					echo ',insertedFatdID='.$insertedFatdID;*/
					if($isDistRCGatewayCopied==false || $isDistMarginCopied == false || $isSpAssigned == false || $insertedFatdID==0){
						$resultObj->isSuccess=false;
						$resultObj->code="12";
						$resultObj->message=$this->lang!=null?$this->lang['au_sp_unassign']:"Service Permission Unassigned";
						return $resultObj;//Failed. Client limit reached
					}
					else{		
						$resultObj->isSuccess=true;
						$resultObj->code="NewUserToParent_s";
						$resultObj->InsertedUserID=$insertedUserDisplayID;
						$resultObj->message=$this->lang?$this->lang['au_success']:"Success";
						return $resultObj;//Success
					}
				}
				else{
					$resultObj->isSuccess=false;
					$resultObj->code="14";
					$resultObj->message=$this->lang['au_feature_unassign'];
					return $resultObj;
				}
			}
			else{
				$resultObj->isSuccess=false;
				$resultObj->code="15";
				$resultObj->message=$this->lang!=null?$this->lang['au_failed']:"Failed to Create User,";
				return $resultObj;
			}
		}else{
			$userObj->ModifiedBy = $this->me->UserID;
			$existingUser = $this->getByID($userObj->UserID);
			//echo "<br/> Old Parent ID=".$existingUser->ParentID;
			//echo "<br/> New Parent ID=".$userObj->ParentID;
			
			//If parent ID has changed then delete the settings and reassign.
			//As of now only FAT is being deleted.
			if($existingUser->ParentID!=$userObj->ParentID){
				//echo "<br/> Diff parent";
				$res =$this->dUserObj->deleteUserRelatedData($userObj->UserID);
				$resultObj = $this->addUserRelatedSettings($userObj);
				if(!$resultObj->isSuccess)
					return $resultObj;
				//echo "<br/> addUserRelatedSettings.res=".json_encode($res);
			}
			//If Role is changed then change the display ID.
			if($existingUser->RoleID!=$userObj->RoleID){
				$newDisplayID = $this->updateDisplayID($userObj->UserID,$userObj->RoleID);
				$userObj->DisplayID = $newDisplayID;
			}
			$res=$this->dUserObj->update($userObj);
			$resultObj->isSuccess=$res;
			if($res){
				$resultObj->code="13";
				$resultObj->message=$this->lang['au_success'];
			}else{
				$resultObj->code="15";
				$resultObj->message=$this->lang!=null?$this->lang['au_failed']:"Failed to Update User.";
			}
			return $resultObj;
			
		}
	}
	
	//This fn is being converted to getUsersByRoleIDs
	function getUsersByRoles($roleNames){
		$roles = $this->bRoleObj->getAllByRoleNames($roleNames);
		//echo '<br/> eeee='.json_encode($roles);
		return $this->dUserObj->getUsersByRoles($roles);
	}
	function getUsersByRoleIDs($roleIDs,$userID){
		//$roles = $this->bRoleObj->getRoleByIDs($roleIDs);
		//echo '<br/> eeee='.json_encode($roles);
		return $this->dUserObj->getUsersByRoleIDs($roleIDs,$userID);
	}
	function getUsersByParentID($parentID,$includeParent,$excludeRoleIDs,$includeAllSubUsers=0){
		return $this->dUserObj->getUsersByParentID($parentID,$includeParent,$excludeRoleIDs,$includeAllSubUsers);
	}
	function getAllUsers($parentID,$includeParent,$includeAllSubUsers,$excludeRoleIDs){
		return $this->dUserObj->getAllUsers($parentID,$includeParent,$includeAllSubUsers,$excludeRoleIDs);
	}
	function enableDisableByUserID($userID,$isDisable){
		$res =$this->dUserObj->enableDisableByUserID($userID,$isDisable);
		return $res;
	}
	function deleteUser($userID){
		$resultObj = new httpresult();
		$res =$this->dUserObj->deleteUser($userID);
		if($res==1){
			//Delete dist margin if any
			//Delete rc permission if any
			//Delete Rc gateway if any
			$res =$this->dUserObj->deleteUserRelatedData($userID);
			
			// $res = $this->bFatObj->deleteByUserID($userID);
			// $res = $this->bSpObj->deleteByUserID($userID);
			// $res = $this->dmObj->deleteByUserID($userID);
			// $res = $this->rcgObj->deleteByUserID($userID);
			
			$resultObj->isSuccess=true;
			$resultObj->message=$this->lang['delete_success'];
		}else{
			$resultObj->isSuccess=false;
			$resultObj->message=$this->lang['delete_failed'];
		}
		return $resultObj;
	}
	function refreshUserSession($userID){
		$loginResultJson = s_GetUserDetails();
		$res = $this->getByID($userID);
		//$loginResultJson = json_decode($sessionUser);
		//echo "<br/> refreshUserSession. res=".json_encode($res);
		$loginResultJson->user = $res;
		//echo json_encode($loginResultJson);
		$_SESSION['me']=json_encode(json_encode($loginResultJson));
	}
	function updateAdminUserFee($feesObj){
		$feesObj->ModifiedBy = $this->me->UserID;
		$res = $this->dUserObj->updateAdminUserFee($feesObj);
		$this->refreshUserSession($this->me->UserID);
		return $res;
	}
	function getUsers_DT(){
		return $this->dUserObj->getUsers_DT();
	}
	function getUsersByParent_DT($parentID,$includeParent,$roleID){
		return $this->dUserObj->getUsersByParent_DT($parentID,$includeParent,$roleID);
	}
	function isMobileNoExists($mobileNo, $userId){
		return $this->dUserObj->isMobileNoExists($mobileNo, $userId);
	}
	function updateDisplayID($userID,$roleID){
		$displayIDPrefix='R';//Retailer
		if($roleID==2) $displayIDPrefix='A';//SuperAdmin
		else if($roleID==3) $displayIDPrefix='S';//StateDistibutor
		else if($roleID==4) $displayIDPrefix='D';//Distributor
		else if($roleID==5) $displayIDPrefix='B';//Sub distributor
		else if($roleID==6) $displayIDPrefix='R';//Retailer
		//echo 'No-'.$userID.'is less than 10='.($userID<10?'t':'f');
		$displayIDPostfix='0000';
		if($userID<10) $displayIDPostfix='000'.$userID;
		else if($userID<100) $displayIDPostfix='00'.$userID;
		else if($userID<1000) $displayIDPostfix='0'.$userID;
		else $displayIDPostfix=$userID;
		
		$displayID=$displayIDPrefix.$displayIDPostfix;
		$this->dUserObj->updateDisplayID($userID,$displayID);
		return $displayID;
	}
	function getWalletBalance($userID,$parentID=-1){
		$res = $this->dUserObj->getWalletBalance($userID,$parentID);
		if(count($res)>0)
			return $res[0];
		else{
			/* $this->Wallet=0;
			$this-BalanceLevel=0; */
			return 0;
		}
		/*$resultObj = new httpresult();
			$res = $this->dUserObj->getUserBalance($userID);
		if(count($res)>0){
			$resultObj->isSuccess=true;
			$resultObj->message=$this->lang['au_success'];
			$resultObj->data=json_encode($res[0]);
		}else{
			$resultObj->isSuccess=false;
			$resultObj->message=$this->lang['au_failed'];
		}
		return $resultObj;*/
	}
	function getRoleName($roleID){
		
		$newUserRoleObj = $this->bRoleObj->getByID($roleID);
		if(count($newUserRoleObj)>0)
			return $newUserRoleObj->Name;
		else
			return "No Role";
	}
}
?>