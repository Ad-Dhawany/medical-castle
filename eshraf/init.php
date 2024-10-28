<?php
    require_once('confiq.php');
    if(!isset($dir)){$dir = "";}
    $langP     = $dir. "../includes/lang/";
    $funcP     = /*$dir.*/ "includes/functions/";
    $clsP      = /*$dir.*/ "includes/classes/";
    $tempsP    = /*$dir.*/ "includes/temps/";
    $jsP       = $dir. "themes/js/";
    $cssP      = $dir. "themes/css/";
    include_once $langP . "english.php";
    //include_once $funcP . "functions.php";
    require_once("generalConst.php");
    require_once($clsP. "fnc.class.php");
    spl_autoload_register(function($class){
        global $clsP;
        require($clsP. $class. ".class.php");
    });
    /** */
    if(isset($_SESSION['fullname'])){
        $fullnameStr = $_SESSION['fullname'];
        $charByte = (fnc::isArabicStr($fullnameStr)) ? 2 : 1;
        $profileName = "";
        $fullnameArr = explode(" ",$fullnameStr, 3);
        for($i=0;$i<count($fullnameArr);$i++){
            if($i < count($fullnameArr) - 1){
                $profileName .= substr($fullnameArr[$i],0,$charByte). ".";
            }else{
                $profileName .= " ". $fullnameArr[$i];
            }
        }
    }
  /** */
    if(!isset($noHeader)){
        include_once $tempsP . "header.php"; 
        if(!isset($noNav)){ include_once $tempsP. "navbar.php"; }
        if(!isset($noSideBar)){ include_once $tempsP. "sidebar.php"; }
        if(!isset($noSideBar)){ include_once $tempsP. "bottombar.php"; }
    }