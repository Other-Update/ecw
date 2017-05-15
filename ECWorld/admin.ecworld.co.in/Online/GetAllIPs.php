<?php
include_once "../../BaseUrl.php";
include_once APPROOT_URL.'/Business/z_testing.php';
//echo json_encode($mysqlObj);
$testObj = new z_b_testing($mysqlObj);
$res = $testObj->getAll();
echo "<hr/>";
echo "IPAddressID , ";
echo "Name I, ";
echo "IPAddress";
echo '<br/>';
foreach($res as $item) {
	echo '<hr/>';
    echo $item->IPAddressID." , ";
    echo $item->Name." , ";
    echo $item->IPAddress;
    echo '<br/>';
}
?>