<?php

require_once '../config/db.php';
session_start();
if (!isset($_GET["cid"]) || trim($_GET["cid"]) == "")
    echo 'error';
else {
    $uid = $_SESSION['id'];
    $cid = $_GET["cid"];

    //check if the comment belongs to the user or not
    $check_query = mysqli_query($con, "SELECT * FROM `comments_table` WHERE `cid` = $cid");
    $checku = mysqli_fetch_array($check_query, MYSQLI_BOTH);
    $co = $checku['uid'];
    if ($co == $uid) {
        $b = TRUE; //belongs to user
    } else {
        $b = FALSE;
    }
    $get_activity = mysqli_query($con, "SELECT * FROM `activity_table` WHERE `uid` = $uid AND `cid` = $cid") or die('fuck');
    $exist = mysqli_fetch_array($get_activity, MYSQLI_BOTH);

    if ($exist) { //if the user has a previous activity, update
        $like = $exist['LD'];

        if ($like === '1') {
            mysqli_query($con, "UPDATE `activity_table` SET `LD`= NULL WHERE `cid` = $cid AND `uid` = $uid");
            //stays/ no notifications
            if ($b == FALSE) {
                mysqli_query($con, "UPDATE `user_stat` SET `likes` = CASE WHEN `likes` = 1 THEN NULL ELSE (`likes` - 1) END 
            WHERE `uid` = $uid");
                mysqli_query($con, "UPDATE `user_stat` SET `likes_received` = CASE WHEN `likes_received` = 1 THEN NULL ELSE (`likes_received` - 1) END 
            WHERE `uid` = $co");
            }
            $check_null = mysql_query("SELECT aid FROM `activity_table` WHERE `uid` = $uid 
                AND `cid` = $cid AND `LD` IS NULL AND `shared` IS NULL AND `reported` IS NULL") or die('fuck');
            $check = mysqli_fetch_array($check_null, MYSQLI_BOTH);
            if ($check) {
                $aid = $check['aid'];
                mysqli_query($con, "DELETE FROM `activity_table` WHERE `aid` = $aid") or die('fuck 2');
            }
            echo 'remove';
        } else if ($like === '2') {
            mysqli_query($con, "UPDATE `activity_table` SET `LD`= 1 WHERE `cid` = $cid AND `uid` = $uid");

            trigger($cid, $b, $uid, $co);
            updateActivity($cid, $b, $uid, $co);

            echo 'change';
        } else if (is_null($like)) {
            mysqli_query($con, "UPDATE `activity_table` SET `LD`= 1 WHERE `cid` = $cid AND `uid` = $uid");
            trigger($cid, $b, $uid, $co);
            updateActivity($cid, $b, $uid, $co);
            echo 'null';
        }
    } else { //if the user has no previous activity, create new one
        mysqli_query($con, "INSERT INTO `activity_table`(`cid`, `uid`, `LD`) 
                VALUES ('$cid','$uid', 1)") or die('oops');
        trigger($cid, $b, $uid, $co);
        updateActivity($cid, $b, $uid, $co);
        echo 'new';
    }
    mysqli_close($con);
}

function trigger($cid, $belongs, $sender, $receiver) {
    if ($belongs == FALSE) {
        //update stats
        mysqli_query($con, "UPDATE `user_stat` SET `likes` = CASE WHEN `likes` IS NULL THEN 1 ELSE (`likes` + 1) END 
            WHERE `uid` = $sender");
        mysqli_query($con, "UPDATE `user_stat` SET `likes_received` = CASE WHEN `likes_received` IS NULL THEN 1 ELSE (`likes_received` + 1) END 
            WHERE `uid` = $receiver");
        //now send notification
        $type = 'like';
        $not = 'liked your comment';
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
                $type = 'like';
                $act = "liked a comment for $target_name";
                mysqli_query($con, "INSERT INTO `user_activity`(`recipient_id`, `sender_id`, `target_id`, `object_type`, `object_id`, `activity`) 
                        VALUES ('$receiver','$sender','$target','$type','$cid','$act')") or die(mysqli_error($con));
            }
        }
    }
}

?>
