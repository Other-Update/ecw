<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_automnp.php';
include_once APPROOT_URL.'/Entity/e_networkprovider.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php'; 
class d_automnp{
	private $db;
	var $me;
	private $dtObj;
	function __construct($me,$mysqlObj){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	
	function get_DT($rcType){
		$table = 'm_auto_mnp';
		$index_column = 'NeworkID';
		$columns = array('NeworkID', 'MobileNo', 'Name', 'NetworkName');
		$query="SELECT SQL_CALC_FOUND_ROWS m.NeworkID,m.MobileNo,n.Name,m.NetworkName FROM m_auto_mnp as m left join m_networkprovider as n on 	m.NetworkName=n.NetworkProviderID  WHERE m.RCType='$rcType' AND m.Active=1 ORDER BY m.NeworkID ASC";
		return $this->dtObj->get($table, $index_column, $columns,$query);

	}
	
	function getByID($id){
		$q="SELECT Name FROM m_networkprovider WHERE NetworkProviderID='$id' ";
		$arr=$this->db->selectArray($q,'e_automnp');
		return $arr;
	}
	function getNetwork($mobileno,$type){
		$q="SELECT net.NetworkProviderID, net.Name,net.NetworkProviderID FROM m_auto_mnp AS am 
			LEFT JOIN m_networkprovider AS net ON
				am.NetworkName = net.NetworkProviderID
			WHERE am.MobileNo LIKE '".$mobileno."%' AND am.RCType='$type' AND am.Active=1 AND net.Active=1 "; 
		//echo $q;
		$arr=$this->db->selectArray($q,'e_automnp');
		return $arr;
	}
	
	
	function getNetworkList(){
		$q="SELECT NetworkProviderID,Name FROM m_networkprovider WHERE Active=1 ";
		$arr=$this->db->selectArray($q,'e_automnp');
		return $arr;
	}
	
	
	function add($autoObj){
		try{ 							
			$autoObj->CreatedBy = $this->me->UserID;
			$autoObj->CreatedDate = date('Y-m-d h:i:s');
			$autoObj->ModifiedBy = $this->me->UserID;
			$autoObj->ModifiedDate = date('Y-m-d h:i:s');
			
			 $count ="SELECT Count(*) As FieldCount FROM m_auto_mnp WHERE  MobileNo='$autoObj->MobileNo' AND Active=1";
			 $result=$this->db->selectArray($count,'e_automnp');
			 
			$countName ="SELECT Count(*) As NameCount, NetworkProviderID FROM m_networkprovider WHERE  Name='$autoObj->Name' AND Active=1";
			 $result2=$this->db->selectArray($countName,'e_networkprovider');
			 
			//echo $result2[0]->NetworkProviderID;
			/* if($result2[0]->NameCount <= 0) {
				echo "k";
			} else {
				echo "f";
			}  */
			if($autoObj->Name == ''){
				if($result[0]->FieldCount <= 0 ) {
					$q="INSERT INTO m_auto_mnp(`MobileNo`, `NetworkName`, `RCType`, `CreatedBy`, `CreatedDate`, `ModifiedDate`, `ModifiedBy`) VALUE('$autoObj->MobileNo','$autoObj->NetworkName','$autoObj->RCType','$autoObj->CreatedBy', '$autoObj->CreatedDate', '$autoObj->ModifiedDate','$autoObj->ModifiedBy')"; 
				$res=$this->db->insert($q);
				} else {
				 $q = "UPDATE m_auto_mnp SET  `NetworkName`='$autoObj->NetworkName', `ModifiedDate`= '$autoObj->ModifiedDate', `ModifiedBy`='$autoObj->ModifiedBy' WHERE `MobileNo`='$autoObj->MobileNo' ";
				$res=$this->db->execute($q);
				}
			} 
			else {
				if($result2[0]->NameCount <= 0){
					$q="INSERT INTO m_networkprovider(`Name`, `CreatedBy`, `CreatedDate`, `ModifiedDate`, `ModifiedBy`) 
						VALUE('$autoObj->Name', '$autoObj->CreatedBy', '$autoObj->CreatedDate', '$autoObj->ModifiedDate','$autoObj->ModifiedBy')";
					$lastId=$this->db->insert($q);
				} else{
					$q = "UPDATE m_networkprovider SET  `Name`='$autoObj->Name', `ModifiedDate`= '$autoObj->ModifiedDate', `ModifiedBy`='$autoObj->ModifiedBy' WHERE `Name`='$autoObj->Name' ";
					$res=$this->db->execute($q);
					$lastId=$result2[0]->NetworkProviderID;
				}
				 if($result[0]->FieldCount <= 0 ) {
					$q="INSERT INTO m_auto_mnp(`MobileNo`, `NetworkName`, `RCType`, `CreatedBy`, `CreatedDate`, `ModifiedDate`, `ModifiedBy`) 
					VALUE('$autoObj->MobileNo','$lastId','$autoObj->RCType','$autoObj->CreatedBy', '$autoObj->CreatedDate', '$autoObj->ModifiedDate', '$autoObj->ModifiedBy')";
					$res=$this->db->insert($q);
				} else {
					$q = "UPDATE m_auto_mnp SET  `NetworkName`='$lastId', `ModifiedDate`= '$autoObj->ModifiedDate', `ModifiedBy`='$autoObj->ModifiedBy' WHERE `MobileNo`='$autoObj->MobileNo' ";
					$res=$this->db->execute($q);
				} 
			}
			return $res; 
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
	

	function update($autoObj){
		try{ 							
			$autoObj->ModifiedDate = date('Y-m-d h:i:s');
			$autoObj->ModifiedBy = $this->me->UserID;
			$autoObj->CreatedBy = $this->me->UserID;
			
			$countName ="SELECT Count(*) As NameCount, NetworkProviderID FROM m_networkprovider WHERE  Name='$autoObj->Name' AND Active=1";
			$result2=$this->db->selectArray($countName,'e_networkprovider');
			
			if($autoObj->Name != ''){
				if($result2[0]->NameCount <= 0){
						$q="INSERT INTO m_networkprovider(`Name`, `CreatedBy`, `CreatedDate`, `ModifiedDate`, `ModifiedBy`) 
							VALUE('$autoObj->Name', '$autoObj->CreatedBy', '$autoObj->CreatedDate', '$autoObj->ModifiedDate','$autoObj->ModifiedBy')";
						$lastId=$this->db->insert($q);
				} else{
					$q = "UPDATE m_networkprovider SET  `Name`='$autoObj->Name', `ModifiedDate`= '$autoObj->ModifiedDate', `ModifiedBy`='$autoObj->ModifiedBy' WHERE `Name`='$autoObj->Name' ";
					$res=$this->db->execute($q);
					$lastId=$result2[0]->NetworkProviderID;
				}
			
				$q = "UPDATE m_auto_mnp SET `MobileNo`='$autoObj->MobileNo', `NetworkName`='$lastId', `ModifiedDate`= '$autoObj->ModifiedDate', `ModifiedBy`='$autoObj->ModifiedBy' WHERE 
				`NeworkID`='$autoObj->NeworkID' ";
				$res=$this->db->execute($q);
			
			
			} else {
				$q = "UPDATE m_auto_mnp SET `MobileNo`='$autoObj->MobileNo', `NetworkName`='$autoObj->NetworkName', `ModifiedDate`= '$autoObj->ModifiedDate', `ModifiedBy`='$autoObj->ModifiedBy' WHERE 
				`NeworkID`='$autoObj->NeworkID' ";
				$res=$this->db->execute($q);
			}
			return $res; 
			
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}

	
}
?>