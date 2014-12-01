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
$query = mysqli_query($con,"SELECT u.username FROM user_table u, followers_table f WHERE f.uid = $uid AND f.follower_id = u.uid");
if (mysqli_num_rows($query) > 0) {
    while ($read = mysqli_fetch_array($query, MYSQLI_BOTH)) {
        $results[] = array('user' => $read['username']);
    }
    echo json_encode($results);
} else {
    $results[] = array('none' => 'none');
    echo json_encode($results);
}

mysqli_close($con);
?>