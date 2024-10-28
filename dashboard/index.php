<?php
    session_start();
    $pageTitle = 'Dashboard';
    $dir = "../";
    if(isset($_SESSION['username'],$_SESSION['groupID'])){
        if($_SESSION['groupID'] > 0){
        include "../init.php";
        ?>
        <script>var pageTitle = '<?php echo lang('DASHBOARD') ?>' ;</script>
        <div class="container">
            <div class='home-stat mt-2 mb-2 text-center'>
                <h1 class="text-center m-3"><?php echo lang('DASHBOARD') ?></h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat st-members">
                            <?php echo lang("TOT USE") ?>
                            <span><a href="../users/"><?php echo fnc::countItems('userID', 'users') ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-pending">
                        <?php echo lang("PEN USE") ?>
                        <span><a href="../users/?activ=pend"><?php echo fnc::checkitem('regStatus', 'users', 0) ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-items">
                        <?php echo lang("TOT ITE") ?>
                        <span>2421</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-comments">
                        <?php echo lang("TOT COS") ?>
                        <span>112</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="latest m-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-users"></i> <?php echo lang("LA RE CO") ?>
                            </div>
                            <div class="card-body">
                                <ul class = "list-unstyled latest-users">
                                <?php $latestUsers = fnc::getLatest('*', 'users', 'userID');
                                foreach($latestUsers as $user){
                                    echo    "<li>". $user['username']."
                                                <a class='btn btn-success fa-pull-right' href='$dir". "users/?do=edit&ID=".$user['userID']."'><i class = 'fa fa-edit'></i> ".lang('EDIT')."</a>";
                                                if($user['regStatus'] == 0){
                                                    echo "
                                                    <a class='btn btn-info fa-pull-right' href='?do=activate&ID=".$user['userID']."'><i class = 'fa fa-angles-up'></i> ".lang('ACTIVATE')."</a>";
                                                   }
                                    echo    "</li>";
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-tag"></i> <?php echo lang("LAT ITE") ?>
                            </div>
                            <div class="card-body">
                                <ul class = "list-unstyled latest-items">
                                    Test
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }else {
        header('location: ../');
        exit();
    }
    }else {
        header('location: ../');
        exit();
    }
    include "../" .$tempsP. "footer.php";
?>