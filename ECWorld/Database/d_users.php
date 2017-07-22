<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_users.php';
include_once APPROOT_URL.'/Entity/e_transaction.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_users{
	private $db;
	private $dtObj;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function login($username,$password){
		$password=md5($password);//.'_'.$password;
		//$q="SELECT UserID,Mobile,Name,RoleID,ClientLimit,Active,DistributorFee,MandalFee,RetailerFee FROM m_users WHERE (Mobile='$username' OR DisplayID='$username') and Password='$password'";
		$q="SELECT UserID,Mobile,Name,RoleID,ClientLimit,Active,DistributorFee,MandalFee,RetailerFee FROM m_users WHERE Mobile='$username' and Password='$password'";
		//echo $q;
		//$arr=$this->db->selectArray($q,'e_users');
		$arr=$this->db->login($username,$password,'e_users');
		return $arr;
	}
	function getByID($userID){
		$q="SELECT * FROM m_users WHERE UserID='$userID' OR DisplayID='$userID'";
		$json=$this->db->selectArray($q,'e_users');
		//echo $json;
		return $json;
	}
	function updateWallet($thisUser,$userID,$amount,$reqID,$logMsg){		
		//$obj->ModifiedBy = $thisUser->UserID;
		/* echo $thisUser->UserID.',';
		echo $userID.',';
		echo $amount.',';
		echo $logMsg.','; */
		//$q="call UpdateWallet('$userID','$amount','$logMsg','$thisUser->UserID')";
		/*
			4- is transaction type for add user
			2- debit(Not needed)
			-1 = request id. since there is no request(TODO: Make request first and send proper id)
			0  = closing balance(Not needed)
		*/
		//$q="call AddTransaction('4','$userID','2','$amount','-1','0','$logMsg','m_users','$thisUser->UserID','$thisUser->UserID')";
		$q="call AddTransaction('4','$userID','2','$amount','$reqID','0','$logMsg','m_users','$thisUser->UserID','$thisUser->UserID')";
		
		//echo $q;
		$res=$this->db->execute($q);
		$res=1;
	}
	function add($userObj){
		$userObj->Password=md5($userObj->Password);//.'_'.$userObj->Password;
		$q="INSERT INTO m_users(Name,ParentID,Ancestors,Mobile,Gender,DOB,Email,Address,ClientLimit,BalanceLevel,DistributorFee,MandalFee,RetailerFee,Deposit,Remarks,PAN,Password,RoleID,Refundable,MinOpenBalanceMargin,CreatedDate,CreatedBy,ModifiedBy) 
		VALUE('$userObj->Name','$userObj->ParentID','$userObj->Ancestors','$userObj->Mobile','$userObj->Gender','$userObj->DOB','$userObj->Email','$userObj->Address','$userObj->ClientLimit','$userObj->BalanceLevel','$userObj->DistributorFee','$userObj->MandalFee','$userObj->RetailerFee','$userObj->Deposit','$userObj->Remarks','$userObj->PAN','$userObj->Password','$userObj->RoleID','$userObj->Refundable','$userObj->MinOpenBalanceMargin','$userObj->CreatedDate','$userObj->CreatedBy','$userObj->ModifiedBy')";
		//echo $q;
		$res=$this->db->insert($q);
		return $res;
	}
	function update($userObj){
		//TODO-1: Update only the editable fields
		//TODO-2: Plan to update password seperately
		$pass = $userObj->Password;
		if($pass[0] != '*' && $pass[1] != '*' && $pass[strlen($pass) - 1] != '*') {
			$userObj->Password=md5($userObj->Password);//.'_'.$userObj->Password;
			$qp="UPDATE m_users SET Password='$userObj->Password' WHERE UserID=$userObj->UserID;";
			$PassRes=$this->db->execute($qp);
		}
		$q="UPDATE m_users SET Name='$userObj->Name',ParentID='$userObj->ParentID',Mobile='$userObj->Mobile',Gender='$userObj->Gender',DOB='$userObj->DOB',Email='$userObj->Email',Address='$userObj->Address',ClientLimit='$userObj->ClientLimit',BalanceLevel='$userObj->BalanceLevel',DistributorFee='$userObj->DistributorFee',MandalFee='$userObj->MandalFee',RetailerFee='$userObj->RetailerFee',Remarks='$userObj->Remarks',PAN='$userObj->PAN',RoleID='$userObj->RoleID',Refundable='$userObj->Refundable',ModifiedBy='$userObj->ModifiedBy' WHERE UserID=$userObj->UserID;";
		//echo '<br/>'.$q;
		$res=$this->db->execute($q);
		return $res;
	}
	function getByMobile($mobileNo){
		$q="SELECT * FROM m_users WHERE Mobile='$mobileNo'";
		$json=$this->db->selectArray($q,'e_users');
		//echo $q;
		return $json;
	}
	function getByDisplayID($userDID){
		$q="SELECT * FROM m_users WHERE DisplayID='$userDID'";
		$json=$this->db->selectArray($q,'e_users');
		//echo $json;
		return $json;
	}
	function getByDisplayIDByParent($parentID,$userDID){
		$q="SELECT * FROM m_users WHERE (UserID='$userDID' OR DisplayID='$userDID') AND ParentID='$parentID'";
		//echo $q;
		$json=$this->db->selectArray($q,'e_users');
		//echo json_encode($json);
		return $json;
	}
	function getByDisplayIDByAncestor($ancestorID, $userID){
		$q="SELECT * FROM m_users WHERE (UserID='$userID' OR DisplayID='$userID') AND Ancestors LIKE '%/$ancestorID/%'";
		//echo $q;die;
		$json=$this->db->selectArray($q,'e_users');
		//echo json_encode($json);
		return $json;
	}
	
	function getClientCount($userID){
		$q="SELECT COUNT(*) as Count FROM m_users WHERE ParentID='$userID'";
		$arr=$this->db->selectArray($q,'e_users');
		//echo $json;
		return $arr[0]->Count;
	}
	
	//Updating to getUsersByRoleIDs
	function getUsersByRoles($roles){
		$q='SELECT * FROM m_users';
		$i=0;
		while($i<count($roles)){
			$roleID = $roles[$i]->RoleID;
			if($i==0) $q.=" WHERE (RoleID=$roleID";
			else $q.=" OR RoleID=$roleID";
			$i++;
			//echo '<br/>'.$q;
		}
		//if(count($roles)>0) $q.=" AND Active='1'";
		//echo $q;
		$arr=$this->db->selectArray($q,'e_users');
		return $arr;
	}
	function getUsersByRoleIDs($roleIDs,$userID){
		$q='SELECT * FROM m_users';
		$i=0;
		while($i<count($roleIDs)){
			$roleID = $roleIDs[$i];
			if($i==0) $q.=" WHERE (RoleID=$roleID";
			else $q.=" OR RoleID=$roleID";
			$i++;
			//echo '<br/>'.$q;
		}
		if($userID==1)
			$q.=")";
		else
			$q.=") AND (UserID=$userID OR ParentID=$userID)";
		//if(count($roles)>0) $q.=" AND Active='1'";
		//echo $q;
		//echo json_encode($this->db);
		$arr=$this->db->selectArray($q,'e_users');
		return $arr;
	}
	function getUsersByParentID($parentID,$includeParent,$excludeRoleIDs,$includeAllSubUsers=0){
		//echo "includeAllSubUsers=".$includeAllSubUsers;
		$qORs = "";
		if($includeParent)
			$qORs = $qORs." OR UserID='$parentID'";
		
		$qANDs = " ";
		$i=0;
		while($i<count($excludeRoleIDs)){
			$roleID = $excludeRoleIDs[$i];
			/* if($i==0) $qANDs.=" AND RoleID!=$roleID";
			else $qANDs.=" AND RoleID!=$roleID"; */
			$qANDs.=" AND RoleID!=$roleID";
			$i++;
		}
		$q="SELECT * FROM m_users WHERE ParentID='$parentID' $qANDs $qORs";
		//Ref: http://stackoverflow.com/questions/20215744/how-to-create-a-mysql-hierarchical-recursive-query#answer-20216006
		if($includeAllSubUsers)
			$q="SELECT * FROM m_users WHERE Ancestors LIKE '%$parentID%' $qANDs $qORs";
		//echo $q;
		$arr=$this->db->selectArray($q,'e_users');
		return $arr;
	}
	function getAllUsers($parentID,$includeParent,$includeAllSubUsers,$excludeRoleIDs){
		$qANDs = " ";
		$qORs = " ";
		if($includeParent)
			$qORs = $qORs." OR UserID='$parentID'";
		$i=0;
		while($i<count($excludeRoleIDs)){
			$roleID = $excludeRoleIDs[$i];
			if($i==0) $qANDs.=" AND RoleID!=$roleID";
			else $qANDs.=" AND RoleID!=$roleID";
			$i++;
		}
		$properties = 'UserID,GUID,DisplayID,ParentID,Ancestors,Name,Mobile,Gender,DOB,Email,Address,Wallet,ClientLimit,BalanceLevel,DistributorFee,MandalFee,RetailerFee,Deposit,Remarks,PAN,ID,RoleID,Refundable,MinOpenBalanceMargin,Active';
		$q="SELECT ".$properties." FROM m_users WHERE ParentID='$parentID' $qANDs $qORs";
		if($includeAllSubUsers)
			$q="SELECT ".$properties." FROM m_users WHERE Ancestors LIKE '%$parentID%' $qANDs $qORs";
		//Ref: http://stackoverflow.com/questions/20215744/how-to-create-a-mysql-hierarchical-recursive-query#answer-20216006
		/* if($includeAllSubUsers)
		$q="select  *
			from    (select * from m_users
					 order by ParentID, UserID) products_sorted,
					(select @pv := '$parentID') initialisation
			where   (find_in_set(ParentID, @pv) > 0
			and     @pv := concat(@pv, ',', UserID))
			$qANDs $qORs"; */
		//echo '<br/>'.$q." -";
		$arr=$this->db->selectArray($q,'e_users');
		return $arr;
	}
	function updateAdminUserFee($feesObj){
		
		$q="UPDATE m_users SET DistributorFee='$feesObj->DistributorFees', MandalFee='$feesObj->SubDistributorFees', RetailerFee='$feesObj->RetailerFees', ModifiedBy='$feesObj->ModifiedBy' WHERE `UserID`=1 ";
		//echo '<br/> Query= '.$q;
		$res=$this->db->execute($q);
		//echo '<br/> Result= '.$res;
		return $res;
	}
	
	function getUsers_DT(){
		$table = 'm_users';
		$index_column = 'UserID';
		$columns = array('UserID','Name', 'RoleName', 'Mobile','Refundable','ParentID','ClientLimit','DOB','Wallet','Status','UniqueUserID');
		//$myWhere = "UserID = '".$userID."' AND Active=1 ";
		$qClientCount='SELECT COUNT(*) FROM m_users WHERE ParentID=u.UserID';
		$qCurrentBalance='SELECT p.ClosingBalance FROM t_transaction AS p WHERE UserID=u.UserID ORDER BY p.CreatedDate DESC LIMIT 1';
		$query="SELECT SQL_CALC_FOUND_ROWS u.DisplayID as UserID, u.Name, r.Name as RoleName, u.Mobile, u.Refundable, IFNULL((SELECT DisplayID from m_users where UserID=u.ParentID),0) AS ParentID,u.ClientLimit-($qClientCount) as ClientLimit, IFNULL(u.DOB,'0000-0000') AS DOB,IFNULL(($qCurrentBalance),'0') AS Wallet, u.Active as Status,u.UserID as UniqueUserID FROM m_users as u left join m_role as r on u.RoleID=r.RoleID ORDER BY u.UserID ASC";
		//echo $query;
		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	function getUsersByParent_DT($parentID,$includeParent,$roleID){
		$table = 'm_users';
		$index_column = 'UserID';
		$columns = array('UserID','Name', 'RoleName', 'Mobile','Refundable','ParentID','ClientLimit','DOB','Wallet','Status','UniqueUserID','BalanceLevel');
		//$myWhere = "UserID = '".$userID."' AND Active=1 ";
		
		$qClientCount='SELECT COUNT(*) FROM m_users WHERE ParentID=u.UserID';
		
		$qCurrentBalance='SELECT p.ClosingBalance FROM t_transaction AS p WHERE UserID=u.UserID ORDER BY p.CreatedDate DESC LIMIT 1';
		
		$qSelectFields="SELECT u.DisplayID as UserID, u.Name, r.Name as RoleName, u.Mobile, u.Refundable, IFNULL((SELECT DisplayID from m_users where UserID=u.ParentID),0) AS ParentID,u.ClientLimit-($qClientCount) as ClientLimit, IFNULL(u.DOB,'0000-0000') AS DOB,IFNULL(($qCurrentBalance),'0') AS Wallet, u.Active as Status,u.UserID as UniqueUserID,u.RoleID,u.ParentID AS ParentIDOriginal ,BalanceLevel ";
		
		$qFrom=" FROM m_users as u left join m_role as r on u.RoleID=r.RoleID ";
		
		$qANDsORs="";
		//echo ",includeParent=".$includeParent.",";
		//echo ",roleID=".$roleID.",";
		/* if($includeParent)
			$qANDsORs.=" OR UserID='$parentID' "; */
		
		//-1 means SELECT, meaning - all users but do not include sub users.
		if($roleID!=0 && $roleID!=-1)
			$qANDsORs.=" AND u.RoleID='$roleID' ";
		
		$q="$qSelectFields $qFrom WHERE ParentID='$parentID' $qANDsORs";	
		if($includeParent)
			$q="$qSelectFields $qFrom WHERE (ParentID='$parentID' OR UserID='$parentID') $qANDsORs";	
			
		if($roleID==0){
			$q="$qSelectFields $qFrom WHERE Ancestors LIKE '%/$parentID/%' $qANDsORs";
			if($includeParent)				
				$q="$qSelectFields $qFrom WHERE (Ancestors LIKE '%/$parentID/%'  OR UserID='$parentID') $qANDsORs";
		}			
		/*if($roleID==0){
			//0 means ALL , meaning - include sub users too.
			$qANDsORs="";
			//if($roleID!=0)
				//$qANDsORs.=" AND RoleID='$roleID' "; 
			$q="select * from ($qSelectFields $qFrom) products_sorted,
						(select @pv := '$parentID') initialisation
				where (find_in_set(ParentIDOriginal, @pv) > 0
				and @pv := concat(@pv, ',', UserID)) $qANDsORs";
		}*/			
		
		//$q.=" ORDER BY u.UserID ASC";
		//$query=$query.$qWhere.$qOrderBy;
		//echo $q;
		return $this->dtObj->get($table, $index_column, $columns,$q);
	}
	
	function updateDisplayID($userID,$displayID){
		$q="UPDATE m_users SET DisplayID='$displayID' WHERE UserID='$userID'";
		$res=$this->db->execute($q);
		//$this->dUserObj->updateDisplayID($userID,$displayID);
	}
	function deleteUser($userID){
		$q="DELETE FROM m_users WHERE UserID='$userID' OR DisplayID='$userID'";
		$res=$this->db->execute($q);
		//echo $q;
		return $res;
		//$this->dUserObj->updateDisplayID($userID,$displayID);
	}
	function enableDisableByUserID($userID,$isDisable){
	
		$q="UPDATE m_users SET Active='1'";
		if($isDisable)
			$q="UPDATE m_users SET Active='0'";
		
		if(is_numeric($userID)){
			$q.=" WHERE UserID='$userID'";
		}else{
			$q.=" WHERE DisplayID='$userID'";
		}
		//echo "<br/> enableDisableByUserID=".$q;die;
		$res=$this->db->execute($q);
		return $res;
		//$this->dUserObj->updateDisplayID($userID,$displayID);
	}
	function isMobileNoExists($mobile, $userId){
		$q="SELECT Name FROM m_users WHERE Mobile='$mobile' AND UserID != '$userId' AND Active=1 ";
		//echo "isMobileNoExists:".$q;
		$arr=$this->db->selectArray($q,'e_users');
		return count($arr)>0 ? 1 : 0;
	}
	
	function getWalletBalance($userID,$parentID){
		//$q="SELECT UserID,Wallet,BalanceLevel FROM m_users WHERE UserID='$userID'";
		//echo "<br/> parentid=".$parentID;
		//$qCurrentBalance='SELECT t.ClosingBalance FROM t_payment AS t WHERE UserID=u.UserID ORDER BY t.CreatedDate DESC LIMIT 1';
		$q="SELECT IFNULL(t.ClosingBalance,'0') as Wallet,u.UserID,u.BalanceLevel FROM m_users AS u LEFT JOIN t_transaction AS t ON t.UserID=u.UserID ";
		if($parentID==1 || $parentID==-1)
			$q.=" WHERE u.UserID='$userID' OR u.DisplayID='$userID' ";
		else
			$q.=" WHERE (u.UserID='$userID' OR u.DisplayID='$userID') AND ParentID='$parentID' ";
		$q.=" ORDER BY t.CreatedDate DESC LIMIT 1";
		//echo $q;
		
		$arr=$this->db->selectArray($q,'e_transation');
		return $arr;
	}
	//Here Ancestor means immediate child of Admin from this users ancestors
	//Returns 1 if unable to find ancestor(Which shouldn't happen)
	function getAncestorIDByUserID($userID){	
		$userAncestorID = 1;
		$qAncestor = "SELECT Ancestors FROM m_users WHERE UserID=".$userID;
		//echo "<br/>qAncestor=".$qAncestor;
		$ansRes=$this->db->selectArray($qAncestor,'e_users');
		//echo "<br/>qAncestor ansRes=".json_encode($ansRes);
		if(count($ansRes)>0){
			//echo "<br/> getByUserAndService. Ancestor=".json_encode($res1[0]);
			//echo "<br/> ansRes[0]->Ancestors=".$ansRes[0]->Ancestors;
			$ancestArr = explode("/", $ansRes[0]->Ancestors);
			//echo "<br/> ancestArr=".count($ancestArr);
			if(count($ancestArr)>=4){
				//echo "<br/> getByUserAndService. Ancestor=".$ancestArr[2];						
				$userAncestorID = $ancestArr[2];
			}
		}
		//echo $userAncestorID;
		return $userAncestorID;
	}
	function deleteUserRelatedData($userID){
		$qDelFAT="DELETE FROM m_fat WHERE UserID='$userID' AND IsRole='0'";
		$qDelSP="DELETE FROM m_servicepermission WHERE UserID='$userID'";
		//$qDelSPA="DELETE FROM m_servicepermissionassign WHERE UserID='$userID'";
		$qDelDM="DELETE FROM m_distmargin WHERE UserID='$userID'";
		$qDelRCG="DELETE FROM m_rcusergateway WHERE UserID='$userID'";
		
		/* echo "<br/> Delete FAT q=".$qDelFAT;
		echo "<br/> Delete SP q=".$qDelSP;
		//echo "<br/> Delete SPA q=".$qDelSPA;
		echo "<br/> Delete DM q=".$qDelDM;
		echo "<br/> Delete RCG q=".$qDelRCG; */
		
		$resFAT=$this->db->execute($qDelFAT);
		$resSP=$this->db->execute($qDelSP);
		//$resSPA=$this->db->execute($qDelSPA);
		$resDM=$this->db->execute($qDelDM);
		$resRCG=$this->db->execute($qDelRCG);
		
		return $resRCG;
	}
	function prepareToChangeParent($userID){
	}
}
?>