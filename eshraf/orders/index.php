<?php
ob_start();
    session_start();
    const allowedExt = ['csv', 'CSV', 'TXT','txt'];
    $pageTitle = 'Order';
    $dir = "../";
    $isLoggedIn = false;
    if(!isset($_SESSION['username'], $_SESSION['userID'], $_SESSION['groupID'], $_SESSION['regStatus'], $_SESSION['trustStatus'])
        || $_SESSION['groupID'] < 1 ||$_SESSION['groupID'] > 3 || $_SESSION['regStatus'] < 1 || $_SESSION['trustStatus'] < 0){
        header("location: ../");
        exit();
    }
    $bs5Colors = ['danger','costum-dark','dark','primary','success','success','success']; /**This array represnts procStatus color. it gonna be used in Status fields and simple-overlay div */
    require_once $dir."init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if($do == 'manage'){
        $orders = stat_getObjects::getOrders('ALL');
        if(is_array($orders)){
        ?>
        <main class="" data-page-title="<?php echo lang('ORD MAN') ?>">
            <h1 class="text-center"><?php echo lang("ORD MAN") ?></h1>
            <div class="p-3">
                <section class="">
                    <div class="table-responsive">
                        <table class="main-table manage text-center table table-bordered">
                            <thead class="fw-bold">
                                <tr>
                                    <td><?php echo lang('INV NO')?></td>
                                    <td><?php echo lang('STATUS')?></td>
                                    <td><?php echo lang('PHA NAM')?></td>
                                    <td><?php echo lang('TOTAL')?></td>
                                    <td><?php echo lang('CRE DAT')?></td>
                                    <td><?php echo lang("SUB DAT")?></td>
                                    <td><?php echo lang("COM DAT")?></td>
                                    <td><?php echo lang('CONTROL')?></td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($orders) && !empty($orders)){
                                $cancelIF = setting::getSpecificSetting('minPermissionToCancelOrder')['value'];
                                /* $trTitleArr = ['3'=>'COS SUB NEW ORD', '4'=>'COS SUB NEW PRO']; */
                                foreach($orders as $order){
                                    $trTitle = '';
                                    $orderStatus = $order->procStatus;
                                    $finalContentsCount = fnc::countItems('ID', 'orders_contents', "orderID=". $order->orderID. " AND orderVersion=1");
                                    if($orderStatus > 3 && $finalContentsCount > 0){
                                        $totalField = "<del>". number_format($order->total, 2). "</del> | ". number_format($order->totalNet, 2) ;
                                        $totTitle = lang('DISCOUNT'). " : ". number_format((doubleval($order->total) - doubleval($order->totalNet)), 2). " ". lang("L.D")  ;
                                    }else{
                                        $totalField = $order->total;
                                        $totTitle ="";
                                    }
                                    $createField = ($order->createdDate != "0000-00-00 00:00:00")? date("Y-m-d | h:i a",strtotime($order->createdDate)) : lang("NOT YET");
                                    $submitField = ($order->receiveDate != "0000-00-00 00:00:00")? date("Y-m-d | h:i a",strtotime($order->receiveDate)) : lang("NOT YET");
                                    $complitionField = ($order->doneDate != "0000-00-00 00:00:00")? date("Y-m-d | h:i a",strtotime($order->doneDate)) : lang("NOT YET");
                                    $infoHref = "./?do=info&ID=". $order->orderID ;
                                    $controllers ="";
                                    $trClass = "";
                                    if($_SESSION['groupID'] >= $cancelIF){
                                        if($orderStatus < 6 && $orderStatus > 1){    /** 6 refers to done odrers */
                                            $controllers .= "<li><a class='link dropdown-item' data-order-control='cancel' data-order-num='".$order->orderID."' data-confirm-msg='completed_externally_confirm_msg' title='". lang("CAN ORD"). "'><i class = 'fa fa-ban'></i> ".lang("CANCEL")."</a></li>";
                                        }elseif($orderStatus < 1){
                                            $controllers .= "<li><a class='link dropdown-item confirm' data-order-control='activate' data-order-num='".$order->orderID."' title='". langTXT("ACT ORD RES ORD"). "'><i class = 'fa fa-ban'></i> ".lang("ACTIVATE")."</a></li>";
                                        }
                                    }
                                    if($orderStatus == 3 || $orderStatus == 4){ /** 3 -> submitted (waiting for reply) orders ,, 4-> got reply orders (but doesn't confirmed yet) */
                                        if($orderStatus == 3 || strtotime($order->lastProposalDate) > strtotime($order->lastResponseDate)){
                                            $controllers .= "<li><a id='' class='pointer dropdown-item' data-order-id='". $order->orderID. "' data-bs-toggle='modal' data-bs-target='#replyMethod' title='". langTXT("REP WIT YOU ORD"). "'><i class = 'fas fa-reply'></i> ".lang("SET REP")."</a></li>";
                                            $trTitle = langTXT(RECEIVE_ORDER_TITLE[$orderStatus]);
                                            $trClass = 'bg-blink';
                                        }
                                        if(strtotime($order->lastResponseDate) > strtotime('0000-00-00')){
                                            $controllers .= "<li><a href='./?do=edit&op=edit&ID=". $order->orderID. "' id='edit-order-btn' class='dropdown-item' ' title='". langTXT("EDI LAS REP OPT"). "'><i class = 'fas fa-reply'></i> ".lang("EDI LAS REP")."</a></li>";
                                        }
                                        $controllers .= "<li><a class='link dropdown-item' data-order-control='complete' data-order-num='". $order->orderID. "' ' title='". langTXT("CHO THI OPT IF"). "'><i class='fas fa-file-circle-check'></i> ".lang("COM EXT")."</a></li>";
                                    }elseif($orderStatus == 5){
                                        $controllers .= "<li><a class='link dropdown-item' data-order-control='complete' data-order-num='". $order->orderID. "' ' title=''><i class='fas fa-file-circle-check'></i> ".lang("COMPLETED")."</a></li>";
                                    }
                                    $controllers .= "<li><a id='' class='dropdown-item' href='$infoHref' title='". lang("MOR INF"). "'><i class='fas fa-file-lines'></i> ". lang("DETAILS"). "</a></li>";
                                    echo "<tr class='$trClass' title='$trTitle'>
                                            <td><a class='link link-costum-dark' target='blank' title='". lang("MOR INF"). "' href='$infoHref'><b>".$order->orderID."</b></a></td>
                                            <td><a class='link link-". $bs5Colors[$orderStatus]. "' target='blank' title='". lang("MOR INF"). "' href='$infoHref'><b>". lang(PROCSTATUS[$orderStatus]). "</b></a></td>
                                            <td><a class='link link-costum-dark' target='blank' title='". lang("COS INF"). "' href='../costumers/?do=info&ID=". $order->costumerID. "'><b>". ucwords($order->pharmacy)."</b></a></td>
                                            <td title='$totTitle'>". $totalField. "</td>
                                            <td>". $createField. "</td>
                                            <td>". $submitField. "</td>
                                            <td>". $complitionField. "</td>
                                            <td>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>". lang("OPTIONS"). "</button>
                                                    <ul class='dropdown-menu dropdown-menu-dark'>
                                                        $controllers
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr> ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <a class="btn btn-secondary" href="../"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK'); ?></a>
                <a class="btn btn-primary" href = "?do=new"><i class="fa fa-plus"></i> <?php echo lang('NEW ORD') ?></a>
            </div>

            <!-- Start Modal -->
            <div class="modal fade" id="replyMethod" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replyMethod" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo lang("SEL REP MET") ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div> -->
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            <div class="">
                                <h5 class="col-form-label"> <?php echo langTXT("SEL YOU APP MET") ?> ?. </h5>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo lang("CANCEL") ?></button>
                            <a href="./?do=edit&op=reply&ID=" class="btn btn-primary" data-reply-link='manually'><?php echo lang("SET MAN") ?></a>
                            <a href="./?do=repcsv&ID=" class="btn btn-primary" data-reply-link='import' autofocus><?php echo lang("IMP FIL") ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Modal -->
        <?php
        }else{?>
            <section class="p-2 d-grid justify-content-center">
                <h1 class="text-center"><?php echo lang("ORD MAN") ?></h1>
                <div class="my-5 px-5 py-2 form-container-ver1">
                    <h4 class="text-center mb-3"><b><i><?php echo langTXT("YOU HAV NO ORD") ?> !!</i></b></h4>
                    <div class="d-flex justify-content-around my-1">
                        <a class="btn btn-secondary" href="../"><i class="fa fa-home"></i> <?php echo lang('DASHBOARD'); ?></a>
                        <!-- <a class="btn btn-primary" href = "?do=new"><i class="fa fa-plus"></i> <?php echo lang('NEW ORD') ?></a> -->
                    </div>
                </div>
            </section>

        <?php
        }
        echo "</main>";
    }elseif($do == 'new'){
        //Start New order page
        $costumerID = (isset($_GET['cID']) && is_numeric($_GET['cID'])) ? intval($_GET['cID']) : fnc::redirectHome('خطأ: 6120', 'back', 1);
        $orderNew = new order($is, null, $costumerID,1); /**procStatus = 1 means that the invoice is opened */
        if(!$is){
            fnc::redirectHome("خطأ: 6ءءء", 'back', 1);
        }
        $orderNew->createdBy = 0; /** 0 means that order created by the costumer (This set is temprory, just for condition in getOrderPropWhere() ) */
        $displayUI = true ;
        $forceNew = (isset($_GET['f'], $_SERVER['HTTP_REFERER']) && $_GET['f'] == 1 && strpos($_SERVER['HTTP_REFERER'], 'do=new') > 25) ? true : false;
        if(!$forceNew){
            $openedOrderId = $orderNew->getOrderPropWhere('orderID', ['=', '=','>'], ['costumerID', 'procStatus', 'createdBy']);
            if($openedOrderId != false){
                $orderNew->orderID = $openedOrderId;
                $orderNew->createdBy = $orderNew->getOrderPropWhere('createdBy', ['=', '=','>'], ['costumerID', 'procStatus', 'createdBy']);
                $membName = $orderNew->getOrderPropByJoin('users', ['createdBy', 'userID'], 'username');
                echo '<div id="" class="myConfirm-overlay-background">
                        <div class="myConfirm-modal">
                            <div class="myConfirm-modal-header">
                                <h2 class="text-center">'. lang("CHE UNS ORD"). '</h2>
                                <hr>
                            </div>
                            <div class="myConfirm-msg-cont my-1">
                                <h5 class="">'. langTXT("THE IS AN OPE").' <a href="?do=edit&ID='. $orderNew->orderID.'" class="link link-costum-dark">('. lang("ORDER").': <b>'. $orderNew->orderID.'</b>) </a>, '. lang("CRE BY"). ' ('. $membName. '). '. langTXT("DO YOU WAN CON"). '</h5>
                            </div>
                            <hr>
                            <div class="myConfirm-btn-cont col12 col-md-8 offset-md-4 mt-1">
                                <a href="?do=new&f=1" class="btn btn-secondary col-md-5 offset-1 offset-sm-2 offset-md-1">'. lang("NEW ORD"). '</a>
                                <a href="?do=edit&ID='. $orderNew->orderID.'" class="btn btn-primary col-md-5 focus-it" title="'. langTXT("COM THE UNS ORD"). '" autofocus>'. lang("GO ORD"). ': '.$orderNew->orderID .'</a>
                            </div>
                        </div>
                    </div>';
                $displayUI = false;
            }
        }/* else{
            if(strpos($_SERVER['QUERY_STRING'], 'f=1') != false){
                $openedOrderIdLast = $orderNew->getOrderPropWhere('orderID', ['=', '='], ['costumerID', 'procStatus'], 'AND', 'DESC');
                if($openedOrderIdLast != false){
                    header('location: ?do=edit&ID='.$openedOrderIdLast);
                    exit();
                }
            }
        }
        */
        if($displayUI){
            $orderNew->createdBy = $_SESSION['userID'];
            $orderNew->createdDate = date('Y-m-d H:i');
            $orderID = $orderNew->insertOrder($e, 'costumerID','procStatus','createdDate');
            if($orderID != false){
                /* $orderNew->orderID = $orderID; */
                header('location: ?do=edit&ID='. $orderID);
                exit();
            }else{
                fnc::redirectHome('خطأ: 7171', 'back');
            }
            /* include("orderEdiorUI.php"); */
        }
    }elseif($do == 'edit'){
        //Start Edit page
        $h2Header = lang("SAL INV");
        $orderID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6178", 'back', 0);
        $isReply = (isset($_GET['op']) && $_GET['op'] == 'reply') ? true :false ;
        $isEdit = (isset($_GET['op']) && $_GET['op'] == 'edit') ? true :false ;
        $orderNew = new order($is,$orderID);
        $orderNew->orderVersion = 1; /**just to get single row from the view */
        $orderNew->getOrderByID();
        if(!in_array($orderNew->procStatus, [1,3,4])){ /** 5 refers to comfirmed order, and 6 refers to completed order */
            $errorMsg = lang("CAN NOT ALT"). " ". lang(PROCSTATUS[$orderNew->procStatus]. " ORD");
            fnc::redirectHome($errorMsg, 'back');
        }
        if($isEdit && $orderNew->procStatus == 4 ){
            $orderItems = stat_getObjects::getOrderItems($orderID, 1, 1);
        }elseif($isReply && in_array($orderNew->procStatus, [3,4])){
            $orderItems = stat_getObjects::getOrderItems($orderID, 1, 2);
            if(!is_array($orderItems)){
                $orderItems = stat_getObjects::getOrderItems($orderID, 1, 0);
            }
            if(is_array($orderItems)){
                $orderItems[0]->orderVersion = 1; /** to delete previous final version (This set is temprory, just for condition in deleteOrderItem())*/
                $orderItems[0]->deleteOrderItem($err, 'orderID', 'orderVersion');
                foreach($orderItems as $orderItem){
                    $orderItem->ID = null;
                    $orderItem->itemName = null;
                    $orderItem->itemNum = null;
                    $orderItem->orderVersion = 1; /** refers to final invoice version */
                    $orderItem->insertDate = date("Y-m-d H:i");
                    if($orderItem->insertOrderItem($err, 'ALL') === false){
                        fnc::redirectHome('خطأ :7203', 'back');
                    }
                }
            }else{
                fnc::redirectHome('خطأ :7204', 'back');
            }
            // $bodyDataAttr = "data-body-class='bg-dawn-grad'";
            $h2Header = lang("PRO INV");
        }else{
            fnc::redirectHome('خطأ :7212', 'back');
        }
        include("orderEdiorUI.php");
    }elseif($do == 'info'){
        //start more info page
        $orderID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6085", 'back', 0);
        $orderInfo = new order($is,$orderID);
        $orderInfo->orderVersion = 1;
        $orderInfo->getOrderByID(['pharmacy']);
        $discountValue = "unknown"; /**just in case procStatus < 4 */
        $isFinal = false;
        $isDoneExternally = false;
        $isThereCostProposal = false;
        $isLastProposal = false;
        $orderVersion = 0;
        $countWhere = "orderID=". $orderID. " AND orderVersion=";
        if($orderInfo->procStatus > 3){
            $countFinal = fnc::countItems('ID', 'orders_contents', $countWhere. '1'); /**count items of final version order*/
            if($countFinal > 0){
                $orderVersion = 1;
                $discountValue = number_format((doubleval($orderInfo->total) - doubleval($orderInfo->totalNet)), 2);
                $isFinal = true;
                $countCostPropose = fnc::countItems('ID', 'orders_contents', $countWhere. '2'); /**count items of costumer isReply version order*/
                if($countCostPropose > 0){
                    $isThereCostProposal = true;
                    if(strtotime($orderInfo->lastProposalDate) > strtotime($orderInfo->lastResponseDate)){
                        $orderVersion = 2;
                        $isLastProposal = true;
                    }
                }
            }else{
                $isDoneExternally = true;
            }
        } /**else statement doesn't nessery because all vars pre-defined to act as initial version*/
        $h2Header = ($isLastProposal)? lang("LAS PRO INV") : (($isFinal)? lang("FIN SAL INV") : lang("INI SAL INV"));
        $overlayColor = $bs5Colors[$orderInfo->procStatus];
        $orderItems = stat_getObjects::getOrderItems($orderID,"1",$orderVersion);
        if(is_array($orderItems)){
            $minCancelGroupID = setting::getSpecificSetting('minPermissionToCancelOrder')['value'];
            $cancelIF = ($orderInfo->procStatus > 1 && $_SESSION['groupID'] >= $minCancelGroupID) ? true : false;
           /*  $orderInfo->total = 0;
            foreach($orderItems as $item){
                $orderInfo->total += doubleval( number_format($item->getTotalItemPrice($orderInfo->discountRatio),2));
            } */
            include("showDoneOrder.php");
        }else{
            fnc::redirectHome('خطأ: 7291 ، هذه الفاتورة لا تحتوي أصنافاً بعد', 'back', 1);
        }
    }elseif($do == 'repcsv'){
        //start Reply with CSV file page
        $orderID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6265", 'back', 0); 
        $orderReply = new order($is,$orderID);
        $orderReply->orderVersion = 1; /**just to get single row from the view */
        $orderReply->getOrderByID();
       if(!in_array($orderReply->procStatus, [3,4])){
            fnc::redirectHome("خطأ: 6299", 'back',1);
        }
        ?>
        <main class="csv-upload-container overflow-hidden p-2">
            <h1 class="text-center mb-3"><?php echo lang("IMP FIN INV") ?></h1>
            <div class="row">
                <div class="col-10 offset-1 p-4 form-container-ver1">
                    <form class = "csv-upload" method="POST" enctype="multipart/form-data" action="./?do=csvmapping">
                        <div class="form-group form-group-lg row mb-3">
                            <div class="input-field">
                                <label class="form-label mb-1"><?php echo langTXT("CHO AN CSV INV") ?></label>
                                <input class ="form-control" type="file" name="file" id="csv-file" required data-no-asterisk='1' />
                            </div>
                        </div>
                        <div class="importing-setting is-hidden" data-visibility="target" data-target-vis-Id="importing-setting" style="display: none;">
                            <div class="form-group form-group-lg row mb-3">
                                <label class="control-label"><?php echo langTXT('IF THI FIL'); ?></label>
                                <div class="form-check form-check-inline" >
                                    <input type="checkbox" class="form-check-input" id="arabic-or-not" name="dateSyntax" value ="1" checked>
                                    <label class="form-check-label" for="arabic-or-not"> <?php echo langTXT("CON DAT FRO") ?> </label>
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2">
                                <label class="control-label"><?php echo langTXT('CON ITE PRI AS'); ?> : </label>
                                <div class="input-field">
                                    <div class="form-check form-check-inline" title="<?php echo langTXT("THE ITE PRI ORI") ?>">
                                        <input class="form-check-input" type="radio" name="isDisc" id="before" value="before">
                                        <label class="form-check-label" for="before"> <?php echo lang("BEF DIS PRI") ?> </label>
                                    </div>
                                    <div class="form-check form-check-inline" title="<?php echo langTXT("THE ITE PRI AFT") ?>">
                                        <input class="form-check-input" type="radio" name="isDisc" id="after" value="after" checked>
                                        <label class="form-check-label" for="after"> <?php echo lang("AFT DIS PRI") ?> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-lg" title="<?php echo langTXT('THE DIS RAT FIE') ?>">
                                <label class="control-label"><?php echo langTXT('FIL FIE OF FOL') ?> : </label>
                                <div class="row">
                                    <div class="input-field">
                                        <label class="control-label"> <?php echo lang("DIS RAT") ?> </label>
                                        <input type="number" class="" name="discRatio" value="0" size="2" pattern="{0,20}">
                                    </div>
                                     <?php echo lang('OR') ?> 
                                    <div class="input-field">
                                        <label class="control-label"> <?php echo lang("TOTAL"). " (<span id='before-after-total'>". lang("BEFORE"). "</span> ". lang('DISCOUNT'). ")" ?> </label>
                                        <input type="number" class="" name="total"  size="4" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2">
                                <div class="input-field">
                                    <label class="control-label"> <?php echo lang("DELIMITER") ?> </label>
                                    <input type="text" name="delimiter" value=";" size="2" pattern="{1,3}" required >
                                </div>
                            </div>
                        </div>
                        <div class="form-label" data-visibility='switcher' data-vis-Id="importing-setting">
                            <label class="switcher-text"><?php echo lang("ADV SET") ?> <i class="fas fa-caret-down"></i></label>
                        </div>
                        <div class="form-group form-group-lg row ">
                                <div class="offset-10">
                                    <input style="display: none;" type="hidden" name="ID" value="<?php echo $orderID ?>">
                                    <input type="submit" class="btn btn-primary btn-lg" value= "<?php echo lang('CONTINUE'); ?>">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </main>
     <?php
    }elseif($do == 'csvmapping'){
        //Start csvmapping page
        if(!isset($_FILES['file']['name']) || !isset($_POST['delimiter'])){
            $errorMsg = "خطأ: 6342";
            fnc::redirectHome($errorMsg, 'back',0);
        }
        /*start validate received data */
        $delimiter = (in_array($_POST['delimiter'], [";",";;",",",",,","  ","   "," ",".",".."])) ? $_POST['delimiter'] : fnc::redirectHome("invalid Delimiter",'back',3);
        $isDiscountCalc = (isset($_POST['isDisc']) && in_array($_POST['isDisc'], ['before','after'])) ? $_POST['isDisc'] : fnc::redirectHome("خطأ: 6345",'back',0);
        $discRatio = (isset($_POST['discRatio']) && is_numeric($_POST['discRatio'])) ? doubleval($_POST['discRatio']) : 0 ;
        $outInvTotal = (isset($_POST['total']) && is_numeric($_POST['total'])) ? doubleval($_POST['total']) : 0 ;
        if($_FILES['file']['error'] > 0){
            $errorMsg = "Please Select a File";
            fnc::redirectHome($errorMsg, 'back',0);
        }
        $orderID = (isset($_POST['ID']) && is_numeric($_POST['ID'])) ? intval($_POST['ID']) : fnc::redirectHome("خطأ: 6085", 'back', 0);
        /* rename file for security purpose. and make sure that file is valid*/
        $fileName = $_FILES['file']['name'];
        $fileNameArray = explode(".", $fileName);
        $extension = end($fileNameArray); /* get extension */
        if(!in_array($extension, allowedExt)){ /* if extension is not in allowed extensions redirect back */
            $errorMsg = "Invalid File";
            fnc::redirectHome($errorMsg, 'back');
        }elseif($_FILES['file']['size'] >  262144){ /* if file size more than 256KB prevent uploading process */
            $errorMsg = "Invalid File Size";
            fnc::redirectHome($errorMsg, 'back');
        }else{ /* if everything ok then done rename, uploading and updating processes */
            $_FILES['file']['name'] = "22". rand(0,1000). "". $_SESSION['username']. "". rand(0, 1000) ."". rand(0,1000). ".". $extension;
            $uploadFilePath = '../uploads/'.basename($_FILES['file']['name']);
            (glob("../uploads/*")) ? array_map('unlink', glob("../uploads/*")) : "empty" ; /* to delete All previous files */
            move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
            $CSVfp = fopen($uploadFilePath, "r");
            if ($CSVfp !== FALSE) {
                $row = 0;
                $table = "<div class='table-responsive'>
                        <table class='main-table manage text-center table table-bordered'>
                        <thead><tr>";
                $items=[];
                /*print "<PRE>";*/
                while (!feof($CSVfp) && $row < 11) { /* !fefo($CSVfp) meaning that the file hasn't end yet , $row < 11 to take a sample of table with 10 items + the subjects */
                    $data = fgetcsv($CSVfp, 1000, $delimiter);
                    if (!empty($data) && count($data) >= 4) {
                        
                            foreach($data as $index=>$value){
                                if(mb_detect_encoding($value,null,true) != "UTF-8"){
                                    $value = iconv("windows-1256", "UTF-8", $value);
                                }
                                $items[$row][$index] = $value;
                            }
                        }else{
                            $formWarnings[] = "Invalid Data Form into row:". $row + 1 .". This row has skipped";
                        }
                        $row++;
                    }
                fclose($CSVfp);
                foreach($items[0] as $subject){
                    $table .= "<th>$subject</th>";
                }
                $table .= "</tr></thead>
                            <tbody>";
                for($i=1 ; $i < count($items) ; $i++){
                    $table .="<tr>";
                    foreach($items[$i] as $item){
                        $table .= "<td>$item</td>";
                    }
                    $table .= "</tr>";
                }
                $table .="</tbody></table>
                        </div>";
                $options = '';
                foreach($items[0] as $index=>$val){
                    $options .= "<option value='$index'>$val</option>";
                }
                ?>
                <div class="csv-match-container overflow-hidden p-2">
                    <h1 class="text-center mb-3"><?php echo langing("COL MAT") ?></h1>
                    <section class="row">
                        <div class="col-md-8 offset-md-2 col-10 offset-1 p-4 form-container-ver1">
                            <form method="POST" action="./?do=importing" class="csv-match">
                                <header class="my-2">
                                    <label class="form-label"><?php echo langTXT("SEL THE APP") ?>.</label>
                                    <label class="form-label dim-text"><?php echo lang("NOTICE"). ": ". langTXT("BEL THE IS") ?>.</label>
                                </header>
                                <hr>
                                <section class="offset-lg-1 my-1">
                                    <div class="match-section-header mb-2 mt-1">
                                        <label class="form-label col-lg-4"><?php echo lang("PROPERTY") ." (". lang("SIT SID") .")"?></label>
                                        <label class="form-label col-lg-7"><?php echo lang("COL NAM") ." (". lang("FIL SID") .")"?></label>
                                    </div>
                                    <div class="form-group form-group-lg row mb-2">
                                        <label class="col-lg-4 control-label"><?php echo lang('ITE NUM') ?>: </label>
                                        <div class="input-field col-lg-7 col-sm-10">
                                            <select class="form-select csv-match-selector" name="itemNum" id="itemNum" required="required">
                                                <option value="" selected disabled>----------</option>
                                            <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg row mb-2">
                                        <label class="col-lg-4 control-label"><?php echo lang('ITE NAM') ?>: </label>
                                        <div class="input-field col-lg-7 col-sm-10">
                                            <select class="form-select csv-match-selector" name="itemName" id="itemName" required="required">
                                                <option value="" selected disabled>----------</option>
                                            <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg row mb-2">
                                        <label class="col-lg-4 control-label"><?php echo lang('ITE PRI') ?>: </label>
                                        <div class="input-field col-lg-7 col-sm-10">
                                            <select class="form-select csv-match-selector" name="itemPrice" id="itemPrice" required="required">
                                                <option value="" selected disabled>----------</option>
                                                <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg row mb-2">
                                        <label class="col-lg-4 control-label"><?php echo lang('QUANTITY') ?>: </label>
                                        <div class="input-field col-lg-7 col-sm-10">
                                            <select class="form-select csv-match-selector" name="Qty" id="Qty" required="required">
                                                <option value="" selected disabled>----------</option>
                                                <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg row mb-2">
                                        <label class="col-lg-4 control-label"><?php echo lang('EXP DAT') ?>: </label>
                                        <div class="input-field col-lg-7 col-sm-10">
                                            <select class="form-select csv-match-selector" name="expDate" id="expDate" required="required">
                                                <option value="" selected disabled>----------</option>
                                                <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <section class="hidden" style="display: none;">
                                    <input style="display: none;" type="hidden" name="ID" value="<?php echo $orderID ?>">
                                    <input style="display: none;" type="hidden" name="delimiter" value="<?php echo $delimiter ?>">
                                    <input style="display: none;" type="hidden" name="filePath" value="<?php echo $uploadFilePath ?>">
                                    <input style="display: none;" type="hidden" name="isDisc" value="<?php echo $isDiscountCalc ?>">
                                    <input style="display: none;" type="hidden" name="discRatio" value="<?php echo $discRatio ?>">
                                <?php echo (isset($_POST['dateSyntax'])) ? "<input style='display: none;' type='hidden' name='dateSyntax' value='". $_POST['dateSyntax']. "'>" :"" ?>
                                <?php echo ($outInvTotal > 0) ? "<input style='display: none;' type='hidden' name='total' value='". $outInvTotal. "'>" :"" ?>
                                </section>
                                <section>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check form-check-inline" title="<?php echo langTXT("CHE THI OPT IF") ?>">
                                            <input class="form-check-input" type="checkbox" id="include-first-row" name="incFirst" value="1" checked>
                                            <label class="form-check-label" for="include-first-row"><?php echo lang("INC FIR ROW") ?></label>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <input type="submit" id="csv-import-btn" class="btn btn-primary btn-lg mt-2" value= "<?php echo lang('IMPORT'); ?>">
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </section>
                    <hr>
                    <section class="mt-4 p-3">
                        <div>
                            <h4 class="mb-5 text-center"><?php echo langTXT("SAM TAB OF") ?>:</h4>
                        </div>
                        <?php echo $table ?>
                    </section>
                </div>
                <?php
            }else{
                $errorMsg = "upload process has interrupted please try again";
                fnc::redirectHome($errorMsg,'back');
            }
        }
    }elseif($do == 'importing'){
        //Start importing page
        if(!isset($_POST['filePath']) || !isset($_POST['delimiter']) || !isset($_POST['itemNum']) || !isset($_POST['itemName']) 
           || !isset($_POST['Qty']) || !isset($_POST['itemPrice']) || !isset($_POST['expDate'])){
            $errorMsg = "6475";
            fnc::redirectHome($errorMsg, 'back',0);
        }
        /** لضمان عدم الدخول إلى هذه الصفح مباشرة دون المرور بصفحة csvmapping */
        if(!isset($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'do=csvmapping') > 25){
            $errorMsg = "6511";
            fnc::redirectHome($errorMsg, 'back',0);
        }
        /** Start validate received dhiddenata */
        $orderID = (isset($_POST['ID']) && is_numeric($_POST['ID'])) ? intval($_POST['ID']) : fnc::redirectHome("خطأ: 6085", 'back', 0);
        if(!is_numeric($_POST['itemNum']) || !is_numeric($_POST['itemName']) || !is_numeric($_POST['Qty'])
            || !is_numeric($_POST['itemPrice']) || !is_numeric($_POST['expDate'])){
            $errorMsg = "6491";
            fnc::redirectHome($errorMsg, 'back',0);
        }else{
            $itemNum      = ($_POST['itemNum'] >= 0)      ?   $_POST['itemNum'] : fnc::redirectHome("6335", 'back',0) ;
            $itemName    = ($_POST['itemName'] >= 0)    ?   $_POST['itemName'] : fnc::redirectHome("6336", 'back',0) ;
            $Qty         = $_POST['Qty'];
            /* $payPrice    = $_POST['payPrice'] ;
            $avrPayPrice = $_POST['avrPayPrice'] ; */
            $itemPrice   = $_POST['itemPrice'] ;
            /* $profitRatio = $_POST['profitRatio']; */
            $expDateIndex= $_POST['expDate'] ;
        }
        $uploadFilePath = (file_exists($_POST['filePath']))? $_POST['filePath'] : fnc::redirectHome("File Lost: The system can't reach the uploaded file. <span style='color:red;'> Please retry again</span>", 'back', 2);
        $delimiter = (in_array($_POST['delimiter'], [";",";;",",",",,","  ","   "," ",".",".."])) ? $_POST['delimiter'] : fnc::redirectHome("invalid Delimiter",'back',0);
        $isDiscountCalc = (isset($_POST['isDisc']) && in_array($_POST['isDisc'], ['before','after'])) ? $_POST['isDisc'] : fnc::redirectHome("خطأ: 6538",'back',0);
        $outInvTotal = (isset($_POST['total']) && is_numeric($_POST['total'])) ? doubleval($_POST['total']) : 0 ;
        $discRatio = (isset($_POST['discRatio']) && is_numeric($_POST['discRatio'])) ? doubleval($_POST['discRatio']) : 0 ;
        $tempMax=setting::getSpecificSetting('maximumDiscRatio')['value'];
        $maxDiscount = ($tempMax < TOP_MAX_DISC_RATIO)? $tempMax : TOP_MAX_DISC_RATIO; /* */
        $discRatio = ($discRatio < $maxDiscount)? $discRatio : $maxDiscount;/* في حال تجاوزة قيمة نسبة التخفيض الحد الأقصى اضبطها عند الحد الأقصى */
            $CSVfp = fopen($uploadFilePath, "r");
            if ($CSVfp !== FALSE) {
                $tempOrderItem = new orderItem($e, null, $orderID); /**temp. just to delete the previous final version contents */
                $tempOrderItem->orderVersion = 1;
                $tempOrderItem->deleteOrderItem($err, 'orderID','orderVersion');
                $row = (isset($_POST['incFirst']) && $_POST['incFirst'] == 1) ? 1 : 0 ;
                /* $table = "<div class='table-responsive'>
                        <table class='main-table manage text-center table table-bordered'>
                        <thead><tr>"; */
                $orderItems=[];
                $uploadedInvTotal = 0;
                
                while (! feof($CSVfp)) {
                    $data = fgetcsv($CSVfp, 1000, $delimiter);
                    if (!empty($data) && count($data) >= 4) {
                        if($row > 0){
                            if(mb_detect_encoding($data[$itemName],null,true) != "UTF-8"){
                                $data[$itemName] = iconv("windows-1256", "UTF-8", $data[$itemName]);
                            }
                            $item = new item($e, null, ((!empty($data[$itemNum])) ? $data[$itemNum] : 0), $data[$itemName]);
                            $itemID = $item->getItemPropWhere('itemID', ['=','='], ['itemNum','itemName']);
                            if($itemID < 1){
                                $formWarnings[] = "Unkown item into row:". $row + 1 .". This row has skipped. Warning No. 6516";
                            }else{
                                if(isset($data[$expDateIndex])){
                                    $arArray = str_split($arAlphabet);
                                    $data[$expDateIndex] = trim(str_replace($arArray, "", $data[$expDateIndex])); /**تصفية التاريخ من الحروف العربية والمسافات الزائدة*/
                                    if(isset($_POST['dateSyntax']) && $_POST['dateSyntax'] == 1){
                                        $date = DateTime::createFromFormat('d/m/Y H:i:s', $data[$expDateIndex]);
                                        $date2 = DateTime::createFromFormat('d/m/Y H:i', $data[$expDateIndex]);
                                        $data[$expDateIndex] = ($date !== false) ? date_format($date,"Y-m-d") : (($date2 !== false)? date_format($date2,"Y-m-d") : $data[$expDateIndex]);
                                    }
                                    
                                }
                                $orderItem = new orderItem($e, null, $orderID, $itemID, (($itemPrice >= 0)? $data[$itemPrice] : 0),  $data[$Qty], $data[$expDateIndex], 1);
                                if(!$e){
                                    $formWarnings[] = "Invalid data form into row:". $row + 1 .". This row has skipped. Warning No. 5521";
                                }else{
                                    $uploadedInvTotal += doubleval($orderItem->itemPrice) * doubleval($orderItem->itemQty);
                                    $orderItem->itemName = $data[$itemName];
                                    $orderItems[] = $orderItem;
                                }
                            }
                        }
                    }else{
                        $formWarnings[] = "Invalid Data Form into row:". $row + 1 .". This row has skipped. 0567";
                    }
                    $row++;
                }
                $isThereDiff = false;
                $realDiscRatio = $discRatio / 100 ;
                if($isDiscountCalc == 'after'){ /* in this case the $uploadedInvTotal represents 'after discount total' and $outInvTotal represents 'before discount total'*/
                    if($discRatio <= 0 && $outInvTotal > $uploadedInvTotal){
                        $realDiscRatio = 1 - ($uploadedInvTotal/$outInvTotal);
                    }
                    $discFactor = 1 / (1 - ($realDiscRatio));
                }else{ /* in this case ($isDiscountCalc == 'before') the $uploadedInvTotal represents 'before discount total' and $outInvTotal represents 'after discount total'*/
                    if($discRatio <= 0 && $outInvTotal < $uploadedInvTotal){
                        $realDiscRatio = 1 - ($outInvTotal/$uploadedInvTotal);
                    }
                    $discFactor = 1; /**if the items price of the uploaded invoice are considered as before discount price so the factor must be 1 to doesn't change the price*/
                }
                foreach($orderItems as $orderItem){
                    $orderItem->itemPrice = $orderItem->itemPrice * $discFactor;
                    /* Below I am using the item name as a variable ($name), to use this variable as variable name ($$name).
                    if this item name repeates then the value of this variable with name of item name ($$name) will be bigger than one.
                    by this way the script can know if this item repeatd in the same file, that's meaning the Qty should be summed with the previous value within the same file */
                    $name = $orderItem->itemID;
                    $$name = (isset($$name))? $$name + 1 : 1;
                    if($$name > 1){
                        $targetID = $orderItem->getOrderItemPropWhere('ID', ['=','=','='], ['itemID','orderID','orderVersion']);
                        if($targetID > 0){
                            $orderItem->itemQty += doubleval($orderItem->getOrderItemPropWhere('itemQty', ['=','=','='], ['orderID', 'itemID', 'orderVersion']));
                            $orderItem->ID = $targetID;
                            if(!($orderItem->updateOrderItem($err, 'ID','itemQty'))){
                                $formWarnings[] = "The quantity of : (". $orderItem->itemName.") may be incorrect. Warning No. 5604";
                                $isThereDiff = true;
                            }
                        }else{
                            $cond = $orderItem->insertOrderItem($err, 'orderID','itemID','itemPrice','itemQty','itemExpDate','orderVersion');
                            if($cond < 1){
                                $formWarnings[] = "The item: (". $orderItem->itemName.") dosn't inserted. Warning No. 5609";
                            }
                            $isThereDiff = true;
                        }
                    }else{
                        $cond = $orderItem->insertOrderItem($err, 'orderID','itemID','itemPrice','itemQty','itemExpDate','orderVersion');
                        if($cond < 1){
                            $formWarnings[] = "The item: (". $orderItem->itemName.") dosn't inserted. Warning No. 5616";
                            $isThereDiff = true;
                        }
                    }
                    
                }
                $orderInfo = new order($is,$orderID);
                $orderInfo->orderVersion = 1;
                $orderInfo->getOrderByID();
                $orderInfo->discountRatio = $realDiscRatio * 100;
                $propsToUpdate = ['discountRatio'];
                if($orderInfo->procStatus == 3){
                    $propsToUpdate[] = 'procStatus';
                    $orderInfo->procStatus = 4;
                    $orderInfo->firstResponseBy = $_SESSION['userID'];
                    $orderInfo->firstResponseDate = date("Y-m-d H:i");
                    $propsToUpdate[] = "firstResponseBy" ;
                    $propsToUpdate[] = "firstResponseDate" ;
                }
                $orderInfo->lastResponseBy = $_SESSION['userID'];
                $orderInfo->lastResponseDate = date("Y-m-d H:i");
                $propsToUpdate[] = "lastResponseBy" ;
                $propsToUpdate[] = "lastResponseDate" ;
                $cond = $orderInfo->updateOrder($err, 'orderID', ...$propsToUpdate);
                if($cond){
                    $recipientID = $orderInfo->costumerID;
                    $notification = new specificNotifications($is, null, $_SESSION['userID'], $recipientID, "order//reply", 2, $orderID);
                    $notification->insertNotification($e, 'ALL');
                }else{
                    $formWarnings[] = "The discount ratio may be considered as 0%. Warning No. 5642";
                }
                /* $orderInfo->orderVersion = 1;
                $orderInfo->getOrderByID(); */
                $warningsCount = count($formWarnings);
                if($isThereDiff){
                    $orderItems = stat_getObjects::getOrderItems($orderID,'1',1);
                }
            ?>
            <div class="p-1">
                <div class="row p-4">
                    <div class = "form-container-ver1">
                        <h5 class="mt-2"><i class="fas fa-check-circle" style="color: green;"></i> <?php echo langTXT('THE IMP PRO') ?>. <u class="link-dark pointer" data-visibility="switcher" data-vis-Id="imported-table"><span class="fs-6 switcher-text"><?php echo lang('VIE IMP TAB') ?> <i class="fas fa-caret-down"></i></span></u></h5>
                        <p class="ms-4">
                    <?php echo ($warningsCount > 0) ? lang('THE ARE')." $warningsCount ". langTXT('WAR DUR IMP'). '. <u class="link-dark pointer" data-visibility="switcher" data-vis-Id="warnings-log"><span class="switcher-text">'. lang('VIE WAR LOG'). ' <i class="fas fa-caret-down"></i></u></p>' : langTXT('THE NO WAR'). '.</p>'; ?>
                        <div class="col-4 offset-lg-8 offset-sm-6 my-3">
                            <a class="btn btn-primary" href="./?do=manage"><?php echo lang('ORD MAN') ?></a>
                            <a class="btn btn-primary" href="../dashboard/"><?php echo lang('DASHBOARD') ?></a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="my-4 p-2 fs-7 is-hidden" data-visibility="target" data-target-vis-Id="imported-table" style="display:none;">
                    <h5 class=""><?php echo langTXT('THE IMP ITE') ?></h5>
                    <!--  -->
                    <section class="">
                        <div id="order-items-container" class="table-responsive fs-7">
                            <div class='simple-overlay bg-<?php echo $overlayColor ?> opacity-25'></div>
                            <table class="main-table items-table table table-bordered mb-0 text-center">
                                <thead class="text-center">
                                    <tr>
                                        <th><?php echo lang("ITE NO") ?></th>
                                        <th><?php echo lang("ITE NAM") ?></th>
                                        <th><?php echo lang("ITE PRI") ?></th>
                                        <th><?php echo lang("QUANTITY") ?></th>
                                        <th title="<?php echo lang("TOT ITE PRI") ?>"><?php echo lang("TOT PRI") ?></th>
                                        <th title="<?php echo lang("EXP DAT") ?>"><?php echo lang("EXP DATE") ?></th>
                                    </tr>
                                </thead>
                                <tbody id="" class="info-items-tbody" data-order-controller="1">
                                    <?php foreach($orderItems as $item){
                                                echo "<tr class=''>
                                                    <td>".$item->itemNum. "</td>
                                                    <td class=''><b>".$item->itemName. "</b></td>
                                                    <td title='".$item->itemPrice. " ". lang("L.D")."'>".$item->getItemPrice($orderInfo->discountRatio). "</td>
                                                    <td>".$item->itemQty. "</td>
                                                    <td title='".$item->totalItemPrice. " ". lang("L.D")."'>". number_format($item->getTotalItemPrice($orderInfo->discountRatio),2). "</td>
                                                    <td>".$item->itemExpDate. "</td>
                                                </tr>";
                                            }
                                    ?>
                                <tfoot class="info-items-tfoot">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td title='<?php echo lang("DISCOUNT"). " = ". number_format(($orderInfo->total - $orderInfo->totalNet), 2). " ". lang("L.D")?>'><?php echo lang("TOTAL"). " : " ?> </td>
                                        <td title='<?php echo lang("BEF DIS") ?>'><span id='order-real-total'><?php echo number_format($orderInfo->total, 2). "</span> ". lang("L.D") ?> </td>
                                        <td title='<?php echo lang("AFT DIS") ?>'><span id='order-real-total-net'><?php echo number_format($orderInfo->totalNet, 2). "</span> ". lang("L.D") ?> </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- <div class="">
                            <a href=""></a>
                        </div> -->
                    </section>
                    <!--  -->
                </div> <hr>
                <div class='my-3 p-2 fs-7 is-hidden' data-visibility='target' data-target-vis-Id='warnings-log' style='display:none;'>
            <?php if(isset($formWarnings) && count($formWarnings) > 0){
                    foreach($formWarnings as $index=>$warning){
                        echo "<p class='alert alert-warning'>$index :  $warning </p>";
                    }
                }
                echo "</div></div>";
            }
            fclose($CSVfp);
        //}
    }else{ exit(); }
include $dir. $tempsP. "footer.php";
ob_end_flush();