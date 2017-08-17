<?php
class b_http{
	private $me;
	private $lang;
	//var $dObj;
	function __construct($thisUser,$mysqlObj,$lang){
		$this->me=$thisUser;
		$this->lang=$lang;
		//$this->dObj=new d_transaction($mysqlObj);
	}
	function getEncodedString($str){
		// Set String to search for.
		$x = " ";
		// Set string to replace with. 
		$y = "%20";
		// Concatenate
		$url = str_replace($x,$y,$str);
		return $url;
	}
	function HTTPGet($url){
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // return web page
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "ECW Name", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
		); 
		//echo "<br/> HTTTP URL=".$url;
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);

		$content  = curl_exec($ch);

		curl_close($ch);

		return $content;
	}
}
/*
ECWStatus
---------
1-Pending
2-Suspense
//-Accepted
3-Success
4-Failed
*/
class b_cloudapi{
	var $apiType = "Cloud";
	var $maxRetryCount =3;
	function __construct(){
	}
	function getDetailsByResponseCode($response){
		$details = '{"Description":"","ECWStatus":2}';
		switch($response){
			case "SUCCESS":
				$details = '{"Description":"Success","ECWStatus":3}';
				break;
			case "CANCEL":
				$details = '{"Description":"Cancel","ECWStatus":4}';
				break;
			case "FAILURE":
				$details = '{"Description":"Failure","ECWStatus":4}';
				break;
			case "SUSPENSE":
				$details = '{"Description":"Suspense","ECWStatus":2}';
				break;
			default:
				$details = '{"Description":"Unknown Response","ECWStatus":2}';
				break;
		}
		return json_decode($details);
	}
	/*
		1-Pending(requested)
		2-Suspense(Accepted/Processing)
		3-Success
		4-Failed
	*/
	function getStringBetween($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	function getTargetMobileFromResponse($msg){
		//echo "<br/>getTargetMobileFromResponse".$msg;
		$mobile = $this->getStringBetween($msg,"NUM: "," AMT:");
		if(strlen($mobile)==10)
			return $mobile;
		else
			return null;
	}
	function getTargetAmountFromResponse($msg){
		//echo "<br/>getTargetAmountFromResponse".$msg;
		$amount = $this->getStringBetween($msg,"AMT: "," TXID:");
		return $amount;
	}
	function getDetailsByImmediateResponseCode($respCode){
		//echo "<br/> from getDetailsByImmediateResponseCode=".$respCode;
		$details = '{"Description":"","Retry":0,"ECWStatus":2}';
		switch($respCode){
			case false;
				$details = '{"Description":"False","Retry":0,"ECWStatus":4}';
				break;
			case 1200:
				$details = '{"Description":"Request Accepted","Retry":0,"ECWStatus":2}';
				break;
			case 1201:
				$details = '{"Description":"Invalid Login","Retry":0,"ECWStatus":4}';
				break;
			case 1202:
				$details = '{"Description":"Invalid Mobile Number","Retry":0,"ECWStatus":4}';
				break;
			case 1203:
				$details = '{"Description":"Invalid Amount","Retry":0,"ECWStatus":4}';
				break;
			case 1204://Need to check again
				$details = '{"Description":"Transaction ID missing","Retry":0,"ECWStatus":4}';
				break;
			case 1205://Admin wants message
				$details = '{"Description":"Operator not found","Retry":0,"ECWStatus":4}';
				break;
			case 1206://Admin wants message
				$details = '{"Description":"Permission Required","Retry":0,"ECWStatus":4}';
				break;
			case 1207://Admin wants message
				$details = '{"Description":"Balance Limit","Retry":0,"ECWStatus":4}';
				break;
			case 1208://Admin wants message
				$details = '{"Description":"Low Balance","Retry":0,"ECWStatus":4}';
				break;
			case 1209://No chance if getting this situation. Failed.
				$details = '{"Description":"Duplicate Request","Retry":0,"ECWStatus":4}';
				break;
			case 1210://Failed
				$details = '{"Description":"Request not accepted","Retry":0,"ECWStatus":4}';
				break;
			case 1211://Retry
				$details = '{"Description":"Recharge server not connected","Retry":1,"ECWStatus":4}';
				break;
			case 1212:
				$details = '{"Description":"Authentication Failed","Retry":0,"ECWStatus":4}';
				break;
			case 2000://Ilaiya resp code. not API code
				$details = '{"Description":"Unable to inser record","Retry":0,"ECWStatus":4}';
				break;
			default:
				$details = '{"Description":"Unknown Response","Retry":0,"ECWStatus":2}';
				break;
		}
		return json_decode($details);
	}
}

//TODO: This is copied from b_cloudapi. So change to mars API
class b_marsapi{
	var $apiType = "Mars";
	var $maxRetryCount =3;
	function __construct(){
	}
	function getDetailsByResponseCode($response){
		$details = '{"Description":"","ECWStatus":2}';
		switch($response){
			case "SUCCESS":
				$details = '{"Description":"Success","ECWStatus":3}';
				break;
			case "FAILURE":
				$details = '{"Description":"Failure","ECWStatus":4}';
				break;
			case "SUSPENSE":
				$details = '{"Description":"Suspense","ECWStatus":2}';
				break;
			case "POWER ON DEL":
				$details = '{"Description":"POWER ON DEL","ECWStatus":4}';
				break;
			case "ABORTED":
				$details = '{"Description":"ABORTED","ECWStatus":4}';
				break;
			default:
				$details = '{"Description":"Unknown Response","ECWStatus":2}';
				break;
		}
		return json_decode($details);
	}
	/*
		1-Pending(requested)
		2-Suspense(Accepted/Processing)
		3-Success
		4-Failed
	*/
	function getStringBetween($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	function getTargetMobileFromResponse($msg){
		//echo "<br/>getTargetMobileFromResponse".$msg;
		$mobile = $this->getStringBetween($msg,"NUM: "," AMT:");
		if(strlen($mobile)==10)
			return $mobile;
		else
			return null;
	}
	function getTargetAmountFromResponse($msg){
		//echo "<br/>getTargetAmountFromResponse".$msg;
		$amount = $this->getStringBetween($msg,"AMT: "," TXID:");
		return $amount;
	}
	function getDetailsByImmediateResponseCode($respCode){
		//echo "<br/> from getDetailsByImmediateResponseCode=".$respCode;
		$details = '{"Description":"","Retry":0,"ECWStatus":2}';
		switch($respCode){
			case false;
				$details = '{"Description":"False","Retry":0,"ECWStatus":4}';
				break;
			case 1200:
				$details = '{"Description":"Request Accepted","Retry":0,"ECWStatus":2}';
				break;
			case 1201:
				$details = '{"Description":"Invalid Login","Retry":0,"ECWStatus":4}';
				break;
			case 1202:
				$details = '{"Description":"Invalid Mobile Number","Retry":0,"ECWStatus":4}';
				break;
			case 1203:
				$details = '{"Description":"Invalid Amount","Retry":0,"ECWStatus":4}';
				break;
			case 1204://Need to check again
				$details = '{"Description":"Transaction ID missing","Retry":0,"ECWStatus":4}';
				break;
			case 1205://Admin wants message
				$details = '{"Description":"Operator not found","Retry":0,"ECWStatus":4}';
				break;
			case 1206://Admin wants message
				$details = '{"Description":"Permission Required","Retry":0,"ECWStatus":4}';
				break;
			case 1207://Admin wants message
				$details = '{"Description":"Balance Limit","Retry":0,"ECWStatus":4}';
				break;
			case 1208://Admin wants message
				$details = '{"Description":"Low Balance","Retry":0,"ECWStatus":4}';
				break;
			case 1209://No chance if getting this situation. Failed.
				$details = '{"Description":"Duplicate Request","Retry":0,"ECWStatus":4}';
				break;
			case 1210://Failed
				$details = '{"Description":"Request not accepted","Retry":0,"ECWStatus":4}';
				break;
			case 1211://Retry
				$details = '{"Description":"Recharge server not connected","Retry":1,"ECWStatus":4}';
				break;
			case 1212:
				$details = '{"Description":"Authentication Failed","Retry":0,"ECWStatus":4}';
				break;
			case 2000://Ilaiya resp code. not API code
				$details = '{"Description":"Unable to inser record","Retry":0,"ECWStatus":4}';
				break;
			default:
				$details = '{"Description":"Unknown Response","Retry":0,"ECWStatus":2}';
				break;
		}
		return json_decode($details);
	}
}

class b_manualapi{
	var $apiType = "Manual";
	var $maxRetryCount =3;
	function __construct(){
	}
	function getDetailsByResponseCode($response){
		$details = '{"Description":"","ECWStatus":2}';
		switch($response){
			case "SUCCESS":
				$details = '{"Description":"Success","ECWStatus":3}';
				break;
			case "CANCEL":
				$details = '{"Description":"Cancel","ECWStatus":4}';
				break;
			case "FAILURE":
				$details = '{"Description":"Failure","ECWStatus":4}';
				break;
			case "SUSPENSE":
				$details = '{"Description":"Suspense","ECWStatus":2}';
				break;
			default:
				$details = '{"Description":"Unknown Response","ECWStatus":2}';
				break;
		}
		return json_decode($details);
	}
	/*
		1-Pending(requested)
		2-Suspense(Accepted/Processing)
		3-Success
		4-Failed
	*/
	function getStringBetween_NIU($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	function getTargetMobileFromResponse_NIU($msg){
		//echo "<br/>getTargetMobileFromResponse".$msg;
		$mobile = $this->getStringBetween($msg,"NUM: "," AMT:");
		if(strlen($mobile)==10)
			return $mobile;
		else
			return null;
	}
	function getTargetAmountFromResponse_NIU($msg){
		//echo "<br/>getTargetAmountFromResponse".$msg;
		$amount = $this->getStringBetween($msg,"AMT: "," TXID:");
		return $amount;
	}
	//Only 2000 status is required
	function getDetailsByImmediateResponseCode($respCode){
		//echo "<br/> from getDetailsByImmediateResponseCode=".$respCode;
		$details = '{"Description":"","Retry":0,"ECWStatus":2}';
		switch($respCode){
			case false;
				$details = '{"Description":"False","Retry":0,"ECWStatus":4}';
				break;
			case 1200:
				$details = '{"Description":"Request Accepted","Retry":0,"ECWStatus":2}';
				break;
			case 1201:
				$details = '{"Description":"Invalid Login","Retry":0,"ECWStatus":4}';
				break;
			case 1202:
				$details = '{"Description":"Invalid Mobile Number","Retry":0,"ECWStatus":4}';
				break;
			case 1203:
				$details = '{"Description":"Invalid Amount","Retry":0,"ECWStatus":4}';
				break;
			case 1204://Need to check again
				$details = '{"Description":"Transaction ID missing","Retry":0,"ECWStatus":4}';
				break;
			case 1205://Admin wants message
				$details = '{"Description":"Operator not found","Retry":0,"ECWStatus":4}';
				break;
			case 1206://Admin wants message
				$details = '{"Description":"Permission Required","Retry":0,"ECWStatus":4}';
				break;
			case 1207://Admin wants message
				$details = '{"Description":"Balance Limit","Retry":0,"ECWStatus":4}';
				break;
			case 1208://Admin wants message
				$details = '{"Description":"Low Balance","Retry":0,"ECWStatus":4}';
				break;
			case 1209://No chance if getting this situation. Failed.
				$details = '{"Description":"Duplicate Request","Retry":0,"ECWStatus":4}';
				break;
			case 1210://Failed
				$details = '{"Description":"Request not accepted","Retry":0,"ECWStatus":4}';
				break;
			case 1211://Retry
				$details = '{"Description":"Recharge server not connected","Retry":1,"ECWStatus":4}';
				break;
			case 1212:
				$details = '{"Description":"Authentication Failed","Retry":0,"ECWStatus":4}';
				break;
			case 2000://Ilaiya resp code. not API code
				$details = '{"Description":"No need to call API","Retry":0,"ECWStatus":2}';
				break;
			default:
				$details = '{"Description":"Unknown Response","Retry":0,"ECWStatus":2}';
				break;
		}
		return json_decode($details);
	}
}

?>