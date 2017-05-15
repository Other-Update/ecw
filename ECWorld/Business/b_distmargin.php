<?php
include_once APPROOT_URL.'/Database/d_distmargin.php';
include_once APPROOT_URL.'/Business/b_datatable.php';
class b_distmargin{
	var $dbObj;
	private $me;
	private $lang;
	function __construct($thisUser,$mysqlObj,$lang){
		$this->me=$thisUser;
		$this->lang=$lang;
		$this->dbObj=new d_distmargin($mysqlObj);
	}
	function getByUserID($userID){
		return $this->dbObj->getByUserID($userID);
		/*$httpObj = new httpresult();
		$res = $this->dbObj->getByUserID($userID);
		$httpResult=$httpObj->getHttpResultObj(true,"Success",$res);
		return json_encode($httpResult);*/
	}
	
	function getUserMinOpeningBalance($userID){
		$res=  $this->dbObj->getUserMinOpeningBalance($userID);
		//echo json_encode($res);
		if(count($res) > 0 )
			return $res[0]->MinOpenBalanceMargin;
		else
			return 0;
		
	}
	
	function updateOpenBalance($userID, $openBalance){
		return $this->dbObj->updateOpenBalance($userID, $openBalance);
	}
	
	function getByUserIDAndAmount_NIU($userID,$amount){
		return $res = $this->dbObj->getByUserIDAndAmount($userID,$amount);
	}
	function add($obj){
		$obj->ModifiedBy = $this->me->UserID;
		$obj->CreatedBy = $this->me->UserID;
		return $this->dbObj->add($obj);
	}
	function deleteByUser($userID){
		return $this->dbObj->deleteByUser($userID);
	}
	function update($userID){
		$httpObj = new httpresult();
		$res = $this->dbObj->getByUserID($userID);
		//echo '<br/> d_margin='.json_encode($res);
		$httpResult=$httpObj->getHttpResultObj(true,"Success",$res);
		return json_encode($httpResult);
	}
	function getUsers_DT($parentID,$excludeRoleIDs){
		return $this->dbObj->getUsers_DT($parentID,$excludeRoleIDs);
	}
	function copy($fromUserID,$toUserID){
		try{
			$res = $this->dbObj->copy($fromUserID,$toUserID);
			
			return $res;
		}catch(Exception $ex){
			echo '<br />BS- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
	/* function getOpeningBalanceByDate($date){
		$res = $this->dbObj->getOpeningBalanceByDate($date);
	} */
}
?>
