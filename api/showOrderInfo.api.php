<?php
session_start();
const ORDER_VERSION = 0; /* 0 refers to this item inserted by costumer */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if(isset($_POST['orderID']) && is_numeric($_POST['orderID'])){
        $versionName = (isset($_POST['ver'])) ? strip_tags($_POST['ver']) : "initial";
        if($versionName == 'initial'){
            $orderVersion = 0;
        }elseif($versionName == 'final'){
            $orderVersion = 1;
        }elseif($versionName == 'proposal'){
            $orderVersion = 2;
        }else{
            exit();
        }
        /*get and validate order */
        $orderID = intval($_POST['orderID']);
        $orderInfo = new order($is,$orderID);
        $orderInfo->orderVersion = $orderVersion;
        if(!($orderInfo->getOrderByID())){
            echo "error: 6023.  Loading Faild."; 
            exit();
        }elseif($orderInfo->costumerID !== $_SESSION['userID']){
            echo "error: 6026.  Loading Faild.";
            exit();
        }else{
            $orderItems = stat_getObjects::getOrderItems($orderID, 1 , $orderVersion);
            if(is_array($orderItems)){

        ?>
            <section id="additinal-version" data-additional="2" class="order-items-section my-4" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0">
                        <section class="d-flex flex-wrap">
                            <div class="col-12 d-inline-flex">
                                <div class="p-2 col-4 col-md-2">
                                    <a id="hide-invoice-btn" class="btn btn-secondary col-12"><i class="fa fa-arrow-up"></i> <?php echo lang('HIDE') ?></a>
                                </div>
                                <div class="p-2 col-0 col-md-2">

                                </div>
                                <div class="p-2 col-4 text-center" data-order-controller="1">
                                    <p class="fw-bold"><?php echo lang("VERSION") ?> : <span id="" class="text-dark"><?php echo lang(strtoupper($versionName)) ?></span></p>
                                </div>
                                <div class="col-4 d-flex justify-content-end" data-order-status="<?php echo strtolower(PROCSTATUS[$orderInfo->procStatus]) ?>">
                                    <label id="" class="lh-xl mx-2"><?php echo lang("INV NO") ?>. </label>
                                    <input type="text" id="invoice-num" class="form-control w-auto h-min-cont text-center point-none" size="2" value="<?php echo $orderInfo->orderID ?>">
                                    
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="card-body p-0">
                        <div id="order-items-container" class="table-responsive items-manage-table fs-7">
                            <div class='simple-overlay bg-dark opacity-25'></div>
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
                                                    <td title='".$item->totalItemPrice. " ". lang("L.D")."'>".$item->getTotalItemPrice($orderInfo->discountRatio). "</td>
                                                    <td>".$item->itemExpDate. "</td>
                                                </tr>";
                                            }
                                    ?>
                                <tfoot class="info-items-tfoot">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo ($orderVersion === 1)? lang("TOTAL"). " : " : "" ?> </td>
                                        <td <?php echo ($orderVersion === 1)? " title='". lang("DISCOUNT"). " = ". $discountValue. " ". lang("L.D"). "' ><span id='order-real-total'>". $orderInfo->total. "</span> ". lang("L.D") : ">". lang("TOTAL"). " : " ?> </td>
                                        <td><?php echo ($orderVersion === 1)? lang("AFT DIS"). " : " : "<span id='order-total'>". $orderInfo->total. "</span> ". lang("L.D") ?></td>
                                        <td <?php echo ($orderVersion === 1)? " title='". lang("DISCOUNT"). " = ". $discountValue. " ". lang("L.D"). "' ><span id='order-real-total-net'>". $orderInfo->totalNet. "</span> ". lang("L.D") : ">" ?> </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        <?php
        exit();
            }else{
                echo "error: 6099.  Loading Faild.";
                exit();
            }
        }
    }
}
echo "error: 7105, Operation Faild";
exit();