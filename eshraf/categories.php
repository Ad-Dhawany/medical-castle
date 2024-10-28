<?php
    session_start();
    $pageTitle = 'Categories';
    if(!isset($_SESSION['username']) || !isset($_SESSION['ID'])){
        header('location: index.php');
        exit();
    }
        include "init.php";
        ?>
        <script>var pageTitle = "<?php echo lang('CATEGORIES') ?>" ;</script>
        <?php
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        if($do == 'manage'){
            //Start Manage page 
            $sort = 'ASC';
            $sort_array = array('ASC', 'DESC');
            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort = $_GET['sort'];
            }
            $stmt = $conn->prepare("SELECT * FROM categories ORDER BY ordering $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll();
            ?>
            <div class="container">
            <h2 class="text-center mb-3"><?php echo  lang('MANAGE') .' '. lang('CATEGORIES'); ?></h2>
            <div class="container cats">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-object-ungroup"></i> <?php echo lang('MANAGE') .' '. lang('CATEGORIES'); ?>
                        <div class="sort-options fa-pull-right">
                            <?php echo langing('ORDER') ?>:
                            <a class = '<?php if($sort == 'ASC') echo 'active'; ?>' href="?sort=ASC"><?php echo lang('ASC')?></a> | 
                            <a class = '<?php if($sort == 'DESC') echo 'active'; ?>' href="?sort=DESC"><?php echo lang('DESC')?></a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach($cats as $cat){
                                echo    "<div class = 'cat p-2'>
                                            <div class = 'hidden-btns'>
                                                <a class='btn btn-success' href='?do=edit&ID=".$cat['ID']."'><i class = 'fa fa-edit'></i> ".lang('EDIT')."</a>
                                                <a class='btn btn-danger confirm' href='?do=delete&ID=".$cat['ID']."'><i class = 'fa fa-close'></i> ".lang('DELETE')."</a>
                                            </div>
                                            <div>
                                            <h3>" . $cat['name'] . "</h3>
                                            <p>"; if($cat['description'] == ''){ echo "This category has no description"; }else{ echo $cat['description']; } echo "</p>";
                                            if($cat['visibility'] == 0){echo "<span class = 'prop vis'>Hiddin</span>";}
                                            if($cat['allow_comments'] == 0){echo "<span class = 'prop comm'>Comments-disable</span>";}
                                            if($cat['allow_ads'] == 0){echo "<span class = 'prop ads'>Ads-disable</span>";}
                                echo    "</div>
                                        </div>
                                         <hr> ";
                        }
                        ?>
                    </div>
                </div>
                <a class="btn btn-primary mt-2 mb-2" href = "?do=add"><i class="fa fa-plus"></i> <?php echo lang('NEW').' '. lang('CATEGORY') ?></a>
            </div>
            </div>
            <?php
        }elseif($do == 'add'){
            ?>
            <script>var pageTitle = "<?php echo  lang('ADD') .' '. lang('CATEGORY'); ?>" ;</script>
                <h1 class="text-center m-3"><?php echo lang('ADD') .' '. lang('NEW') .' '. lang('CATEGORY') ?></h1>
                <div class="container offset-lg-2">
                    <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                    <form action="?do=insert" method="POST" class="form row g-1">
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('CATEGORY') .' '. lang('NAME'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('DESCRIPTION'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo langing('ORDER'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="number" name="order" class="form-control">
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('IS').' <strong>'. langTH('CATEGORY').'</strong> '. lang('VISIBLE').''.lang('?') ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="1" checked />
                                    <label for="vis-yes"><?php echo lang('YES'); ?></label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="0" />
                                    <label for="vis-no"><?php echo lang('NO'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('IS').' <strong>'. langTHs('COMMENT').'</strong> '. langing('ALLOW').''.lang('?') ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="allow-comments" value="1" checked />
                                    <label for="com-yes"><?php echo lang('YES'); ?></label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="allow-comments" value="0" />
                                    <label for="com-no"><?php echo lang('NO'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('IS').' <strong>'. langTH('ADS').'</strong> '. langing('ALLOW').''.lang('?') ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="allow-ads" value="1" checked />
                                    <label for="ads-yes"><?php echo lang('YES'); ?></label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="allow-ads" value="0" />
                                    <label for="ads-no"><?php echo lang('NO'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                        <label class="col-2"></label>
                            <div class="col-sm-10">
                                <input style = 'font-family: Times New Roman' type="submit" class="btn btn-primary btn-lg" value= "+ <?php echo lang('ADD') .' '. lang('CATEGORY'); ?>">
                            </div>
                        </div>
                </form>
                </div> <?php
        }elseif($do == 'insert'){
            echo "<div class='container'>";
            //Start Insert methodology
            if(isset($_POST['name'])){
                $name = $_POST['name'];
                $description = $_POST['description'];
                $order = $_POST['order'];
                $visible = $_POST['visibility'];
                $comment = $_POST['allow-comments'];
                $ads = $_POST['allow-ads'];
                //confirm that there is no category with same name
                if(checkitem('name', 'categories', $name) > 0){
                    $errorMsg = "There is another category with the same name";
                    redirectHome($errorMsg, 'back');
                    exit();
                }
                //insert new category
                    $stmt = $conn->prepare("INSERT INTO categories(name, description, ordering, visibility, allow_comments, allow_ads) VALUES(:vname, :vdesc, :vorder, :vvis, :vcomm, :vads)");
                    $stmt->execute(array('vname' => $name, 'vdesc' => $description, 'vorder' => $order, 'vvis' => $visible, 'vcomm'=> $comment, 'vads'=> $ads));
                    $count  = $stmt->rowCount(); // get numper of rows in $stmt
                    if ($count == 1){
                        $sucMsg = " The addition has completed";
                        redirectHome($sucMsg, 'back', 4, 'succsess');
                        exit();
                }else{
                    $errorMsg = "The addition has completed";
                    redirectHome($errorMsg, 'back');
                    exit();
                }
                /* else{
                    foreach($formErrors as $error){
                        echo "<div class='alert alert-warning col-lg-8 offset-lg-2 mb-0 mt-3' style='color: #842029'> $error. </div>";
                    }
                    echo "<div class='col-lg-2 col-md-4 col-sm-6 offset-lg-5 offset-md-4 offset-sm-3' ><a class='btn btn-primary btn-lg col-10 mt-2' href='members.php?do=edit'>". lang('EDIT_PROFILE') ."</a></div>";
                    echo "<div class='col-lg-2 col-md-4 col-sm-6 offset-lg-5 offset-md-4 offset-sm-3' ><a class='btn btn-primary btn-lg col-10 mt-2' href='members.php'>". langs('MEMBER') ."</a></div>";
                } */
            }else{
                $errorMsg ='please fill the required fields with valid values';
                redirectHome($errorMsg);
            }
            echo "</div>";
        }elseif($do == 'edit'){
            //Start Edit page
            ?>
            <script>var pageTitle = "<?php echo  lang('EDIT') .' '. lang('CATEGORY') ?>" ;</script>
           <?php
           //If the ID doesn't set will be set as current admin ID if it set by get method it will validating if it numeric or not. The get method for use the page to edit the user information from member page
           if (isset($_GET['ID']) && is_numeric($_GET['ID'])){
           $ID = intval($_GET['ID']);
           $stmt = $conn->prepare("SELECT * FROM categories WHERE ID=?");//$stmt: متغير جملة الإستعلام prepare: تحضير جملة الإستعلام قبل ارسالها لتجنب الأخطاء
           $stmt->execute(array($ID));
           $cat = $stmt->fetch();
           $count  = $stmt->rowCount(); // get numper of rows in $stmt
           if ($count == 1){?>
               <h1 class="text-center m-3"><?php echo lang('EDIT') .' '. lang('CATEGORY') ?></h1>
                <div class="container offset-lg-2">
                    <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                    <form action="?do=update" method="POST" class="form row g-1">
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('CATEGORY') .' '. lang('NAME'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" required="required" value="<?php echo $cat['name'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('DESCRIPTION'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" value="<?php echo $cat['description'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo langing('ORDER'); ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <input type="number" name="order" class="form-control" value="<?php echo $cat['ordering'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('IS').' <strong>'. langTH('CATEGORY').'</strong> '. lang('VISIBLE').''.lang('?') ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="1" <?php if($cat['visibility'] == 1) echo 'checked'?> />
                                    <label for="vis-yes"><?php echo lang('YES'); ?></label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="0" <?php if($cat['visibility'] == 0) echo 'checked'?> />
                                    <label for="vis-no"><?php echo lang('NO'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('IS').' <strong>'. langTHs('COMMENT').'</strong> '. langing('ALLOW').''.lang('?') ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="allow-comments" value="1" <?php if($cat['allow_comments'] == 1) echo 'checked'?> />
                                    <label for="com-yes"><?php echo lang('YES'); ?></label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="allow-comments" value="0" <?php if($cat['allow_comments'] == 0) echo 'checked'?> />
                                    <label for="com-no"><?php echo lang('NO'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                            <label class="col-sm-2 control-label"><?php echo lang('IS').' <strong>'. langTH('ADS').'</strong> '. langing('ALLOW').''.lang('?') ?></label>
                            <div class="input-field col-sm-10 col-md-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="allow-ads" value="1" <?php if($cat['allow_ads'] == 1) echo 'checked'?> />
                                    <label for="ads-yes"><?php echo lang('YES'); ?></label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="allow-ads" value="0" <?php if($cat['allow_ads'] == 0) echo 'checked'?> />
                                    <label for="ads-no"><?php echo lang('NO'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg row mb-2">
                        <label class="col-2"></label>
                            <div class="col-sm-10">
                                <a class="btn btn-secondary btn-lg" href="categories.php"><?php echo lang('BACK'); ?></a>
                                <input type="submit" class="btn btn-primary btn-lg" value= "<?php echo lang('SAVE'); ?>">
                            </div>
                        </div>
                </form>
                </div>
           <?php
           }else{
                $errorMsg = 'An Error in Category information <br>please retry or logout and relogin';
                redirectHome($errorMsg,'back',2);
           }
        }else{
            $errorMsg = 'You can not access this page directly';
            redirectHome($errorMsg,'back',2);
        }
        }elseif($do == 'update'){
            //Start Update methodology
        }elseif($do == 'delete'){
            //start delete page
        }elseif($do == 'activate'){
            //start activate page

        }
    include $tempsP. "footer.php";
?>