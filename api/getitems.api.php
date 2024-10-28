<?php
session_start();
    if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
        $dir = "../";
    $isLoggedIn = false;
        $noHeader = "No";
        require("../init.php");
    /*     $limit = (isset($_POST['limit']) && is_numeric($_POST['limit'])) ? intval($_POST['limit']) : 50;
        $page = (isset($_POST['page']) && is_numeric($_POST['page'])) ? intval($_POST['page']) : 1; */
        $smallestQty = setting::getSpecificSetting('leastItemQtyVis')['value']; /** return number if success or false if faild */
        $smallestQty = (is_numeric($smallestQty)) ? $smallestQty : 0 ; /** if isn't number that means it's false so set it 0 as default value */
        $where = "visibility > 0 AND Qty >= ". $smallestQty. " ORDER BY `itemName` ASC";
        $items = stat_getObjects::getItems($where);
        if(is_array($items)){
            echo json_encode($items, JSON_PRETTY_PRINT);
        }else{
            echo json_encode([0,'There are no items for this invoice'], JSON_PRETTY_PRINT);
        }
    }
 exit();