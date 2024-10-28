<?php
    require_once('confiq.php');
    if(!isset($dir)){$dir = "";}
    $langP     = /*$dir.*/ "includes/lang/";
    $funcP     = /*$dir.*/ "includes/functions/";
    $clsP      = /*$dir.*/ "includes/classes/";
    $tempsP    = /*$dir.*/ "includes/temps/";
    $jsP       = $dir. "themes/js/";
    $cssP      = $dir. "themes/css/";
    /** */
    $arabic = [
        "lang-file" =>  "arabic",
        "dir"       =>  "rtl",
        "lang-html" =>  "ar",
        "bootstrap" =>  "bootstrap.rtl.min.css",
        "dropdown"  =>  "عربي"
    ];
    $english = [
        "lang-file" =>  "english",
        "dir"       =>  "ltr",
        "lang-html" =>  "en",
        "bootstrap" =>  "bootstrap.min.css",
        "dropdown"  =>  "EN"
    ];
    $LANGUAGE = $_COOKIE['lang'] ?? "english";
    $langArray = (in_array($LANGUAGE, ['english', 'arabic']))? $$LANGUAGE : $english;
    include_once $langP . $langArray['lang-file']. ".php";
    /***********/
    /** */
   /*  $unregistredsArray = array (
        "disabled-attr"     =>  "",
        "disabled-class"    =>  "",
        "title"             =>  "",
    );
    if(!$isLoggedIn){
        $unregistredsArray = array (
            "disabled-attr"     =>  "disabled='disabled'",
            "disabled-class"    =>  "disabled",
            "title"             =>  langTXT("FOR REG CUS ONL")
        );
    } */
    if(isset($isLoggedIn)){
        if($isLoggedIn){
            $unregistredsArray = array (
                "disabled-attr"     =>  "",
                "disabled-class"    =>  "",
                "title"             =>  "",
            );
        }else{
            $unregistredsArray = array (
                "disabled-attr"     =>  "disabled='disabled'",
                "disabled-class"    =>  "disabled",
                "title"             =>  langTXT("FOR REG CUS ONL")
            );
        }
    }
    /*******************/
    //include_once $funcP . "functions.php";
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
            $profileName = ucwords($profileName);
        }
    }
  /** */
  require_once("generalConst.php");
    if(!isset($noHeader)){
        include_once $tempsP . "header.php"; 
        if(!isset($noNav)){ include_once $tempsP. "navbar.php"; }
        if(!isset($noSideBar)){ include_once $tempsP. "sidebar.php"; }
        if(!isset($noSideBar) && $isLoggedIn){ include_once $tempsP. "bottombar.php"; }
    }