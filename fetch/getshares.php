<?php

require_once '../config/db.php';
session_start();
if (!isset($_GET["cid"])) {
    echo 'error';
} else {
    $results = array();
    $cid = $_GET['cid'];
    $query = mysqli_query($con, "SELECT uid FROM `activity_table` WHERE `cid` = $cid AND `shared` = 1");
    if (mysqli_num_rows($query) > 0) {
        while ($liker = mysqli_fetch_array($query, MYSQLI_BOTH)) {
            $uid = $liker['uid'];
            $uquery = mysqli_query($con, "SELECT * FROM `user_table` WHERE `uid` = $uid");
            $user = mysqli_fetch_array($uquery, MYSQLI_BOTH);
            if ($user > 0)
                $results[] = array('user' => $user['username']);
        }
        echo json_encode($results);
    }else {
        $results[] = array('none' => 'none');
        echo json_encode($results);
    }
}
mysqli_close($con);
?>