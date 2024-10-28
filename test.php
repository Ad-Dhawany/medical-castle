<?php
session_start();
if(!isset($_SESSION['username'])){
   header('location: dashboard.php');
   exit();
}
  $noNav = "no";
  $noSideBar = "no";
   include "init.php"; 
   ?>
    <main class="m-5 p-5">
         
      
    <br>
    <br>
   <?php
      echo var_dump($_SESSION['fullname']). "<br>";
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
    echo ucwords($profileName);
  echo "</main>";
   include $tempsP. "footer.php";
   