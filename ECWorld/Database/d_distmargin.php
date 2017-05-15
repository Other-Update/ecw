<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_distmargin.php';
include_once APPROOT_URL.'/Entity/e_users.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_distmargin{
	private $db;
	private $dtObj;
	function __construct($mysqlObj){
		$this->dtObj = new DataTable();
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function getByUserID($userID){
		$q="SELECT * FROM m_distmargin WHERE UserID='$userID' ";
		$res=$this->db->selectArray($q,'e_distmargin');
		//echo '<br/> d_margin='.$userID;
		return $res;
	}
	
	function getUserMinOpeningBalance($userID){
		$q="SELECT `MinOpenBalanceMargin` FROM m_users WHERE UserID='$userID' AND Active=1 ";
		$res=$this->db->selectArray($q,'e_users');
		//echo '<br/> d_margin='.$userID;
		return $res;
	}
	
	function updateOpenBalance($userID, $openBalance){
		$q="UPDATE m_users SET `MinOpenBalanceMargin`='$openBalance'  WHERE UserID='$userID'  ";
		$res=$this->db->execute($q);
		return $res;
	}
	
	function getByUserIDAndAmount_NIU($userID,$amount){
		$q="SELECT * FROM m_distmargin WHERE UserID='$userID' AND FromAmount<='$amount' AND ToAmount>='$amount'";
		$res=$this->db->selectArray($q,'e_distmargin');
		//echo '<br/> d_margin='.$userID;
		return $res;
	}
	function deleteByUser($userID){
		$q="DELETE FROM m_distmargin WHERE UserID='$userID'";
		$res=$this->db->execute($q);
		//echo $q;
		return $res;
	}
	function add($obj){
		$obj->CreatedDate = date('Y-m-d h:i:s');
		$q="INSERT INTO m_distmargin(UserID,FromAmount,ToAmount,NormalBilling,RegularBilling,CreatedDate,CreatedBy,ModifiedBy) 
		VALUE('$obj->UserID','$obj->FromAmount','$obj->ToAmount','$obj->NormalBilling','$obj->RegularBilling','$obj->CreatedDate','$obj->CreatedBy','$obj->ModifiedBy')";
		//echo $q;
		$res=$this->db->insert($q);
		return $res;
	}
	/* function getOpeningBalanceByDate($date){
		$q = "SELECT OpeningBalance FROM t_payment WHERE UserID=u.UserID AND Amount>0 AND CreatedDate LIKE '%$date%' ORDER BY CreatedDate DESC LIMIT 1";
		return $this->db->selectArray($q,'e_distmargin');
	} */
	function getUsers_DT($parentID,$excludeRoleIDs){
		$table_name = 'm_users';
		$index_column = 'UserID';
		$columns = array('UserUID','UserID', 'UserName', 'MobileNo', 'PreviousMargin', 'OpeningBalance','Purchase','CurrentMargin','CurrentBalance','IsEligibleForRegularBilling');
		$qANDs = " ";
		$i=0;
		while($i<count($excludeRoleIDs)){
			$roleID = $excludeRoleIDs[$i];
			if($i==0) $qANDs.=" AND RoleID!=$roleID";
			else $qANDs.=" AND RoleID!=$roleID";
			$i++;
		}
		$yesterday=date('Y-m-d',strtotime("-1 days"));
		$today=date('Y-m-d');
		$qPrevMargin = "SELECT CommissionPercent FROM t_payment WHERE UserID=u.UserID AND Amount>0 AND CreatedDate like '%$yesterday%' ORDER BY CreatedDate DESC LIMIT 1";
		//$qOpeningBal = "SELECT OpeningBalance FROM t_payment WHERE UserID=u.UserID AND Amount>0 AND CreatedDate LIKE '%$yesterday%' ORDER BY CreatedDate DESC LIMIT 1";
		$qOpeningBal = "SELECT IFNULL((SELECT OpeningBalance FROM t_transaction WHERE UserID=u.UserID AND CreatedDate LIKE '%$today%' ORDER BY CreatedDate ASC LIMIT 1),(SELECT ClosingBalance AS OpeningBalance FROM t_transaction WHERE UserID=u.UserID ORDER BY CreatedDate DESC LIMIT 1)) AS OpeningBalance";
		//echo "<br/> qOpeningBal=".$qOpeningBal;
		$qTotalPurchase = "SELECT IFNULL(ABS(SUM(Amount)),0.00) FROM t_payment WHERE UserID=u.UserID AND ((Mode<>6 AND Amount>0) OR (Mode=6 AND Amount<0)) AND CreatedDate like '%$today%'";
		//$qTotalPurchaseCredit = "SELECT IFNULL(SUM(Amount),0.00) FROM t_payment WHERE UserID=u.UserID AND Amount>0 AND Type=1 AND CreatedDate like '%$today%'";
		//$qTotalPurchaseDebit = "SELECT IFNULL(SUM(Amount),0.00) FROM t_payment WHERE UserID=u.UserID AND Amount>0 AND Type=2 AND CreatedDate like '%$today%'";
		
		$qCurMargin = "SELECT CommissionPercent FROM t_payment WHERE UserID=u.UserID AND (Type=1 OR Type=2) AND Amount>0 AND CreatedDate like '%$today%' ORDER BY CreatedDate DESC LIMIT 1";
		$qCurrentBalance='SELECT ClosingBalance AS OpeningBalance FROM t_transaction WHERE UserID=u.UserID ORDER BY CreatedDate DESC LIMIT 1';
		
		//$columnNames = "u.UserID as UserUID,u.DisplayID as UserID, u.Name as UserName, u.Mobile as MobileNo, ($qPrevMargin) as PreviousMargin, ($qOpeningBal) as OpeningBalance, ($qTotalPurchase) as Purchase, ($qCurMargin) as CurrentMargin,($qCurrentBalance) as CurrentBalance, IF((($qPrevMargin)=($qCurMargin)),'Yes','No') as IsEligibleForRegularBilling";
		$isEligibileForRegularBill = "IF((($qOpeningBal)>=u.MinOpenBalanceMargin),'Yes','No')";
		$columnNames = "u.UserID as UserUID,u.DisplayID as UserID, u.Name as UserName, u.Mobile as MobileNo, ($qPrevMargin) as PreviousMargin, ($qOpeningBal) as OpeningBalance, ($qTotalPurchase) as Purchase, ($qCurMargin) as CurrentMargin,($qCurrentBalance) as CurrentBalance, ($isEligibileForRegularBill) as IsEligibleForRegularBilling";
		$query="SELECT SQL_CALC_FOUND_ROWS $columnNames FROM m_users as u WHERE (u.ParentID=$parentID OR u.ParentID=-1) $qANDs AND u.Active=1";
		
		//echo $query;
		return $this->dtObj->get($table_name, $index_column, $columns,$query);
	}
	
	function copy($fromUserID,$toUserID){
		try{
			$q="SELECT * FROM m_distmargin where UserID='$fromUserID' and Active='1'";
			//echo '<br />DB- Query = '.$q;
			$res=$this->db->selectArray($q,'e_distmargin');
			//echo '<br />DB- Query Res sp id= '.$res[0]->ServicePermissionID;
			//echo '<br />DB- Query Res isOTFCommission= '.$res[0]->isOTFCommission;
			if(count($res)>0)
			{
				$i=0;
				//echo '<br/> count($res)='.count($res);
				while($i<count($res))
				{
					$res[$i]->UserID=$toUserID;
					//ServicePermissionID
					$this->add($res[$i]);
					$i++;
				}
				return true;
			}
			else return false;
			//return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','servicepermission','Testing');
		}
	}
}