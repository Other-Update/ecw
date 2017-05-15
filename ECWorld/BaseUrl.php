<?php
//Reghu PC
/* define("APPROOT_URL", "C:\wamp\www\ECWorld\\"); 
define("GENERAL_URL", "C:\wamp\www\ECWorld\General\\");
define("BUSINESS_URL", "C:\wamp\www\ECWorld\Business\\");
define("DATABASE_URL", "C:\wamp\www\ECWorld\Database\\");
define("ENTITY_URL", "C:\wamp\www\ECWorld\Entity\\");
define("RESOURCE_URL", "C:\wamp\www\ECWorld\Resource\\");
define("SESSION_URL", "C:\wamp\www\ECWorld\Website\Session\\"); */

// Ilaiya PC
define("APPROOT_URL", dirname(__FILE__)); 

//To set IST time in all functions by default
date_default_timezone_set('Asia/Calcutta');

/* 
APPROOT_URL.'/Database/
APPROOT_URL.'/Resource/
APPROOT_URL.'/General/
APPROOT_URL.'/Business/
APPROOT_URL.'/Entity/
 */

/* define("GENERAL_URL", APPROOT_URL."\General\\");
define("BUSINESS_URL", APPROOT_URL."\Business\\");
define("DATABASE_URL", APPROOT_URL."\Database\\");
define("ENTITY_URL", APPROOT_URL."\Entity\\");
define("RESOURCE_URL", APPROOT_URL."\Resource\\");
define("SESSION_URL", APPROOT_URL."\Website\Session\\"); */
?>