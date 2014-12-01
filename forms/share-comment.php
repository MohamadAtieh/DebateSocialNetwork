<?php

require_once '../config/db.php';
session_start();
if (!isset($_GET["cid"]) || trim($_GET["cid"]) == "")
    echo 'error';
else {
    $uid = $_SESSION['id'];
    $cid = $_GET["cid"];
    $get_activity = mysqli_query($con, "SELECT * FROM `activity_table` WHERE `uid` = $uid AND `cid` = $cid") or die('fuck');
    $exist = mysqli_fetch_array($get_activity, MYSQLI_BOTH);

    $recq = mysqli_query($con, "SELECT * FROM `comments_table` WHERE `cid` = $cid");
    $rec = mysqli_fetch_array($recq, MYSQLI_BOTH);
    if ($rec > 0) {
        $co = $rec['uid'];
        //$comment = $rec['comment'];
    }

    if ($exist) { //if the user has a previous activity, update
        $shared = $exist['shared'];

        if (is_null($shared)) {
            mysqli_query($con, "UPDATE `activity_table` SET `shared`= 1 WHERE `cid` = $cid AND `uid` = $uid");
            trigger($uid, $co, $cid);
            updateActivity($uid, $co, $cid);
            echo 'null';
        }
    } else { //if the user has no previous activity, create new one
        mysqli_query($con, "INSERT INTO `activity_table`(`cid`, `uid`, `shared`) 
                VALUES ('$cid','$uid', 1)") or die('oops');
        trigger($uid, $co, $cid);
        updateActivity($uid, $co, $cid);
        echo 'new';
    }
    mysqli_close($con);
}

function trigger($uid, $co, $cid) {
    //insert notification

    if ($co != $uid) {
        $type = 'share';
        $not = 'shared your comment';
        mysqli_query($con, "INSERT INTO `notification_table`(`recipient_id`, `sender_id`, `notification`, `object_type`, `object_id`) 
            VALUES ('$co','$uid','$not','$type','$cid')") or die(mysqli_error($con));
    }
}

function updateActivity($sender, $co, $cid) {
    if ($co != $sender) {
        $followers_query = mysqli_query($con, "SELECT * FROM followers_table WHERE uid = $sender");
        if (mysqli_num_rows($followers_query) > 0) {
            $target_query = mysqli_query($con, "SELECT * FROM user_table WHERE uid = $co");
            $target_row = mysqli_fetch_array($target_query, MYSQLI_BOTH);
            $target_name = $target_row['username'];
            while ($follower = mysqli_fetch_array($followers_query, MYSQLI_BOTH)) {
                $receiver = $follower['follower_id'];
                $type = 'share';
                $act = "shared a comment for $target_name";
                mysqli_query($con, "INSERT INTO `user_activity`(`recipient_id`, `sender_id`, `target_id`, `object_type`, `object_id`, `activity`) 
                        VALUES ('$receiver','$sender','$co','$type','$cid','$act')") or die(mysqli_error($con));
            }
        }
    }
}

?>
