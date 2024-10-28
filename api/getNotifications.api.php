<?php
session_start();
if(isset($_SESSION['userID'], $_SESSION['regStatus'], $_POST['req']) && $_SESSION['regStatus'] > 0 && $_SESSION['groupID'] > -1){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    $req = $_POST['req'];
    if($req == 'all'){
        $notifications = stat_getObjects::getAllNotifications($_SESSION['userID']);
        $notifMessages=[];
        foreach($notifications as $i=>$notif){
            $notifMessages[$i]['msg']       = $notif->getNotificationMsg();
            $notifMessages[$i]['link']      = $notif->getNotificitionLink();
            $notifMessages[$i]['date']      = $notif->createdDate;
            $notifMessages[$i]['isRead']    = $notif->isRead;
            $notifMessages[$i]['noteID']    = $notif->noteID;
            $notifMessages[$i]['noteGroup'] = ($notif->noteGroup == "specific")? "s": "b";
            $notifMessages[$i]['importance']= $notif->importance;
            $notifMessages[$i]['pharmacy']  = $notif->launcherPharmacy;
        }
        echo json_encode($notifMessages, JSON_PRETTY_PRINT);
        /* fnc::prePrint(json_encode($notifMessages, JSON_PRETTY_PRINT)); */
    }elseif($req == "count"){
        
    }
}
exit();