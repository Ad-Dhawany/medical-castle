<?php
session_start();
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $req = $_POST['req']?? "posts";
        if($req == "posts"){
            // $where = "`postID` >= (SELECT MAX(id) FROM `get_posts`) - 10";
            $posts = stat_getObjects::getPosts();
            if(is_array($posts)){
                echo json_encode($posts, JSON_PRETTY_PRINT);
            }else{
                echo json_encode([0,'There are no posts yet'], JSON_PRETTY_PRINT);
            }
        }elseif($req == "attach"){
            $postAttachs = stat_getObjects::getPostAttachments();
            if(is_array($postAttachs)){
                echo json_encode($postAttachs, JSON_PRETTY_PRINT);
            }else{
                echo json_encode([0,'There are no attachments for this post'], JSON_PRETTY_PRINT);
            }
        }elseif($req == "post" && isset($_POST['id']) && is_numeric($_POST['id'])){
            $post = new posts($is, $_POST['id']);
            if($post->getPostByID()){
                echo json_encode($post, JSON_PRETTY_PRINT);
            }else{
                echo json_encode([0,'Wrong post id'], JSON_PRETTY_PRINT);
            }
        }else{
            echo json_encode([0,'There is no post'], JSON_PRETTY_PRINT);
        }
    }
}
exit();