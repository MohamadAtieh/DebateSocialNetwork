<?php
$host = 'localhost';
$username = '';
$password = '';
$db = '';


$con = mysqli_connect($host, $username, $password, $db);
if(mysqli_connect_errno($con)){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
