<?php

require_once '../config/db.php';
$termh = $_GET["term"];

$term = substr(strtolower($termh), 1);

$query = mysqli_query($con, "SELECT * FROM tag_table where tagname like '%" . $term . "%' order by tagname");
$json = array();

while ($tag = mysqli_fetch_array($query,MYSQLI_BOTH)) {
    $json[] = array(
        'value' => $tag["tagname"],
        'label' => $tag["tagname"] . " - " . $tag["category"],
        'category' => $tag['category']
    );
}

echo json_encode($json);
?>
