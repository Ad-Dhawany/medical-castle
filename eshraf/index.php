<?php
session_start();
if(isset($_SESSION['username'],$_SESSION['groupID'])){
   if($_SESSION['groupID'] > 0){
      header('location: dashboard/');
      exit();
   }
}
   $noNav='';
   $noSideBar='';
   $pageTitle = 'Login';
   include "init.php";
   ?>
   <script>var pageTitle = '<?php echo lang('LOGIN') ?>' ;</script>
   <?php
   //check if comming by POST METHOD
   if($_SERVER['REQUEST_METHOD']=='POST'){
      if(isset($_POST['user'], $_POST['pass'])){
         $username   = strip_tags($_POST['user']);
         $password   = $_POST['pass'];
         $memb = new member();
         if($memb->membLoign($username, $password, $err)){
            if(!isset($err)){
               $_SESSION['userID']   = $memb->userID;
               $_SESSION['username'] = $memb->username;
               $_SESSION['fullname'] = $memb->fullname;
               $_SESSION['groupID']  = $memb->getGroupID();
               $_SESSION['regStatus']  = $memb->regStatus;
               $_SESSION['trustStatus']= $memb->trustStatus;
               header('location: dashboard/');
               exit();
            }
            exit();
         }else{
            fnc::redirectHome($err, 'back');
         }
      }
      }

    ?>

   <main>
      <div class="logindiv">
         <form action="" method="POST" class="login">
            <h4 class="text-center">Admins Login</h4>
         <!--  <div style = "display: none;" class="input-field">
               <input type="text" class="hidden form-control" name="type" value="login" required="required">
            </div> -->
            <div class="input-field">
               <input type="text" class="form-control" name="user" placeholder="Username" autocomplete="off" required="required">
            </div>
            <div class="input-field">
               <input type="password" id="pass-field" class="form-control" name="pass" placeholder="Password" autocomplete="new-password" required="required">
            </div>
            <input type="submit" value="Login" class="btn btn-primary w-100">
         </form>
      </div>
      
   <!--   <div class="signup-div">
         <form action="" method="POST" class="signup">
            <h4 class="text-center">Admins Sign-Up</h4>
            <div style = "display: none;" class="input-field">
               <input type="text" class="hidden form-control" name="type" value="signup" required="required">
            </div>
            <div class="input-field">
               <input type="text" class="form-control" name="user" placeholder="Username" autocomplete="off" required="required">
            </div>
            <div class="input-field">
               <input type="password" id="pass-field" class="form-control" name="pass" placeholder="Password" autocomplete="new-password" required="required">
            </div>
            <input type="submit" value="Login" class="btn btn-primary w-100">
         </form>
      </div> -->
   </main>

<?php include $tempsP . "footer.php"; ?>
