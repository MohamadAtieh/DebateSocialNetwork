<?php

require_once '../config/db.php';
session_start();
$uid = $_SESSION['id'];
$lastCheck = $_GET['time']; //time of last activity
if ($lastCheck == 'none') {
    $query = mysqli_query($con,"SELECT * FROM `user_activity` WHERE `recipient_id` = $uid ORDER BY UNIX_TIMESTAMP(time_sent) DESC");

    if (mysqli_num_rows($query) > 0) {
        $act = mysqli_fetch_array($query, MYSQLI_BOTH); //get first occurance
        $lastCheck = strtotime($act['time_sent']);
    }
}
$where = ' WHERE `recipient_id` = ' . $uid . ' AND UNIX_TIMESTAMP(time_sent) >= ' . $lastCheck;
$query = mysqli_query($con, 'SELECT * FROM user_activity' . $where);
if (mysqli_num_rows($query) > 0) {
    $update = 'update';
} else {
    $update = 'nothing new';
}

echo $update;                    // send it to the client

mysqli_close($con);
?>
