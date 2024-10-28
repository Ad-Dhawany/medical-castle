<?php
    if(isset($_GET['lang'])){
        $lang = $_GET['lang'];
        if($lang == "en"){
            setcookie("lang", "english", time() + (86400 * 30), "/"); // 86400 = 1 day
        }elseif($lang == "ar"){
            setcookie("lang", "arabic", time() + (86400 * 30), "/"); // 86400 = 1 day
        }
        header("location: ". ($_SERVER['HTTP_REFERER'] ?? "../"));
    }else{
        header("location: ../");
        exit();
    }
