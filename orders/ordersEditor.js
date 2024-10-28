$(function(){
    'use strict'
    /* this script adminstrates and organizes all edit orders page functions:
    ** (AJAX requests, order table contents, items operations, etc...)
    */
    const orderNum = $("#invoice-num").val();
    const isProposal = ($("input#proposal").val() > 1) ? true : false;
    const itemsKey = "All-Items";
    var allItems = [], discountRatio = 3,
        orderItmes = [], injectMode = 'insert', 
        orderStatus = $("[data-order-status]").attr("data-order-status"),
        divOverlay = "<div id='items-table-overlay' class='simple-overlay'></div>";
    var discPriceTitle  = $("#disc-price-th").attr('title') ;
    var fieldItmeID     = $("#itemID"),
        fieldName       = $("#search-by-name"),
        fieldNum        = $("#search-by-num"),
        fieldPrice      = $("#itemPrice"),
        fieldQuantity   = $("#itemQty"),
        fieldExpDate    = $("#itemExpDate"),
        resultByNum     = $("#item-num-results"),
        resultByName    = $("#item-name-results"),
        tbodyItemsSelector = $("#items-info");
    getOrderContents();
    handleOrderStatus();
    ajaxItems();
   
    /**********/
   /** events area to trigger 'setItemsTableContents()' function */
   $("tbody.result-items").on('click', '.result-item-row', function(){
        var targetID = $(this).attr('data-orderItem-id'),
        targetItem = $.grep(allItems, function(e){
            return (e.itemID == targetID);
        });
        fieldItmeID.val(targetItem[0].itemID);
        fieldNum.val(targetItem[0].itemNum);
        fieldName.val(targetItem[0].itemName);
        fieldPrice.val(targetItem[0].salePrice);
        fieldQuantity.val("1").attr("data-max-quantity",targetItem[0].Qty - 1).select();
        fieldExpDate.empty().append(`<option  value="1" selected>`+ targetItem[0].expDate1 + `</option>`);
        if(targetItem[0].expDate2 != '0000-00-00' && targetItem[0].expDate2 != null){
            fieldExpDate.append(`<option  value="2">`+ targetItem[0].expDate2 + `</option>`);
        }
        $(".search-results-container").slideUp(0);
   });
    /***********/
    /** */
    fieldName.on('input', function(){
        if(allItems.length > 1){
            if($(this).val().length > 1){
                resultByNum.slideUp(50);
                resultByName.slideDown(100);
                itemSearch($(this).val());
            }else{
                resultByName.slideUp(50);
            }
        }
    }).focus(function(){
        if(allItems.length > 1){
            if($(this).val().length > 1){
                resultByNum.slideUp(50);
                resultByName.slideDown(100);
                itemSearch($(this).val());
            }else{
                resultByName.slideUp(50);
            }
        }
    });

    fieldNum.on('input', function(e){
        if(allItems.length > 1){
            if($(this).val().length > 0){
                resultByName.slideUp(50);
                resultByNum.slideDown(100);
                itemSearch($(this).val(), 'num');
            }else{
                resultByNum.slideUp(50);
            }
        }
    }).focus(function(){
        if(allItems.length > 1){
            if($(this).val().length > 0){
                resultByName.slideUp(50);
                resultByNum.slideDown(100);
                itemSearch($(this).val(), 'num');
            }else{
                resultByNum.slideUp(50);
            }
        }
    });

    $("body").click(function(){
        $(".search-results-container").slideUp(100);
    });
    $(".search-results-container, .search-input").click(function(e){
        e.stopPropagation();
    });
    /******************************************/
    /** لتمكين التنقل داخل جدول نتائج البحث عن صنف باستخدام لوحة المفاتيح*/
    /** لتمكين الدخول إلى جدول نتئج البحث عن طريق الضغط على الأسهم من داخل حقل البحث مباشرة*/
    $(".search-input").keydown(function(e){
        if(e.keyCode == 38){/** 38 refers to arrowUp btn */
            $(this).parents(".form-group").find(".result-item-row").last().focus();
        }else if(e.keyCode == 40){/** 40 refers to arrowDown btn */
            $(this).parents(".form-group").find(".result-item-row").first().focus();
        }
    });
    /******/
    /** لتمكين التنقل داخل جدول النتائج واختيار العنصر عن طريق زر ENTER */
    $("tbody.result-items").keydown(function(e){
        e.preventDefault();
        if(e.keyCode == 38){/** 38 refers to arrowUp btn */
            $(".result-item-row:focus").prev().focus();
        }else if(e.keyCode == 40){/** 40 refers to arrowDown btn */
            $(".result-item-row:focus").next().focus();
        }else if(e.keyCode == 13){ /** 13 refers to Enter btn */
            $(".result-item-row:focus").click();
        }
    })
    /******************************************/
    /*لإظهار تنبيه سريع للمستخدم لوضع الهاتف في وضع أفقي في حال دخل الموقع بالجوال*/
    if(parseInt($(window).width()) < 560){
        setTimeout(function(){
            tempAlert("Please put your phone screen on the horizontal view");
        },100)
    }
    /******************************************/
    $("#insert-item-form").submit(function(e){
        e.preventDefault();
        if(injectMode == 'insert' && $("#insert-order-item").css('display') != "none"){
            insUpdtOrderItem();
            getOrderContents();
        }else if(injectMode == 'update' && $("#update-order-item").css('display') != "none"){
            insUpdtOrderItem('update');
            getOrderContents();
        }
        resetInsertItemPanel();
    });
    /** insert Item*/
    // $("#insert-order-item").click(function (e) { 
    //     e.preventDefault();
    //     if(injectMode == 'insert'){
    //         insUpdtOrderItem();
    //         getOrderContents();
    //     }
    //     resetInsertItemPanel();
    // });
    // /******************************************/
    // /** */
    // $("#update-order-item").click(function (e) { 
    //     e.preventDefault();
    //     if(injectMode == 'update'){
    //         insUpdtOrderItem('update');
    //         getOrderContents();
    //     }
    //     resetInsertItemPanel();
    // });
    /** */
    $("#cancel-update-mode").click(function(e){
        e.preventDefault;
        resetInsertItemPanel();
    })
    /******************************************/
    /**give index number of selected item to control btns
     * this index number is the same index into orderItems array
    */
   var trClicks = 0;
    tbodyItemsSelector.on('click','tr.select-item',function(){
        $(this).css("background-color","#aaa").siblings().css("background-color","");
        $("[data-selected-item-i]").attr("data-selected-item-i", $(this).attr('data-item-index'));
        trClicks++;
        if(trClicks >= 2){
            $("#edit-Qty-btn").click();
        }
        setTimeout(()=>{trClicks=0},200);
    });
    /******************************************/
    /** Edit Item */
    $("#edit-Qty-btn").click(function(){
        var i = $(this).attr("data-selected-item-i");
        if(i > 0 || i === "0"){
            fieldItmeID.val(orderItmes[i].itemID);
            fieldNum.val(orderItmes[i].itemNum).addClass("point-none");
            fieldName.val(orderItmes[i].itemName).addClass("point-none");
            fieldPrice.val(orderItmes[i].itemPrice);
            fieldQuantity.val(orderItmes[i].itemQty).select();
            fieldExpDate.empty().append(`<option  value="1" selected>`+ orderItmes[i].itemExpDate + `</option>`).addClass("point-none");
            $("#insert-order-item").hide();
            $(".update-btns").show();
            injectMode = 'update';
        }
    })
    /******************************************/
    /** Delete orderItem*/
    $("#remove-item-btn").click(async function () { 
        var i = $(this).attr("data-selected-item-i");
        if(i > 0 || i === "0"){ /** ...|| i === 0 , because "" >= 0 equal true so when i is an empty string it will consider that the condition is true*/
            if(await $(this).myConfirm()){
                removeOrderItem(i);
                getOrderContents();
            }
        }
        return ;
    });
    /******************************************/
    /** */
    $("#empty-invoice-btn").click(async function(){
        if(orderItmes.length > 0){
            if(await $(this).myConfirm()){
                removeOrderItem("all");
                getOrderContents();
            }
        }
        return ;
    });
    /*******************************************/
    /**#save-invoice-btn doesn't save invoice. it just show save and request modal */
    $("#save-invoice-btn").click(function(){
        checkAndAllowSaveInvoiceBtn();
    });
    /*******/
    /**#save-invoice-only is the real save invoice btn */
    $("#save-invoice-only").click(function(){
        if(orderItmes.length > 0){
            saveOrder();
        }
    });
    /******************/
    /** */
    $("#save-send-request").click(function(){
        if(orderItmes.length > 0){
            saveOrder('submit');
            setTimeout(function(){
                location.href = location.origin+ location.pathname;
            }, 1000);
        }
    });
    /******/
    /** */
    $("#submit-invoice-btn").click(async function(){
        if(orderItmes.length > 0){
            if(await $(this).myConfirm()){
                if(saveOrder('submit')){
                    setTimeout(function(){
                        location.href = location.origin+ location.pathname;
                    }, 1000);
                }
            }
        }
        return ;
    });
    /*************/
    /** */
    $("#edit-invoice-btn").click(function(){
        if(orderItmes.length > 0){
            saveOrder('edit');
        }
    });
    /** */
    $("#submit-proposal-btn").click(async function(){
        if(orderItmes.length > 0){
            if(await $(this).myConfirm()){
                if(saveOrder('subProposal')){
                    setTimeout(function(){
                        location.href = location.origin+ location.pathname;
                    }, 1000);
                }
            }
        }
        return ;
    });
    
    /*********************************************/
    /*********************************************/
    /** functions area*/
    /** */
   function itemSearch(text, method = 'name'){
    var needle, matchedItems ;
    if(method == 'name'){
        needle = text.toLowerCase();
        matchedItems = $.grep(allItems, function(e){
                        return e.itemName.toLowerCase().indexOf(needle) > -1;
                    });
        }else if(method == 'num'){
            matchedItems = $.grep(allItems, function(e){
                needle = ""+ e.itemNum+ "";
                return needle.indexOf(text) > -1;
            });
        }
    $(".result-items").empty();
    $.each(matchedItems , function (index, matchedItem) {
        if(index > 50){
            return ;
        }
        $(".result-items").append(`<tr tabindex="0" data-orderItem-id="`+ matchedItem.itemID+ `" class="pointer result-item-row">
                                <td>`+ matchedItem.itemNum + `</td>
                                <td>`+ matchedItem.itemName + `</td>
                                <td>`+ matchedItem.salePrice.toFixed(2) + ` د.ل</td>
                                <td title = "`+ discPriceTitle+`">`+ (matchedItem.salePrice - (matchedItem.salePrice * (discountRatio / 100))).toFixed(2) + ` د.ل </td></tr>`);
    });
   }
   /**************************************/
    /** لمنع حفظ الفاتورة في حال كانت فارغة */
    function checkAndAllowSaveInvoiceBtn(isClick = true){
        if(orderItmes.length > 0){
            $("#save-invoice-btn").attr('data-bs-toggle','modal');
        }else{
            $("#save-invoice-btn").removeAttr('data-bs-toggle');
            if(isClick){
                tempAlert("Can't save empty invoice");
            }
        }
    }
    /********/
    
    /******************************************/
    /** */
    function fillOrderTable(){
        var totalPrice = 0;
        tbodyItemsSelector.empty().append("<tr></tr>");
        for(let i=0; i<orderItmes.length; i++ ){
            var totalItemPrice = parseFloat(orderItmes[i].itemPrice) * parseFloat(orderItmes[i].itemQty);
            tbodyItemsSelector.append(`<tr class='select-item' data-item-index='${i}'>
                                                <td>${orderItmes[i].itemNum}</td>
                                                <td><b>${orderItmes[i].itemName}</b></td>
                                                <td>${orderItmes[i].itemPrice}</td>
                                                <td>${orderItmes[i].itemQty}</td>
                                                <td>${totalItemPrice}</td>
                                                <td>${orderItmes[i].itemExpDate}</td>
                                            </tr>`);
            totalPrice += totalItemPrice;
        }
        $("#order-total").text(totalPrice.toFixed(2));
    }
    /******************************************/
    /** */
    function resetInsertItemPanel(){
        fieldItmeID.val("");
        fieldNum.val("").removeClass("point-none");
        fieldName.val("").removeClass("point-none").select();
        fieldPrice.val("");
        fieldQuantity.val("");
        fieldExpDate.empty().append(`<option  value="" selected disabled>---- -- --</option>`).removeClass("point-none");
        $("#insert-order-item").show();
        $(".update-btns").hide();
        injectMode = 'insert';
    }
    /******************************************/
    /** */
    function handleOrderStatus(){
        
        switch(orderStatus){
            case 'wai for con':
            case 'open':
                $("#order-items-container").children(".simple-overlay").remove();
                $("[data-order-controller]").removeClass("point-none");
                $("#save-invoice-btn").show().removeClass("point-none");
                $("#edit-invoice-btn").hide().addClass("point-none");
                $("#submit-invoice-btn").hide().addClass("point-none");
                break;
            case 'saved':
                $("#order-items-container").prepend(divOverlay);
                $("#items-table-overlay").css('background-color','#333');
                $("[data-order-controller]").addClass("point-none");
                $("#save-invoice-btn").hide().addClass("point-none");
                $("#edit-invoice-btn").show().removeClass("point-none");
                $("#submit-invoice-btn").show().removeClass("point-none");
                break;
            case 'und pro':
            case 'subProposal':
                $("#order-items-container").prepend(divOverlay);
                $("#items-table-overlay").css('background-color','#0d6efdaa'); /*##0d6efd same --bs-primary and ..aa to reduce opacity*/
                $("[data-order-controller]").addClass("point-none");
                $("#save-invoice-container").hide();
                break;
            default:
                location.href = location.origin+ location.pathname;
                return;
        }
    }
    /******************************************/
    /**AJAXs area */
    /** AJAX get items to show them into the items table*/
    var getitemsTries = 0;
    function ajaxItems(){
        $.ajax({ /** get all visible items by depending on admin setting */
             /*type: "POST", */
             url: "../api/getitems.api.php",
             dataType: "json",
             success: function (response) {
                if(response[0] !== 0){
                    localStorage.setItem(itemsKey,JSON.stringify(response));
                    allItems = response;
                }else if(getitemsTries < 2){
                    getitemsTries++;
                    ajaxItems();
                }else{
                    allItems=JSON.parse(localStorage.getItem(itemsKey));
                    tempAlert("warning: 8389.  Some problems with items details. It's recommended to refresh this page after a few seconds","warning");
                }
             },
             error: function(){
                if(getitemsTries < 2){
                    getitemsTries++;
                    ajaxItems();
                }else{
                    allItems=JSON.parse(localStorage.getItem(itemsKey));
                    tempAlert("error: 7397.  (can't reach the server)","danger");
                }
             }
         });
         /**********/
        /** get default discount ratio */
        $.ajax({
            type: "POST",
            url: "../api/getSetting.api.php",
            data: {'target': 'defaultDiscRatio'},
            dataType: "json",
            success: function (response) {
                discountRatio = response;
            }
        });
    }
    /*************************/
    function getOrderContents(){
        var dataObj = {orderID: orderNum};
        if(isProposal){
            dataObj.proposal = 2;
        }
        $.ajax({
            type: "POST",
            url: "../api/getOrderContents.api.php",
            data: dataObj,
            dataType: "JSON",
            success: function (response) {
                if(response[0] !== 0){ /**if response[0] == 0 means that the order has no items (mostly it is new order)*/
                        orderItmes = response;
                    }else{
                        orderItmes = [];
                        tempAlert(response[1],"warning",1000);
                    }
                    fillOrderTable();
                    checkAndAllowSaveInvoiceBtn(false); /** لفخص جدول المحتويات من بداية الصفحة لأن الصفحة قد تكون صفحة تعديل وليس فاتورة جديدة */
                    $("[data-selected-item-i]").attr("data-selected-item-i", "");
                    resetInsertItemPanel();
                },
                error: function(){
                    tempAlert("error: 7413.  Download invoice content process Faild (can't reach the server)","danger");
                }
        });
    }
    /*******************************************/
    /** */
    function insUpdtOrderItem(type = 'insert'){
        var formData = $("#insert-item-form").serialize()+ "&orderID="+ orderNum+ "&op="+ type ;
        var op = (type == 'update') ? "Updating" : "Addition";
        var requiredCond = true, maxQty = parseFloat(fieldQuantity.attr("data-max-quantity"));
        if(maxQty >= 0){
            if(parseFloat(fieldQuantity.val()) >= maxQty){
                tempAlert("Probably this item's quantity is not available.");
            }
        }
        $("#insert-item-form").find("select, input").each(function(){
            if(($(this).val() == 0 && $(this).attr("name") != "itemNum") || $(this).val() == ""){
                requiredCond = false;
            }
        });
        if(requiredCond){
            $.ajax({
                type: "POST",
                url: "../api/insertOrderItem.api.php",
                data: formData,
                async: false,
                success: function (response) {
                    if(parseInt(response) === 1){
                        tempAlert(op+ " Success.",'success',1500);
                    }else{
                        tempAlert(response,'danger',1500);
                    }
                },
                error: function(){
                    tempAlert("error: 7300.  Inserting Faild (can't reach the server)","danger");
                }
            });
        }else{
            tempAlert("Please fill all required fields");
        }
    }
    /*****************************/
    /** */
    function removeOrderItem(i){
        var dataObj = {};
        if(i > -1 && i !== ''){
            dataObj = { orderID: orderNum,
                        itemID: orderItmes[i].itemID,
                        itemQty: orderItmes[i].itemQty};
        }else{
            dataObj = { orderID: orderNum,
                        empty: i };
        }
        if(isProposal){
            dataObj.proposal = 2;
        }
        $.ajax({
            type: "POST",
            url: "../api/removeOrderItem.api.php",
            data: dataObj,
            async: false,
            success: function (response) {
                if(parseInt(response) === 1){
                    tempAlert("Deleted Successfully.",'success',1500);
                }else{
                    tempAlert(response,'danger',10000);
                }
            },
            error: function(){
                tempAlert("error: 7400.  Deleting Faild (can't reach the server)","danger");
            }
        });
    }
    /**********************************************/
    /** */
    function saveOrder(oper = 'save'){
        var successMsg, expectStatus, retStatus;
        switch(oper){
            case 'save':
                successMsg = "Saved Successfully.";
                expectStatus='saved';
                break;
            case 'submit':
                successMsg = "Submitted Successfully.";
                expectStatus='und pro';
                break;
            case 'edit':
                successMsg = "The invoice ready to editing.";
                expectStatus='open';
                break;
            case 'subProposal':
                successMsg = "Submitted Successfully.";
                expectStatus='subProposal';
                break;
            default:
                return false;
                break;
        }
        $.ajax({
            type: "POST",
            url: "../api/saveOrder.api.php",
            data: { orderID: orderNum , op: oper },
            async: false,
            success: function (response) {
                if(parseInt(response) === 1){
                    tempAlert(successMsg,'success',1500);
                    orderStatus = expectStatus;
                    handleOrderStatus();
                    retStatus = true;
                }else{
                    tempAlert(response,'danger',1500);
                    retStatus = false;
                }
            },
            error: function(){
                tempAlert("error: 7415.  Saving Faild (can't reach the server)","danger");
                retStatus = false;
            }
        });
        return retStatus;
    }
    /**********************************************/
    /**********************************************/
});