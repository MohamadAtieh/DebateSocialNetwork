<?php

require_once '../config/db.php';
session_start();
$results = array();
if (!isset($_GET['username']))
    header("Location: ../alert/error.php");

$username = $_GET['username'];
$user_query = mysqli_query($con,"SELECT * FROM `user_table` WHERE `username` = '$username'");
$user = mysqli_fetch_array($user_query, MYSQLI_BOTH);
$uid = $user['uid'];
$follows = mysqli_query($con,"SELECT * FROM followers_table WHERE follower_id = $uid");
$followers = mysqli_query($con,"SELECT * FROM followers_table WHERE uid = $uid");

$results[] = array('follows' => mysqli_num_rows($follows),
    'followers' => mysqli_num_rows($followers));

echo json_encode($results);

mysqli_close($con);
?>
