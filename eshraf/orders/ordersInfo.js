$(function(){
    'use strict'
    const orderNum = $("#invoice-num").val();
    /** */
    $("#show-initial-version-btn").click(function(){
        showCorrespondVerison('initial');
    });
    /** */
    $("#show-costumer-proposal-btn").click(function(){
        showCorrespondVerison('proposal');
    });
    /** */
    $("#show-final-version-btn").click(function(){
        showCorrespondVerison('final');
    });
    /************************/
    /** */
    $("main.main-order-container").on('click','#hide-invoice-btn',function(){
        $(this).parents(".order-items-section").slideUp(300);
    });
    /************************/
    /** */
    $("#complete-order-btn").click(async function(){
        if(await $(this).myConfirm()){
            if(cancConfCompOrder('done')){
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
            if(cancConfCompOrder('cancel')){
                setTimeout(function(){
                    location.href = location.origin+ location.pathname;
                }, 1000);
            }
        }
        return ;
    });
    /***************************/
    /** to print the invoice*/
    $("#print-btn").click(function(){
        $(this).printThis();
    });
    /***************************/
    /****************************/
    /*functions area */
    /** */
    function showCorrespondVerison(version = 'initial'){
        var selector = "#additinal-"+ version+ "-version";
        if($(selector).css('display') == 'none'){
            $(selector).slideDown(300);
            setTimeout(function(){scrollBy({
                top: 400,
                behavior: 'smooth'
            });
        }, 250);
        }else if($(selector).length < 1){
            requestCorrespondVerison(version);
        }
    }
    /*****/
    /** AJAX Funcs */
    /** */
    function requestCorrespondVerison(version){
        var  retStatus, selector = "#additinal-"+ version+ "-version";;
        $.ajax({
            type: "POST",
            url: "../api/showOrderInfo.api.php",
            data: {orderID: orderNum, ver: version },
            dataType: 'html' ,
            success: function (response) {
                if(response.indexOf("error:") < 0){
                    retStatus = true;
                    $('main.main-order-container').append("<hr>").append(response);
                    $(selector).slideDown(100);
                    setTimeout(function(){scrollBy({
                                    top: 400,
                                    behavior: 'smooth'
                                });
                            }, 90);
                }else{
                    tempAlert(response,'danger',1500);
                    retStatus = false;
                }
            },
            error: function(){
                tempAlert("error: 7091.  request Faild (can't reach the server)","danger");
                retStatus = false;
            }
        });
        return retStatus;
    }
    /**********************************************/
    /** */
    /*function cancConfCompOrder(oper = 'done'){
        var  retStatus;
        $.ajax({
            type: "POST",
            url: "../api/saveOrder.api.php",
            data: { orderID: orderNum , op: oper },
            async: false,
            success: function (response) {
                if(parseInt(response) === 1){
                    tempAlert("Operated successfully",'success',1500);
                    retStatus = true;
                }else{
                    tempAlert(response,'danger',1500);
                    retStatus = false;
                }
            },
            error: function(){
                tempAlert("error: 7109.  Saving Faild (can't reach the server)","danger");
                retStatus = false;
            }
        });
        return retStatus;
    }*/
    /**********************************************/
    /**********************************************/
});