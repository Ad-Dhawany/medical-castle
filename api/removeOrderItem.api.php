<?php
session_start();
const ORDER_VERSION = 0; /* 0 refers to this item inserted by a costumer */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
    /* $orderID = intval($_POST['orderID']); */
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if(isset($_POST['orderID']) && is_numeric($_POST['orderID'])){
        $orderID = intval($_POST['orderID']);
        if(isset($_POST['proposal'])){ /** if it is proposal version */
            $allowStatus = 4;
            $orderVersion = 2;
        }else{
            $allowStatus = 1;
            $orderVersion = ORDER_VERSION;
        }
        /*get and validate order */
        $orderData = new order($is,$orderID);
        $orderData->orderVersion = $orderVersion;
        if(!($orderData->getOrderByID())){
            if(!($orderData->getOrderByID(false))){
                echo "error: 6021.  Deleting Faild.";
                exit();
            }
        }elseif($orderData->costumerID !== $_SESSION['userID']){
            echo "error: 6024.  Deleting Faild.";
            exit();
        }elseif($orderData->procStatus !== $allowStatus){ /** 1 refers to opened order */
            var_dump($orderData->procStatus);
            var_dump($allowStatus);
            echo "error: 6027.  Deleting Faild.";
            exit();
        }elseif($orderData->getCreatedBy() > 0 && $orderVersion != 2){
            echo "error: 6032.  Processing Faild.";
            exit();
        }else{
            if(isset($_POST['itemID'],$_POST['itemQty']) && is_numeric($_POST['itemID']) && is_numeric($_POST['itemQty'])){
                $Qty = intval($_POST['itemQty']);
                $itemID = intval($_POST['itemID']);
                $orderItem = new orderItem($is, null, $orderID, $itemID, null, $Qty,null,$orderVersion);
                if($is){
                    if($orderItem->deleteOrderItem($err, 'orderID','itemID','itemQty','orderVersion')){
                        echo 1;
                        exit();
                    }else{
                        echo "error: 7031, Deleting Faild";
                    }
                }
            }elseif(isset($_POST['empty']) && $_POST['empty'] == 'all'){
                $orderItem = new orderItem($is, null, $orderID, null, null,null,null,$orderVersion);
                if($is){
                    if($orderItem->deleteOrderItem($err, 'orderID','orderVersion')){
                        echo 1;
                        exit();
                    }else{
                        echo "error: 7039, Deleting Faild";
                    }
                }
            }
        }
    }
}
/* var_dump($_POST['itemID']);
var_dump($_POST['empty']); */
echo "error: 7045, Deleting Faild";
exit();