<?php

require_once '../config/db.php';
session_start();
if (!isset($_GET["tid"]) || trim($_GET["tid"]) == "" || trim($_GET["tid"]) == "tid" ||
        !isset($_GET["s"]) || trim($_GET["s"]) == "" || trim($_GET["s"]) == "tid" ||
        !isset($_GET["term"]) || trim($_GET["term"]) == "" || trim($_GET["term"]) == "tid")
    echo 'error';
else {
    $uid = $_SESSION['id'];
    $tid = $_GET["tid"];
    $side = $_GET["s"];
    $get_tag = mysqli_query($con,"SELECT * FROM `tag_table` WHERE `tid` = $tid") or die('fuck');
    $exist = mysqli_fetch_array($get_tag,MYSQLI_BOTH);

    if ($exist) {
        $termh = $_GET["term"];
        $term = substr(strtolower($termh), 1);

        /* $query = mysql_query("SELECT DISTINCT a.uid, a.username FROM user_table a, comments_table b, tag_table c
          WHERE c.tid = $tid AND b.side = $side AND b.uid != $uid AND a.uid = b.uid AND a.username LIKE '%" . $term . "%'
          ORDER BY a.username") or die(mysqli_error($con));
         */
        $query = mysqli_query($con, "SELECT * FROM user_table WHERE `uid` != $uid AND `username` LIKE '%" . $term . "%'
        ORDER BY username");
        $json = array();

        while ($tag = mysqli_fetch_array($query,MYSQLI_BOTH)) {
            $json[] = array(
                'value' => '@' . $tag["username"],
                'label' => $tag["username"]
            );
        }

        echo json_encode($json);
    } else {
        echo 'I see what you did there';
    }
}
?>
