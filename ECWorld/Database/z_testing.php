<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/z_testing.php';
class z_d_testing{
	private $db;
	function __construct($mysqlObj){
		$this->db = $mysqlObj;
	}
	function getAll(){
		$q= "SELECT * FROM z_ipaddress";
		//echo $q;
		$res=$this->db->selectArray($q,'z_testing');
		//echo "111111111111";
		return $res;
	}
	function getByName($name){
		$q= "SELECT * FROM z_ipaddress WHERE Name='$name'";
		$res=$this->db->selectArray($q,'z_testing');
		return $res;
	}
	function upsert($name,$ip){
		$now=date('Y-m-d H:i:s');
		$rows = $this->getByName($name);
		$q="INSERT INTO z_ipaddress(Name,IPAddress) VALUE('$name','$ip')";
		if(count($rows)>0){
		$q="UPDATE z_ipaddress SET IPAddress='$ip' WHERE Name='$name'";
		}
		echo '<br/> Query= '.$q;
		$res=$this->db->insert($q);
		return $res;
	}
}
?>