<?php

session_start();
require_once '../config/db.php';

$follower = $_SESSION['username'];
$username = $_GET['username'];
$get_user_id = mysqli_query($con, "SELECT * FROM user_table WHERE username ='$username' ");
$userid = mysqli_fetch_array($get_user_id, MYSQLI_BOTH);
$uid = $userid['uid'];
$get_user_id = mysqli_query($con, "SELECT * FROM user_table WHERE username ='$follower' ");
$userid = mysqli_fetch_array($get_user_id, MYSQLI_BOTH);
$follower_id = $userid['uid'];
$get_user_id = mysqli_query($con, "insert into followers_table (uid,follower_id) values ($uid,$follower_id) ");
$userid = mysqli_fetch_array($get_user_id, MYSQLI_BOTH);
$b = FALSE;
trigger($uid, $b, $follower_id, $uid);
updateActivity($uid, $b, $follower_id, $uid);

function trigger($cid, $belongs, $sender, $receiver) {
    if ($belongs == FALSE) {
        
        //now send notification
        $type = 'follow';
        $not = 'is now following you';
        mysqli_query($con, "INSERT INTO `notification_table`(`recipient_id`, `sender_id`, `notification`, `object_type`, `object_id`) 
            VALUES ('$receiver','$sender','$not','$type','$cid')") or die(mysqli_error($con));
    }
}

function updateActivity($cid, $belongs, $sender, $target) {
    if ($belongs == FALSE) {


        $followers_query = mysqli_query($con, "SELECT * FROM followers_table WHERE uid = $sender");
        if (mysqli_num_rows($followers_query) > 0) {
            $target_query = mysqli_query($con, "SELECT * FROM user_table WHERE uid = $target");
            $target_row = mysqli_fetch_array($target_query, MYSQLI_BOTH);
            $target_name = $target_row['username'];
            while ($follower = mysqli_fetch_array($followers_query, MYSQLI_BOTH)) {
                $receiver = $follower['follower_id'];
                $type = 'follow';
                $act = "followed $target_name";
                mysqli_query($con, "INSERT INTO `user_activity`(`recipient_id`, `sender_id`, `target_id`, `object_type`, `object_id`, `activity`) 
                        VALUES ('$receiver','$sender','$target','$type','$cid','$act')") or die(mysqli_error($con));
            }
        }
    }
}

?>