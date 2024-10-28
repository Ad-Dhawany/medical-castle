<?php
    require_once('confiq.php');
    if(!isset($dir)){$dir = "";}
    $langP     = /*$dir.*/ "includes/lang/";
    $funcP     = /*$dir.*/ "includes/functions/";
    $clsP      = /*$dir.*/ "includes/classes/";
    $tempsP    = /*$dir.*/ "includes/temps/";
    $jsP       = $dir. "themes/js/";
    $cssP      = $dir. "themes/css/";
    include $langP . "english.php";
    //include $funcP . "functions.php";
    require_once($clsP. "fnc.class.php");
    spl_autoload_register(function($class){
        global $clsP;
        require($clsP. $class. ".class.php");
    });
    if(!isset($noHeader)){
        include $tempsP . "header.php"; 
        if(!isset($noNav)){ include $tempsP. "navbar.php"; }
        if(!isset($noSideBar)){ include $tempsP. "sidebar.php"; }
    }
    const permissionsCONST =["COSTUMER", "EMPLOYEE", "RESPONSIBLE", "ADMIN"];
    $arAlphabet = "ءإاأبتثجحخدذرزسشصضطظعغفقكلمنهويةى";