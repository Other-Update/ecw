<?php 
//include '../WebsiteUrl/WebsiteUrl.php';
function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
redirect('http://www.ecworld.info/Distributor/Login/');
?>
