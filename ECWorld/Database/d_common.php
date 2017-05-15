<?php
include_once 'd_mysqldb.php';
class d_common{
	private $db;
	private $me;
	private $lang;
	function __construct($me,$mysqlObj,$lang){
		$this->me = $me;
		$this->db = $mysqlObj;
		$this->lang = $lang;
	}
	function makeInActive($table,$field,$value){
		try{
			$userID=$this->me->UserID;
			$q="UPDATE $table SET Active=0,ModifiedBy=$userID WHERE $field=$value;";
			$res=$this->db->execute($q);
			return $res;
		}catch(Exception $ex){
			echo '<br />DB- Error';
			$errorlogObj = new errorlog($this);
			$errorlogObj->add($ex->getMessage(),'0','Testing','Testing');
		}
	}
}
?>