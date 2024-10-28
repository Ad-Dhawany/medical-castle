<?php
session_start();
const ORDER_VERSION = 0; /* 0 refers to this item inserted by costumer */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0
    && isset($_POST['orderID']) && is_numeric($_POST['orderID'])){
    $orderID = intval($_POST['orderID']);
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    $orderVersion = (isset($_POST['proposal'])) ? 2 : ORDER_VERSION;
    $orderItems = stat_getObjects::getOrderItems($orderID, 1 , $orderVersion);
    if(is_array($orderItems)){
        echo json_encode($orderItems, JSON_PRETTY_PRINT);
    }else{
        echo json_encode([0,'There are no items for this invoice'], JSON_PRETTY_PRINT);
    }
}
exit();