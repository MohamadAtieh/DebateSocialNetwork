<?php

require_once '../config/db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$result00 = mysqli_query($con,"SELECT * FROM user_table WHERE username='$username' && password='$password'");
$row = mysqli_fetch_array($result00,MYSQLI_BOTH);
#echo $row;

if ($row > 0) {
    if ($row['IsApproved'] && $row['IsComplete']) {
        session_start();
    
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['uid'];
        $_SESSION['status'] = TRUE;
        mysqli_close($con);
        header("Location: ../home.php");
    } else if (!$row['IsApproved']) {
        mysqli_close($con);
        header("Location: ../alert/activate-reminder.php");
    } else if ($row['IsApproved'] && !$row['IsComplete']) {
        $token = $row['token'];
        mysqli_close($con);
        header("Location: ../fillinfo.php?token=". $token);
    }
} else {
    $fail = "login";
    mysqli_close($con);
    header("Location: ../index.php?fail=" . $fail);
}
?>
