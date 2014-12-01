<?php

require_once '../config/db.php';
session_start();
$results = array();
$uid = $_SESSION['id'];
$query = mysqli_query($con, "SELECT t.tagname, t.category FROM readlater_table r, tag_table t WHERE r.uid = $uid AND r.tid = t.tid");
if (mysqli_num_rows($query) > 0) {
    while ($read = mysqli_fetch_array($query, MYSQLI_BOTH)) {
        $results[] = array('tag' => $read['tagname'],
            'cat' => $read['category']);
    }
    echo json_encode($results);
} else {
    $results[] = array('none' => 'You have no marked tags!');
    echo json_encode($results);
}

mysqli_close($con);
?>
