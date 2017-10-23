<?php
include_once APPROOT_URL.'/Business/Token/b_jwthelper.php';
class ECWToken{	
	//Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
	//$secretKey = base64_decode($config->get('jwtKey'));
	var $secretKey = "ECWSecretKey";
	// 3600 hrs is 1 hour
	var $tokenExpiryInSec = 36000;//This is 10 hours
	function __construct(){
	}
	function isValid($token){
		//echo $token->exp;
		//echo "<br/>";
		//echo time();
		if($token){
			if($token->exp < time())return false;
			else	return true;
		}
		else{
			return false;
		}
		//echo "<br />";
	}
	function renew($token){
		$token->iat = time();
		$token->exp = $token->iat+$this->tokenExpiryInSec;
		return $token;
	}
	//Reurns token Object
	function decrypt($tokenEnc){
		$token = JWT::decode($tokenEnc, $this->secretKey);
		return $token;
	}
	//Returns encrypted token string
	function encrypt($token){
		$tokenEnc = JWT::encode($token, $this->secretKey);
		return $tokenEnc;
	}
	function getToken($user){//$userID,$userName){
		$tokenId    = base64_encode(mcrypt_create_iv(32));
		$issuedAt   = time();
		$notBefore  = $issuedAt;//Adding 10 seconds
		$expire     = $notBefore+$this->tokenExpiryInSec;// Adding 3600 seconds/60mins/1hr
		$serverName = "ECWServer";//$config->get('serverName'); // Retrieve the server name from config file
		
		/*
		 * Create the token as an array
		 */
		$data = [
			'iat'  => $issuedAt,         // Issued at: time when the token was generated
			'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
			'iss'  => $serverName,       // Issuer
			'nbf'  => $notBefore,        // Not before
			'exp'  => $expire,           // Expire
			'data' => [                  // Data related to the signer user
				'userId'   => $user->UserID, // userid from the users table
				'userName' => $user->Name, // User name
			]
		];
		$token = JWT::encode($data, $this->secretKey);
		return $token;
	}
}
global $ecwToken;
$ecwToken = new ECWToken();
$GLOBALS['EcwToken'] = $ecwToken;
?>
