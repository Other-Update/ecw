<?php
$username ="root";
$password ="";
$hostname = "localhost";
$database = "ecworld";
$username =escapeshellcmd($username);
$password =escapeshellcmd($password);
$hostname =escapeshellcmd($hostname);
$database =escapeshellcmd($database);
//$backupFile=’/home/www/example.com/backuprestricted/’.date(“Y-m-d-H-i-s”).$database.’.sql’;
//$backupFile="G:/wamp/www/ECWorldBB/ECWorldwww/DBBackup/".date("Y-m-d-H-i-s").$database.".sql";
$backupFile=getcwd()."\\".date("Y-m-d-H-i-s").$database.".sql";
$command = "mysqldump -u $username -p$password $database > $backupFile 2>&1";
echo $command;echo "\n";
system($command, $result);
echo $result;


 
/* 
 * This script only works on linux.
 * It keeps only 31 backups of past 31 days, and backups of each 1st day of past months.
 */
 /*
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecworld');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('BACKUP_SAVE_TO', 'G:/wamp/www/ECWorldBB/ECWorldwww/DBBackup'); // without trailing slash
 
$time = time();
$day = date('j', $time);
if ($day == 1) {
    $date = date('Y-m-d', $time);
} else {
    $date = $day;
}
 
$backupFile = BACKUP_SAVE_TO . '/' . DB_NAME . '_' . $date . '.gz';
if (file_exists($backupFile)) {
    unlink($backupFile);
}
$command = 'mysqldump --opt -h ' . DB_HOST . ' -u ' . DB_USER . ' -p\'' . DB_PASSWORD . '\' ' . DB_NAME . ' | gzip > ' . $backupFile;

system($command, $result);
echo $result; 
*/
?>
