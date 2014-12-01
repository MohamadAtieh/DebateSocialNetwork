<?php

require_once '../config/db.php';
require_once '../fetch/getAverage.php';
session_start();
if (!isset($_GET['t']))
    header("Location: ../alert/error.php");


$tid = $_GET['t'];


$result=  getAverage($tid,$con);
echo json_encode($result);

mysqli_close($con);
?>
