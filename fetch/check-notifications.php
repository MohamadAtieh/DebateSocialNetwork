<?php

require_once '../config/db.php';
session_start();
$where = '';
$update = '';
$uid = $_SESSION['id'];

$where = ' WHERE `recipient_id` = ' . $uid . ' AND `is_unread` = 1';
$query = mysqli_query($con,'SELECT * FROM notification_table' . $where);
if (mysqli_num_rows($query) > 0) {
    $update = 'update';
} else {
    $update = '';
}

echo $update;                    // send it to the client

mysqli_close($con);
?>
