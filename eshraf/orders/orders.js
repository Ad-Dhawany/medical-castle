$(function(){
    'use strict'
    const isDiscWords = {after: 'BEFORE', before: 'AFTER'};
    /** */
    $("[data-bs-target='#replyMethod']").click(function(){
        var id = $(this).attr('data-order-id');
        $("[data-reply-link]").each(function(){
            var hrefCont = $(this).attr('href') + id;
            $(this).attr('href', hrefCont);
        })
    });
    /**********************/
    /** */
    $("[data-order-control='complete']").click(async function(){
        if(await $(this).myConfirm()){
            if(cancConfCompOrder('done' , $(this).attr("data-order-num"))){
                setTimeout(function(){
                    location.href = location.origin+ location.pathname;
                }, 1000);
            }
        }
        return ;
    });
    /**********************/
    /** */
    $("[data-order-control='cancel']").click(async function(){
        if(await $(this).myConfirm()){
            if(cancConfCompOrder('cancel', $(this).attr("data-order-num"))){
                setTimeout(function(){
                    location.href = location.origin+ location.pathname;
                }, 1000);
            }
        }
        return ;
    });
    /***************************/
    /** */
    $("[name=isDisc]").click(function(){
        var word = isDiscWords[$(this).val()];
        $("#before-after-total").text(lang[word]);
    });
    /**********************/
    /**********************************************/
});

/** */
function cancConfCompOrder(oper = 'done', tarOrderID = 0){
    var  retStatus;
    if(tarOrderID == 0){
        tarOrderID = orderNum;
    }
    $.ajax({
        type: "POST",
        url: "../api/saveOrder.api.php",
        data: { orderID: tarOrderID , op: oper },
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
            tempAlert("error: 7059.  Saving Faild (can't reach the server)","danger");
            retStatus = false;
        }
    });
    return retStatus;
}
/**********************************************/