<?php
session_start();
if(isset($_SESSION['userID'], $_SESSION['regStatus'], $_POST['set']) && $_SESSION['regStatus'] > 0 && $_SESSION['groupID'] === 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    $set = $_POST['set'];
    if($set == 'read'){
        $id = $_POST['ID'] ?? exit();
        $type = $_POST['type'] ?? exit();
        if($type == "s"){ /** s => specific */
            $notify = new specificNotifications($is, $id, null, $_SESSION['userID']);
        }elseif($type == "b"){ /* b => broad */
            $notify = new broadNotifications($is, $id);
        }else{
            echo "خطأ: 7016";
            exit();
        }
        if($notify->markAsRead()){
            echo 1;
        }else{
            echo "خطأ: 7022";
        }
        echo "خطأ: 7024";
        exit();
    }elseif($set == "count"){
        
    }
}
echo "خطأ: 7030";
exit();