<?php
class stat_getObjects{
    /**تم تجميع كل الكلاسات الستاتيك التى ترجع مصفوفة من الكائنات في كلاس واحد بدلاً من أن تكون كل دالة في كلاس لوحدها
     * 
     */    
    /** */
    static function getItems($where = "1"){
        global $conn;
        $items=[];
        $stmt = $conn->prepare("SELECT * FROM items WHERE $where");
            $stmt->execute();
            $rows = $stmt->fetchAll(2);
            $count  = $stmt->rowCount();
            if($count > 0){
                $e="";
                foreach($rows as $row){
                    $items[] = new item($e, $row['itemID'], $row['itemNum'], $row['itemName'], $row['Qty'], $row['payPrice'], $row['avrPayPrice'], $row['salePrice'],$row['profitRatio'], $row['avrProfitRatio'],$row['visibility'],$row['addDate'],$row['addBy'],$row['expDate1'],$row['expDate2']);
                }
                return $items;
            }else return false;
    }
    /************************************/
    /** */
    //private const TABLE_NAME = "orders_contents";
    const ORDER_VERSION = 0;
    static function getOrderItems($orderID, $ANDwhere = "1", $orderVersion=self::ORDER_VERSION){
        global $conn;
        $where = "(orderVersion = ". $orderVersion . " AND orderID=". $orderID. ") AND ". $ANDwhere;
        $orderItems=[];
        $stmt = $conn->prepare("SELECT oc.*, i.itemName, i.itemNum FROM `get_order_content` oc INNER JOIN `items` i USING(itemID) WHERE $where  ORDER BY insertDate ASC");
        $stmt->execute();
        $rows = $stmt->fetchAll(2);
        $count  = $stmt->rowCount();
        if($count > 0){
            $e="";
            foreach($rows as $row){
                $orderItem = new orderItem($e, $row['ID'], $row['orderID'], $row['itemID'], $row['itemPrice'], $row['itemQty'], $row['itemExpDate'],$row['orderVersion'],$row['insertDate'],$row['totalItemPrice']);
                $orderItem->itemName = $row['itemName'] ;
                $orderItem->itemNum  = $row['itemNum'] ;
                $orderItems[] = $orderItem;
            }
            return $orderItems;
        }else return false;
    }
    /************************************/
    /** */
    static function getOrders($costumerID, $ANDwhere = "1", $orderBy = 'createdDate', $limit = '100'){
        global $conn;
        $orders=[];
        /**`costumerID` = ? AND (`createdBy` = 0 || (`createdBy` > 0 AND `procStatus` > 3)) :: لجلب كل فواتير الزبون التي أنشأها بنفسه والفواتير المرسلة له من إدارة الشركة */
        $where = "`costumerID` = ? AND (`createdBy` = 0 || (`createdBy` > 0 AND `procStatus` > 3)) AND ($ANDwhere)";
        $stmt = $conn->prepare("SELECT `orderID`,`procStatus` FROM `get_order_meta` WHERE $where GROUP BY `orderID` ORDER BY $orderBy LIMIT $limit");
            $stmt->execute([$costumerID]);
            $rows = $stmt->fetchAll(2);
            $count  = $stmt->rowCount();
            if($count > 0){
                foreach($rows as $row){
                    $order = new order($is, $row['orderID']);
                    /* $orderVersion = ($row['procStatus'] > 3) ? 1 : 0;
                    $order->orderVersion = $orderVersion;
                    if($order->getOrderByID()){
                        $orders[] =$order;
                    }elseif($order->getOrderByID(false)){
                        $orders[] =$order;
                    }else{
                        return false;
                    } */
                    if($order->getOrderByID()){
                        $orders[] = $order;
                    }else{
                        return false;
                    }
                }
                return $orders;
            }else return false;
    }
    /************************************/
    /** */
    //private const TABLE_NAME = "orders_contents";
    static function getAllNotifications($recipientID){
        global $conn;
        $where = "`recipientID` = $recipientID OR  `recipientID` =". BROAD_RECIPIENTS_ID['costumers'] ;
        $notifications=[];
        $stmt = $conn->prepare("SELECT an1.`noteID`, an1.`launcherID`, an1.`recipientID`, an1.`type`, an1.`importance`, an1.`referenceID`, an1.`createdDate`, an1.`noteGroup`, an1.`launcherName`, an1.`launcherPharmacy`, an1.`recipientName`, MAX(`readORnot`) AS `isRead`
                                    FROM (SELECT *, (CASE 
                                                    WHEN `readersID` = $recipientID THEN 1
                                                    WHEN `readersID` = 1 AND `noteGroup` = 'specific' THEN 1
                                                    WHEN `readersID` = 0 THEN 0
                                                    WHEN `readersID` IS NULL THEN 0
                                                    ELSE -1
                                            END) AS `readORnot`
                                            FROM `all_notifications`
                                            WHERE $where ) an1
                                    GROUP BY `noteID`,`noteGroup`
                                    ORDER BY an1.`createdDate` DESC LIMIT 100");
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
    /************************************/
    /** */
    static function getPosts($where = "1", $limit = '100'){
        global $conn;
        $posts=[];
        $stmt = $conn->prepare("SELECT * FROM `get_posts` WHERE `recipientsGroup` = 0 AND ($where) ORDER BY `postID` DESC LIMIT $limit");
            $stmt->execute();
            $rows = $stmt->fetchAll(2);
            $count  = $stmt->rowCount();
            if($count > 0){
                $e="";
                foreach($rows as $row){
                    $posts[] = new posts($e, ...$row);
                }
                return $posts;
            }else return false;
    }
    /************************************/
    /** */
    static function getPostAttachments($where = "1", $limit = '1000'){
        global $conn;
        $postAttachs=[];
        $stmt = $conn->prepare("SELECT * FROM `post_attachment` WHERE ($where) ORDER BY `ID` DESC LIMIT $limit");
            $stmt->execute();
            $rows = $stmt->fetchAll(2);
            $count  = $stmt->rowCount();
            if($count > 0){
                $e="";
                foreach($rows as $row){
                    $postAttachs[] = new postAttachment($e, ...$row);
                }
                return $postAttachs;
            }else return false;
    }
    /************************************/
    /** */

}