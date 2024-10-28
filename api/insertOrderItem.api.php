<?php
session_start();
const ORDER_VERSION = 0; /* 0 refers to this item inserted by a costumer */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if(isset($_POST['orderID'],$_POST['itemID'],$_POST['itemQty'],$_POST['itemExpDate']) &&
            is_numeric($_POST['orderID']) && is_numeric($_POST['itemID']) && is_numeric($_POST['itemQty']) && is_numeric($_POST['itemExpDate'])){
        if(isset($_POST['proposal'])){ /** if it is proposal version */
            $allowStatus = 4;
            $orderVersion = 2;
        }else{
            $allowStatus = 1;
            $orderVersion = ORDER_VERSION;
        }
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
            if(!($orderData->getOrderByID(false))){
                echo "error: 6031.  Deleting Faild.";
                exit();
            }
        }
        if($orderData->costumerID !== $_SESSION['userID']){
            echo "error: 6026.  Processing Faild.";
            exit();
        }
        if($orderData->procStatus !== $allowStatus){ /** 1 refers to opened order */
            echo "error: 6036.  Processing Faild.";
            exit();
        }
        if($orderData->getCreatedBy() > 0 && $orderVersion != 2){
            echo "error: 6041.  Processing Faild.";
            exit();
        }
        /*get and validate item */
        $itemData =  new item($isSuc, $itemID);
        if(!($itemData->getItemByID())){
            echo "error: 6036.  Processing Faild.";;
            exit();
        }
        $orderItem = new orderItem($is,null,$orderID,$itemID,$itemData->salePrice,$Qty,$itemData->$expDateProp,$orderVersion);
        if(isset($_POST['op']) && $_POST['op'] == 'update'){
            $targetID = $orderItem->getOrderItemPropWhere('ID', ['=','=','='], ['itemID','orderID','orderVersion']);
            if($targetID > 0){
                $orderItem->ID = $targetID;
                if($orderItem->updateOrderItem($err, 'ID','itemQty')){
                    echo 1;
                    exit();
                }else{
                    echo "error: 7046.  Updating faild";
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
                echo "error: 7059.  Addition Faild.";
                exit();
            }
        }
    }
}
echo "error: 6065.  Processing Faild.";;
exit();