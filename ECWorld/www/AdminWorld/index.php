<?php 
//include '../WebsiteUrl/WebsiteUrl.php';
function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
redirect('http://www.ecworld.co.in/AdminWorld/Login/');
?>
