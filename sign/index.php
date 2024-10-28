<?php
ob_start();
session_start();
if(isset($_SESSION['username'],$_SESSION['groupID'])){
   $isLoggedIn = true;
   if($_SESSION['groupID'] > -1){
      header('location: ../');
      exit();
   }
}
$noNav='';
$noSideBar='';
$pageTitle = 'Login';
$dir = "../";
$isLoggedIn = false;
require_once $dir. "init.php";
//check if comming by POST METHOD
if($_SERVER['REQUEST_METHOD']=='POST'){
   /** بدأ خوارزميات تسجيل الدخول */
   if(isset($_POST['type']) && $_POST['type'] == 'login'){
      if(isset($_POST['username'], $_POST['password'])){
         $username   = trim(strip_tags($_POST['username']));
         $password   = $_POST['password'];
         $costumer = new costumer();
         if($costumer->membLoign($username, $password, $err)){
            if(!isset($err)){
               $_SESSION['userID']     = $costumer->userID;
               $_SESSION['username']   = $costumer->username;
               $_SESSION['fullname']   = $costumer->fullname;
               $_SESSION['groupID']    = $costumer->getGroupID();
               $_SESSION['regStatus']  = $costumer->regStatus;
               $_SESSION['trustStatus']= $costumer->trustStatus;
               $_SESSION['email']      = $costumer->email;
               header('location: '. $dir);
               exit();
            }
         }else{
            fnc::redirectHome($err, 'back');
         }
      }
   /***************/
   /** بدأ خوارزميات تسجيل عضوية جديدة */
   }elseif(isset($_POST['type']) && $_POST['type'] == 'register'){
      if(isset($_POST['username'],$_POST['password'],$_POST['repeated-password'],$_POST['fullname'],$_POST['phone'],$_POST['email'],$_POST['pharmacy'],$_POST['municipal'])){
         //Validation inputs
         $formErrors = array();
         if(!fnc::isArabicStr($_POST['username'], false)){
               if (!preg_match("/^[a-zA-Z]+([a-zA-Z'.0-9]){3,20}$/",$_POST['username'])) {
                  $formErrors[] = langTXT("use err mes");
               }
         }
         if(empty($_POST['fullname'])){
               $formErrors[] = '<strong>Fullname</strong> is required';
         }
         $min = 14; /* أقل عدد حروف = 7 في حالة الأحرف عربية (لأن الأحرف لعربية تأخذ 2بايت) */
         $max = 48; /* أكبر عدد من الأحرف يساوي 24 في حالة الأحرف عربية */
         if(!fnc::isArabicStr($_POST['fullname'])){
               if (!preg_match("/^[a-zA-Z]+([a-zA-Z' ])+[a-z]$/",$_POST['fullname'])) {
                  $formErrors[] = langTXT("ful nam err");
               }
               $min = 7; /* في حال الأحرف ليست عربية فإن الأحرف الإنجليزية تأخذ 1 بايت وفي هذه الحالة أقل عدد هو 7 أحرف*/
               $max = 24; /* وأكبر عدد هو 24 حرف*/
         }
         if(strlen($_POST['fullname']) < $min){
               $formErrors[] = '<strong>Fullname</strong> should content at lest <b>7</b> chararcters';
         }
         if(strlen($_POST['fullname']) > $max){
               $formErrors[] = '<strong>Fullname</strong> should content at most <b>24</b> chararcters';
         }
         if(!($costumerNew->preparePhone())){
            $formErrors[] = 'Invalid <strong>Phone Number</strong>. The phone number must be <b>Libyan</b> number';
         }
         if(empty($_POST['pharmacy'])){
               $formErrors[] = '<strong>Pharmacy</strong> is required';
         }
         if(!fnc::isArabicStr($_POST['pharmacy'])){
               if (!preg_match("/^[a-zA-Z]+([a-zA-Z' ])+[a-z]$/",$_POST['pharmacy'])) {
                  $formErrors[] = langTXT("pha err mes");
               }
         }
         if(empty($_POST['password'])){
               $formErrors[] = '<strong>password</strong> is required';
         }
         if(empty($_POST['email'])){
               $formErrors[] = '<strong>E-mail</strong> is required';
         }
         if(empty($formErrors)){
         //confirm is password and repeated password matched
               if($_POST['password'] != $_POST['repeated-password']){
                  $errorMsg='Password and repeated password are not matched';
                  fnc::redirectHome($errorMsg);
                  exit();
               }
               /** validate pharmacy address */
               $address = (in_array($_POST['municipal'], $municipalsArray)) ? strip_tags($_POST['municipal']) : fnc::redirectHome("خطأ: 6092", 'back',1);
               if(isset($_POST['town'])){
               $postedTown = strtoupper(strip_tags(trim($_POST['town'])));
               if(fnc::isTownExistInConstArray($postedTown)){
                  $address .= " | ". $postedTown;
               }else{
                  $address .= " | OTHER | ". $postedTown;
               }
               }
               $costumerNew = new costumer($e, null, strtolower($_POST['username']), $_POST['email'],$_POST['phone'], strtolower($_POST['fullname']),null,-1,date("Y-m-d H:i"),0,$_POST['password'],strtolower($_POST['pharmacy']),$address);
               if(!$e){
                  fnc::redirectHome("خطأ 6086", 'back', 1);
               }
               if($costumerNew->getGroupID() !== costGroupID){
                  fnc::redirectHome("خطأ:6090", 'back', 1);
               }
         //confirm that there is no user with same username
               if($costumerNew->isMemberExist($prob, "OR",'username', 'fullname','phone', 'email', 'pharmacy') != 0){
                  $errorMsg = "The <span style='color: red'>$prob</span>: ". $costumerNew->$prob ." is used before, Please try another $prob";
                  fnc::redirectHome($errorMsg,'back');
               }
               $costumerNew->setMembPassword(null, true);
               $verifyEmail = rand(100, 999). "". rand(100, 999);
               /* $verifyEmail = str_pad($verifyEmail,6,0); */
               $costumerNew->verifyEmail = $verifyEmail;
               //insert new costumer
               $count  = $costumerNew->insertMemb($e,"username","fullname", 'password', 'email', 'regDate', 'phone', "groupID", 'trustStatus', 'regStatus', "pharmacy","pharmacyAddress", "verifyEmail"); // get numper of rows in $stmt
               if ($count){
               $costumerNew->userID = $count ; /* insertMemb will return inserted userID automatically */ /* $costumerNew->getMemPropByProp('userID', 'username', 'username'); */
               $_SESSION['userID']     = $costumerNew->userID;
               $_SESSION['username']   = $costumerNew->username;
               $_SESSION['fullname']   = $costumerNew->fullname;
               $_SESSION['groupID']    = $costumerNew->getGroupID();
               $_SESSION['regStatus']  = $costumerNew->regStatus;
               $_SESSION['trustStatus']= $costumerNew->trustStatus;
               $_SESSION['email']      = $costumerNew->email;
               $subject = "رمز التأكيد";
               $msg = "<main>
                        <section>
                              <div dir='rtl'>
                                 مرحباً بك في شركة القلعة الطبية بالأسفل رمز تأكيد بريدك الإلكتروني قم بإدخاله بالحقل المطلوب أو انقر على الرابط في آخر الرسالة
                              </div>
                              <div>
                                 <center><h3 dir='rtl'>رمز التأكيد: <strong>". $verifyEmail. "</strong></h3></center>
                              </div>
                              <div>
                                 Welcome to Medical Castle, above is verification code to verify your e-mail. Enter it the required field onto the website. or click on the link bellow. 
                              </div>
                        </section>
                        </main>
                        <footer>
                        <div dir='rtl'><center><a href='www.medicalcastle.ly/?verify=email&code=". $verifyEmail. "'><i><b>رابط التأكيد المباشر  Verifiction  Link</b></i></a></center></div>
                        <div>
                           <p dir='rtl'>ملاحظة: يجب إدخال رابط التأكيد المباشر من نفس المتصفح الذي سجلت منه</p>
                           <p>Notice: The verification link must be entered by using the same browser that used to registration</p>
                        </div>
                        </footer>";
               $headers = "From: support@medicalcastle.ly\r\n";
               $headers .= "MIME-Version: 1.0 \r\n";
               $headers .= "Content-type: text/html; charset=UTF-8\r\n";
               mail($costumerNew->email, $subject, $msg, $headers);
               $successMsg = "The addition has completed";
               fnc::redirectHome($successMsg, './home/?verify=email', 3, 'success');
         }else{
               $errorMsg = '<span style="color: red">خطأ: 7104</span> اتصل بمزود الخدمة أو أعد المحاولة لاحقاً';
               fnc::redirectHome($errorMsg, 'back');
         }
         }else{
               $errorMsg = "";
               $errorsCount = 3;
               foreach($formErrors as $error){
                  $errorMsg .= "<div class='alert alert-warning col-lg-8 offset-lg-2 mb-0 mt-3' style='color: #842029'> $error. </div>";
                  $errorsCount++;
               }
               fnc::redirectHome($errorMsg, 'back', $errorsCount);
         }
      }else{
            $errorMsg ='please fill the required fields with valid values';
            fnc::redirectHome($errorMsg);
      }
   }
}

?>
   <style>
      body{
         background-image: url("../media/backgrounds/login-back-2.jpg");
      }
   </style>
<main class="p-0" data-page-title="<?php echo lang('LOGIN') ?>">
   <div class="card log-panel col-lg-5 offset-lg-7 col-md-6 offset-md-6 col-10 offset-1 mt-md-0">
      <header class="card-header log-panel-header">
         <div class="btn-group log-tab vis-switcher-tab">
            <button data-vis-switch="login-form" class="btn btn-lg active"><?php echo lang("LOGIN") ?></button>
            <button data-vis-switch="register-form" class="btn btn-lg"><?php echo lang("REGISTER") ?></button>
         </div>
         <!-- <div class="">
            <h4 class="form-title card-title text-center"><?php echo lang("LOGIN") ?></h4>
         </div> -->
      </header>
      <section class="card-body px-5">
         <div class="login-div" data-vis-target="login-form">
            <form action="" method="POST" class="login" data-session="sign-in">
               <h4 id="login-title" class="text-center" style=""><?php echo lang("LOGIN") ?></h4>
               <div style = "display: none;" class="input-field">
                  <input type="hidden" class="hidden form-control" name="type" value="login" required="required">
               </div>
               <div title="<?php echo lang("USERNAME") ?>" class="input-field d-inline-flex w-100">
                  <label class="icon-label" for="username"><i class="fas fa-user"></i></label>
                  <input type="text" class="form-control" name="username" placeholder="<?php echo lang("USERNAME") ?>" autocomplete="off" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z'.0-9]){3,20}$" required="required">
               </div>
               <div title="<?php echo lang("PASSWORD") ?>" class="input-field d-inline-flex w-100">
                  <label class="icon-label" for="password"><i class="fas fa-lock"></i></label>
                  <input type="password" id="pass-field" class="form-control" name="password" placeholder="<?php echo lang("PASSWORD") ?>" autocomplete="new-password" required="required">
               </div>
               <input type="submit" value="<?php echo lang("SIGN-IN") ?>" class="btn btn-primary w-100">
            </form>
         </div>

         <div class="signup-div" data-vis-target="register-form" style="display: none;">
            <form action="" method="POST" class="signup login" data-session="sign-up">
               <h4 id="signup-title" class="text-center"><?php echo lang("SIGN-UP") ?></h4>
               <div style = "display: none;" class="input-field">
                  <input type="hidden" class="form-control" name="type" value="register" required="required">
               </div>
               <div title="<?php echo lang("USERNAME") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("USERNAME") ?></label>
                  <label class="icon-label" for="username"><i class="far fa-user"></i></label>
                  <input type="text" class="form-control" name="username" placeholder="<?php // echo lang("USERNAME") ?>" autocomplete="off" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z'.0-9]){3,20}$" required="required">
               </div>
               <div title="<?php echo lang("PASSWORD") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("PASSWORD") ?></label>
                  <label class="icon-label" for="password"><i class="fas fa-lock"></i></label>
                  <input type="password" id="pass-field" name="password" data-pass="origin" class="form-control" placeholder="<?php // echo lang("PASSWORD") ?>" autocomplete="new-password" required="required">
               </div>
               <div title="<?php echo lang("REP PAS") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("REP PAS") ?></label>
                  <label class="icon-label" for="repeatPass"><i class="fas fa-key"></i></label>
                  <input type="password" id="pass-field" name="repeated-password" data-pass="repeat" class="form-control" autocomplete="new-password" placeholder="<?php // echo lang("REP PAS") ?>" required="required">
               </div>
               <div title="<?php echo lang("FULLNAME") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("FULLNAME") ?></label>
                  <label class="icon-label" for="repeatPass"><i class="fas fa-user"></i></label>
                  <input type="text" name="fullname" class="form-control" placeholder="<?php // echo lang("FULLNAME") ?>" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' ])+[ء-يa-z]$" required="required">
               </div>
               <div title="<?php echo lang("PHO NUM") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("PHO NUM") ?></label>
                  <label class="icon-label" for="phone"><i class="fas fa-phone"></i></label>
                  <input type="text" name="phone" class="form-control" placeholder="<?php // echo lang("PHO NUM") ?>" title="<?php echo langTXT("PHO NUM SHO") ?>" pattern="^[+0]{1}[- 0-9]{9,15}$" required="required">
               </div>
               <div title="<?php echo lang("EMAIL") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("EMAIL") ?></label>
                  <label class="icon-label" for="email"><i class="fas fa-envelope"></i></label>
                  <input type="Email" name="email" class="form-control" placeholder="<?php // echo lang("EMAIL") ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required="required">
               </div>
               <div title="<?php echo lang("PHA NAM") ?>" class="input-field d-inline-flex w-100">
                  <label class="fake-placeholder"><?php echo lang("PHA NAM") ?></label>
                  <label class="icon-label" for="pharmacy"><i class="fas fa-prescription"></i></label>
                  <input type="text" name="pharmacy" class="form-control" placeholder="<?php // echo lang("PHA NAM") ?>" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' ])+[ء-يa-z]$" required="required">
               </div>
               <div class="municipals-cyties form-group d-flex w-100 mb-2">
                  <div class="municipals-towns col-6 me-1">
                     <label for="" class="form-label"><?php echo lang("MUNICIPAL")?></label>
                     <select class="form-select" name="municipal" id="select-municipal" required>
                        <option selected disabled> ----- </option>
                     <?php foreach($municipalsArray as $municipal){
                        echo "<option value='". $municipal. "'>". langTown($municipal). "</option>";
                     } ?>
                     </select>
                  </div>
                  <div class="municipals-towns col-6 ms-1">
                     <label for="" class="form-label"><?php echo lang("TOWN"). " / ". lang("DISTRICT")?></label>
                     <select class="form-select" name="town" id="select-town" disabled>
                        <option selected disabled> ----- </option>
                        <option value="other">Other</option>
                     </select>
                  </div>
               </div>
               <div>
                  <input type="submit" value="<?php echo lang("SIGN-UP") ?>" class="btn btn-primary w-100 my-1">
               </div>
            </form>
         </div>
      </section>
   </div>
</main>
<!-- Modal -->
   <div class="modal fade" id="otherTownModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="otherTownModal" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
                  <h2 class="modal-title" id="staticBackdropLabel"><?php echo langTXT("ENT THE PHA ADD") ?></h2>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
         <div class="modal-body">
            <input type="text" id="other-town-text" class="form-control" placeholder="<?php echo lang("TOW DES NAM") ?>">
         </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" id="enter-other-town" data-bs-dismiss="modal" class="btn btn-primary"><?php echo lang("ENTER") ?></button>
            </div>
         </div>
      </div>
   </div>
<?php include $dir. $tempsP. "footer.php";
ob_end_flush();