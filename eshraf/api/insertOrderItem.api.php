<?php
session_start();
const ORDER_VERSION = 1; /* 1 refers to this item inserted by an admin */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0 && $_SESSION['groupID'] > 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if(isset($_POST['orderID'],$_POST['itemID'],$_POST['itemQty'],$_POST['itemExpDate']) &&
            is_numeric($_POST['orderID']) && is_numeric($_POST['itemID']) && is_numeric($_POST['itemQty']) && is_numeric($_POST['itemExpDate'])){
        $allowStatus = [3,4];
        $orderVersion = ORDER_VERSION;
        $expDateNum = intval($_POST['itemExpDate']);
        if($expDateNum > 2 || $expDateNum < 1){
            echo "error: 6012.  Processing Faild.";;
            exit();
        }
        $expDateProp = "expDate". $expDateNum;
        $Qty = intval($_POST['itemQty']);
        $orderID = intval($_POST['orderID']);
        $itemID = intval($_POST['itemID']);
        /*get and validate order */
        $orderData = new order($is,$orderID);
        $orderData->orderVersion = $orderVersion;
        if(!($orderData->getOrderByID())){
            echo "error: 6022.  Processing Faild.";
            exit();
        }
        if($orderData->createdBy > 0){ /** if it is created by an admin */
            $allowStatus[] = 1;
        }
        if(!in_array($orderData->procStatus, $allowStatus)){ /** 1 refers to opened order */
            echo "error: 6036.  Processing Faild.";
            exit();
        }
        /*get and validate item */
        $itemData =  new item($isSuc, $itemID);
        if(!($itemData->getItemByID())){
            echo "error: 6036.  Processing Faild.";;
            exit();
        }
        $orderItem = new orderItem($is,null,$orderID,$itemID,$itemData->salePrice,$Qty,$itemData->$expDateProp,$orderVersion/* ,date("Y-m-d H:i") */);
        if(isset($_POST['op']) && $_POST['op'] == 'update'){
            $targetID = $orderItem->getOrderItemPropWhere('ID', ['=','=','='], ['itemID','orderID','orderVersion']);
            if($targetID > 0){
                $orderItem->ID = $targetID;
                if($orderItem->updateOrderItem($err, 'ID','itemQty')){
                    echo 1;
                    exit();
                }else{
                    echo "error: 7057.  Updating faild";
                    exit();
                }
            }
        }else{
            if($orderItem->isOrderItemExist($prop, "AND", 'itemID','orderID','orderVersion')){
                echo "This item is already inserted";
                exit();
            }
            if($orderItem->insertOrderItem($err,'ALL') != false){
                echo 1;
                exit();
            }else{
                echo "error: 7070.  Addition Faild.";
                exit();
            }
        }
    }
}
echo "error: 7076.  Processing Faild.";;
exit();