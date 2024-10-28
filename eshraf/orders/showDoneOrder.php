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
                                <p class="fw-bold"><?php echo lang("STATUS") ?> : <span id="" class="text-<?php echo $overlayColor ?>"><?php echo lang(PROCSTATUS[$orderInfo->procStatus]) ?></span><?php echo ($isDoneExternally) ? " <span class='' title='". langTXT("IT IS DON VIA"). "'>(". lang("DON EXT"). ")</span>" : "" ?></p>
                            </div>
                            <div class="col-4 d-flex justify-content-end" data-order-status="<?php echo strtolower(PROCSTATUS[$orderInfo->procStatus]) ?>">
                                <label id="" class="lh-xl mx-2"><?php echo lang("INV NO") ?>. </label>
                                <input type="text" id="invoice-num" class="form-control w-auto h-min-cont text-center point-none" size="2" value="<?php echo $orderInfo->orderID ?>">
                                
                            </div>
                        </div>
                    </section>
                </div>
                <div class="card-body p-0">
                    <section id="invoice-table" class="">
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
                                        <th title="<?php echo lang("ADD DAT") ?>"><?php echo lang("ADD DAT") ?></th>
                                    </tr>
                                </thead>
                                <tbody id="" class="info-items-tbody" data-order-controller="1">
                                    <?php foreach($orderItems as $item){
                                                echo "<tr class=''>
                                                    <td>".$item->itemNum. "</td>
                                                    <td class=''><b>".$item->itemName. "</b></td>
                                                    <td title='".$item->itemPrice. " ". lang("L.D")."'>".number_format($item->getItemPrice($orderInfo->discountRatio),2). "</td>
                                                    <td>".$item->itemQty. "</td>
                                                    <td title='".$item->totalItemPrice. " ". lang("L.D")."'>". number_format($item->getTotalItemPrice($orderInfo->discountRatio),2). "</td>
                                                    <td>".$item->itemExpDate. "</td>
                                                    <td>". date("Y-m-d / h:i a",strtotime($item->insertDate)). "</td>
                                                </tr>";
                                            }
                                    ?>
                                <tfoot class="info-items-tfoot">
                                    <?php $totalFieldsCond = (/* $orderInfo->procStatus > 3 &&  */$orderVersion === 1) ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo ($totalFieldsCond)? lang("TOTAL"). " : " : lang("TOTAL"). " : " ?> </td>
                                        <td <?php echo ($totalFieldsCond)? " title='". lang("DISCOUNT"). " = ". $discountValue. " ". lang("L.D"). "' ><span id='order-real-total'>". number_format($orderInfo->total, 2). "</span> ". lang("L.D") : "> <span id='order-total'>". number_format($orderInfo->total, 2). "</span> ". lang("L.D") ?> </td>
                                        <td><?php echo ($totalFieldsCond)? lang("AFT DIS"). " : " : "" ?></td>
                                        <td <?php echo ($totalFieldsCond)? " title='". lang("DISCOUNT"). " = ". $discountValue. " ". lang("L.D"). "' ><span id='order-real-total-net'>". number_format($orderInfo->totalNet, 2). "</span> ". lang("L.D") : ">" ?> </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </section>
                    <!-- <section class="d-flex justify-content-between">
                        </section> -->
                    <div class="m-2 w-max-cont" data-visibility='switcher' data-vis-Id='order-details'>
                        <div class="switcher-text"><i class="fas fa-caret-down"></i> <?php echo lang("MOR DET") ?></div>
                    </div>
                    <section class="row row-cols-3 row-cols-md-4 row-cols-lg-5 g-0 my-2 is-hidden" data-target-vis-Id='order-details' style="display: none;">
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("ORD NUM") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo $orderInfo->orderID ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("PHA NAM") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo $orderInfo->pharmacy ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("ORD STA") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo lang(PROCSTATUS[$orderInfo->procStatus]) ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("TOTAL"). " <span class='text-secondary'>(". lang("INI VER"). ")</span> " ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo $orderInfo->total ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("TOTAL"). " <span class='text-secondary'>(". lang("FIN VER"). ")</span> " ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->procStatus > 3) ? "<del>". $orderInfo->total. "</del> | ". $orderInfo->totalNet : lang('NO FIN VER') ?>
                            </div>
                        </div>
                        <div class="col spec-cell" title="<?php echo langTXT("PRO ON LAS PUR"). " | ". langTXT("PRO ON AVR PUR")?>">
                            <div class="spec-head">
                                <?php echo lang("PROFIT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->procStatus > 3) ? number_format($orderInfo->lastProfit, 2). " ". lang("L.D"). "  |  ". number_format($orderInfo->avrProfit, 2). " ". lang("L.D") : lang('NO FIN VER') ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("CRE DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->createdDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->createdDate)) : "0000" ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("SAV DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->saveDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->saveDate)) : "0000" ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("LAS EDI DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->lastEditDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->lastEditDate )) : "0000" ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("REC DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->receiveDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->receiveDate )) : "0000" ?>
                            </div>
                        </div>
                     <?php if($orderInfo->procStatus > 3){
                         if(!$isDoneExternally){ ?>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("FIR RES BY") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo (($text = $orderInfo->getOrderPropByJoin('users', ['firstResponseBy', 'userID'], 'username')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$orderInfo->firstResponseBy'><b> $text </b></a>" : lang("NOT RES BEF") ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("FIR RES DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->firstResponseDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->firstResponseDate )) : "0000" ?>
                            </div>
                        </div>
                     <?php if($orderInfo->lastResponseBy > 0){ ?>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("LAS RES BY") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo (($text = $orderInfo->getOrderPropByJoin('users', ['lastResponseBy', 'userID'], 'username')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$orderInfo->lastResponseBy'><b> $text </b></a>" : "" ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("LAS RES DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->lastResponseDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->lastResponseDate )) : "0000" ?>
                            </div>
                        </div>
                     <?php } ?>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("LAS PRO DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->lastProposalDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->lastProposalDate )) : "0000" ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("CON DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->confirmDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->confirmDate )) : "0000" ?>
                            </div>
                        </div>
                     <?php }else{ ?>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("COM MET") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo lang("EXTERNALLY") ?>
                            </div>
                        </div>
                     <?php } ?>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("DON BY") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo (($text = $orderInfo->getOrderPropByJoin('users', ['doneBy', 'userID'], 'username')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$orderInfo->firstResponseBy'><b> $text </b></a>" : lang("NOT DON YET") ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("DON DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->doneDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->doneDate )) : "0000" ?>
                            </div>
                        </div>
                     <?php }
                     if($orderInfo->procStatus < 1){?>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("CAN BY") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo (($text = $orderInfo->getOrderPropByJoin('users', ['cancelBy', 'userID'], 'username')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$orderInfo->firstResponseBy'><b> $text </b></a>" : "" ?>
                            </div>
                        </div>
                        <div class="col spec-cell">
                            <div class="spec-head">
                                <?php echo lang("CAN DAT") ?> : 
                            </div>
                            <div class="spec-body">
                                <?php echo ($orderInfo->cancelDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($orderInfo->cancelDate )) : "0000" ?>
                            </div>
                        </div>
                     <?php }?>
                
                    </section>
                    <section id="" class="my-1 px-1 w-100 justify-content-between flex-wrap <?php echo ($orderInfo->procStatus == 1 || $isDoneExternally) ? 'point-none" style="display: none;' : 'd-flex' ?>">
                     <?php if($orderInfo->procStatus > 3){
                                if($isFinal){?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a id="show-initial-version-btn" class="btn btn-secondary w-100" title="<?php echo langTXT("SHO THE COR INI") ?>"><b><i class="fa fa-arrow-down"></i> <?php echo lang("PRI PRO") ?></b></a>
                                </div>
                            <?php }
                            if($isLastProposal && $isFinal){ ?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a id="show-final-version-btn" class="btn btn-secondary w-100" title="<?php echo langTXT("SHO THE COR FIN") ?>"><b><i class="fa fa-arrow-down"></i> <?php echo lang("COM VER") ?></b></a>
                                </div>
                        <?php }
                            if($isThereCostProposal && !$isLastProposal){?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a id="show-costumer-proposal-btn" class="btn btn-secondary w-100" title="<?php echo langTXT("SHO THE COR COS") ?>"><b><i class="fa fa-arrow-down"></i> <?php echo lang("LAS PRO") ?></b></a>
                                </div>
                        <?php }}
                            if($cancelIF){?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a id="cancel-order-btn" class="btn btn-danger w-100" ><b><?php echo lang("CAN ORD") ?></b></a>
                                </div>
                        <?php }
                            if($orderInfo->procStatus > 2 && $orderInfo->procStatus < 5){
                                if(strtotime($orderInfo->lastResponseDate) > strtotime('0000-00-00')){?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a href='./?do=edit&op=edit&ID=<?php echo $orderInfo->orderID ?>' id="" class="btn btn-success w-100" title='<?php echo langTXT("EDI LAS REP OPT") ?>'><b><?php echo lang("EDI LAS REP") ?></b></a>
                                </div>
                             <?php } ?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a  data-order-id='<?php echo $orderInfo->orderID ?>' data-bs-toggle='modal' data-bs-target='#replyMethod' class="btn btn-success w-100" ><b><?php echo lang("SET REP") ?></b></a>
                                </div>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a id="complete-order-btn" class="btn btn-primary w-100" title='<?php echo langTXT("CHO THI OPT IF") ?>' data-confirm-msg='completed_externally_confirm_msg'><b><?php echo lang("COM EXT") ?></b></a>
                                </div>
                        <?php }
                            if(in_array($orderInfo->procStatus,[3,4]) && (strtotime($orderInfo->lastProposalDate) > strtotime($orderInfo->lastResponseDate) || strtotime($orderInfo->receiveDate) > strtotime($orderInfo->lastResponseDate) )){?>
                                <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                    <a id="accept-proposal-btn" class="btn btn-primary w-100" title="<?php echo langTXT("ACC COS PRO DIR") ?>" ><b><?php echo lang("ACC PRO") ?></b></a>
                                </div>
                        <?php } 
                        if($orderInfo->procStatus == 5){ ?>
                            <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                <a id="complete-order-btn" class="btn btn-primary w-100" ><b><?php echo lang("COMPLETED") ?></b></a>
                            </div>
                        <?php } ?>
                            <div class="col-4 col-md-3 col-lg-2 my-1 px-1 min-w-max-cont">
                                <a id="print-btn" data-print-element-id="invoice-table" class="btn btn-primary w-100"><?php echo lang("PRINT") ?></a>
                            </div>
                    </section>
                </div>
            </div>
        </section>
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
</main>