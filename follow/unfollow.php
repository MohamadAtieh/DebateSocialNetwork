<?php 
session_start();
require_once '../config/db.php';

$follower = $_SESSION['username'];
$username=$_GET['username'];
$get_user_id = mysqli_query($con, "SELECT * FROM user_table WHERE username ='$username' ");
$userid = mysqli_fetch_array($get_user_id, MYSQLI_BOTH);
$uid = $userid['uid'];
$get_user_id = mysqli_query($con, "SELECT * FROM user_table WHERE username ='$follower' ");
$userid = mysqli_fetch_array($get_user_id, MYSQLI_BOTH);
$follower_id=$userid['uid'];
$get_user_id = mysqli_query($con, "delete from followers_table where uid='$uid' AND follower_id='$follower_id' ");
$userid = mysqli_fetch_array($get_user_id, MYSQLI_BOTH);
?>