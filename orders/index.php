<?php
ob_start();
session_start();
$pageTitle = 'Order';
$dir = "../";
$isLoggedIn = false;
if(!isset($_SESSION['username'], $_SESSION['userID'], $_SESSION['groupID'], $_SESSION['regStatus'], $_SESSION['trustStatus'])
    || $_SESSION['groupID'] < 0 ||$_SESSION['groupID'] > 3 || $_SESSION['regStatus'] < 1 || $_SESSION['trustStatus'] < 0){
    header("location: ../sign/");
    exit();
}else{
    $isLoggedIn = true;
}
$bs5Colors = ['danger','costum-dark','dark','primary','success','success','success']; /**This array represnts procStatus color. it gonna be used in Status fields and simple-overlay div */
require_once $dir."init.php";
$do = isset($_GET['do']) ? $_GET['do'] : 'manage';
if($do == 'manage'){
    $orders = stat_getObjects::getOrders($_SESSION['userID']);
    if(is_array($orders)){
    ?>
    <main class="">
        <h1 class="text-center"><?php echo lang("ORD MAN") ?></h1>
        <div class="p-3">
            <div class="table-responsive">
                <table class="main-table manage text-center table table-bordered">
                    <thead class="fw-bold">
                        <td><?php echo lang('INV NO')?></td>
                        <td><?php echo lang('STATUS')?></td>
                        <td><?php echo lang('TOTAL')?></td>
                        <td><?php echo lang('CRE DAT')?></td>
                        <td><?php echo lang("SUB DAT")?></td>
                        <td><?php echo lang("COM DAT")?></td>
                        <td><?php echo lang('CONTROL')?></td>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($orders) && !empty($orders)){
                        $cancelIF = setting::getSpecificSetting('costCancelXorders')['value'];
                        $cancelIF = explode("/", $cancelIF);
                        foreach($orders as $order){
                            $trTitle = ($order->getCreatedBy() > 0) ? lang('CRE BY ADM') : "";
                            if($order->procStatus > 3){
                                $totalField = "<del>". $order->total. "</del> | ". $order->totalNet ;
                                $totTitle = lang('DISCOUNT'). " : ". (doubleval($order->total) - doubleval($order->totalNet))  ;
                            }else{
                                $totalField = $order->total;
                                $totTitle ="";
                            }
                            $createField = ($order->createdDate != "0000-00-00 00:00:00")? date("Y-m-d | h:i a",strtotime($order->createdDate)) : lang("NOT YET");
                            $submitField = ($order->receiveDate != "0000-00-00 00:00:00")? date("Y-m-d | h:i a",strtotime($order->receiveDate)) : lang("NOT YET");
                            $complitionField = ($order->doneDate != "0000-00-00 00:00:00")? date("Y-m-d | h:i a",strtotime($order->doneDate)) : lang("NOT YET");
                            $controlers ="";
                            $controlersCount = 0;
                            $infoHref = "?do=info&ID=". $order->orderID;
                            if(in_array($order->procStatus, $cancelIF)){
                                $controlers .= "<a id='cancel-order-btn' class='btn btn-danger confirm' data-order-num='".$order->orderID."' title='". lang("CAN ORD"). "'><i class = 'fa fa-ban'></i> ".lang("CANCEL")."</a>";
                                $controlersCount++;
                            }
                            if($order->procStatus == 2){
                                $infoHref = "?do=info&ID=". $order->orderID ."&type=init";
                                $controlers .= "<a id='' class='btn btn-success' href='?do=edit&ID=".$order->orderID."' title='". lang("EDI INV"). "'><i class = 'fa fa-pen-to-square'></i> ".lang("EDIT")."</a>";
                                $controlers .= "<a id='submit-order-btn' class='btn btn-primary' data-order-num='".$order->orderID."' title='". lang("SUB ORD"). "'><i class = 'fa fa-mail'></i> ".lang("SUBMIT")."</a>";
                                $controlersCount += 2;
                            }elseif($order->procStatus == 1){
                                $infoHref = "#";
                                $controlers .= "<a id='' class='btn btn-success' href='?do=edit&ID=".$order->orderID."' title='". lang("EDI INV"). "'><i class = 'fa fa-pen-to-square'></i> ".lang("EDIT")."</a>";
                                $controlersCount++;
                            }
                            if($controlersCount < 2 && $order->procStatus != 1){
                                $controlers .= "<a id='' class='btn btn-success' href='$infoHref' title=''>".lang("MOR INF")."</a>";
                            }
                            echo "<tr title='$trTitle'>
                                    <td><a class='link link-costum-dark' target='blank' title='More Info.' href='$infoHref'><b>".$order->orderID."</b></a></td>
                                    <td><a class='link link-". $bs5Colors[$order->procStatus]. "' target='blank' title='More Info.' href='$infoHref'><b>". lang(PROCSTATUS[$order->procStatus]). "</b></a></td>
                                    <td title='$totTitle'>". $totalField. "</td>
                                    <td>". $createField. "</td>
                                    <td>". $submitField. "</td>
                                    <td>". $complitionField. "</td>
                                    <td>". $controlers. "</td>
                                    </tr> ";
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <a class="btn btn-secondary" href="../"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK'); ?></a>
            <a class="btn btn-primary" href = "?do=new"><i class="fa fa-plus"></i> <?php echo lang('NEW ORD') ?></a>
        </div>
    </main>
<?php
}else{?>
    <main class="p-2 d-grid justify-content-center">
        <h1 class="text-center"><?php echo lang("ORD MAN") ?></h1>
        <div class="my-5 px-5 py-2 form-container-ver1">
            <h4 class="text-center mb-3"><b><i><?php echo langTXT("YOU HAV NO ORD") ?> !!</i></b></h4>
            <div class="d-flex justify-content-around my-1">
                <a class="btn btn-secondary" href="../"><i class="fa fa-home"></i> <?php echo lang('HOME'); ?></a>
                <a class="btn btn-primary" href = "?do=new"><i class="fa fa-plus"></i> <?php echo lang('NEW ORD') ?></a>
            </div>
        </div>
    </main>
<?php
}
}elseif($do == 'new'){
    //Start New order page
    $orderNew = new order($is, null, intval($_SESSION['userID']),1); /**procStatus = 1 means that the invoice is opened */
    if(!$is){
        fnc::redirectHome("خطأ: 6ءءء", 'back', 1);
    }
    $displayUI = true ;
    $forceNew = (isset($_GET['f'], $_SERVER['HTTP_REFERER']) && $_GET['f'] == 1 && strpos($_SERVER['HTTP_REFERER'], 'do=new') > 25) ? true : false;
    if(!$forceNew){
        $openedOrderId = $orderNew->getOrderPropWhere('orderID', ['=', '='], ['costumerID', 'procStatus']);
        if($openedOrderId != false){
            $orderNew->orderID = $openedOrderId;
            echo '<div id="" class="myConfirm-overlay-background">
                    <div class="myConfirm-modal">
                        <div class="myConfirm-modal-header">
                            <h2 class="text-center">'. lang("CHE UNS ORD"). '</h2>
                            <hr>
                        </div>
                        <div class="myConfirm-msg-cont my-1">
                            <h5 class="">'. langTXT("YOU HAV AN OPE").' <a href="?do=edit&ID='. $orderNew->orderID.'" class="link link-costum-dark">('. lang("ORDER").': <b>'. $orderNew->orderID.'</b>) </a>. '. langTXT("DO YOU WAN CON"). '</h5>
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
        $orderNew->createdDate = date('Y-m-d H:i');
        $orderID = $orderNew->insertOrder($e, 'costumerID','procStatus','createdDate');
        if($orderID != false){
            /* $orderNew->orderID = $orderID; */
            header('location: ?do=edit&ID='. $orderID);
            exit();
        }else{
            fnc::redirectHome('خطأ: 7051', 'back');
        }
        /* include("orderEdiorUI.php"); */
    }
}elseif($do == 'edit'){
    //Start Edit page
    $h2Header = lang("PUR INV");
    $isProposalInput = false;
    $orderID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6059", 'back', 0);
    $proposal = (isset($_GET['prop'])) ? $_GET['prop'] : null ;
    $orderNew = new order($is,$orderID);
    $orderNew->orderVersion = 0; /**just to get single row from the view */
    if(!($orderNew->getOrderByID())){
        if(!($orderNew->getOrderByID(false))){
            exit();
        }
    }
    if($orderNew->costumerID !== $_SESSION['userID']){
        exit();
    }
    if(!isset($proposal) && !in_array($orderNew->procStatus, [1,2]) ){ /** 4 refers to waiting for Confirmation order and 0 refers to canceled order */
        $errorMsg = lang("CAN NOT ALT"). " ". lang(PROCSTATUS[$orderNew->procStatus]. " ORD");
        fnc::redirectHome($errorMsg, 'back');
    }elseif(isset($proposal) && $orderNew->procStatus == 4){
        $proposalItems = stat_getObjects::getOrderItems($orderID, 1, 2);
        /** شرح الشرط في الأسفل
         * إذا لم تكن أوردرآيتمس مصفوفة فهذا يعني أنه لا يوجد مقترح سابقاً
         * أو إذا كان طول المصفوفة أقل من عنصر واحد فهذا يعني أنه لا يوجد مقترح سابقاً
         * أو إذا وجد مقترح ولكن تاريخ إنشاء المقترح (تاريخ إضافة أول عنصر هو تاريخ الإنشاء) سابق لتاريخ آخر رد من الإدارة فهذا يعني أن المقترح يجب إهماله لأنه تم الرد عليه من الإدارة وسيكون المقترح الجديد مبني عللى الرد الجديد (آخر رد من الإدارة)
         */
        if(!is_array($proposalItems) || count($proposalItems) < 1 || strtotime($proposalItems[0]->insertDate) < strtotime($orderNew->lastResponseDate)){
            $orderItems = stat_getObjects::getOrderItems($orderID, 1, 1);
            if($orderItems){
                if(is_array($proposalItems)){
                    $proposalItems[0]->deleteOrderItem($err, 'orderID', 'orderVersion');
                }
                foreach($orderItems as $orderItem){
                    $orderItem->ID = null;
                    $orderItem->itemName = null;
                    $orderItem->itemNum = null;
                    $orderItem->orderVersion = 2; /** refers to proposal invoice version */
                    $orderItem->insertDate = date("Y-m-d H:i");
                    if($orderItem->insertOrderItem($err, 'ALL') === false){
                        fnc::redirectHome('خطأ :7100', 'back');
                    }
                }
            }else{
                fnc::redirectHome('خطأ :7104', 'back');
            }
        }
        $bodyDataAttr = "data-body-class='bg-dawn-grad'";
        $h2Header = lang("PRO INV");
        $isProposalInput = true;
    }
    include("orderEdiorUI.php");
}elseif($do == 'info'){
    //start more info page
    $orderID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6085", 'back', 0);
    $orderInfo = new order($is,$orderID);
    $orderInfo->orderVersion = 0;
    $orderInfo->getOrderByID();
    if($orderInfo->costumerID !== $_SESSION['userID']){
        exit();
    }
    $discountValue = "unknown"; /**just in case procStatus < 4 */
    $createdByText = ($orderInfo->getCreatedBy() > 0) ? lang('CRE BY ADM') : "";
    $isFinal = false;
    $isDoneExternally = false;
    $maxStatus = 3;
    if(isset($_GET['type']) && $_GET['type'] == "init"){
        $orderVersion = 0;
        $maxStatus = 2;
    }else{
        if($orderInfo->procStatus < 4){ /** 4 refers to wait for confirm order */
            $orderVersion = 0;
        }else{
            $orderVersion = 1;
            $discountValue = $orderInfo->discountRatio;
            $isFinal = true;
        }
    }
    if($orderInfo->procStatus > 0 && $orderInfo->procStatus < $maxStatus){ /** 1 refers to opened order, 2 refers to saved order but not sumbmitted*/
        $errorMsg = "خطأ: 6200";
        fnc::redirectHome($errorMsg, 'back',0);
    }
    $h2Header = ($isFinal)? lang("FIN PUR INV") : lang("INI PUR INV");
    $overlayColor = $bs5Colors[$orderInfo->procStatus];
    $orderItems = stat_getObjects::getOrderItems($orderID,"1",$orderVersion);
    if(is_array($orderItems)){
        include("showDoneOrder.php");
    }elseif($orderVersion == 1 && $orderInfo->procStatus > 4){
        $orderVersion = 0;
        $isDoneExternally = true;
        $orderItems = stat_getObjects::getOrderItems($orderID,"1",$orderVersion);
        if(is_array($orderItems)){
            include("showDoneOrder.php");
        }else{
            fnc::redirectHome('خطأ: 7230', 'back', 1);
        }
    }else{
        fnc::redirectHome('خطأ: 7233', 'back', 1);
    }
}elseif($do == 'activate'){
    //start activate page

}
include $dir. $tempsP. "footer.php";
ob_end_flush();