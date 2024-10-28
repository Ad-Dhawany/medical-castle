<?php
session_start();
const ORDER_VERSION = 1; /* 1 refers to this item inserted by an admin */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
    /* $orderID = intval($_POST['orderID']); */
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if(isset($_POST['orderID']) && is_numeric($_POST['orderID'])){
        $orderID = intval($_POST['orderID']);
        $allowStatus = [3,4];
        $orderVersion = ORDER_VERSION;
        /*get and validate order */
        $orderData = new order($is,$orderID);
        $orderData->orderVersion = $orderVersion;
        if(!($orderData->getOrderByID())){
            echo "error: 6021.  Deleting Faild.";
            exit();
        }
        if($orderData->createdBy > 0){ /** if it is created by an admin */
            $allowStatus[] = 1;
        }
        if(!in_array($orderData->procStatus, $allowStatus)){ /** 1 refers to opened order */
            echo "error: 6023.  Processing Faild.";
            exit();
        }else{
            if(isset($_POST['itemID'],$_POST['itemQty']) && is_numeric($_POST['itemID']) && is_numeric($_POST['itemQty'])){
                $Qty = intval($_POST['itemQty']);
                $itemID = intval($_POST['itemID']);
                $orderItem = new orderItem($is, null, $orderID, $itemID, null, $Qty,null,$orderVersion);
                if($is){
                    if($orderItem->deleteOrderItem($err, 'orderID','itemID','itemQty','orderVersion')){
                        echo 1;
                    }else{
                        echo "error: 7035, Deleting Faild";
                    }
                    exit();
                }
            }elseif(isset($_POST['empty']) && $_POST['empty'] == 'all'){
                $orderItem = new orderItem($is, null, $orderID, null, null, null,null,$orderVersion);
                if($is){
                    if($orderItem->deleteOrderItem($err, 'orderID','orderVersion')){
                        echo 1;
                        exit();
                    }else{
                        echo "error: 7045, Deleting Faild";
                    }
                }
            }
        }
    }
}
/* var_dump($_POST['itemID']);
var_dump($_POST['empty']); */
echo "error: 7054, Deleting Faild";
exit();