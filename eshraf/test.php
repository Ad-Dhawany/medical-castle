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

  <div class="my-5">

  <?php
   
   // fnc::prePrint(gd_info());
   // fnc::prePrint($_SERVER);
   var_dump(fnc::isArabicStr("حسن", false));
   




   ?>
  </div>
   <?php

   include $tempsP. "footer.php";
   