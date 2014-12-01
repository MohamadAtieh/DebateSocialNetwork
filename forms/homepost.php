<?php

require_once '../config/db.php';

session_start();

$roomname = $_POST['roomname'];
$cat = $_POST['cat'];
$side = $_POST['side'];
$comment = $_POST['comment'];
$comment = str_replace("'", "''", $comment);

$uid = $_SESSION['id'];

$get_tags = mysqli_query($con, "SELECT * FROM `tag_table` WHERE LOWER(`tagname`) = LOWER('$roomname') && `category` = '$cat'") or die(mysqli_error($con));

$tag_rows = mysqli_fetch_array($get_tags, MYSQLI_BOTH);

if ($tag_rows > 0) { //if tag + category exists, identical
    $tid = $tag_rows['tid']; 

    $insert_comment = mysqli_query($con, "INSERT INTO `comments_table`(`uid`, `tid`, `comment`, `side`, `reports`) 
                VALUES ('$uid','$tid','$comment','$side',0)") or die(mysqli_error($con));
    $cid = mysql_insert_id() or die(mysqli_error($con));

    $update_stat = mysqli_query($con, "UPDATE `user_stat` SET `posts` = CASE WHEN `posts` IS NULL THEN 1 ELSE (`posts` + 1) END 
            WHERE `uid` = $uid");
    $tagname = substr($roomname, 1);
    //header("Location: room.php?cat=$cat&tag=$tagname");
    
     //check mentions and add update stats ONCE ONLY
  //   mysqli_query($con, "UPDATE `user_stat` SET `mentioned` = CASE WHEN `mentioned` IS NULL THEN 1 ELSE (`mentioned` + 1) END 
      //      WHERE `uid` = $uid");*/

    echo 'identical tag';
} else { //new tag = new category
    //echo 'new tag';
    $insert_tag = mysqli_query($con, "INSERT INTO `tag_table`(`tagname`, `uid`, `category`) 
                VALUES ('$roomname','$uid','$cat')");
    $tid = mysql_insert_id(); //get the last inserted id

    $insert_comment = mysqli_query($con, "INSERT INTO `comments_table`(`uid`, `tid`, `comment`, `side`, `reports`) 
                VALUES ('$uid','$tid','$comment','$side',0)");

    $cid = mysql_insert_id();

   // $insert_activity = mysqli_query($con, "INSERT INTO `activity_table`(`cid`, `uid`)
               // VALUES ('$cid','$uid')");

    $update_stat = mysqli_query($con, "UPDATE `user_stat` SET `posts` = CASE WHEN `posts` IS NULL THEN 1 ELSE (`posts` + 1) END 
            WHERE `uid` = $uid");
    $tagname = substr($roomname, 1);
    //header("Location: room.php?cat=$cat&tag=$tagname");
    echo "new tag";
}
mysqli_close($con);
?>
