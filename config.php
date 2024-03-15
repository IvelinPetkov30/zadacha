<?php
define('DBSERVER', 'localhost:3307');
define('DBUSERNAME', 'root');
define('DBPASSWORD', 'admin123');
define('DBNAME', 'mysql');

$db = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

if($db === false){
	die("Error: connection error. " . mysqli_connect_error());
}
?>

