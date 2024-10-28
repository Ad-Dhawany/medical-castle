<?php
ob_start();
    session_start();
    $pageTitle = 'Users';
    $dir = "../";
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
        <script>var pageTitle = "<?php echo langs('USER') ?>" ;</script>
        <?php /* this page actually is a collects of pages and $do is the param to select the required page */
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; /* do by default is manage page */
        if($do == 'manage'){
            //Start Manage page
            ?>
            <script>var pageTitle = "<?php echo lang('MEM MAN') ?>" ;</script>
            <?php
            $pending = 'AND regStatus >= 0'; // $pending to set nonActivated users only or All users
            $title = 'MEM MAN';
            $btnDanger = "TRASH"; /*to use it in lang()*/
            $faClass = "trash";
            $backHref = "../";
            /*if(isset($_GET['activ']) && $_GET['activ'] == 'pend'){
                $pending = 'AND regStatus = 0';
                $title = "PEN MEM MAN";
            }*//** تم إلغاء هذه الميزة في صفحة المستخدمين لأنه لن يكون هناك تسجيل عضوية إلاّ عن طريق المشرف */
            if(isset($_GET['activ']) && $_GET['activ'] == 'trash'){
                $pending = 'AND regStatus = -1';
                $title = "TRA MEM LIS";
                $btnDanger = "DELETE";
                $faClass = "close";
                $backHref = "?do=manage";
            }
            $finalBackHref = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $backHref;
            $hrefDanger = strtolower($btnDanger); /* to use it in href */
            //Select all users (company team)
            $where = "groupID != 0 $pending";
            $membs = stat_memb::getMembers($where);
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
                            <td><?php echo lang("PERMISSION")?></td>
                            <td><?php echo lang('CONTROL')?></td>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($membs) && !empty($membs)){
                            foreach($membs as $memb){
                                echo "<tr>
                                        <td><a class='text-decoration-none link-dark' target='blank' title='More Info.' href='?do=info&ID=". $memb->userID ."'><b>".$memb->userID."</b></a></td>
                                        <td><a class='text-decoration-none link-dark' target='blank' title='More Info.' href='?do=info&ID=". $memb->userID ."'><b>".ucwords($memb->username)."</b></a></td>
                                        <td><a class='text-decoration-none' target='blank' title='Send Mail' href='mailto:". $memb->email ."'>".$memb->email."</a></td>
                                        <td><a class='text-decoration-none' target='blank' title='Open whatsapp Chat' href='https://wa.me/". substr_replace($memb->phone, "",0,2) ."'>".fnc::prettyPhone($memb->phone)."</a></td>
                                        <td>".ucwords($memb->fullname)."</td>
                                        <td>".lang(permissionsCONST[$memb->getGroupID()])."</td>
                                        <td>
                                            <a class='btn btn-success' href='?do=edit&ID=".$memb->userID."'><i class = 'fa fa-edit'></i> ".lang('EDIT')."</a>
                                            <a class='btn btn-danger confirm' href='?do=$hrefDanger&ID=".$memb->userID."'><i class = 'fa fa-$faClass'></i> ".lang($btnDanger)."</a>";
                                            if($memb->regStatus < 1){
                                             echo "
                                             <a class='btn btn-info' href='?do=activate&ID=".$memb->userID."'><i class = 'fa fa-angles-up'></i> ".lang('ACTIVATE')."</a>";
                                            }
                                    echo "</td>
                                     </tr>
                                ";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <a class="btn btn-secondary" href="<?php echo $finalBackHref ?>"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK'); ?></a>
                <a class="btn btn-secondary" href = "?activ=trash"><i class="fa fa-trash"></i> <?php echo lang('TRA BAS') ?></a>
                <a class="btn btn-primary" href = "?do=add"><i class="fa fa-plus"></i> <?php echo lang('NEW MEM') ?></a>
            </div>
            <?php
        }elseif($do == 'edit'){
            //Start Edit page
            ?>
             <script>var pageTitle = "<?php echo  lang('EDI USE') ?>" ;</script>
            <?php
            //If the ID doesn't set will be set as current admin ID if it set by get method it will validating if it numeric or not. The get method for use the page to edit the user information from member page
            $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : $_SESSION['userID'];
            $memb = new member($e,$ID);
            $count = $memb->getMemberByID(); /* fitch user info by useing ID, return true if the proccess success, return false if proccess fail */
            if ($count){?>
                <h1 class="text-center m-3"><?php echo lang('EDIT'); ?> <span style="color:darkslategray"><?php echo $memb->fullname; ?></span> <?php echo lang('INFORMATION'); ?></h1>
                <div class="container">
                <div class="row offset-lg-2">
                    <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                    <form action="./?do=update" method="POST" class="form row g-1">
                        <div style="display: none;"><input type="text" name="username" class="form-control" value="<?php echo ucwords($memb->username)?>"></div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("FULLNAME") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="fullname" class="form-control" value="<?php echo ucwords($memb->fullname) ?>" pattern="^[ء-يa-zA-Z]+([ء-يa-zA-Z' ])+[ء-يa-z]$"  required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("PASSWORD") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="password" id="pass-field" name="password"  data-pass="origin" class="form-control"  autocomplete="new-password" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("NEW PAS") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="password" id="pass-field" name="new-password"  data-pass="repeat" class="form-control" autocomplete="new-password" placeholder="<?php echo langTXT("LEA IT EMP") ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("PHO NUM") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="phone" class="form-control" value="<?php echo $memb->phone; ?>" pattern="^[+0]{1}[- 0-9]{9,15}$" placeholder="e.g: 0921234567 , +218911234567 or 00218-91-123-4567" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang("EMAIL") ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="Email" name="email" class="form-control" value="<?php echo $memb->email; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo langs('PERMISSION'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                    <div class = "form-check-inline">
                                        <input id="perm-admin" type="radio" name="permission" value="3"<?php if($memb->getGroupID() == 3){echo "checked";} ?> />
                                        <label for="perm-admin"><?php echo lang(permissionsCONST[3]) ?></label>
                                    </div>
                                    <div class = "form-check-inline">
                                        <input id="perm-responsible" type="radio" name="permission" value="2" <?php if($memb->getGroupID() == 2){echo "checked";} ?> />
                                        <label for="perm-responsible"><?php echo lang(permissionsCONST[2]) ?></label>
                                    </div>
                                    <div class = "form-check-inline">
                                        <input id="perm-employee" type="radio" name="permission" value="1" <?php if($memb->getGroupID() == 1){echo "checked";} ?> />
                                        <label for="perm-employee"><?php echo lang(permissionsCONST[1]) ?></label>
                                    </div>
                                    <div class = "form-check-inline">
                                        <input id="perm-costumer" type="radio" name="permission" value="0" <?php if($memb->getGroupID() == 0){echo "checked";} ?> />
                                        <label for="perm-costumer"><?php echo lang(permissionsCONST[0]); ?></label>
                                    </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2 d-block col-lg-8 col-md-8 col-sm-12">
                        <!-- <label class="col-2"></label> -->
                            <div class="w-max-cont fa-pull-right">
                                <a class="btn btn-secondary btn-lg" href="./"><?php echo lang('BACK'); ?></a>
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
            if(isset($_POST['username'],$_POST['fullname'], $_POST['password'], $_POST['email'], $_POST['phone'],$_POST['permission'])){
                //Validation inputs
                $formErrors = array();
                if(!fnc::isArabicStr($_POST['username'], false)){
                    if (!preg_match("/^[a-zA-Z]+([a-zA-Z'.0-9]){3,24}$/",$_POST['username'])) {
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
                if(empty($_POST['password'])){
                    $formErrors[] = '<strong>password</strong> is required';
                }
                if(empty($_POST['email'])){
                    $formErrors[] = '<strong>E-mail</strong> should is required';
                }

                if(empty($formErrors)){
                $membUb = new member($e, null, strtolower($_POST['username']), $_POST['email'],$_POST['phone'], strtolower($_POST['fullname']),$_POST['permission']);
                if(!$e){
                    fnc::redirectHome("خطأ: 7209", 'back', 0);
                }
                if(!($membUb->getGroupID() >= 0 && $membUb->getGroupID() < 4)){
                    fnc::redirectHome("خطأ: 6212", 'back', 0);
                }
                $membUb->preparePhone();
                $newPassword = (empty($_POST['new-password'])) ? $_POST['password'] : $_POST['new-password'];
                $membUb->setMembPassword($_POST['password']);
                //confirm the user identity
               
                $count  = $membUb->membLoign(null,null,$e,"username","verify"); // get numper of rows in $stmt
                if ($count){ // confirming success
                    $membUb->setMembPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                    $membUb->lastEditBy = $_SESSION['userID'];
                    $membUb->lastEditDate = date("Y-m-d H:i");
                    /* $stmt2 = $conn->prepare("UPDATE users SET fullname = ?, password = ?, email = ?, phone = ?, groupID = ? WHERE username = ?");
                    $stmt2->execute(array($membUb->fullname, $membUb->getMembPassword(), $membUb->email, $membUb->phone, $membUb->getGroupID(),$membUb->username)); */
                    $count2 = $membUb->updateMemb($e,"username","fullname", 'password', 'email', 'phone', "groupID");
                    if($count2){
                        if($_SESSION['username'] == $membUb->username){  /* إذا تم الإستعلام بنجاح وكان التعديل من المشرف نفسه على نفسه لابد من تغيير الإسم الكامل من جديد */
                            $_SESSION['fullname'] = $membUb->fullname;
                        }
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
            <script>var pageTitle = "<?php echo  lang('ADD USE'); ?>" ;</script>
                <h1 class="text-center m-3"><?php echo lang('AD NE ME') ?></h1>
                <div class="container">
                    <div class="row offset-lg-2">
                        <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                        <form action="?do=insert" method="POST" data-session="add-user" class="form row g-1">
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
                                <label class="col-sm-2 control-label"><?php echo langs('PERMISSION'); ?></label>
                                <div class="input-field col-sm-10 col-md-6">
                                        <div class = "form-check-inline">
                                            <input id="perm-admin" type="radio" name="permission" value="3" />
                                            <label for="perm-admin"><?php echo lang(permissionsCONST[3]); ?></label>
                                        </div>
                                        <div class = "form-check-inline">
                                            <input id="perm-responsible" type="radio" name="permission" value="2" />
                                            <label for="perm-responsible"><?php echo lang(permissionsCONST[2]); ?></label>
                                        </div>
                                        <div class = "form-check-inline">
                                            <input id="perm-employee" type="radio" name="permission" value="1" checked />
                                            <label for="perm-employee"><?php echo lang(permissionsCONST[1]); ?></label>
                                        </div>
                                        <div class = "form-check-inline">
                                            <input id="perm-costumer" type="radio" name="permission" value="0" />
                                            <label for="perm-costumer"><?php echo lang(permissionsCONST[0]); ?></label>
                                        </div>
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 d-block col-lg-8 col-md-8 col-sm-12">
                            <!-- <label class="col-2"></label> -->
                                <div class="w-max-cont fa-pull-right">
                                    <input style = 'font-family: Times New Roman' type="submit" class="btn btn-primary btn-lg" value= "+ <?php echo lang('ADD USE'); ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        <?php }elseif($do == 'insert'){
            echo "<div class='container'>";
            //Start Insert methodology
            if(isset($_POST['username'],$_POST['fullname'], $_POST['password'], $_POST['phone'], $_POST['email'], $_POST['permission'])){

                if(is_numeric($_POST['permission'])){
                    $groupID = intval($_POST['permission']);
                    if(!($groupID >= 0 && $groupID < 4)){
                        fnc::redirectHome("خطأ: 6336", 'back', 0);
                    }
                }else{
                    fnc::redirectHome("خطأ: 5339", 'back', 0);
                }
                //Validation inputs
                $formErrors = array();
                if(!fnc::isArabicStr($_POST['username'], false)){
                    if (!preg_match("/^[a-zA-Z]+([a-zA-Z'.0-9]){3,24}$/",$_POST['username'])) {
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
                    $membNew = new member($e, null, strtolower($_POST['username']), $_POST['email'],$_POST['phone'], strtolower($_POST['fullname']),$_POST['permission'],null,date("Y-m-d H:i"),1,$_POST['password']);
                    if(!$e){
                        fnc::redirectHome("خطأ: 7376", 'back', 5);
                    }
                    if(!($membNew->getGroupID() >= 0 && $membNew->getGroupID() < 4)){
                        fnc::redirectHome("خطأ: 6379", 'back', 5);
                    }
                //confirm that there is no user with same username
                    if($membNew->isMemberExist($prob, "OR",'username', 'phone', 'email') != 0){
                        $errorMsg = "The <span style='color: red'>$prob</span>: ". $membNew->$prob ." is used before, Please try another $prob";
                        fnc::redirectHome($errorMsg,'back');
                    }
                    $membNew->setMembPassword(null, true);
                    $membNew->lastActiveBy = $_SESSION['userID'];
                    $membNew->lastActiveDate = date("Y-m-d H:i");
                //insert new user
                    $count  = $membNew->insertMemb($e,"username","fullname", 'password', 'email', 'regDate', 'phone', "groupID", 'regStatus', "lastActiveBy", "lastActiveDate"); // get numper of rows in $stmt
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
                $membDel = new member($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('sdبيس420',"back",0);
                }
                $count  = $membDel->getMemberByID(); // if can't get a member by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($count){
                    if($membDel->getGroupID() != 3){ /* if thid member has admin permissions privent*/
                        if($membDel->deleteMemb($e, "userID")){
                            $successMsg = "<h5>The user : <span style='color: red'>".$membDel->username."</span> has deleted</h5>";
                            fnc::redirectHome($successMsg, 'back', 3, 'success');
                        }else{
                            fnc::redirectHome($e,'back');
                        }
                }else{
                    $errorMsg = "This User <span style='color: red'>".$membDel->fullname."</span> can't deleted";
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
                $membAct = new member($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('sdبيس420',"back",0);
                }
                $cound  = $membAct->getMemberByID(); // if can't get a member by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($cound){
                    $membAct->regStatus = 1;
                    $membAct->lastActiveBy = $_SESSION['userID'];
                    $membAct->lastActiveDate = date("Y-m-d H:i");
                    if($membAct->updateMemb($e, "userID", "regStatus", "lastActiveBy", "lastActiveDate")){
                        $successMsg = "<h5>The user : <span style='color: red'>".$membAct->username."</span> has Activated</h5>";
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
            $membTr = new member($e, $_GET['ID']);
                if(!$e){
                    fnc::redirectHome('خطأ: 6467',"back",0);
                }
                $cound  = $membTr->getMemberByID(); // if can't get a member by th receiving ID will return false; by this way we can aprov if member exist or not
                if ($cound){
                    $membTr->regStatus = -1;
                    $membTr->lastTrushBy = $_SESSION['userID'];
                    $membTr->lastTrushDate = date("Y-m-d H:i");
                    if($membTr->getGroupID() != 3){ /* if thid member has admin permissions privent*/
                        if($membTr->updateMemb($e, "userID", "regStatus", "regStatus", "lastTrushBy","lastTrushDate")){
                            $successMsg = "<h5>The user : <span style='color: red'>".$membTr->username."</span> has trashed</h5>";
                            fnc::redirectHome($successMsg, 'back', 3, 'success');
                        }else{
                            fnc::redirectHome($e,'back');
                        }
                }else{
                    $errorMsg = "This User <span style='color: red'>".$membTr->fullname."</span> can't trashed";
                    fnc::redirectHome($errorMsg,'back');
                }
            }else{
                fnc::redirectHome('خطأ: 6486',"back",0);
            }
            echo "</div>";
        }elseif($do == 'info'){?>
         <script>var pageTitle = "<?php echo lang('MOR INF') ?>" ;</script>
            <?php
                $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : $_SESSION['userID'];
                $memb = new member($e,$ID);
                $count = $memb->getMemberByID();
                if($count){
            ?>
            <h1 class="text-center"><?php echo $memb->username. " " .lang('INFORMATION') ?></h1>
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
                                        <td><b><?php echo $memb->userID ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("USERNAME")?></td>
                                        <td><b><?php echo ucwords($memb->username) ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("EMAIL")?></td>
                                        <td><a class='text-decoration-none' target='blank' title='Send Mail' href='mailto:<?php echo $memb->email ?>'><?php echo $memb->email ?></a></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("PHO NUM")?></td>
                                        <td><a class='text-decoration-none' target='blank' title='Open whatsapp Chat' href='https://wa.me/<?php echo substr_replace($memb->phone, "",0,2) ?>'><?php echo fnc::prettyPhone($memb->phone) ?></a></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("PASSWORD")?></td>
                                        <td><span style="position:relative; top: 2px; cursor:default;">*********</span></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("FULLNAME")?></td>
                                        <td><?php echo ucwords($memb->fullname) ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("PERMISSION")?></td>
                                        <td><?php echo lang(permissionsCONST[$memb->getGroupID()]) ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("REG DAT")?></td>
                                        <td><?php echo date("Y-m-d / h:i a",strtotime($memb->regDate)) ?></td>
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
                                    <td><?php echo (($text = $memb->getMemPropByProp('username','userID','lastActiveBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$memb->lastActiveBy'><b> $text </b></a>" : "Not Acivated Before" ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS ACT DAT")?></td>
                                    <td><?php echo ($memb->lastActiveDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($memb->lastActiveDate)) : "0000"; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS EDI BY")?></td>
                                    <td><?php echo (($text = $memb->getMemPropByProp('username','userID','lastEditBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$memb->lastEditBy'><b> $text </b></a>" : "Not Edited Before" ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS EDI DAT")?></td>
                                    <td><?php echo ($memb->lastEditDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($memb->lastEditDate)) : "0000" ; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS PEN BY")?></td>
                                    <td><?php echo (($text = $memb->getMemPropByProp('username','userID','lastPendBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$memb->lastPendBy'><b> $text </b></a>" : "Not Blocked Before" ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS PEN DAT")?></td>
                                    <td><?php echo ($memb->lastPendDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($memb->lastPendDate)) : "0000"; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS TRA BY")?></td>
                                    <td><?php echo (($text = $memb->getMemPropByProp('username','userID','lastTrushBy')) != false) ? "<a class='link-dark text-decoration-none' href='?do=info&ID=$memb->lastTrushBy'><b> $text </b></a>" : "Not Trashed Before" ;?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS TRA DAT")?></td>
                                    <td><?php echo ($memb->lastTrushDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($memb->lastTrushDate)) : "0000"; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div  >
                    <a class="btn btn-secondary" href = "?do=manage"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK') ?></a>
                    <a class='btn btn-secondary' href='?do=edit&ID=<?php echo $memb->userID ?>'><i class = 'fa fa-edit'></i> <?php echo lang("EDIT")?></a>
                    <?php if ($memb->regStatus > -1){ ?>
                    <a class='btn btn-secondary confirm' href='?do=trash&ID=<?php echo $memb->userID ?>'><i class = 'fa fa-trash'></i> <?php echo lang("TRASH")?></a>
                    <?php } ?>
                    <a class='btn btn-danger confirm' href='?do=delete&ID=<?php echo $memb->userID ?>'><i class = 'fa fa-close'></i> <?php echo lang("DELETE")?></a>
                    <?php if ($memb->regStatus < 1){ ?>
                        <a class='btn btn-info' href='?do=activate&ID=<?php echo $memb->userID ?>'><i class = 'fa fa-angles-up'></i> <?php echo lang("ACTIVATE")?></a>
                    <?php } ?>
                </div>
            </div>
            <?php
            }else{
                $errorMsg = "Invalid ID";
                fnc::redirectHome($errorMsg, 'back', 0);
            }
        }else{
            fnc::redirectHome("","back",0);
        }
    include $dir. $tempsP. "footer.php";
ob_end_flush();
?>