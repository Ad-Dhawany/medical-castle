<?php
session_start();
/* if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){ */
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    echo json_encode(MUNICIPALS_TOWNS, JSON_PRETTY_PRINT);
/* } */
exit();