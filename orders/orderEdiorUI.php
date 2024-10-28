<main class="main-order-container overflow-hidden p-2" data-page-title="<?php echo lang('PUR INV') ?>" data-body-width="560px" <?php echo $bodyDataAttr ?? "" ?>>
                <div class="row m-2">
                    <section class="order-invoice-page-header">
                        <h2 class="text-center my-2 <?php echo (isset($bodyDataAttr)) ? " text-white" : "" ?>"><?php echo $h2Header ?></h2>
                    </section>
                    <section class="col-10 offset-1 my-3 p-3 form-container-ver1 bg-card-costum">
                        <!-- <div class="me-3">

                            <hr>
                        </div> -->
                        <form action="#" method="POST" class="item-search" id="insert-item-form">
                            <!-- <h5 class="mb-3">Search for a specific item:</h5> -->
                            <input id="itemID" name="itemID" type="hidden" class="point-none" style="display:none;">
                            <div class="d-flex">
                                <div class="form-group form-group-lg col-4 m-1" data-order-controller="1">
                                    <label class="col-lg-4 control-label"> <?php echo lang("ITE NO") ?> </label>
                                    <div class="input-field col-11">
                                        <input id="search-by-num" type="text" name="itemNum" class="form-control search-input" placeholder="">
                                    </div>
                                    <div id="item-num-results" class="search-results-container fs-8" style="display: none;">
                                    <table class="search-results m-0 table table-bordered text-center">
                                        <thead class="text-center">
                                            <tr>
                                                <th><?php echo lang("ITE NO") ?></th>
                                                <th><?php echo lang("ITE NAM") ?></th>
                                                <th><?php echo lang("ITE PRI") ?></th>
                                                <th title="<?php echo langTXT("EXP ITE PRI AFT") ?>"><?php echo lang("DIS PRI") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="result-items items-table-body">
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg col-8 m-1" data-order-controller="1">
                                    <label class="col-lg-4 control-label"> <?php echo lang("ITE NAM") ?> </label>
                                    <div class="input-field col-12 pe-3">
                                        <input id="search-by-name" type="text" name="itemName" class="form-control search-input" placeholder="">
                                    </div>
                                    <div id="item-name-results" class="search-results-container fs-8" style="display: none;">
                                    <table class="search-results m-0 table table-bordered text-center">
                                        <thead class="text-center">
                                            <tr>
                                                <th><?php echo lang("ITE NO") ?></th>
                                                <th><?php echo lang("ITE NAM") ?></th>
                                                <th><?php echo lang("ITE PRI") ?></th>
                                                <th id='disc-price-th' title="<?php echo langTXT("EXP ITE PRI AFT") ?>"><?php echo lang("DIS PRI") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="result-items items-table-body">
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex my-2">
                                <div class="form-group form-group-lg col-md-4 m-1">
                                    <label class="col-lg-4 control-label point-none"> <?php echo lang("ITE PRI") ?> </label>
                                    <div class="input-field col-11">
                                        <input id="itemPrice" type="text" name="itemPrice" class="form-control point-none" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg col-md-4 m-1">
                                    <label class="col-lg-4 control-label"> <?php echo lang("QUANTITY") ?> </label>
                                    <div class="input-field col-11">
                                        <input id="itemQty" type="text" name="itemQty" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg col-md-4 pe-4 m-1">
                                    <label for="itemExpDate" class=""> <?php echo lang("EXP DAT") ?> </label>
                                    <select class="form-select" id="itemExpDate" name="itemExpDate" required>
                                        <option  value="" selected disabled>---- -- --</option>
                                    </select>
                                </div>
                            </div>
                            <?php echo ($isProposalInput) ? '<input id="proposal" name="proposal" type="hidden" class="point-none" style="display:none;" value="2">' : '' ?>
                            <div class="col-lg-4 offset-lg-8 col-md-5 offset-md-7" data-order-controller="1">
                                <botton id="cancel-update-mode" class="btn btn-secondary col-5 col-md-4 mx-1 fw-bold update-btns" style="display: none;"><?php echo lang("CANCEL") ?></botton>
                                <input type="submit" id="update-order-item" class="btn btn-primary col-6 col-md-7 fw-bold update-btns" style="display: none;" value="<?php echo lang("UPD ITE") ?>">
                                <input type="submit" id="insert-order-item" class="btn btn-primary fw-bold col-12 col-md-7 offset-md-5" value="<?php echo lang("INS ITE") ?>">
                            </div>
                        </form>
                    </section>
                    
                    <section class="order-items-section mb-4">
                        <div class="card bg-card-costum">
                            <div class="card-header pb-0">
                                <section class="d-flex flex-wrap">
                                    <div class="col-12 col-lg-6 mx-0 d-inline-flex">
                                        <div class="p-2 col-4">
                                            <a href="./" class="btn btn-secondary col-12"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK') ?></a>
                                        </div>
                                        <div class="p-2 col-4" data-order-controller="1">
                                            <a data-selected-item-i="" id="edit-Qty-btn" class="btn btn-secondary col-12"><i class = 'fa fa-edit'></i> <?php echo lang('EDI QUA') ?></a>
                                        </div>
                                        <div id="trash-item-cont" class="p-2 col-4" data-order-controller="1">    
                                            <a data-selected-item-i="" id="remove-item-btn" class="btn btn-secondary col-12"><i class = 'fa fa-trash'></i> <?php echo lang('REM ITE') ?></a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 mx-0 d-inline-flex" data-order-controller="1">
                                        <div id="hide-item-cont" class="p-2 col-4">
                                            <a id="empty-invoice-btn" class="btn btn-secondary col-12" data-confirm-msg="empty_invoice_confirm_msg"><i class="fa fa-ban"></i> <?php echo lang("EMP INV") ?></a>
                                        </div>
                                        <div class=" col-3"></div>
                                        <div class="col-5 d-flex justify-content-end" data-order-status="<?php echo strtolower(PROCSTATUS[$orderNew->procStatus]) ?>">
                                            <label id="" class="lh-xl mx-2"><?php echo lang("INV NO") ?>. </label>
                                            <input type="text" id="invoice-num" class="form-control w-auto h-min-cont text-center point-none" size="2" value="<?php echo $orderNew->orderID ?>">
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="card-body p-0">
                                <div id="order-items-container" class="table-responsive items-manage-table fs-7">
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
                                        <tbody id="items-info" class="items-table-body" data-order-controller="1">
                                            <tr>
                                                <!-- <td class='select-item-td'><input type='radio' name='selectItem' class='select-item' value='1730'></td>
                                                <td>996</td>
                                                <td><a class='text-decoration-none link-costum-dark' target='blank' title='More Info.' href='?do=info&ID=1730'><b>L-CARNITINE 500MG 30CAP/RITO</b></a></td>
                                                <td>19</td>
                                                <td>25.371</td>
                                                <td>0000-00-00</td>
                                                <td>0000-00-00</td> -->
                                            </tr>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo lang("TOTAL") ?> : </td>
                                                <td><span id='order-total'><?php echo $orderNew->total ?></span> <?php echo lang("L.D") ?> </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="save-invoice-container" class="p-2 col-7 offset-5 col-lg-4 offset-lg-8">
                                    <?php if($isProposalInput){
                                            if($orderNew->procStatus == 4){
                                                echo '<a id="submit-proposal-btn" class="btn btn-primary mb-1 col-6 offset-6"><b>'. lang("SUB PRO"). '</b></a>';
                                            }
                                    }else{?>
                                        <a id="edit-invoice-btn" class="btn btn-success mb-1 col-5 mx-1 <?php echo ($orderNew->procStatus !== 2) ? 'point-none" style="display: none;' : '' ?>"><b><?php echo lang("EDI INV") ?></b></a>
                                        <a id="submit-invoice-btn" class="btn btn-primary mb-1 col-6 <?php echo ($orderNew->procStatus !== 2) ? 'point-none" style="display: none;' : '' ?>" data-confirm-msg="done_request_confirm_msg"><b><?php echo lang("SUB REQ") ?></b></a>
                                        <a id="save-invoice-btn" class="btn btn-primary mb-1 col-6 offset-6 <?php echo ($orderNew->procStatus !== 1) ? 'point-none" style="display: none;' : '' ?>" data-bs-target="#saveInvoiceModal"><b><?php echo lang("SAV INV") ?></b></a>
                                    <?php } ?>
                                    </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>

            <!-- Modal -->
            <div class="modal fade" id="saveInvoiceModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="saveInvoiceModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo lang("RES EMA ADD") ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div> -->
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        <div class="">
                            <h5 class="col-form-label"> <?php echo langTXT("DO YOU WAN SAV") ?> ? </h5>
                        </div>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="save-send-request" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#alertModal" class="btn btn-primary"><?php echo lang("SAV AND REQ") ?></button>
                            <button type="button" id="save-invoice-only" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#alertModal" class="btn btn-primary"><?php echo lang("SAV ONL") ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <!-- <div id="myConfirm-main-cont" class="myConfirm-overlay-background">
                <div class="myConfirm-modal">
                    <div class="myConfirm-modal-header">
                        <button class="btn btn-close mb-1" data-myConfirm-close="close"></button>
                    </div>
                    <div class="myConfirm-msg-cont my-1">
                        <h5 id="myConfirm-msg" class="col-form-label">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil beatae, fugiat libero expedita architecto ?</h5>
                    </div>
                    <hr>
                    <div class="myConfirm-btn-cont col-5 offset-7 mt-1">
                        <button id="myConfirm-no" class="btn btn-secondary col-5 offset-1" data-myConfirm-close="close">لا</button>
                        <button id="myConfirm-yes" class="btn btn-primary col-5" data-myConfirm-close="close">نعم</button>
                    </div>
                </div>
            </div> -->