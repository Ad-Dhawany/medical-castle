<?php
ob_start();
    session_start();
    $pageTitle = 'Users';
    $dir = "../";
    $isLoggedIn = false;
    /* to verify that the person is user and has admin permissions */
    if(!isset($_SESSION['username']) || !isset($_SESSION['userID']) || !isset($_SESSION['groupID'])){
        header('location: ../');
        exit();
    }
    require_once $dir."init.php";
    if($_SESSION['groupID'] != 3){
        $errorMsg = "You have no permission to access here";
        fnc::redirectHome($errorMsg,"back");
    }
    $log = $_GET['log'] ?? fnc::redirectHome("e0x7016: Wrong log name", "back", 1);
    if($log == "delmem"){
        echo "<h1 class='text-center my-3'>Deleted Members Log</h1>
                <section class='container'>";
        if(file_exists("./deleted_members_log.txt") && filesize("./deleted_members_log.txt") > 10){
            $logTable = file_get_contents("./deleted_members_log.txt");
            echo $logTable;
        }else{
            echo "<h5 class='text-center'>There is no deleting process yet</h5>";
        }
        echo "</tbody></table></section>";
    }
    include $dir. $tempsP. "footer.php";
ob_end_flush();