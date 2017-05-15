<?php
include_once 'd_mysqldb.php';
include_once APPROOT_URL.'/Entity/e_recharge.php';
include_once APPROOT_URL.'/Database/d_ecwdatatable.php';
class d_sms{
	private $db;
	var $me;
	private $dtObj;
	function __construct($mysqlObj,$thisUser){
		$this->db = $mysqlObj;
		$this->me = $thisUser;
		//echo "<br/><hr/>d_sms user =".json_encode($this->me);
		$this->dtObj = new EcwDataTable($mysqlObj);
	}
	function add($gatewayID,$refID,$refTable,$to,$msg,$devInfo,$response,$smsGateway){
		$this->p("add");
		//$createdDate=date('Y-m-d h:i:s');
		$userID=$this->me?$this->me->UserID:0;
		
		$q="INSERT INTO t_sms(GatewayID,ReferenceID,ReferenceTable,Mobile,Message,DevInfo,Response,SMSGateway,CreatedBy) VALUE('$gatewayID','$refID','$refTable','$to','$msg','$devInfo','$response','$smsGateway','$userID')";
		//echo '<br/> Query= '.$q;
		$res=$this->db->insert($q);
		$this->p("Inserted ID=".$res);
		return $res;
	}
	function p($msg){
		//echo "<br/> SMS ->".$msg;
	}
	
	/* OutGoing Request */
	function getOutgoing_DT($userId, $message, $api_name, $fromDate, $toDate){
		$table = 't_sms';
		$index_column = 'SmsID';
		$columns = array('SmsID', 'User_ID', 'Name', 'DisplayID', 'Mobile', 'Message', 'CreatedDate', 'SMSGateway' );
		$select="SELECT SQL_CALC_FOUND_ROWS  u.DisplayID As User_ID,u.Name,s.SmsID,s.Mobile,s.Message,	
			s.CreatedDate, r.DisplayID, s.SMSGateway FROM t_sms as s 
		       LEFT JOIN t_request as r on 
			   		s.ReferenceID =r.RequestID 
		       LEFT JOIN m_users as u on 
			   		r.UserID =u.UserID ";
			$where=" WHERE DATE(s.`CreatedDate`) >= STR_TO_DATE('$fromDate 00:00:00', '%Y-%m-%d %H:%i:%s') AND DATE(s.`CreatedDate`)<= STR_TO_DATE('$toDate 23:59:59', '%Y-%m-%d %H:%i:%s') ";
			if($userId!=""){
				$where.="AND r.UserID='$userId' ";
			}
			if($api_name!=""){
				$where.="AND s.`SMSGateway`='$api_name' ";
			}
			if($message!=""){
				$where.="AND s.`Message` LIKE '%$message%' ";
			}
			$orderBy=" ORDER BY s.SmsID DESC ";
			$query = $select.$where.$orderBy;

		return $this->dtObj->get($table, $index_column, $columns,$query);
	}
	/* End */
}
?>