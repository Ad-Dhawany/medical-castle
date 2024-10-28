<?php
class stat_item{
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
}