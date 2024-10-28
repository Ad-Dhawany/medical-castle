<?php
ob_start();
    session_start();
    const allowedExt = ['csv', 'CSV', 'TXT','txt'];
    $visibilityArray = array(-1 => 'trash',
                            0  => 'hidden',
                            1  => 'visibile');
    $pageTitle = 'Items';
    $dir = "../";
    if(!isset($_SESSION['username']) || !isset($_SESSION['userID'])){
        header("location: ../");
        exit();
    }
        include $dir."init.php";
        ?>
        <script>var pageTitle = "<?php echo langs('ITEM') ?>" ;</script>
        <?php
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        if($do == 'manage'){
            //Start Manage page
            $items = stat_item::getItems("visibility > -1 ORDER BY itemID DESC LIMIT 50");
            $allCount = fnc::countItems('*','items','visibility > -1');
            $visCount = fnc::countItems('*','items','visibility = 1');
            $hideCount = fnc::countItems('*','items','visibility = 0');
            $trashCount = fnc::countItems('*','items','visibility = -1');
        ?>
            <main class="main-items-container overflow-hidden p-2">
                <div class="row m-2">
                    <section class="items-manage-header">
                        <h2 class="text-center my-2"><?php echo lang("ITE MAN") ?></h2>
                    </section>
                    <section class="col-10 offset-1 my-3 p-3 form-container-ver1">
                        <div class="me-3">
                            <h6 class="m-0">Add new item manually, or import from external file:</h6>
                            <div class="col-4 offset-8 d-inline-flex justify-content-between mb-2 add-items">
                                <a href="./?do=csvupload" class="btn btn-primary col-6 mx-1"><?php echo langs("IMP ITE") ?></a>
                                <a href="./?do=add" class="btn btn-primary col-6 mx-1"><?php echo lang("NEW ITE") ?></a>
                            </div>
                            <hr>
                        </div>
                        <form action="#" class="item-search">
                            <h5 class="mb-3">Search for a specific item:</h5>
                            <div class="d-flex">
                                <div class="form-group form-group-lg col-5 m-1">
                                    <label class="col-lg-4 control-label"> Item NO. </label>
                                    <div class="input-field col-11">
                                        <input id="search-by-num" type="text" name="" class="form-control search-input" placeholder="">
                                    </div>
                                    <div id="item-num-results" class="item-search-results fs-8" style="display: none;">
                                    <table class="search-results m-0 table table-bordered text-center">
                                        <thead class="text-center">
                                            <tr>
                                                <th><?php echo lang("ITE NO") ?></th>
                                                <th><?php echo lang("ITE NAM") ?></th>
                                                <th><?php echo lang("QUANTITY") ?></th>
                                                <th title="<?php echo lang("LAS PUR PRI") ?>"><?php echo lang("LAS P.P") ?></th>
                                                <th title="<?php echo lang("AVR PUR PRI") ?>"><?php echo lang("AVR P.P") ?></th>
                                                <th><?php echo lang("SAL PRI") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="result-items">
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg col-7 m-1">
                                    <label class="col-lg-4 control-label"> Item Name </label>
                                    <div class="input-field col-11">
                                        <input id="search-by-name" type="text" name="" class="form-control search-input" placeholder="">
                                    </div>
                                    <div id="item-name-results" class="item-search-results fs-8" style="display: none;">
                                    <table class="search-results m-0 table table-bordered text-center">
                                        <thead class="text-center">
                                            <tr>
                                                <th><?php echo lang("ITE NO") ?></th>
                                                <th><?php echo lang("ITE NAM") ?></th>
                                                <th><?php echo lang("QUANTITY") ?></th>
                                                <th title="<?php echo lang("LAS PUR PRI") ?>"><?php echo lang("LAS P.P") ?></th>
                                                <th title="<?php echo lang("AVR PUR PRI") ?>"><?php echo lang("AVR P.P") ?></th>
                                                <th><?php echo lang("SAL PRI") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="result-items">
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                    
                    <section class="items-section">
                        <div class="card">
                            <div class="card-header pb-0">
                                <section class="col-12 d-inline-flex p-2">
                                <!-- <div class="col-6"> -->
                                    <span class="p-2 col-2">
                                        <a href="#" class="btn btn-secondary col-12"><i class="fa fa-arrow-left"></i> Back</a>
                                    </span>
                                    <span class="p-2 col-2">
                                        <a href="#" id="edit-item-btn" class="btn btn-secondary col-12"><i class = 'fa fa-edit'></i> <?php echo lang('EDI ITE') ?></a>
                                    </span>
                                    <span id="hide-item-cont" class="p-2 col-2">
                                        <a href="#" id="hide-item-btn" class="btn btn-secondary col-12"><i class="fa fa-ban"></i> <?php echo lang("HID ITE") ?></a>
                                    </span>
                                    <span id="publish-item-cont" class="p-2 col-2" style="display: none;">
                                        <a href="#" id="publish-item-btn" class="btn btn-info col-12"><i class="fa fa-angles-up"></i> <?php echo lang('PUB ITE') ?></a>
                                    </span>
                                <!-- </div>
                                <div class="col-6"> -->
                                    <span id="trash-item-cont" class="p-2 col-2">    
                                        <a href="#" id="trash-item-btn" class="btn btn-secondary col-12"><i class = 'fa fa-trash'></i> <?php echo lang('TRA ITE') ?></a>
                                    </span>
                                    <span id="delete-item-cont" class="p-2 col-2" style="display: none;">
                                        <a href="#" id="delete-item-btn" class="btn btn-danger col-12"><i class = 'fa fa-close'></i> <?php echo lang('DEL ITE') ?></a>
                                    </span>
                                    <span class="p-2 col-4">
                                        <label for="num-show-items" class="">Rows Number: </label>
                                        <select class="form-select d-inline-block w-auto" id="num-show-items">
                                            <option value="30">30</option>
                                            <option value="50" selected>50</option>
                                            <option value="100">100</option>
                                            <option value="150">150</option>
                                        </select>
                                        <span id='location-descr'>
                                            1:<?php echo count($items). " <span id='of-word'>". lang("OF"). "</span> ". $allCount ?>
                                        </span>
                                    </span>
                                <!-- </div> -->
                                </section>
                                <section class="w-100 px-2">
                                    <left class="lh-xl">
                                        <span title="<?php echo langTXT("ALL ITE EXC") ?>" id="vis-all" class="view-items pointer active point-none"><?php echo lang('ALL'). " ($allCount - $trashCount)" ?></span> | 
                                        <span title="<?php echo langTXT("ITE THA SHO") ?>" id="vis-visibile" class="view-items pointer <?php echo ($visCount === 0)? 'point-none':'' ?>"><?php echo langs('VISIBILE'). " ($visCount)" ?></span> | 
                                        <span title="<?php echo langTXT("ITE THA HID") ?>" id="vis-hidden" class="view-items pointer <?php echo ($hideCount === 0)? 'point-none':'' ?>"><?php echo langs('HIDDEN'). " ($hideCount)" ?></span> | 
                                        <span id="vis-trash" class="view-items pointer <?php echo ($trashCount === 0)? 'point-none':'' ?>"><?php echo lang('TRASH'). "($trashCount)" ?></span>
                                    </left>
                                    <right class="fa-pull-right">
                                    <label for="items-order-by" class="">Order By: </label>
                                        <select class="form-select d-inline-block w-auto" id="items-order-by">
                                            <option value="name">Name</option>
                                            <option value="add" selected>Adding Date</option>
                                            <option value="pay">Last Purchase Price</option>
                                            <option value="sale">Sale Price</option>
                                            <option value="exp">Exp Date</option>
                                        </select>
                                        <span id="ord-asc" class="items-order-dir pointer">ASC</span> | <span id="ord-desc" class="items-order-dir pointer active point-none">DESC</span>
                                    </right>
                                </section>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive items-manage-table fs-7">
                                    <table class="main-table table table-bordered items-table text-center">
                                        <thead class="text-center">
                                            <tr>
                                                <td></td>
                                                <td><?php echo lang("ITE NO") ?></td>
                                                <td><?php echo lang("ITE NAM") ?></td>
                                                <td><?php echo lang("QUANTITY") ?></td>
                                                <td title="<?php echo lang("LAS PUR PRI") ?>"><?php echo lang("LAS P.P") ?></td>
                                                <td title="<?php echo lang("AVR PUR PRI") ?>"><?php echo lang("AVR P.P") ?></td>
                                                <td><?php echo lang("SAL PRI") ?></td>
                                                <td title="<?php echo lang("PRO RAT"). " \n ". lang("LAS PRO RAT"). " (". lang("AVR PRO RAT"). ")" ?>"><?php echo lang("LAS P.R"). " (". lang("AVR P.R"). ")" ?></td>
                                                <td title="<?php echo lang("EXP DAT") ?>"><?php echo lang("EXP DATE") ?></td>
                                            </tr>
                                        </thead>
                                        <tbody id="items-info">
                                        <?php foreach($items as $item){
                                            echo "<tr><td class='select-item-td'><input type='radio' name='selectItem' class='select-item' value='". $item->itemID ."'></td>
                                            <td>". $item->itemNum ."</td>
                                            <td><a class='text-decoration-none link-costum-dark' target='blank' title='More Info.' href='?do=info&ID=". $item->itemID. "'><b>". $item->itemName ."</b></a></td>
                                            <td>". $item->Qty ."</td>
                                            <td>". $item->payPrice ."</td>
                                            <td>". $item->avrPayPrice ."</td>
                                            <td>". $item->salePrice ."</td>
                                            <td>". number_format($item->profitRatio,2) ."% , (". number_format($item->avrProfitRatio,2) ."%)</td>
                                            <td>". $item->expDate1 ."</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <nav class="mt-1" aria-label="items table pagination">
                                    <ul class="pagination justify-content-center" id="items-table-pagination">
                                        <li class="page-item page-nav pointer point-none disabled" id="prev-page">
                                            <a class="page-link"><?php echo lang("PREV") ?></a>
                                        </li>
                                        <li class="page-item pointer page-number active" data-page-num="1" id="first-page">
                                            <a class="page-link">1</a>
                                        </li>
                                        <?php for($i=2 ; $i<5; $i++){
                                            echo (ceil($allCount / 50) >= $i) ? '<li data-page-num="'. $i. '" class="page-item page-number pointer">
                                                                                    <a class="page-link">'. $i.'</a>
                                                                                </li>':"";
                                            }
                                            if(ceil($allCount / 50) > 4){
                                                echo '<li data-page-num="'. ceil($allCount / 50). '" class="page-item page-number pointer" id="last-page">
                                                        <a class="page-link">'. ceil($allCount / 50).'</a>
                                                    </li>';
                                            }
                                        ?>
                                        <li class="page-item pointer page-nav <?php echo (count($items) < 50)? "point-none disabled" : "" ?>" id="next-page">
                                            <a class="page-link"><?php echo lang("NEXT") ?></a>
                                        </li>
                                    </ul>
                                </nav>
                                
                                    

                            </div>
                        </div>
                    </section>
                </div>
            </main>
        <?php
        }elseif($do == 'csvupload'){
            /* Start csvupload page */ ?>
            <div class="csv-upload-container overflow-hidden p-2">
                <h1 class="text-center mb-3"><?php echo lang("IMP ITE INF") ?></h1>
                <div class="row">
                    <div class="col-10 offset-1 p-4 form-container-ver1">
                        <form class = "csv-upload" method="POST" enctype="multipart/form-data" action="./?do=csvmapping">
                            <div class="form-group form-group-lg row mb-3">
                                <div class="input-field">
                                    <label class="form-label mb-1"><?php echo langTXT("CHO AN CSV") ?></label>
                                    <input class ="form-control" type="file" name="file" id="csv-file"/>
                                </div>
                            </div>
                            <div class="importing-setting is-hidden" data-visibility="target" data-target-vis-Id="importing-setting" style="display: none;">
                                <div class="form-group form-group-lg row mb-3">
                                    <label class="control-label"><?php echo langTXT('DEL OR TRA'); ?></label>
                                    <div class="form-check form-check-inline" title="<?php echo langTXT("MOV ALL PRE") ?>">
                                        <input type="checkbox" class="form-check-input" id="trash-or-not" name="trash" value ="trash" checked>
                                        <label class="form-check-label" for="trash-or-not"> <?php echo lang("TRA PRE ITE") ?> </label>
                                    </div>
                                    <div class="form-check form-check-inline" title="<?php echo langTXT("EMP ITE LIS") ?>">
                                        <input type="checkbox" class="form-check-input" id="delete-or-not" name="empty" value ="delete" >
                                        <label class="form-check-label" for="delete-or-not"> <?php echo lang("EMP ITE LIS") ?> </label>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg row mb-2">
                                    <label class="control-label"><?php echo lang('IMP PRO TYP'); ?></label>
                                    <div class="input-field">
                                        <div class="form-check form-check-inline" title="<?php echo langTXT("UPD THE EXI") ?>">
                                            <input class="form-check-input" type="radio" name="process" id="upd-ins" value="upd-ins" checked>
                                            <label class="form-check-label" for="upd-ins"> <?php echo lang("UPD AND INS") ?> </label>
                                        </div>
                                        <div class="form-check form-check-inline" title="<?php echo langTXT("UPD THE MAT") ?>">
                                            <input class="form-check-input" type="radio" name="process" id="update-only" value="update">
                                            <label class="form-check-label" for="update-only"> <?php echo lang("ONL UPD") ?> </label>
                                        </div>
                                        <div class="form-check form-check-inline" title = "<?php echo langTXT("INS NEW ITE") ?>">
                                            <input class="form-check-input" type="radio" name="process" id="insert-only" value="insert">
                                            <label class="form-check-label" for="insert-only"> <?php echo lang("ONL INS") ?> </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg row mb-3">
                                    <label class="control-label"><?php echo langTXT('IF THI FIL'); ?></label>
                                    <div class="form-check form-check-inline" >
                                        <input type="checkbox" class="form-check-input" id="arabic-or-not" name="dateSyntax" value ="1" checked>
                                        <label class="form-check-label" for="arabic-or-not"> <?php echo langTXT("CON DAT FRO") ?> </label>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg row mb-2">
                                    <div class="input-field">
                                        <label class="control-label"> <?php echo lang("DELIMITER") ?> </label>
                                        <input type="text" name="delimiter" value=";" size="2" pattern="{1,3}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-label" data-visibility='switcher' data-vis-Id="importing-setting">
                                <label class="switcher-text pointer"><?php echo lang("ADV SET") ?> <i class="fas fa-caret-down"></i></label>
                            </div>
                            <div class="form-group form-group-lg row ">
                                    <div class="offset-10">
                                        <input type="submit" class="btn btn-primary btn-lg" value= "<?php echo lang('CONTINUE'); ?>">
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
        }elseif($do == 'csvmapping'){
            //Start csvmapping page
            if(!isset($_FILES['file']['name']) || !isset($_POST['delimiter'])){
                $errorMsg = "sssss";
                fnc::redirectHome($errorMsg, 'back',0);
            }
            /*start validate received data */
            $delimiter = (in_array($_POST['delimiter'], [";",";;",",",",,","  "," ",".",".."])) ? $_POST['delimiter'] : fnc::redirectHome("invalid Delimiter",'back',3);
            /* rename file for security purpose. and make sure that file is valid*/
            $fileName = $_FILES['file']['name'];
            $fileNameArray = explode(".", $fileName);
            $extension = end($fileNameArray); /* get extension */
            if(!in_array($extension, allowedExt)){ /* if extension is not in allowed extensions redirect back */
                $errorMsg = "Invalid File";
                fnc::redirectHome($errorMsg, 'back');
            }elseif($_FILES['file']['size'] >  524288){ /* if file size more than 512KB prevent uploading process */
                $errorMsg = "Invalid File Size";
                fnc::redirectHome($errorMsg, 'back');
            }else{ /* if everything ok then done rename, uploading and updating processes */
                $_FILES['file']['name'] = "10". rand(0,1000). "". $_SESSION['username']. "". rand(0, 1000) ."". rand(0,1000). ".". $extension;
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
                                $formWarnings[] = "Invalid Data Form into row:". $row + 1 .". This row has escaped";
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
                    ?>
                    <div class="csv-match-container overflow-hidden p-2">
                        <h1 class="text-center mb-3"><?php echo langing("COL MAT") ?></h1>
                        <section class="row">
                            <div class="col-8 offset-lg-2 p-4 form-container-ver1">
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
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('ITE NAM') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="itemName" id="itemName" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('QUANTITY') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="Qty" id="Qty" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                    <option value="zeros"><?php echo lang("ALL AS ZER") ?></option>
                                                    <option value="ones"><?php echo langs("ALL AS ONE") ?></option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('LAS PUR PRI') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="payPrice" id="payPrice" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                    <option value="-1"><?php echo lang("NOT EXI") ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('AVR PUR PRI') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="avrPayPrice" id="avrPayPrice" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                    <option value="-1"><?php echo lang("NOT EXI") ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('SAL PRI') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="salePrice" id="salePrice" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                    <option value="-1"><?php echo lang("NOT EXI") ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('PRO RAT') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="profitRatio" id="profitRatio" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                    <option value="-1"><?php echo lang("NOT EXI") ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg row mb-2">
                                            <label class="col-lg-4 control-label"><?php echo lang('EXP DAT') ?>: </label>
                                            <div class="input-field col-lg-7 col-sm-10">
                                                <select class="form-select csv-match-selector" name="expDate" id="expDate" required="required">
                                                    <option value="" selected disabled>----------</option>
                                                <?php foreach($items[0] as $index=>$val){
                                                    echo "<option value='$index'>$val</option>";
                                                    }
                                                ?>
                                                    <option value="-1"><?php echo lang("NOT EXI") ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="hidden" style="display: none;">
                                        <input class="hidden" style="display: none;" type="text" name="delimiter" value="<?php echo $delimiter ?>">
                                        <input class="hidden" style="display: none;" type="text" name="process" value="<?php echo $_POST['process'] ?>">
                                        <input class="hidden" style="display: none;" type="text" name="filePath" value="<?php echo $uploadFilePath ?>">
                                    <?php echo (isset($_POST['trash'])) ? "<input class='hidden' style='display: none;' type='text' name='trash' value='". $_POST['trash']. "'>" :"" ;
                                        echo (isset($_POST['empty'])) ? "<input class='hidden' style='display: none;' type='text' name='empty' value='". $_POST['empty']. "'>" :"" ;
                                        echo (isset($_POST['dateSyntax'])) ? "<input class='hidden' style='display: none;' type='text' name='dateSyntax' value='". $_POST['dateSyntax']. "'>" :"" ?>
                                    </section>
                                    <section>
                                        <div class="form-group form-group-lg">
                                            <div class="offset-lg-10 offset-sm-5">
                                                <input type="submit" id="csv-import-btn" class="btn btn-primary btn-lg" value= "<?php echo lang('IMPORT'); ?>">
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
            if(!isset($_POST['filePath']) || !isset($_POST['delimiter']) || !isset($_POST['itemNum']) || !isset($_POST['itemName']) || !isset($_POST['Qty']) 
               || !isset($_POST['payPrice']) || !isset($_POST['avrPayPrice']) || !isset($_POST['salePrice']) || !isset($_POST['profitRatio']) || !isset($_POST['expDate'])){
                $errorMsg = "6335";
                fnc::redirectHome($errorMsg, 'back',0);
            }
            /** Start validate received data */
            if(!is_numeric($_POST['itemNum']) || !is_numeric($_POST['itemName']) || !is_numeric($_POST['payPrice']) 
            || !is_numeric($_POST['avrPayPrice']) || !is_numeric($_POST['salePrice']) || !is_numeric($_POST['profitRatio']) || !is_numeric($_POST['expDate'])){
                $errorMsg = "6332";
                fnc::redirectHome($errorMsg, 'back',0);
            }else{
                $itemNum      = ($_POST['itemNum'] >= 0)      ?   $_POST['itemNum'] : fnc::redirectHome("6335", 'back',0) ;
                $itemName    = ($_POST['itemName'] >= 0)    ?   $_POST['itemName'] : fnc::redirectHome("6336", 'back',0) ;
                $Qty         = ((is_numeric($_POST['Qty']) && $_POST['Qty'] >= 0) || (in_array($_POST['Qty'], ['zeros', 'ones']))) ? $_POST['Qty'] : fnc::redirectHome("6337", 'back',0) ;
                $payPrice    = $_POST['payPrice'] ;
                $avrPayPrice = $_POST['avrPayPrice'] ;
                $salePrice   = $_POST['salePrice'] ;
                $profitRatio = $_POST['profitRatio'];
                $expDate     = $_POST['expDate'] ;
            }
            $method = ($_POST['process'] == "update" || $_POST['process'] == "insert"  || $_POST['process'] == "upd-ins") ? $_POST['process'] : fnc::redirectHome("6328",'back',0);
            $uploadFilePath = (file_exists($_POST['filePath']))? $_POST['filePath'] : fnc::redirectHome("File Lost: The system can't reach the uploaded file. <span style='color:red;'> Please retry again</span>", 'back', 3);
                $CSVfp = fopen($uploadFilePath, "r");
                if ($CSVfp !== FALSE) {
                    $row = 0;
                    $table = "<div class='table-responsive'>
                            <table class='main-table manage text-center table table-bordered'>
                            <thead><tr>";
                    //$items=[];
                    if((isset($_POST['empty']) && $_POST['empty'] == "delete") || (isset($_POST['trash']) && $_POST['trash'] == "trash")){
                        $stmt = $conn->prepare("UPDATE items SET Qty = ?, visibility = ? WHERE 1");
                        $stmt->execute(array(0, -1));
                    }
                    if(isset($_POST['empty']) && $_POST['empty'] == "delete"){
                        $stmt2 = $conn->prepare("DELETE FROM items
                                                WHERE NOT EXISTS (SELECT *
                                                FROM orderContents
                                                WHERE orderContents.itemID = items.itemID)");
                        $stmt2->execute();
                        $stmt3 = $conn->prepare("ALTER TABLE items AUTO_INCREMENT = 1");
                        $stmt3->execute();
                    }
                    /*print "<PRE>";*/
                    $delimiter = $_POST['delimiter'];
                    while (! feof($CSVfp)) {
                        $data = fgetcsv($CSVfp, 1000, $delimiter);
                        if (!empty($data) && count($data) >= 4) {
                            if($row > 0){
                                if(mb_detect_encoding($data[$itemName],null,true) != "UTF-8"){
                                    $data[$itemName] = iconv("windows-1256", "UTF-8", $data[$itemName]);
                                }
                                $item = new item($e, null, ((!empty($data[$itemNum])) ? $data[$itemNum] : 0) , $data[$itemName], ((is_numeric($Qty))? $data[$Qty] : (($Qty == 'zeros')? 0 : 1)), (($payPrice >= 0)? $data[$payPrice] : 0),
                                                (($avrPayPrice >= 0)? $data[$avrPayPrice] : 0), (($salePrice >= 0)? $data[$salePrice] : 0), (($profitRatio >= 0)? $data[$profitRatio] : 0), null, 1,date("Y-m-d h:i"),$_SESSION['userID']);
                                if(!$e){
                                    $formWarnings[] = "Invalid Data Form into row:". $row + 1 .". This row has escaped. Warning No. 388";
                                }else{
                                    $isAccepted = 0; /* if this item successes to reach database then it is gonna equal 1 */
                                    /* Below I am using the item name as a variable ($name), to use this variable as variable name ($$name).
                                    if this item name repeates then the value of this variable with name of item name ($$name) will be bigger than one.
                                    by this way the script can know if this item repeatd in the same file, that's meaning the Qty should be summed with the previous value within the same file */
                                    $name = $data[$itemName];
                                    $$name = (isset($$name))? $$name + 1 : 1;
                                    ($$name > 1) ? ($item->Qty += doubleval($item->getItemPropByProp('Qty', 'itemName', 'itemName'))) :"";
                                    /** expDate Sanitization */
                                    if(isset($data[$expDate])){
                                        $arArray = str_split($arAlphabet);
                                        $data[$expDate] = trim(str_replace($arArray, "", $data[$expDate])); /**تصفية التاريخ من الحروف العربية والمسافات الزائدة*/
                                        if(isset($_POST['dateSyntax']) && $_POST['dateSyntax'] == 1){
                                            $date = DateTime::createFromFormat('d/m/Y h:i:s', $data[$expDate]);
                                            $date2 = DateTime::createFromFormat('d/m/Y h:i', $data[$expDate]);
                                            ($date !== false) ? $data[$expDate] = date_format($date,"Y-m-d"): (($date2 !== false)? $data[$expDate] = date_format($date2,"Y-m-d"): "");
                                        }
                                        $item->visibility = 0; /** set visibility to 0 temperory just to use it into mySql stmt below */
                                        $oldExpDate = $item->getItemPropWhere('expDate1', "AND", ["=", ">="], ['itemName', 'visibility']);
                                        if($oldExpDate == false || strtotime($oldExpDate) < 1640988000){ /** 1640988000 is 2022-01-01 in unix time sytem */
                                            $item->expDate1 = $data[$expDate] ;
                                        }else{
                                            $item->expDate2 = $data[$expDate] ;
                                        }
                                        $item->visibility = 1;
                                    }
                                    if($item->isItemExist($prop, "AND", 'itemName')){
                                        if($oldExpDate === false && $method !== 'update' ){ /** if $oldExpDate === false, this means the visibility == -1, So if method = update and the item in trash don't update, Becuase trashed items supposed as deleted items*/
                                            if(!($item->updateItem($e,'itemName','ALL'))){
                                                $formWarnings[] = "Row number: (". $row + 1 .") doesn't updated. It has escaped ";
                                            }
                                            $isAccepted = 1;
                                        }elseif($oldExpDate !== false && $method !== 'insert'){/* elseif (meaning visibility more than -1) if(method update or upd-ins) update the item.*/
                                            if(!($item->updateItem($e,'itemName','ALL'))){
                                                $formWarnings[] = "Row number: (". $row + 1 .") doesn't updated. It has escaped ";
                                            }
                                            $isAccepted = 1;
                                        }
                                    }elseif($method !== 'update'){
                                        if(!($item->insertItem($err, 'ALL'))){
                                            $formWarnings[] = "Row number: (". $row + 1 .") doesn't inserted. It has escaped ";
                                        }
                                        $isAccepted = 1;
                                    }
                                    if($isAccepted == 1){
                                        $table .= "<tr>";
                                        foreach($data as $entry){
                                            $table .= "<td>". $entry. "</td>";
                                        }
                                        $table .= "</tr>";
                                    }
                                }
                                    /*$stmt = $conn->prepare("INSERT INTO aboghrisall (`item_num`,`item_name`, `Qty`, `profit_percentage`, `sales_price`, `purchasing_price`, `purchasing_avr_price`) VALUES (:Inum, :Iname, :IQty, :Iprofit, :Isales, :Ipurch, :IpurchAVR)");
                                    $stmt->execute(array('Inum' => $data[6], 'Iname' => $data[5], 'IQty' => $data[4], 'Iprofit' => $data[3], 'Isales' => $data[2], 'Ipurch' => $data[1], 'IpurchAVR' => $data[0]));
                                    $count  = $stmt->rowCount(); // get numper of rows in $stmt
                                /*  if ($count == 1){
                                        echo "<div class='alert alert-success col-lg-8 offset-lg-2 mb-0 mt-3 text-center'> <h6> The addition has completed</h6> </div><br>";
                                    } */
                            }else{
                                //setlocale(LC_CTYPE, 'cs_CZ');
                                foreach($data as $subject){
                                    $table .= "<th>". $subject. "</th>";
                                }
                                $table .= "</tr><tbody>";
                                if(mb_detect_encoding($table,null,true) != "UTF-8"){
                                    $table = iconv("windows-1256", "UTF-8", $table);
                                }
                            }
                        }else{
                            $formWarnings[] = "Invalid Data Form into row:". $row + 1 .". This row has escaped. 448";
                        }
                        $row++;
                    }
                    $table .="</tbody></table></div>";
                    /*if(mb_detect_encoding(file_get_contents($fileBath),null,true) != "UTF-8"){
                        $table = iconv("windows-1256", "UTF-8", $table);
                    }*/
                $warningsCount = count($formWarnings);
                echo '<div class="p-1">
                    <div class="row p-4">
                        <div class = "form-container-ver1">
                            <h5 class="mt-2"><i class="fas fa-check-circle" style="color: green;"></i> '. langTXT('THE IMP PRO'). '. <u class="link-dark pointer" data-visibility="switcher" data-vis-Id="imported-table"><span class="fs-6 switcher-text">'. lang('VIE IMP TAB'). ' <i class="fas fa-caret-down"></i></span></u></h5>
                            <p class="ms-4">';
                            echo ($warningsCount > 0) ? lang('THE ARE')." $warningsCount ". langTXT('WAR DUR IMP'). '. <u class="link-dark pointer" data-visibility="switcher" data-vis-Id="warnings-log"><span class="switcher-text">'. lang('VIE WAR LOG'). ' <i class="fas fa-caret-down"></i></u></p>' : langTXT('THE NO WAR'). '.</p>';
                        echo '<div class="col-4 offset-lg-8 offset-sm-6 my-3">
                                <a class="btn btn-primary" href="./?do=manage">'. lang('ITE MAN'). '</a>
                                <a class="btn btn-primary" href="../dashboard/">'. lang('DASHBOARD'). '</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="my-4 p-2 fs-7 is-hidden" data-visibility="target" data-target-vis-Id="imported-table" style="display:none;">
                        <h5 class="">'. langTXT('THE IMP ITE'). '</h5>';
                
                    echo $table ;
                    echo "</div> <hr>";
                    echo "<div class='my-3 p-2 fs-7 is-hidden' data-visibility='target' data-target-vis-Id='warnings-log' style='display:none;'>";
                    if(isset($formWarnings) && count($formWarnings) > 0){
                        foreach($formWarnings as $index=>$warning){
                            echo "<p class='alert alert-warning'>$index :  $warning </p>";
                        }
                    }
                    echo "</div></div>";
                }
                fclose($CSVfp);
            //}
        }elseif($do == 'edit'){
            //Start Edit page
            $itemID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("6623", 'back', 0) ;
            $item = new item($e,$itemID);
            $count = $item->getItemByID(); /* fitch user info by useing ID, return true if the proccess success, return false if proccess fail */
            if ($count){?>
            <script>var pageTitle = "<?php echo  lang('ITE EDI'); ?>" ;</script>
                <h2 class="text-center m-3"><?php echo lang('EDIT'). " (<span style='color:darkslategray'>". $item->itemName. "</span>) ". lang('INFORMATION') ?></h2>
                <div class="container">
                    <div class="row offset-lg-1 offset-md-1 col-md-10">
                        <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                        <form action="./?do=update" method="POST" class="form row g-1">
                            <input type="number" name="itemID" style="display: none" value="<?php echo $item->itemID ?>">
                            <div class="form-group form-group-lg row mb-2 " title="<?php echo lang('ITE NAM') ?>">
                                <label class="col-sm-4 col-md-2 control-label"><?php echo lang('ITE NAM') ?></label>
                                <div class="input-field col-sm-12 col-md-10 item-name-edit">
                                    <input type="text" name="itemName" class="form-control" placeholder="<?php echo lang('ITE NAM') ?>" value="<?php echo $item->itemName ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 ">
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('ITE NUM'); ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang('ITE NO') ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8 me-md-1 mb-sm-2 mb-lg-0">
                                        <input type="number" id="" name="itemNum" class="form-control" placeholder="<?php echo lang('ITE NUM'); ?>" value="<?php echo $item->itemNum ?>">
                                    </div>
                                </div>
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('QUANTITY'); ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang('QUANTITY') ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8">
                                        <input type="number" id="" data-pass="" name="Qty" class="form-control" placeholder="<?php echo lang('QUANTITY'); ?>" value="<?php echo $item->Qty ?>" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 ">
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('LAS PUR PRI') ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang('LAS P.P') ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8 me-md-1 mb-sm-2 mb-lg-0">
                                        <input type="number" name="payPrice" class="form-control" placeholder="<?php echo lang('LAS PUR PRI') ?>" value="<?php echo $item->payPrice ?>" required="required">
                                    </div>
                                </div>
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang("AVR PUR PRI") ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang("AVR P.P") ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8">
                                        <input type="number" name="avrPayPrice" class="form-control" placeholder="<?php echo lang("AVR PUR PRI") ?>" value="<?php echo $item->avrPayPrice ?>" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 ">
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('SAL PRI') ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang('SAL PRI') ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8 me-md-1 mb-sm-2 mb-lg-0">
                                        <input type="number" name="salePrice" class="form-control" placeholder="<?php echo lang('SAL PRI') ?>" value="<?php echo $item->salePrice ?>" required="required">
                                    </div>
                                </div>
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('EXP DAT'); ?> 1">
                                    <label class="col-sm-4 control-label"><?php echo lang('EXP DATE'); ?> 1 : </label>
                                    <div class="input-field col-sm-12 col-md-8">
                                        <input type="date" name="expDate1" class="form-control" placeholder="<?php echo lang('EXP DATE'); ?> 1" value="<?php echo $item->expDate1 ?>" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 ">
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('EXP DAT'); ?> 2">
                                    <label class="col-sm-4 control-label"><?php echo lang('EXP DATE'); ?> 2 : </label>
                                    <div class="input-field col-sm-12 col-md-8">
                                        <input type="date" name="expDate2" class="form-control" placeholder="<?php echo lang('EXP DATE'); ?> 2" value="<?php echo $item->expDate2 ?>">
                                    </div>
                                </div>
                                <div class="input-field col-sm-12 col-md-6 d-md-inline-flex" title="<?php echo lang('VISIBILITY') ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang('VISIBILITY') ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8 me-md-1 mb-sm-2 mb-lg-0">
                                        <div class = "form-check-inline">
                                            <input id="vis-vis" type="radio" name="visibility" value="1" <?php echo ($item->visibility == 1)? "checked='checked'" :"" ?>/>
                                            <label for="vis-vis"><?php echo lang('VISIBILE'); ?></label>
                                        </div>
                                        <div class = "form-check-inline">
                                            <input id="vis-hide" type="radio" name="visibility" value="0" <?php echo ($item->visibility == 0)? "checked='checked'" :"" ?>/>
                                            <label for="vis-hide"><?php echo lang('HIDDEN'); ?></label>
                                        </div>
                                    <?php if($item->visibility == -1){
                                        echo '<div class = "form-check-inline">
                                            <input id="vis-trash" type="radio" name="visibility" value="-1" checked="checked" />
                                            <label for="vis-trash" style="color: red">'. lang('TRASH'). '</label>
                                        </div>';} ?>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group form-group-lg row my-2 col-md-11 col-sm-12 justify-content-end">
                            <!-- <label class="col-2"></label> -->
                                <div class="w-max-cont">
                                    <a class="btn btn-secondary btn-lg" href="./"><?php echo lang('BACK'); ?></a>
                                    <input type="submit" class="btn btn-primary btn-lg" value="<?php echo lang('SAVE'); ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <?php
            }
        }elseif($do == 'update'){
            //Start Update methodology
            echo "<div class='container'>";
            //Start Insert methodology
            if(isset($_POST['itemName'], $_POST['itemID']) && !empty($_POST['itemName']) && strlen($_POST['itemName']) > 3 && strlen($_POST['itemName']) < 200
                    && is_numeric($_POST['itemID']) && intval($_POST['itemID']) == $_POST['itemID']){

                //Validation inputs
                $itemID = $_POST['itemID'];
                $itemName = trim(strip_tags($_POST['itemName']));
                $itemNum = (isset($_POST['itemNum']) && is_numeric($_POST['itemNum'])) ? intval($_POST['itemNum']) : 0 ;
                $payPrice = (isset($_POST['payPrice']) && is_numeric($_POST['payPrice'])) ? doubleval($_POST['payPrice']) : 0.001 ;
                $avrPayPrice = (isset($_POST['avrPayPrice']) && is_numeric($_POST['avrPayPrice'])) ? doubleval($_POST['avrPayPrice']) : 0.001 ;
                $salePrice = (isset($_POST['salePrice']) && is_numeric($_POST['salePrice'])) ? doubleval($_POST['salePrice']) : 0 ;
                $Qty = (isset($_POST['Qty']) && is_numeric($_POST['Qty'])) ? doubleval($_POST['Qty']) : 0 ;
                $profitRatio = $salePrice / ($payPrice + 0.000001); /** 0.000001 is very small value, it is just to prevent 'Division by zero' error */
                $expDate1 = (isset($_POST['expDate1']) && strtotime($_POST['expDate1']) !== false)? $_POST['expDate1'] : "0000-00-00" ;
                $expDate2 = (isset($_POST['expDate2']) && strtotime($_POST['expDate2']) !== false)? $_POST['expDate2'] : "0000-00-00" ;
                $visibility = (isset($_POST['visibility']) && in_array($_POST['visibility'], [-1, 0, 1])) ? $_POST['visibility'] : fnc::redirectHome('خطأ: 6735', 'back', 10);
                
                $itemUpdate = new item($e,$itemID,$itemNum,$itemName,$Qty,$payPrice,$avrPayPrice,$salePrice,$profitRatio,null,$visibility,null,null,$expDate1,$expDate2);
                if(!$e){
                    fnc::redirectHome("خطأ: 7742", 'back', 10);
                }
                $itemUpdate->lastEditBy = $_SESSION['userID'];
                $itemUpdate->lastEditDate = date("Y-m-d h:i");
            //confirm that there is no item with the same name
                if($itemUpdate->isThereUniqueProbInAnotherItem($prop, 'itemID', 'itemName') != 0){
                    $errorMsg = "This Item Name ( <span style='color: red'>". $itemUpdate->itemName ."</span> ) is used before.";
                    fnc::redirectHome($errorMsg,'back');
                }
                //insert new user
                $count = $itemUpdate->updateItem($e,'itemID', 'ALL') ; /* get numper of rows in $stmt */
                if ($count){
                    $successMsg = "The updating has completed";
                    fnc::redirectHome($successMsg, '?do=manage', 3, 'success');
                }else{
                    $errorMsg = '<span style="color: red">The updating failed</span> Please try again. خطأ: 7757';
                    fnc::redirectHome($errorMsg, 'back');
                }
            }else{
                $errorMsg ='please fill the required fields with valid values';
                fnc::redirectHome($errorMsg);
            }
            echo "</div>";
        }elseif($do == 'add'){
            //Start add page ?>
            <script>var pageTitle = "<?php echo  lang('ADD ITE'); ?>" ;</script>
                <h1 class="text-center m-3"><?php echo lang('ADD NEW ITE') ?></h1>
                <div class="container">
                    <div class="row offset-lg-1 offset-md-1">
                        <?php if(isset($_GET['error'])){?> <div class="alert alert-warning col-lg-6 col-sm-8 offset-lg-2 p-1 "><h6 class="text-center"><?php echo $_GET['error'] ?> </h6></div> <?php } ?>
                        <form action="./?do=insert" method="POST" data-session="add-item" class="form row g-1">
                            <div class="form-group form-group-lg row mb-2 justify-content-center">
                                <!-- <label class="col-sm-2 control-label"></label> -->
                                <div class="input-field col-sm-12 col-md-10">
                                    <input title="<?php echo lang('ITE NAM') ?>" type="text" name="itemName" class="form-control" placeholder="<?php echo lang('ITE NAM') ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 justify-content-center">
                                <!-- <label class="col-sm-2 control-label"></label> -->
                                <div class="input-field col-sm-12 col-md-5 me-md-1 mb-sm-2 mb-lg-0">
                                    <input title="<?php echo lang('ITE NUM'); ?>" type="number" id="" name="itemNum" class="form-control" placeholder="<?php echo lang('ITE NUM'); ?>">
                                </div>
                                <!-- <label class="col-sm-2 control-label"></label> -->
                                <div class="input-field col-sm-12 col-md-5">
                                    <input title="<?php echo lang('QUANTITY'); ?>" type="number" id="" data-pass="" name="Qty" class="form-control" placeholder="<?php echo lang('QUANTITY'); ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 justify-content-center">
                                <!-- <label class="col-sm-2 control-label"></label> -->
                                <div class="input-field col-sm-12 col-md-5 me-md-1 mb-sm-2 mb-lg-0">
                                    <input title="<?php echo lang('LAS PUR PRI') ?>" type="number" name="payPrice" class="form-control" placeholder="<?php echo lang('LAS PUR PRI') ?>" required="required">
                                </div>
                                <!-- <label class="col-sm-2 control-label"></label> -->
                                <div class="input-field col-sm-12 col-md-5">
                                    <input title="<?php echo lang("AVR PUR PRI") ?>" type="number" name="avrPayPrice" class="form-control" placeholder="<?php echo lang("AVR PUR PRI") ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group form-group-lg row mb-2 justify-content-center">
                                <!-- <label class="col-sm-2 control-label"></label> -->
                                <div class="input-field col-sm-12 col-md-5 me-md-1 mb-sm-2 mb-lg-0">
                                    <input title="<?php echo lang('SAL PRI') ?>" type="number" name="salePrice" class="form-control" placeholder="<?php echo lang('SAL PRI') ?>" required="required">
                                </div>
                                <div class="input-field col-sm-12 col-md-5 d-md-inline-flex">
                                    <label class="col-sm-4 control-label"><?php echo lang('EXP DATE'); ?> : </label>
                                    <div class="input-field col-sm-12 col-md-8">
                                        <input title="<?php echo lang('EXP DAT'); ?>" type="date" name="expDate" class="form-control" placeholder="<?php echo lang('EXP DATE'); ?>" required="required">
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group form-group-lg row my-2 col-md-11 col-sm-12 justify-content-end">
                            <!-- <label class="col-2"></label> -->
                                <div class="w-max-cont">
                                    <input type="submit" class="btn btn-primary btn-lg" value= "+ <?php echo lang('ADD ITE'); ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <?php
        }elseif($do == 'insert'){
            echo "<div class='container'>";
            //Start Insert methodology
            if(isset($_POST['itemName']) && !empty($_POST['itemName']) && strlen($_POST['itemName']) > 3 && strlen($_POST['itemName']) < 200){

                //Validation inputs
                $itemName = trim(strip_tags($_POST['itemName']));
                $itemNum = (isset($_POST['itemNum']) && is_numeric($_POST['itemNum'])) ? intval($_POST['itemNum']) : 0 ;
                $payPrice = (isset($_POST['payPrice']) && is_numeric($_POST['payPrice'])) ? doubleval($_POST['payPrice']) : 0.001 ;
                $avrPayPrice = (isset($_POST['avrPayPrice']) && is_numeric($_POST['avrPayPrice'])) ? doubleval($_POST['avrPayPrice']) : 0.001 ;
                $salePrice = (isset($_POST['salePrice']) && is_numeric($_POST['salePrice'])) ? doubleval($_POST['salePrice']) : 0 ;
                $Qty = (isset($_POST['Qty']) && is_numeric($_POST['Qty'])) ? doubleval($_POST['Qty']) : 0 ;
                $expDate = (isset($_POST['expDate']) && strtotime($_POST['expDate']) !== false)? $_POST['expDate'] : "0000-00-00" ;
                $profitRatio = $salePrice / ($payPrice + 0.000001); /** 0.000001 is very small value, it is just to prevent 'Division by zero' error */
                
                $itemNew = new item($e,null,$itemNum,$itemName,$Qty,$payPrice,$avrPayPrice,$salePrice,$profitRatio,null,1,date("Y-m-d h:i"),$_SESSION['userID'],$expDate);
                if(!$e){
                    fnc::redirectHome("خطأ: 7699", 'back', 2);
                }
                $itemNew->addBy = $_SESSION['userID'];
            //confirm that there is no item with the same name
                if($itemNew->checkItem($err, 'items', 'AND', 'itemName') !== 0){
                    $errorMsg = "This Item Name ( <span style='color: red'>". $itemNew->itemName ."</span> ) is already exist.";
                    fnc::redirectHome($errorMsg,'back');
                }
                //insert new user
                $count = $itemNew->insertItem($e, 'ALL') ; /* get numper of rows in $stmt */
                if ($count){
                    $successMsg = "The addition has completed";
                    fnc::redirectHome($successMsg, '?do=manage', 3, 'success');
                }else{
                    $errorMsg = '<span style="color: red">The Addition failed</span> Please try again. خطأ: 7713';
                    fnc::redirectHome($errorMsg, 'back');
                }
            }else{
                $errorMsg ='please fill the required fields with valid values';
                fnc::redirectHome($errorMsg);
            }
            echo "</div>";
        }elseif($do == 'visibility'){
            //start delete page
            /** prepare vars */
            $opArray = ['hidden', 'trash', 'delete', 'visibile'];
            $ID = (isset($_GET['ID']) && is_numeric($_GET['ID']))? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6868", 'back', 0);
            $op = (isset($_GET['op']) && in_array($_GET['op'], $opArray))? $_GET['op'] : fnc::redirectHome("خطأ: 6869", 'back', 0);
            $item = new item($e, $ID);
            if(!$e){
                fnc::redirectHome("خطأ: 7873", 'back', 0);
            }
            /** operations area */
            if($op == 'delete'){
                if($item->checkItem($err, 'orderContents' , "AND", 'itemID') > 0){ /* make sure that item doesn't exist in any order */
                    $errorMsg = langTXT("SYS CAN DEL") ;
                    fnc::redirectHome($errorMsg, './', 3);
                }else{
                    if($item->deleteItem($err, 'itemID')){
                        $successMsg = langTXT("ITE HAS DEL");
                        fnc::redirectHome($successMsg, './', 3, 'success');
                    }else{
                        fnc::redirectHome("خطأ: 7886", 'back');
                    }
                }
            }else{
                if($op == 'trash'){
                    $lastBy = 'lastTrashBy';
                    $lastDate = 'lastTrashDate';
                    $process = 'Trashing';
                }elseif($op == 'hidden'){
                    $lastBy = 'lastHideBy';
                    $lastDate = 'lastHideDate';
                    $process = 'Hiding';
                }else{
                    $lastBy = 'lastPublishBy';
                    $lastDate = 'lastPublishDate';
                    $process = 'Publishing';
                }
                $visibility = array_search($op, $visibilityArray);
                $item->visibility = $visibility;
                $item->$lastBy = $_SESSION['userID'];
                $item->$lastDate = date("Y-m-d h:i");
                if($item->updateItem($err, 'itemID', 'visibility', $lastBy, $lastDate)){
                    $successMsg = "The ". $process. " process done successfully";
                    fnc::redirectHome($successMsg, 'back', 4, 'success');
                }else{
                    fnc::redirectHome("خطأ: 7896", 'back');
                }
            }
        }elseif($do == 'info'){
            //start more-info page ?>
            <script>var pageTitle = "<?php echo lang('MOR INF') ?>" ;</script>
            <?php
                $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("you can't reach this page imediatly", 'back', 2);
                $item = new item($e,$ID);
                $count = $item->getitemByID();
                if($count){
            ?>
            <h3 class="text-center"><?php echo $item->itemName. " " .lang('INFORMATION') ?></h3>
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
                                        <td><?php echo $item->itemID ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("ITE NAM")?></td>
                                        <td class="fs-8"><b><?php echo ucwords($item->itemName) ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("ITE NUM")?></td>
                                        <td><b><?php echo $item->itemNum ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("QUANTITY")?></td>
                                        <td><b><?php echo $item->Qty ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("LAS PUR PRI")?></td>
                                        <td><b><?php echo $item->payPrice ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("AVR PUR PRI")?></td>
                                        <td><b><?php echo $item->avrPayPrice ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("SAL PRI")?></td>
                                        <td><b><?php echo $item->salePrice ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("PRO RAT")?></td>
                                        <td><b><?php echo number_format($item->profitRatio, 2) ?> %</b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("AVR PRO RAT")?></td>
                                        <td><b><?php echo number_format($item->avrProfitRatio, 2) ?> %</b></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("STATUS")?></td>
                                        <td><b><?php echo lang(strtoupper($visibilityArray[$item->visibility])) ?></b></td>
                                    </tr>
                                    <tr>
                                    <td><?php echo lang("ADD BY")?></td>
                                    <td><b><?php echo (($textAdder = $item->getObjectPropByProp('username',"userID", 'addBy', 'users')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$item->addBy'><b> $textAdder </b></a>" : "" ?></b></td>
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
                                    <td><?php echo lang("ADD DAT")?></td>
                                    <td><?php echo ($item->addDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($item->addDate)) : "0000"; ?></td>
                                </tr>
                                <tr>
                                        <td><?php echo lang("EXP DAT")?> 1</td>
                                        <td><?php echo $item->expDate1 ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo lang("EXP DAT")?> 2</td>
                                        <td><?php echo $item->expDate2 ?></td>
                                    </tr>
                                <tr>
                                    <td><?php echo lang("LAS PUB BY")?></td>
                                    <td><?php echo (($text = $item->getObjectPropByProp('username',"userID", 'lastPublishBy', 'users')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$item->lastPublishBy'><b> $text </b></a>" : $textAdder ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS PUB DAT")?></td>
                                    <td><?php echo ($item->lastPublishDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($item->lastPublishDate)) : "0000"; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS EDI BY")?></td>
                                    <td><?php echo (($text = $item->getObjectPropByProp('username',"userID", 'lastEditBy', 'users')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$item->lastEditBy'><b> $text </b></a>" : "Not Edited Before" ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS EDI DAT")?></td>
                                    <td><?php echo ($item->lastEditDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($item->lastEditDate)) : "0000" ; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS HID BY")?></td>
                                    <td><?php echo (($text = $item->getObjectPropByProp('username',"userID", 'lastHideBy', 'users')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$item->lastHideBy'><b> $text </b></a>" : "Not Blocked Before" ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS HID DAT")?></td>
                                    <td><?php echo ($item->lastHideDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($item->lastHideDate)) : "0000"; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS TRA BY")?></td>
                                    <td><?php echo (($text = $item->getObjectPropByProp('username',"userID", 'lastTrashBy', 'users')) != false) ? "<a class='link-dark text-decoration-none' href='../users/?do=info&ID=$item->lastTrashBy'><b> $text </b></a>" : "Not Trashed Before" ;?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("LAS TRA DAT")?></td>
                                    <td><?php echo ($item->lastTrashDate != "0000-00-00 00:00:00")? date("Y-m-d / h:i a",strtotime($item->lastTrashDate)) : "0000"; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mb-4" >
                    <a class="btn btn-secondary" href = "?do=manage"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK') ?></a>
                    <a class='btn btn-secondary' href='?do=edit&ID=<?php echo $item->itemID ?>'><i class = 'fa fa-edit'></i> <?php echo lang("EDIT")?></a>
                    <?php if ($item->visibility !== 0){ ?>
                        <a class='btn btn-secondary' href='?do=visibility&op=hidden&ID=<?php echo $item->itemID ?>'><i class = 'fa fa-ban'></i> <?php echo lang("HIDE")?></a>
                        <?php } ?>
                    <?php if ($item->visibility < 1){ ?>
                        <a class='btn btn-info' href='?do=visibility&op=visibile&ID=<?php echo $item->itemID ?>'><i class = 'fa fa-angles-up'></i> <?php echo lang("PUBLISH")?></a>
                    <?php } ?>
                    <?php if ($item->visibility > -1){ ?>
                        <a class='btn btn-secondary confirm' href='?do=visibility&op=trash&ID=<?php echo $item->itemID ?>'><i class = 'fa fa-trash'></i> <?php echo lang("TRASH")?></a>
                    <?php } ?>
                    <a class='btn btn-danger confirm' href='?do=visibility&op=delete&ID=<?php echo $item->itemID ?>'><i class = 'fa fa-close'></i> <?php echo lang("DELETE")?></a>
                </div>
            </div>
            <?php
            }else{
                $errorMsg = "Invalid ID";
                fnc::redirectHome($errorMsg, 'back', 0);
            }
        }
    include $dir. $tempsP. "footer.php";
ob_end_flush();