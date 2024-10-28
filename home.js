$(function(){
    'use strict'
    var resendTimerStart = 60,
        resendTimer,
        timerText,
        resetEmailCounter = 0,
        timerSelector = $("#resend-timer"),
        resendLinkSelector = $("#resend-link"),
        resetEmailSelector = $("#new-email-submit"),
        alertBodySelector = $("#alert-modal-body") ;
    /** */
    function resendTimerInterval(){
        resendTimer = resendTimerStart;
        const interval = setInterval(function(){
            resendTimer--;
            timerText = (resendTimer < 10) ? "0" + resendTimer : resendTimer;
            timerSelector.text(timerText);
            if(resendTimer == 0){
                resendTimerStart = 90;
                resendLinkSelector.removeClass('point-none');
                clearInterval(interval);
            }
        },1000);
    }
    /***********************/
    /** */
    resendLinkSelector.click(function(){
        if(resendTimer == 0 && timerText == "00"){
            $.ajax({
                type: "post",
                url: "../api/resendverification.api.php",
                // data: "data",
                dataType: "html",
                async: false,
                success: function (response) {
                    alertBodySelector.html(response);
                }
            });
            resendLinkSelector.addClass('point-none');
            resendTimerInterval();
        }
    });
    /***********************/
    /** */
    resetEmailSelector.click(function(){
        if(resetEmailCounter === 0){
            var newEmail = $("#newEmail").val();
            $(".modal").css('cursor', 'wait');
            $.ajax({
                type: "POST",
                url: "../api/resendverification.api.php",
                data: {"newEmail": newEmail},
                dataType: "html",
                async: false,
                success: function (response) {
                    alertBodySelector.html(response);
                    if($("#newEmailAddress").text().indexOf("@") > 1){ /** إذا نجح تغيير عنوان البريد غيره في نموذج إرسال رمز التأكيد */
                        $("#emailAddress").text(`(`+ $("#newEmailAddress").text()+ `)`);
                    }
                    $(".modal").css('cursor', 'auto');
                }
            });
            /* $("#staticResetEmailModal").removeClass('show'); */
            $("#reset-email-link").addClass('point-none').css('color','#333333a5');
        }
        resetEmailCounter++;
    });
    /***************************/
    resendTimerInterval();
});