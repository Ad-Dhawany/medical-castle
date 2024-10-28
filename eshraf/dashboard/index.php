<?php
    session_start();
    $pageTitle = 'Dashboard';
    $dir = "../";
    $isLoggedIn = false;
    if(isset($_SESSION['username'],$_SESSION['groupID'])){
        if($_SESSION['groupID'] > 0){
        include $dir. "init.php";
        ?>
        <script>var pageTitle = '<?php echo lang('DASHBOARD') ?>' ;</script>
        <section class="container-lg">
            <div class='home-stat mt-2 mb-2 text-center'>
                <h1 class="text-center my-4 mb-md-5"><?php echo lang('DASHBOARD') ?></h1>
                <div class="row row-cols-2 row-cols-md-4">
                    <div class="col">
                        <a href="../costumers/" class="stat mt-4 mt-md-2">
                        <!-- <div class="stat mt-4 mt-md-2"> -->
                            <i class="fas fa-users icon"></i>
                            <h6 class="stat-title"><?php echo lang("TOT COS") ?></h6>
                            <span class="stat-value"><?php echo fnc::countItems('userID', 'users', 'groupID = 0') ?></span>
                        <!-- </div> -->
                        </a>
                    </div>
                    <div class="col">
                        <a href="../items/" class="stat mt-4 mt-md-2">
                        <!-- <div class="stat mt-4 mt-md-2"> -->
                            <i class="fas fa-layer-group icon"></i>
                            <h6 class="stat-title"><?php echo lang("TOT ITE") ?></h6>
                            <span class="stat-value"><?php echo fnc::countItems('itemID', 'items') ?></span>
                        <!-- </div> -->
                        </a>
                    </div>
                    <div class="col">
                        <a href="../orders/" class="stat mt-4 mt-md-2">
                        <!-- <div class="stat mt-4 mt-md-2"> -->
                            <i class="fas fa-file-invoice icon"></i>
                            <h6 class="stat-title"><?php echo lang("TOT ORD") ?></h6>
                            <span class="stat-value"><?php echo fnc::countItems('orderID', 'orders_meta') ?></span>
                        <!-- </div> -->
                        </a>
                    </div>
                    <div class="col">
                        <a href="../orders/" class="stat mt-4 mt-md-2" title="<?php echo langTXT("CUR MON TOT SAL") ?>.">
                        <!-- <div class="stat mt-4 mt-md-2"> -->
                            <i class="fas fa-money-bills icon"></i>
                            <h6 class="stat-title"><?php echo lang("MON SAL") ?></h6>
                            <span class="stat-value"><?php echo number_format(fnc::sumTotal('totalNet', 'get_order_meta', 'orderVersion = 1 AND procStatus = 6 AND MONTH(CURRENT_DATE()) = MONTH(`doneDate`)'), 2). "<span class='fs-4'>". lang("L.D"). "</span>" ?></span>
                        <!-- </div> -->
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="latest m-4">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-6 mt-2 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-users"></i> <?php echo "<a href='$dir". "costumers/' class='link link-dark'>". lang("REC REG COS")."</a>" ?>
                            </div>
                            <div class="card-body p-0">
                                <ul class = "list-unstyled latests latest-users">
                                <?php $where = "groupID = ". costGroupID;
                                $latestCostumers = stat_getObjects::getMembers($where, 'costumer', '5');
                                if(is_array($latestCostumers)){
                                    foreach($latestCostumers as $costumer){
                                        echo    "<li class='latests-item d-flex m-0'>
                                                    <span class='col-4' title='". lang("USERNAME"). "'>". $costumer->username."</span>
                                                    <span class='col-4' title='". lang("PHA NAM"). "'>". $costumer->pharmacy. "</span>
                                                    <div class='hidden-controllers'>
                                                        <a class='btn btn-success' target='blank' href='$dir". "costumers/?do=edit&ID=".$costumer->userID."'><i class = 'fa fa-edit'></i> ".lang('EDIT')."</a>";
                                                        if($costumer->regStatus == 0){
                                                        echo "
                                                        <a class='btn btn-info' target='blank' href='$dir". "costumers/?do=activate&ID=".$costumer->userID."'><i class = 'fa fa-angles-up'></i> ".lang('ACTIVATE')."</a>";
                                                        }
                                        echo    "   </div>
                                                </li>";
                                    }
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-file-invoice"></i> <?php echo "<a href='$dir". "orders/' class='link link-dark'>". lang("REC SUB ORD"). "</a>" ?>
                            </div>
                            <div class="card-body p-0">
                                <ul class = "list-unstyled latests latest-orders">
                                <?php $latestOrders = stat_getObjects::getOrders('ALL', '1', 'receiveDate', "DESC", '6');
                                if(is_array($latestOrders)){
                                    foreach($latestOrders as $order){
                                        if($order->procStatus == 3 || strtotime($order->lastProposalDate) > strtotime($order->lastResponseDate)){
                                            $liTitle = langTXT(RECEIVE_ORDER_TITLE[$order->procStatus]);
                                            $liClass = 'bg-blink';
                                        }else{
                                            $liTitle = "";
                                            $liClass = "";
                                        }
                                        echo    "<li class='latests-item d-flex m-0 $liClass' title='$liTitle'>
                                                    <span class='col-2' title='". lang("ORD NUM"). "'>". $order->orderID."</span>
                                                    <span class='col-5' title='". lang("ORD STA"). "'>". lang(PROCSTATUS[$order->procStatus])."</span>
                                                    <span class='col-5' title='". lang("PHARMACY"). "'>". $order->pharmacy."</span>
                                                    <div class='hidden-controllers'>
                                                        <a class='btn btn-success' target='blank' href='$dir". "orders/?do=info&ID=". $order->orderID. "'><i class = 'fas fa-file-lines'></i> ".lang('MOR INF')."</a>";
                                        echo    "   </div>
                                                </li>";
                                    }
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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