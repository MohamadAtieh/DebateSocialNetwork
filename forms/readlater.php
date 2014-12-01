<?php

require_once '../config/db.php';
session_start();
$tid = $_POST['tid'];
$uid = $_SESSION['id'];
$mark_query = mysqli_query($con, "SELECT * FROM `readlater_table` WHERE `tid` = $tid AND `uid` = $uid");
$marked = mysqli_fetch_array($mark_query, MYSQLI_BOTH);
if ($marked > 0) {
    mysqli_query($con, "DELETE FROM `readlater_table` WHERE `tid` = $tid AND `uid` = $uid");
    echo 'remove';
} else {
    mysqli_query($con, "INSERT INTO `readlater_table`(`uid`, `tid`) 
                VALUES ('$uid','$tid')") or die('oops');
    echo 'new';
}
mysqli_close($con);
?>
