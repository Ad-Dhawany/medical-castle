<main class="main-order-container overflow-hidden p-2" data-page-title="<?php echo lang('ORD INF') ?>" data-body-width="560px" >
    <div class="row m-2">
        <section class="order-invoice-page-header">
            <h2 class="text-center my-2"><?php echo $h2Header ?></h2>
        </section>
        
        <section class="order-items-section mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <section class="d-flex flex-wrap">
                        <div class="col-12 d-inline-flex">
                            <div class="p-2 col-4 col-md-2">
                                <a href="./" class="btn btn-secondary col-12"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK') ?></a>
                            </div>
                            <div class="p-2 col-0 col-md-2">

                            </div>
                            <div class="p-2 col-4 text-center" data-order-controller="1">
                                <p class="fw-bold"><?php echo lang("STATUS") ?> : <span id="" class="text-<?php echo $overlayColor ?>"><?php echo lang(PROCSTATUS[$orderInfo->procStatus]) ?></span><?php echo ($isDoneExternally) ? " <span class='' title='". langTXT("IT IS DON VIA"). "'>(". lang("DON EXT"). ")</span>" : "" ?> <span class="text-warning"><?php echo $createdByText?></span></p>
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
                                                <td title='".$item->totalItemPrice. " ". lang("L.D")."'>".$item->getTotalItemPrice($orderInfo->discountRatio). "</td>
                                                <td>".$item->itemExpDate. "</td>
                                            </tr>";
                                        }
                                ?>
                            <tfoot class="info-items-tfoot">
                                <?php $totalFieldsCond = ($orderInfo->procStatus > 3 && $orderVersion === 1) ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo ($totalFieldsCond)? lang("TOTAL"). " : " : "" ?> </td>
                                    <td <?php echo ($totalFieldsCond)? " title='". lang("DISCOUNT"). " = ". $discountValue. " ". lang("L.D"). "' ><span id='order-real-total'>". $orderInfo->total. "</span> ". lang("L.D") : ">". lang("TOTAL"). " : " ?> </td>
                                    <td><?php echo ($totalFieldsCond)? lang("AFT DIS"). " : " : "<span id='order-total'>". $orderInfo->total. "</span> ". lang("L.D") ?></td>
                                    <td <?php echo ($totalFieldsCond)? " title='". lang("DISCOUNT"). " = ". $discountValue. " ". lang("L.D"). "' ><span id='order-real-total-net'>". $orderInfo->totalNet. "</span> ". lang("L.D") : ">" ?> </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="" class=" mb-1 w-100 <?php echo ($orderInfo->procStatus !== 4) ? 'point-none" style="display: none;' : 'd-inline-flex' ?>">
                        <?php if($orderInfo->procStatus > 3 && !$isDoneExternally){
                            if($isFinal){?>
                            <div class="col-4 col-md-2 p-2">
                                <a id="show-initial-version-btn" class="btn btn-secondary w-100" title="<?php echo langTXT("SHO THE COR INI") ?>"><b><i class="fa fa-arrow-down"></i> <?php echo lang("COS VER") ?></b></a>
                            </div>
                        <?php }else{ ?>
                            <div class="col-4 col-md-2 p-2">
                                <a id="show-final-version-btn" class="btn btn-secondary w-100" title="<?php echo langTXT("SHO THE COR FIN") ?>"><b><i class="fa fa-arrow-down"></i> <?php echo lang("COM VER") ?></b></a>
                            </div>
                        <?php } } ?>
                            <div class="col-4 col-md-2 offset-md-6 p-2">
                                <a href="./?do=edit&prop=1&ID=<?php echo $orderInfo->orderID ?>" id="propose-edit-btn" class="btn btn-success w-100" ><b><?php echo lang("PRO EDI") ?></b></a>
                            </div>
                            <div class="col-4 col-md-2 p-2">
                                <a id="confirm-order-btn" class="btn btn-primary w-100" ><b><?php echo lang("CON REQ") ?></b></a>
                            </div>
                        </div>
                </div>
            </div>
        </section>
    </div>
</main>