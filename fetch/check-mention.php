<?php

require_once '../config/db.php';
session_start();

//$uid = $_SESSION['id'];
$result = array();
$arr = $_POST['mentions'];

foreach ($arr as $m) {
    $mention = substr($m, 1);
    $search_query = mysqli_query($con, "SELECT * FROM user_table WHERE `username` = '$mention'");
    $exist = mysqli_fetch_array($search_query, MYSQLI_BOTH);
    if ($exist > 0) {
        $result[] = array('mention' => $mention);
    }
}

/*$uid = $exist['uid'];
mysqli_query("UPDATE `user_stat` SET `mentioned` = CASE WHEN `mentioned` IS NULL THEN 1 ELSE (`mentioned` + 1) END 
            WHERE `uid` = $uid");*/

//$result[] = array('mention' => 'fuck');
echo json_encode($result);
?>