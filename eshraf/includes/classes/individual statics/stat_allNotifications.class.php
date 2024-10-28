<?php
class stat_allNotifications{
    //private const TABLE_NAME = "orders_contents";
    static function getAllNotifications($recipientID){
        global $conn;
        $where = "`recipientID` = $recipientID OR  `recipientID` =". BROAD_RECIPIENTS_ID['costumers'] ;
        $notifications=[];
        $stmt = $conn->prepare("SELECT an1.`noteID`, an1.`launcherID`, an1.`recipientID`, an1.`type`, an1.`importance`, an1.`referenceID`, an1.`createdDate`, an1.`noteGroup`, an1.`launcherName`, an1.`recipientName`, MAX(`readORnot`) AS `isRead`
                                    FROM (SELECT *, (CASE 
                                                    WHEN `readersID` = $recipientID THEN 1
                                                    WHEN `readersID` = 1 AND `noteGroup` = 'specific' THEN 1
                                                    WHEN `readersID` = 0 THEN 0
                                                    WHEN `readersID` IS NULL THEN 0
                                                    ELSE -1
                                            END) AS `readORnot`
                                            FROM `all_notifications`
                                            WHERE $where ) an1
                                    GROUP BY `noteID`,`noteGroup`");
        $stmt->execute();
        $rows = $stmt->fetchAll(2);
        $count  = $stmt->rowCount();
        if($count > 0){
            foreach($rows as $row){
                $notification = new allNotifications($is, ...$row);
                $notifications[] = $notification;
            }
            return $notifications;
        }else return false;
    }
}