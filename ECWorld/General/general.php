<?php
$config = require APPROOT_URL.'/Settings/config.php';
class httpresult{
	var $isSuccess;
	var $message;
	var $otherInfo;
	var $code;
	var $data;
	var $IsSuccess;
	var $Message;
	var $Data;
	function __construct(){
		$isSuccess=false;
	}
	function getHttpResultObj($isSuccess,$message,$data){
		$this->isSuccess = $isSuccess;
		$this->message = $message;
		$this->data = $data;
		return $this;
	}
	function getHttpResult($isSuccess,$message,$data){
		$this->IsSuccess = $isSuccess;
		$this->Message = $message;
		$this->Data = $data;
		return $this;
	}
}

class common{
	var $putpriority;
	public $config;
	function __construct(){
		$this->putpriority[1]=1;//Enable always
		$this->putpriority[2]=1;//Enable while debug
		$this->putpriority[3]=0;//NIU
	}
	function getToday(){
		return date('Y-m-d H:i:s');
	}
	function put($p,$msg){
		echo "<br/>".$p."->";
		if($this->putpriority[$p])
			echo $msg;
	}

}

/* class configurationsettings{
	public $config;
	function __construct($config){
		$this->config=$config;
	}
}
global $configuration;
$configuration = new configurationsettings($config); */
?>