<?php
ob_start();
    session_start();
    $pageTitle = 'Users';
    $dir = "../";
    const costGroupID = 0;
    /* to verify that the person is user and has admin permissions */
    if(!isset($_SESSION['username']) || !isset($_SESSION['userID']) || !isset($_SESSION['groupID'])){
        header('location: ../');
        exit();
    }
        include $dir."init.php";
        if($_SESSION['groupID'] != 3){
            $errorMsg = "You have no permission to access";
            fnc::redirectHome($errorMsg,"back");
        }
        ?>
        <script>var pageTitle = "<?php echo langs('COSTUMER') ?>" ;</script>
        <?php /* this page actually is a collects of pages and $do is the param to select the required page */
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; /* do by default is manage page */
        if($do == 'manage'){
            //Start Manage page
            ?>
            <script>var pageTitle = "<?php echo lang('COS MAN') ?>" ;</script>
            <?php
            $pending = 'AND regStatus > 0'; // $pending to set nonActivated users only or All users
            $title = 'COS MAN';
            $btnDanger = "TRASH"; /*to use it in lang()*/
            $faClass = "trash";
            $backHref = "../";
            if(isset($_GET['activ']) && $_GET['activ'] == 'pend'){
                $pending = 'AND regStatus = 0';
                $title = "PEN COS LIS";
                $btnDanger = "DELETE";
                $faClass = "close";
                $backHref = "?do=manage";
            }/** تم إلغاء هذه الميزة في صفحة المستخدمين لأنه لن يكون هناك تسجيل عضوية إلاّ عن طريق المشرف */
            if(isset($_GET['activ']) && $_GET['activ'] == 'trash'){
                $pending = 'AND regStatus = -1';
                $title = "TRA COS LIS";
                $btnDanger = "DELETE";
                $faClass = "close";
                $backHref = "?do=manage";
            }
            $finalBackHref = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $backHref;
            $hrefDanger = strtolower($btnDanger); /* to use it in href */
            //Select all users (company team)
            $where = "groupID = ". costGroupID. " $pending";
            $costumers = stat_memb::getMembers($where, 'costumer');
            ?>
            <h1 class="text-center"><?php echo lang($title) ?></h1>
            <div class="p-3">
                <div class="table-responsive">
                    <table class="main-table manage text-center table table-bordered">
                        <thead>
                            <td><?php echo lang('ID')?></td>
                            <td><?php echo lang('USERNAME')?></td>
                            <td><?php echo lang('EMAIL')?></td>
                            <td><?php echo lang("PHO NUM")?></td>
                            <td><?php echo lang('FULLNAME')?></td>
                            <td><?php echo lang("PHARMACY")?></td>
                            <td><?php echo lang('CONTROL')?></td>
                        </thead>
                        <?php
                        if(isset($costumers) && !empty($costumers)){
                            foreach($costumers as $costumer){
                                echo "<tr>
                                        <td><a class='text-decoration-none link-dark' target='blank' title='More Info.' href='?do=info&ID=". $costumer->userID ."'><b>".$costumer->userID."</b></a></td>
                                        <td><a class='text-decoration-none link-dark' target='blank' title='More Info.' href='?do=info&ID=". $costumer->userID ."'><b>".ucwords($costumer->username)."</b></a></td>
                                        <td><a class='text-decoration-none' target='blank' title='Send Mail' href='mailto:". $costumer->email ."'>".$costumer->email."</a></td>
                                        <td><a class='text-decoration-none' target='blank' title='Open whatsapp Chat' href='https://wa.me/". substr_replace($costumer->phone, "",0,2) ."'>".fnc::prettyPhone($costumer->phone)."</a></td>
                                        <td>".ucwords($costumer->fullname)."</td>
                                        <td>".ucwords($costumer->pharmacy)."</td>
                                        <td>
                                            <a class='btn btn-success' href='?do=edit&ID=".$costumer->userID."'><i class = 'fa fa-edit'></i> ".lang('EDIT')."</a>
                                            <a class='btn btn-danger confirm' href='?do=$hrefDanger&ID=".$costumer->userID."'><i class = 'fa fa-$faClass'></i> ".lang($btnDanger)."</a>";
                                            if($costumer->regStatus < 1){
                                             echo "
                                             <a class='btn btn-info' href='?do=activate&ID=".$costumer->userID."'><i class = 'fa fa-angles-up'></i> ".lang('ACTIVATE')."</a>";
                                            }
                                    echo "</td>
                                     </tr>
                                ";
                            }
                        }
                        ?>
                    </table>
                </div>
                <a class="btn btn-secondary" href="<?php echo $finalBackHref ?>"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK'); ?></a>
                <a class="btn btn-secondary" href = "?activ=pend"><i class="fa fa-person-circle-exclamation"></i> <?php echo lang('PEN LIS') ?></a>
                <a class="btn btn-secondary" href = "?activ=trash"><i class="fa fa-trash"></i> <?php echo lang('TRA BAS') ?></a>
                <a class="btn btn-primary" href = "?do=add"><i class="fa fa-plus"></i> <?php echo lang('NEW COS') ?></a>
            </div>
            <?php
        }elseif($do == 'edit'){
            //Start Edit page
            ?>
             <script>var pageTitle = "<?php echo  lang('EDI COS') ?>" ;</script>
            <?php
            //If the ID doesn't set will be set as current admin ID if it set by get method it will validating if it numeric or not. The get method for use the page to edit the user information from member page
            $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : $_SESSION['userID'];
            $costumer = new costumer($e,$ID);
            $count = $costumer->getMemberByID(); /* fitch user info by useing ID, return true if the proccess success, return false if proccess fail */
            if ($count){?>
                <h1 class="text-center m-3"><?php echo lang('EDIT'); ?> <span style="color:darkslategray"><?php echo ucwords($costumer->fullname) ?></span> <?php echo lang('INFORMATION'); ?></h1>
                <div class="container">
                    <div class="row offset-lg-2">
                    <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                    <form action="./?do=update" method="POST" class="form row g-1">
                        <div style="display: none;"><input type="text" name="username" class="form-control" value="<?php echo ucwords($costumer->username)?>"></div>
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
            if(isset($_POST['username'],$_POST['fullname'], $_POST['password'], $_POST['email'], $_POST['phone'], $_POST['pharmacy'])){
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
                $costumerUb = new costumer($e, null, strtolower($_POST['username']), $_POST['email'],$_POST['phone'], strtolower($_POST['fullname']),null,null,null,null,null,strtolower($_POST['pharmacy']));
                if(!$e){
                    fnc::redirectHome("?بس.,.؟./4534DDD$", 'back', 0);
                }
                if(!($costumerUb->getGroupID() === costGroupID)){
                    fnc::redirectHome("?بس.,.؟./4534DDD$", 'back', 0);
                }
                $costumerUb->preparePhone();
                if(fnc::checkitem("username",'users',$costumerUb->username) != 1){
                    fnc::redirectHome("x0001", 'back', 0);
                }
                if($costumerUb->isThereUniqueProbInAnotherMemb($prob, 'username', 'phone', 'email', 'pharmacy') != 0){
                    $errorMsg = "The <span style='color: red'>$prob</span>: ". $costumerUb->$prob ." is used before, Please try another $prob";
                    fnc::redirectHome($errorMsg,'back');
                }
                $newPassword = (empty($_POST['new-password'])) ? $_POST['password'] : $_POST['new-password'];
                $costumerUb->setMembPassword($_POST['password']);
                //confirm the user identity
               
                $count  = $costumerUb->membLoign(null,null,$e,"username","verify"); // get numper of rows in $stmt
                if ($count){ // confirming success
                    $costumerUb->setMembPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                    $costumerUb->lastEditBy = $_SESSION['userID'];
                    $costumerUb->lastEditDate = date("Y-m-d H:i");
                    /* $stmt2 = $conn->prepare("UPDATE users SET fullname = ?, password = ?, email = ?, phone = ?, groupID = ? WHERE username = ?");
                    $stmt2->execute(array($costumerUb->fullname, $costumerUb->getMembPassword(), $costumerUb->email, $costumerUb->phone, $costumerUb->getGroupID(),$costumerUb->username)); */
                    $count2 = $costumerUb->updateMemb($e,"username","fullname", 'password', 'email', 'phone', "groupID", "pharmacy");
                    if($count2){
                        $successMsg = '<h4 class="text-center">'. $count2 .' Update recorded </h4>';
                        fnc::redirectHome($successMsg, 'back', 3, 'success');
                    }else{
                        fnc::redirectHome($e, 'back',);
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
        }elseif($do == 'add'){
            //Start ADD page
            ?>
            <script>var pageTitle = "<?php echo  lang('ADD COS'); ?>" ;</script>
                <h1 class="text-center m-3"><?php echo lang('AD NE CO') ?></h1>
                <div class="container">
                    <div class="row offset-lg-2">
                    <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                    <form action="./?do=insert" method="POST" class="form row g-1" data-session="add-costumer">
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('USERNAME'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z'.0-9]){3,20}$" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('PASSWORD'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="password" id="pass-field" data-pass="origin" name="password" class="form-control"  autocomplete="new-password" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('REP PAS'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="password" id="pass-field" data-pass="repeat" name="repeated-password" class="form-control" autocomplete="new-password" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('FULLNAME'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="fullname" class="form-control" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' ])+[ء-يa-z]$" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("PHO NUM") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="phone" class="form-control" pattern="^[+0]{1}[- 0-9]{9,15}$" placeholder="e.g: 0921234567 , +218911234567 or 00218-91-123-4567" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('EMAIL'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="Email" name="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("PHARMACY") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="pharmacy" class="form-control" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' 0-9])+[ء-يa-z0-9]$"  required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2 d-block col-lg-8 col-md-8 col-sm-12">
                        <!-- <label class="col-2"></label> -->
                            <div class="w-max-cont fa-pull-right">
                                <input style = 'font-family: Times New Roman' type="submit" class="btn btn-primary btn-lg" value= "+ <?php echo lang('ADD COS'); ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        <?php }elseif($do == 'insert'){
            echo "<div class='container'>";
            //Start Insert methodology
            if(isset($_POST['username'],$_POST['fullname'], $_POST['password'], $_POST['phone'], $_POST['email'], $_POST['pharmacy'])){

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
                //confirm is password and repeated password matched
                    if($_POST['password'] != $_POST['repeated-password']){
                        header('location: ?do=add&error=Password and repeated password are not matched');
                        exit();
                    }
                    $costumerNew = new costumer($e, null, strtolower($_POST['username']), $_POST['email'],$_POST['phone'], strtolower($_POST['fullname']),null,null,date("Y-m-d H:i"),1,$_POST['password'],strtolower($_POST['pharmacy']));
                    if(!$e){
                        fnc::redirectHome("?بس.,.؟./4534DDD$1111", 'back', 5);
                    }
                    if($costumerNew->getGroupID() !== costGroupID){
                        fnc::redirectHome("?بس.,.؟./4534DDD$2222", 'back', 5);
                    }
                //confirm that there is no user with same username
                    if($costumerNew->isMemberExist($prob, "OR",'username', 'phone', 'email', 'pharmacy') != 0){
                        $errorMsg = "The <span style='color: red'>$prob</span>: ". $costumerNew->$prob ." is used before, Please try another $prob";
                        fnc::redirectHome($errorMsg,'back');
                    }
                    $costumerNew->setMembPassword(null, true);
                    $costumerNew->lastActiveBy = $_SESSION['userID'];
                    $costumerNew->lastActiveDate = date("Y-m-d H:i");
                //insert new user
                    $count  = $costumerNew->insertMemb($e,"username","fullname", 'password', 'email', 'regDate', 'phone', "groupID", 'regStatus', "lastActiveBy", "lastActiveDate", "pharmacy"); // get numper of rows in $stmt
                    if ($count){
                        $successMsg = "The addition has completed";
                        fnc::redirectHome($successMsg, '?do=manage', 3, 'success');
                }else{
                    $errorMsg = '<span style="color: red">Wrong Password</span> Please try again';
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
            echo "</div>";
        }elseif($do == 'delete'){
            //start delete page
            // echo "<h1 class='text-center'>".lang('DELETE')." ".lang('MEMBER')."</h1>";
            echo "<div class='container'>";
                //If the ID doesn't set will be set as invalid ID if it set by get method it will validating if it numeric or not. The get method for use the page to edit the user information from member page
                $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("No Id", null, 0);
                $costumerDel = new costumer($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('sdبيس420',"back",0);
                }
                $count  = $costumerDel->getMemberByID(); // if can't get a costumer by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($count){
                    if($costumerDel->getGroupID() === costGroupID){ /* if this costumer has admin permissions privent*/
                        if($costumerDel->deleteMemb($e, "userID")){
                            $successMsg = "<h5>The user : <span style='color: red'>".$costumerDel->username."</span> has deleted</h5>";
                            fnc::redirectHome($successMsg, 'back', 3, 'success');
                        }else{
                            fnc::redirectHome($e,'back');
                        }
                }else{
                    $errorMsg = "This User <span style='color: red'>".$costumerDel->fullname."</span> can't deleted";
                    fnc::redirectHome($errorMsg,'back');
                }
            }
            // echo "<div class='d-grid justify-content-center'> <a class='btn btn-primary' href=''>".lang('MEMBER')." ".lang('PAGE')."</a></div>";
            echo "</div>";
        }elseif($do == 'activate'){
            //start activate page
            /* echo "<h1 class='text-center'>".lang('ACTIVATE')." ".lang('MEMBER')."</h1>"; */
            echo "<div class='container'>";
                //If the ID doesn't set will be set as invalid ID if it set by get method it will validating if it numeric or not. The get method for use the page to edit the user information from member page
                $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("No Id", null, 0);
                $costumerAct = new costumer($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('sdبيس420',"back",0);
                }
                $couna  = $costumerAct->getMemberByID(); // if can't get a member by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($couna){
                    $costumerAct->regStatus = 1;
                    $costumerAct->lastActiveBy = $_SESSION['userID'];
                    $costumerAct->lastActiveDate = date("Y-m-d H:i");
                    if($costumerAct->updateMemb($e, "userID", "regStatus", "lastActiveBy", "lastActiveDate")){
                        $successMsg = "<h5>The user : <span style='color: red'>".$costumerAct->username."</span> has Activated</h5>";
                        fnc::redirectHome($successMsg, 'back', 3, 'success');
                    }else{
                        fnc::redirectHome($e,'back');
                    }
                }
        echo "</div>";
        }elseif($do == 'trash'){
            //start trash page
            echo "<div class='container'>";
            $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("No Id", null, 0);
            $costumerTr = new costumer($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('sdبيس420',"back",0);
                }
                $cound  = $costumerTr->getMemberByID(); // if can't get a member by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($cound){
                    $costumerTr->regStatus = -1;
                    $costumerTr->lastTrushBy = $_SESSION['userID'];
                    $costumerTr->lastTrushDate = date("Y-m-d H:i");
                    if($costumerTr->getGroupID() === costGroupID){ /* if thid member has admin permissions privent*/
                        if($costumerTr->updateMemb($e, "userID", "regStatus", "regStatus", "lastTrushBy","lastTrushDate")){
                            $successMsg = "<h5>The user : <span style='color: red'>".$costumerTr->username."</span> has trashed</h5>";
                            fnc::redirectHome($successMsg, 'back', 3, 'success');
                        }else{
                            fnc::redirectHome($e,'back');
                        }
                }else{
                    $errorMsg = "This User <span style='color: red'>".$costumerTr->fullname."</span> can't trashed";
                    fnc::redirectHome($errorMsg,'back');
                }
            }
            echo "</div>";
        }elseif($do == 'block'){
            //start trash page
            echo "<div class='container'>";
            $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("No Id", null, 0);
            $costumerTr = new costumer($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('sdبيس420',"back",0);
                }
                $cound  = $costumerTr->getMemberByID(); // if can't get a member by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($cound){
                    $costumerTr->regStatus = 0;
                    $costumerTr->lastPendBy = $_SESSION['userID'];
                    $costumerTr->lastPendDate = date("Y-m-d H:i");
                    if($costumerTr->getGroupID() === costGroupID){ /* if thid member has admin permissions privent*/
                        if($costumerTr->updateMemb($e, "userID", "regStatus", "regStatus", "lastPendBy","lastPendDate")){
                            $successMsg = "<h5>The user : <span style='color: red'>".$costumerTr->username."</span> has Blocked</h5>";
                            fnc::redirectHome($successMsg, 'back', 3, 'success');
                        }else{
                            fnc::redirectHome($e,'back');
                        }
                }else{
                    $errorMsg = "This User <span style='color: red'>".$costumerTr->fullname."</span> can't Blocked";
                    fnc::redirectHome($errorMsg,'back');
                }
            }
            echo "</div>";
        }elseif($do == 'info'){?>
         <script>var pageTitle = "<?php echo lang('MOR INF') ?>" ;</script>
            <?php
                $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : $_SESSION['userID'];
                $costumer = new costumer($e,$ID);
                $count = $costumer->getMemberByID();
                if($count){
            ?>
            <h1 class="text-center"><?php echo $costumer->username. " " .lang('INFORMATION') ?></h1>
            <div class="container">
                <div class="row">
                <div class="col col-lg-6 col-md-12 col-sm-10">
                    <div class="table-responsive">
                        <table class="main-table more-info table table-bordered">
                            <thead>
                                <td><?php echo lang('PROPERTY') ?></td>
                                <td><?php echo lang('VALUE') ?></td>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo lang('ID') ?></td>
                                <td><b><?php echo $costumer->userID ?></b></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("USERNAME")?></td>
                                <td><b><?php echo ucwords($costumer->username) ?></b></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("EMAIL")?></td>
                                <td><a class='text-decoration-none' target='blank' title='Send Mail' href='mailto:<?php echo $costumer->email ?>'><?php echo $costumer->email ?></a></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("PHO NUM")?></td>
                                <td><a class='text-decoration-none' target='blank' title='Open whatsapp Chat' href='https://wa.me/<?php echo substr_replace($costumer->phone, "",0,2) ?>'><?php echo fnc::prettyPhone($costumer->phone) ?></a></td>
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
                                <td><?php echo lang("PERMISSION")?></td>
                                <td><?php echo lang(permissionsCONST[$costumer->getGroupID()]) ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("REG DAT")?></td>
                                <td><?php echo date("Y-m-d / h:i a",strtotime($costumer->regDate)) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="col col-lg-6 col-md-12 col-sm-10">
                    <div class="table-responsive">
                        <table class="main-table more-info table table-bordered">
                            <thead>
                                <td><?php echo lang("PROPERTY")?></td>
                                <td><?php echo lang("VALUE")?></td>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo lang("LAS ACT BY")?></td>
                                <td><?php echo (($text = $costumer->getMemPropByProp('username','userID','lastActiveBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$costumer->lastActiveBy'><b> $text </b></a>" : "Not Acivated Before" ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS ACT DAT")?></td>
                                <td><?php echo ($costumer->lastActiveDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($costumer->lastActiveDate)) : "0000"; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS EDI BY")?></td>
                                <td><?php echo (($text = $costumer->getMemPropByProp('username','userID','lastEditBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$costumer->lastEditBy'><b> $text </b></a>" : "Not Edited Before" ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS EDI DAT")?></td>
                                <td><?php echo ($costumer->lastEditDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($costumer->lastEditDate)) : "0000" ; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS PEN BY")?></td>
                                <td><?php echo (($text = $costumer->getMemPropByProp('username','userID','lastPendBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$costumer->lastPendBy'><b> $text </b></a>" : "Not Blocked Before" ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS PEN DAT")?></td>
                                <td><?php echo ($costumer->lastPendDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($costumer->lastPendDate)) : "0000"; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS TRA BY")?></td>
                                <td><?php echo (($text = $costumer->getMemPropByProp('username','userID','lastTrushBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$costumer->lastTrushBy'><b> $text </b></a>" : "Not Trashed Before" ;?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang("LAS TRA DAT")?></td>
                                <td><?php echo ($costumer->lastTrushDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($costumer->lastTrushDate)) : "0000"; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <div  >
                    <a class="btn btn-secondary" href = "?do=manage"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK') ?></a>
                    <a class='btn btn-secondary' href='?do=edit&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-edit'></i> <?php echo lang("EDIT")?></a>
                    <?php if ($costumer->regStatus !== 0){ ?>
                    <a class='btn btn-secondary confirm' href='?do=block&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-ban'></i> <?php echo lang("BLOCK")?></a>
                    <?php }?>
                    <?php if ($costumer->regStatus > -1){ ?>
                    <a class='btn btn-secondary confirm' href='?do=trash&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-trash'></i> <?php echo lang("TRASH")?></a>
                    <?php } ?>
                    <a class='btn btn-danger confirm' href='?do=delete&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-close'></i> <?php echo lang("DELETE")?></a>
                    <?php if ($costumer->regStatus < 1){ ?>
                        <a class='btn btn-info' href='?do=activate&ID=<?php echo $costumer->userID ?>'><i class = 'fa fa-angles-up'></i> <?php echo lang("ACTIVATE")?></a>
                    <?php } ?>
                </div>
                </div>
            </div><?php
            }else{
                $errorMsg = "Invalid ID";
                fnc::redirectHome($errorMsg, 'back', 0);
            }
        }else{
            fnc::redirectHome("","back",0);
        }
    include $dir. $tempsP. "footer.php";
ob_end_flush();