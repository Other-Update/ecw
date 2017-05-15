<?php
//include_once "G:\wamp\www\ECWorld\BaseUrl\baseUrl.php";
include_once APPROOT_URL.'/Database/d_datatable.php';
class b_datatable{
	private $filename='b_service';
	private $dbObj;
	private $me;
	private $lang;
	private $dtObj;
	function __construct($me,$mysqlObj,$lang){
		$this->dbObj=$me;
		$this->lang=$lang;
		$this->dtObj = new DataTable();
	}
	function get($table, $index_column, $columns,$myWhere){
		return $this->dtObj->get($table, $index_column, $columns,$myWhere);
	}
}
//$table_data->get('m_users', 'UserID', array('Name', 'Mobile', 'RoleID'));

?>