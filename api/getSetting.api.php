<?php
session_start();
    if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target'])){
            $dir = "../";
    $isLoggedIn = false;
            $noHeader = "No";
            require("../init.php");
            $target = $_POST['target'];
            if($target == 'all'){
                $result = setting::getAllSetting();
            }else{
                $result = setting::getSpecificSetting($target)['value'];
            }
            echo json_encode($result, JSON_PRETTY_PRINT);
        }
        exit();

    }