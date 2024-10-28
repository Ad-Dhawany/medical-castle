<?php
/* session_start();
if(!isset($_SESSION['username'])){
   header('location: dashboard.php');
   exit();
} */
  $noNav = "no";
  $noSideBar = "no";
   include "init.php"; 
   ?>
  <form action="" method="post">
   <input type="date" name="date" id="">
   <input type="text" name="text">
   <input type="number" name="num" id="">
   <input type="submit" value="go" class="btn btn-success">
  </form>
  <div class="my-5">
   
   <?php
   $t = array(
      -1 => 'trash',
      0  => 'hidden',
      1  => 'visibile'
   );
   fnc::prePrint(array_search('trashs', $t));
   ?>
  </div>
   <?php

   include $tempsP. "footer.php";
   