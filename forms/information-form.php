<?php

require_once '../config/db.php';
$first = trim($_POST['first']);
$last = trim($_POST['last']);
$age = $_POST['age'];
$gender = $_POST['gender'];
$country = $_POST['country'];
$work = trim($_POST['work']);
$education = trim($_POST['education']);
$bio = trim($_POST['biography']);
$imgsrc=$_POST['hala'];
$token = $_POST['token'];


$completequery = "UPDATE `user_table` 
             SET 
               `IsComplete`=1
            WHERE `token` = '$token'";
$completed = mysqli_query($con,$completequery);

$userquery = mysqli_query($con, "SELECT `uid`, `username` FROM `user_table` WHERE `token`= '$token'");

$user = mysqli_fetch_array($userquery, MYSQLI_BOTH) or die($user . "<br/><br/>" . mysqli_error($con));
$uid = $user['uid'];

$query = "INSERT INTO `info_table`(`uid`, `firstname`, `lastname`, `age`, `gender`, `location`, `work`, `education`, `bio`,`profilepic`) VALUES  
   ('$uid','$first','$last','$age','$gender','$country',
                IF(LENGTH('$work')=0, NULL, '$work'),
                    IF(LENGTH('$education')=0, NULL, '$education'),
                        IF(LENGTH('$bio')=0, NULL, '$bio'),'$imgsrc')";
$updatequery = mysqli_query($con, $query) or die($updatequery . "<br/><br/>" . header("Location: ../alert/error.php"));
$stat_query = mysqli_query($con, "INSERT INTO `user_stat` (`uid`) VALUES ('$uid')");
mysqli_close($con);

session_start();
$_SESSION['username'] = $user['username'];
$_SESSION['id'] = $uid;
$_SESSION['status'] = TRUE;

header("Location: ../home.php");
?>
