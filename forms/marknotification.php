<?php

require_once '../config/db.php';
session_start();
if (!isset($_GET["nid"]) || trim($_GET["nid"]) == "")
    echo 'error';
else {
    
    $nid = $_GET["nid"];
    mysqli_query($con, "UPDATE `notification_table` SET `is_unread`= 0 WHERE `nid` = $nid");
    echo 'success';

    mysqli_close($con);
}
?>
