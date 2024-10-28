$(function(){
    'use strict'
    const orderNum = $("#invoice-num").val();
    /** */
    $("#show-initial-version-btn").click(function(){
        showCorrespondVerison('initial');
    });
    /** */
    $("#show-final-version-btn").click(function(){
        showCorrespondVerison('final');
    });
    /************************/
    /** */
    $("main.main-order-container").on('click','#hide-invoice-btn',function(){
        $("#additinal-version").slideUp(300);
    });
    /************************/
    /** */
    $("#confirm-order-btn").click(async function(){
        if(await $(this).myConfirm()){
            if(confirmOrder('confirm')){
                setTimeout(function(){
                    location.href = location.origin+ location.pathname;
                }, 1000);
            }
        }
        return ;
    });
    /***************************/
    /** */
    $("#cancel-order-btn").click(async function(){
        if(await $(this).myConfirm()){
            if(confirmOrder('cancel')){
                setTimeout(function(){
                    location.href = location.origin+ location.pathname;
                }, 1000);
            }
        }
        return ;
    });
    /***************************/
    /****************************/
    /*functions area */
    /** */
    function showCorrespondVerison(version = 'initial'){
        if($("#additinal-version").css('display') == 'none'){
            $("#additinal-version").slideDown(300);
            setTimeout(function(){scrollBy({
                top: 1000,
                behavior: 'smooth'
            });
        }, 250);
        }else if($("#additinal-version").length < 1){
            requestCorrespondVerison(version);
        }
    }
    /*****/
    /** AJAX Funcs */
    /** */
    function requestCorrespondVerison(version){
        var  retStatus;
        $.ajax({
            type: "POST",
            url: "../api/showOrderInfo.api.php",
            data: {orderID: orderNum, ver: version },
            dataType: 'html' ,
            success: function (response) {
                if(response.indexOf("error:") < 0){
                    retStatus = true;
                    $('main.main-order-container').append("<hr>").append(response);
                    $("#additinal-version").slideDown(100);
                    setTimeout(function(){scrollBy({
                                    top: 1000,
                                    behavior: 'smooth'
                                });
                            }, 90);
                }else{
                    tempAlert(response,'danger',1500);
                    retStatus = false;
                }
            },
            error: function(){
                tempAlert("error: 7080.  Saving Faild (can't reach the server)","danger");
                retStatus = false;
            }
        });
        return retStatus;
    }
    /**********************************************/
    /** */
    function confirmOrder(oper = 'confirm'){
        var  retStatus;
        $.ajax({
            type: "POST",
            url: "../api/saveOrder.api.php",
            data: { orderID: orderNum , op: oper },
            async: false,
            success: function (response) {
                if(parseInt(response) === 1){
                    tempAlert("operated successfully",'success',1500);
                    retStatus = true;
                }else{
                    tempAlert(response,'danger',1500);
                    retStatus = false;
                }
            },
            error: function(){
                tempAlert("error: 7080.  Saving Faild (can't reach the server)","danger");
                retStatus = false;
            }
        });
        return retStatus;
    }
    /**********************************************/
    /**********************************************/
});