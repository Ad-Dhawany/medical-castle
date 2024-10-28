<?php
class stat_orderItem{
    //private const TABLE_NAME = "orders_contents";
    const ORDER_VERSION = 0;
    static function getOrderItems($orderID, $ANDwhere = "1", $orderVersion=self::ORDER_VERSION){
        global $conn;
        $where = "(orderVersion = ". $orderVersion . " AND orderID=". $orderID. ") AND ". $ANDwhere;
        $orderItems=[];
        $stmt = $conn->prepare("SELECT orders_contents.*, items.itemName, items.itemNum FROM `orders_contents` INNER JOIN `items` USING(itemID) WHERE $where  ORDER BY insertDate ASC");
            $stmt->execute();
            $rows = $stmt->fetchAll(2);
            $count  = $stmt->rowCount();
            if($count > 0){
                $e="";
                foreach($rows as $row){
                    $orderItem = new orderItem($e, $row['ID'], $row['orderID'], $row['itemID'], $row['itemPrice'], $row['itemQty'], $row['itemExpDate']);
                    $orderItem->itemName = $row['itemName'] ;
                    $orderItem->itemNum  = $row['itemNum'] ;
                    $orderItems[] = $orderItem;
                }
                return $orderItems;
            }else return false;
    }
}