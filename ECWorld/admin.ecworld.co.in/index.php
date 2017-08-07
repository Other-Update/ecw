<?php
function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
redirect("http://ecworld.info/AdminWorld/");
?>