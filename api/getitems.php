<?php
session_start();
    if(isset($_SESSION['userID'])){
    $dir = "../";
    $noHeader = "No";
    require("../init.php");
    $limit = (isset($_POST['limit']) && is_numeric($_POST['limit'])) ? intval($_POST['limit']) : 50;
    $page = (isset($_POST['page']) && is_numeric($_POST['page'])) ? intval($_POST['page']) : 1;
    $where = (isset($_POST['vis']) && $_POST['vis'] == 'trash') ? "visibility = -1 ORDER BY itemID DESC" : "visibility > -1 ORDER BY itemID DESC";
    $items = stat_item::getItems($where);
    echo json_encode($items, JSON_PRETTY_PRINT);
    }