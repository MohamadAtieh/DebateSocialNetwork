<?php

require_once '../config/db.php';
session_start();
if (!isset($_GET["tid"]) || !isset($_GET['side'])) {
    echo 'error';
} else {
    $currentuser = $_SESSION['id'];
    $tid = $_GET["tid"];
    $side = $_GET['side'];
    $get_comments = mysqli_query($con,"SELECT * FROM `comments_table` 
                                            WHERE `tid` = $tid && `side` = $side ORDER BY UNIX_TIMESTAMP(Date) DESC");
    $general = array();
    $related_which = array();
    $results = array();
    
    if (mysqli_num_rows($get_comments) > 0) {
        while ($comment = mysqli_fetch_array($get_comments, MYSQLI_BOTH)) {
            $commentator_id = $comment['uid'];
            $user_query = mysqli_query($con,"SELECT * FROM `user_table` WHERE `uid` = $commentator_id");
            $user = mysqli_fetch_array($user_query,MYSQLI_BOTH);
            $cid = $comment['cid'];

            //for num rows
            $likes_query = mysqli_query($con,"SELECT * FROM `activity_table` WHERE `cid` = $cid AND `LD` = 1");
            $dislikes_query = mysqli_query($con,"SELECT * FROM `activity_table` WHERE `cid` = $cid AND `LD` = 2");
            $share_query = mysqli_query($con,"SELECT * FROM `activity_table` WHERE `cid` = $cid AND `shared` = 1");

            //get all user related comments stats
            $related_query = mysqli_query($con,"SELECT * FROM `activity_table` WHERE `cid` = $cid AND `uid` = $currentuser");
            $related = mysqli_fetch_array($related_query,MYSQLI_BOTH);

            if ($related > 1) {
                $ld = NULL;
                if ($related['LD'] == 1)
                    $ld = 1;
                else if ($related['LD'] == 2)
                    $ld = 2;

                $related_which[] = array('ld' => $ld,
                    'shared' => $related['shared'],
                    'reported' => $related['reported']);
            }

            //count likes and other stats
            $likes_num = mysqli_num_rows($likes_query);
            $dislikes_num = mysqli_num_rows($dislikes_query);
            $shares_num = mysqli_num_rows($share_query);
            $reports_num = $comment['reports'];

            $date = date('d M y', strtotime($comment['Date']));
            $general[] = array('username' => $user['username'],
                'comment' => $comment,
                'date' => $date,
                'likes' => $likes_num,
                'dislikes' => $dislikes_num,
                'shares' => $shares_num,
                'reports' => $reports_num,
                'related' => $related_which);
            $related_which = array();
        }
      
        echo json_encode($general);
    } else {
        
        echo json_encode($general);
    }
}
mysqli_close($con);
?>
