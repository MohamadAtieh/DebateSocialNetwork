<?php
$host = 'localhost';
$username = 'root';
$password = 'asot#8672';
$db = 'debate';


$con = mysqli_connect($host, $username, $password, $db);
if(mysqli_connect_errno($con)){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// $host = 'localhost';
// $username = 'atieh_debate';
// $password = 'asot#8672';
// $db = 'atieh_debate';

?>
