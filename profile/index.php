<?php
ob_start();
    session_start();
    $pageTitle = 'My Prpfile';
    $dir = "../";
    $isLoggedIn = false;
    // const costGroupID = 0;
    /* to verify that the person is user and has admin permissions */
    if(!isset($_SESSION['username']) || !isset($_SESSION['userID']) || !isset($_SESSION['groupID'])){
        header('location: ../sign/');
        exit();
    }else{
        $isLoggedIn = true;
    }
    include $dir."init.php";
    if($_SESSION['groupID'] < 0 || $_SESSION['groupID'] > 3){
        $errorMsg = "You have no permission to access";
        fnc::redirectHome($errorMsg,"back");
    }
    ?>
    <script>var pageTitle = "<?php echo langs('PROFILE') ?>" ;</script>
    <?php /* this page actually is a collects of pages and $do is the param to select the required page */
    $do = isset($_GET['do']) ? $_GET['do'] : 'info'; /* do by default is info page */
    if($do == 'info'){
                $ID = $_SESSION['userID'];
                $costumer = new costumer($e,$ID);
                $count = $costumer->getMemberByID(false);
                if($count){
            ?>
            <main class='' data-page-title="<?php echo lang('PRO INF')?>">
                <h1 class="text-center"><?php echo lang('PRO INF'). " <span class='text-secondary'>(" .ucwords($costumer->username). ")</span> " ?></h1>
                <div class="container">
                    <div class="row">
                    <div class="offset-md-2 offset-1 col-md-8 col-10">
                        <div class="table-responsive">
                            <table class="main-table more-info table table-bordered">
                                <thead>
                                    <td><?php echo lang('PROPERTY') ?></td>
                                    <td><?php echo lang('VALUE') ?></td>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?php echo lang("USERNAME")?></td>
                                    <td><b><?php echo ucwords($costumer->username) ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("EMAIL")?></td>
                                    <td><?php echo $costumer->email ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("PHO NUM")?></td>
                                    <td><?php echo fnc::prettyPhone($costumer->phone) ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("FULLNAME")?></td>
                                    <td><?php echo ucwords($costumer->fullname) ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("PHARMACY")?></td>
                                    <td><?php echo ucwords($costumer->pharmacy) ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("REG DAT")?></td>
                                    <td><?php echo date("Y-m-d / H:i a",strtotime($costumer->regDate)) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="">
                        <a class="btn btn-secondary" href = "?do=manage"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK') ?></a>
                        <a class='btn btn-secondary' href='?do=edit&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-edit'></i> <?php echo lang("EDIT")?></a>
                        <?php if ($costumer->regStatus > -1){ ?>
                        <a class='btn btn-danger confirm disabled' href='?do=trash&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-trash'></i> <?php echo lang("PEND")?></a>
                        <?php } ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    }else{
        $errorMsg = "Invalid ID";
        fnc::redirectHome($errorMsg, 'back', 0);
    }
        
    }elseif($do == 'edit'){
        //Start Edit page
        ?>
            <script>var pageTitle = "<?php echo  lang('EDI PRO') ?>" ;</script>
        <?php
        //If the ID doesn't set will be set as current costumer ID if it set by get method it will validating if it numeric or not. The get method for use the page to edit the user information from member page
        $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : $_SESSION['userID'];
        $costumer = new costumer($e,$ID);
        $count = $costumer->getMemberByID(false); /* fitch user info by useing ID, return true if the proccess success, return false if proccess fail */
        if ($count){?>
            <h1 class="text-center m-3"><?php echo lang('EDIT'); ?> <span style="color:darkslategray"><?php echo ucwords($costumer->fullname) ?></span> <?php echo lang('INFORMATION'); ?></h1>
            <div class="container">
                <div class="row offset-lg-2">
                <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                <form action="./?do=update" method="POST" class="form row g-1">
                    <div style="display: none;"><input type="hidden" name="id" class="form-control" value="<?php echo $costumer->userID?>"></div>
                    <div class="form-group form-group-lg row mb-2">
                        <label class="col-sm-2 control-label"><?php echo lang("FULLNAME") ?></label>
                        <div class="input-field col-sm-10 col-md-6">
                            <input type="text" name="fullname" class="form-control" value="<?php echo ucwords($costumer->fullname) ?>" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' ])+[ء-يa-z]$"  required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg row mb-2">
                        <label class="col-sm-2 control-label"><?php echo lang("PASSWORD") ?></label>
                        <div class="input-field col-sm-10 col-md-6">
                            <input type="password" id="pass-field" name="password" data-pass="origin" class="form-control"  autocomplete="new-password" required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg row mb-2">
                        <label class="col-sm-2 control-label"><?php echo lang("NEW PAS") ?></label>
                        <div class="input-field col-sm-10 col-md-6">
                            <input type="password" id="pass-field" name="new-password" data-pass="repeat" class="form-control" autocomplete="new-password" placeholder="<?php echo langTXT("LEA IT EMP") ?>">
                        </div>
                    </div>
                    <div class="form-group form-group-lg row mb-2">
                        <label class="col-sm-2 control-label"><?php echo lang("PHO NUM") ?></label>
                        <div class="input-field col-sm-10 col-md-6">
                            <input type="text" name="phone" class="form-control" value="<?php echo $costumer->phone; ?>" pattern="^[+0]{1}[- 0-9]{9,15}$" placeholder="e.g: 0921234567 , +218911234567 or 00218-91-123-4567" required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg row mb-2">
                        <label class="col-sm-2 control-label"><?php echo lang("EMAIL") ?></label>
                        <div class="input-field col-sm-10 col-md-6">
                            <input type="Email" name="email" class="form-control" value="<?php echo $costumer->email; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg row mb-2">
                        <label class="col-sm-2 control-label"><?php echo lang("PHARMACY") ?></label>
                        <div class="input-field col-sm-10 col-md-6">
                            <input type="text" name="pharmacy" class="form-control" value="<?php echo ucwords($costumer->pharmacy) ?>" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' 0-9])+[ء-يa-z0-9]$"  required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg row mb-2 d-block col-lg-8 col-md-8 col-sm-8">
                    <!-- <label class="col-2"></label> -->
                        <div class="w-max-cont fa-pull-right">
                            <a class="btn btn-secondary btn-lg" href="?do=manage"><?php echo lang('BACK'); ?></a>
                            <input type="submit" class="btn btn-primary btn-lg" value="<?php echo lang('SAVE'); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        }else{
            $errorMsg = "An Error in user information <br>please retry or logout and relogin";
            fnc::redirectHome($errorMsg);
            exit();
        }
    }elseif($do == 'update'){
        //Start Update methodology
        if(isset($_POST['id'],$_POST['fullname'], $_POST['password'], $_POST['email'], $_POST['phone'], $_POST['pharmacy'])){
            //Validation inputs
            $formErrors = array();
            /* if(!fnc::isArabicStr($_POST['username'], false)){
                if (!preg_match("/^[a-zA-Z]+([a-zA-Z'.0-9]){3,20}$/",$_POST['username'])) {
                    $formErrors[] = langTXT("use err mes");
                }
            } */
            if(empty($_POST['fullname'])){
                $formErrors[] = '<strong>Fullname</strong> is required';
            }
            if(!fnc::isArabicStr($_POST['fullname'])){
                if (!preg_match("/^[a-zA-Z]+([a-zA-Z' ])+[a-z]$/",$_POST['fullname'])) {
                    $formErrors[] = langTXT("ful nam err");
                }
            }
            if(strlen($_POST['fullname']) < 4){
                $formErrors[] = '<strong>Fullname</strong> should content at lest <b>4</b> chararcters';
            }
            if(strlen($_POST['fullname']) > 24){
                $formErrors[] = '<strong>Fullname</strong> should content at most <b>24</b> chararcters';
            }
            /* if(!($costumerUb->preparePhone())){
                $formErrors[] = 'Invalid <strong>Phone Number</strong>. The phone number must be <b>Libyan</b> number';
            } */
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
                $formErrors[] = '<strong>E-mail</strong> should is required';
            }

            if(empty($formErrors)){
            $costumerUb = new costumer($e, $_POST['id'], null, $_POST['email'],$_POST['phone'], strtolower($_POST['fullname']),null,null,null,null,null,strtolower($_POST['pharmacy']));
            if(!$e){
                fnc::redirectHome("?بس.,.؟./4534DDD$", 'back', 0);
            }
            if(!($costumerUb->getGroupID() === costGroupID)){
                fnc::redirectHome("?؟./201DDD$", 'back', 0);
            }
            /* $costumerUb->preparePhone(); */
            if(!($costumerUb->preparePhone())){
                fnc::redirectHome('Invalid <strong>Phone Number</strong>. The phone number must be <b>Libyan</b> number', "back");
            }
            if(fnc::checkitem("userID",'users',$costumerUb->userID) != 1){
                fnc::redirectHome("x0205", 'back', 0);
            }
            if($costumerUb->isThereUniqueProbInAnotherMemb($prob, 'phone', 'email', 'pharmacy') != 0){
                $errorMsg = "The <span style='color: red'>$prob</span>: ". $costumerUb->$prob ." is used before, Please try another $prob";
                fnc::redirectHome($errorMsg,'back');
            }
            $updateProps = ["fullname", 'email', 'phone', "pharmacy"];
            $notiTybe = "costumer//update";
            if(!empty($_POST['new-password'])){
                $newPassword = $_POST['new-password'] ;
                $updateProps[] = "password";
                $notiTybe .= "//password";
            }else{
                $newPassword =  $_POST['password'];
            }
            $costumerUb->setMembPassword($_POST['password']);
            //confirm the user identity
            
            $count  = $costumerUb->membLoign(null,null,$e,"userID","verify"); // get number of rows in $stmt
            if ($count){ // confirming success
                $costumerUb->setMembPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                $costumerUb->lastEditBy = $_SESSION['userID'];
                $costumerUb->lastEditDate = date("Y-m-d H:i");
                /* $stmt2 = $conn->prepare("UPDATE users SET fullname = ?, password = ?, email = ?, phone = ?, groupID = ? WHERE username = ?");
                $stmt2->execute(array($costumerUb->fullname, $costumerUb->getMembPassword(), $costumerUb->email, $costumerUb->phone, $costumerUb->getGroupID(),$costumerUb->username)); */
                $count2 = $costumerUb->updateMemb($e, "userID", ...$updateProps);
                if($count2){
                    
                    $notification = new broadNotifications($is, null, $_SESSION['userID'], -1, $notiTybe, 1, $costumerUb->userID);
                    $notification->insertNotification($e, 'ALL');
                    $successMsg = '<h4 class="text-center">'. $count2 .' Update recorded </h4>';
                    fnc::redirectHome($successMsg, 'back', 3, 'success');
                }else{
                    fnc::redirectHome($e, 'back');
                }
            }else{
                $errorMsg = "Wrong <b>Password</b>.";
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
            header('location: ../');
            exit();
        }
    }else{
        fnc::redirectHome("","back",0);
    }
    include $dir. $tempsP. "footer.php";
ob_end_flush();