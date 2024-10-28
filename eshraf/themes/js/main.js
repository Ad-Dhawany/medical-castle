var lang = enLang;
$(function(){
    'use strict'
    //get page title
    if(typeof pageTitle != 'undefined'){
        $('head').children('title').empty().append(pageTitle);
    }
    if(typeof($('body').children('[data-page-title]').data('page-title')) != 'undefined'){
        $('head').children('title').empty().append($("[data-page-title]").data('page-title'));
    }
    
    //Hide placeholder on form focus
    /** ver.2.0 fake place holder and label*/
    $('[placeholder]').focus(function () { 
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
        $(this).siblings(".fake-placeholder").addClass("focus");
    }).blur(function () {
        if($(this).val() == ""){
            $(this).siblings(".fake-placeholder").removeClass("focus");
        }
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    /**********/
    

    /**********/
    // Add Asterisk (Star) on required field
    $('input').each(function () { 
         if ($(this).attr('required') == 'required' && !($(this).attr('data-no-asterisk') > -1) ){
            $(this).after('<span class="asterisk dir-abs">*</span>');
         }
    //add show pass icon for each password input field
         if ($(this).attr(`type`) == 'password'){
            $(this).after('<i class="fa fa-eye fs-4 show-pass dir-abs"></i>');
            $(this).after("<span class='pass-stat-msg dir-abs'></span>");
         }
    });
    /** '.dir-abs' stands for direction - absolute.
     * dir indicates to text direction in html element 'rtl' or 'ltr'
     * abs indicates to any element has absolute position witch mean if the dir changes to 'rtl' we need to reflex left & right css props
     */
    $(".dir-abs").each(function(){
        if($(this).parents("[dir]").prop("dir") == 'rtl'){
            if(parseInt($(this).css('left')) < parseInt($(this).css('right'))){
                $(this).css({'right':$(this).css('left'), 'left': 'auto'});
            }else{
                $(this).css({'left':$(this).css('right'), 'right': 'auto'});
            }
        }
    });
    /********************************************/
    /*   $('.show-pass').each(function(){
        $(this).hover(() => {
        $(this).siblings('#pass-field').attr('type', 'text');
        }, () => {
        // out
        $(this).siblings('#pass-field').attr('type', 'password');
        });
        
    }); */
    //use eye icon as toggle to show & hide password
    $('body').on('click','.show-pass',function(){
        $(this).addClass('fa-eye-slash').removeClass('fa-eye');
       if($(this).siblings('#pass-field').attr('type') == 'password'){
       $(this).siblings('#pass-field').attr('type', 'text');
       }else {
        $(this).addClass('fa-eye').removeClass('fa-eye-slash');
       $(this).siblings('#pass-field').attr('type', 'password');}
   });
   /********/
   /* to confirm passwords matching (pass-field & re-pass-field) */
   var firstPassFocus = 0;
   $("[data-pass='repeat']").focusout(()=>{
        isPassMatch();
        firstPassFocus++;
   });
   $("[data-pass='origin']").focusout(()=>{
        if(firstPassFocus > 0){
            isPassMatch();
        }
   });
   $("[data-pass]").keyup(()=>{
        if(firstPassFocus > 0){
            isPassMatch();
        }
   });
   function isPassMatch(){
    if($("[data-pass='repeat']").val() == $("[data-pass='origin']").val()){
        $("[data-pass]").css({'border-color':'lime'})
        .siblings(".asterisk").empty().append("<i class='fa fa-circle-check'></i>").css({'color':'lime', 'top':'0'})
        .siblings(".pass-stat-msg").html("Matched").css('color','lime');

    }else{
        $("[data-pass]").css({'border-color':'red'})
        .siblings(".asterisk").empty().append("<i class='fa fa-circle-xmark'></i>").css({'color':'red', 'top':'0'})
        .siblings(".pass-stat-msg").html("Not Matched").css('color','red');
    }
   }
   /********/
   //confirming message for deleting user
   $('.confirm').click(function(){
        return confirm('Are you sure');
   });
    /** set body min width*/
    $("[data-body-width]").parents("body").css("min-width", $("[data-body-width]").data("body-width"));
    /******/
    /** add class to body */
    $("[data-body-class]").parents("body").addClass($("[data-body-class]").data("body-class"));
    /*********************************************** */
   /*********************************************** */
   var sidebarWidth = $('.sidebar-costum').innerWidth();
   var clicksCounter = 0;
   /*$(".sidebar-toggler").css({'left':sidebarWidth});*/
   function sidebarCanvas(counter = 1 , toggler = ".sidebar-toggler"){
    sidebarWidth = (counter == 0) ? 60 : sidebarWidth;
        if($(toggler).hasClass('is-visible')){
            $(toggler).parent().css('width','16.833333%');
            // $(toggler).css("left", sidebarWidth);
            $('body').css("padding-left", sidebarWidth);
            $("a.nav-link span").addClass("d-sm-inline"); // toggle sidebar texts display to show text
            $(".sidebar-costum ul.nav").addClass("align-items-sm-start").parent().addClass("align-items-sm-start"); // to align items start (left) in nav-ul into slidebar
            $("#side-logo-img-text").show(100, function(){
                $("#side-logo-img-icon").hide(50);
            });
        }else{
            $(toggler).parent().css('width','60px');
            // $(toggler).css("left", sidebarWidth);
            $('body').css("padding-left", sidebarWidth);
            $("a.nav-link span").removeClass("d-sm-inline"); // toggle sidebar texts display to hide text
            $(".sidebar-costum ul.nav").removeClass("align-items-sm-start").parent().removeClass("align-items-sm-start"); // to align items centerd in nav-ul into slidebar
            $("#side-logo-img-text").hide(150, function(){
                $("#side-logo-img-icon").show(50);
            });
        }
        //sideLinkText();
    }
    $('.sidebar-costum .sidebar-toggler').click(()=>{ // side bar toggler btn
        $('.sidebar-toggler').toggleClass('is-visible');
        if(clicksCounter == 0){sidebarWidth = 0.16833 * $('body').width();}
        sidebarCanvas();
        sidebarWidth = $('.sidebar-costum').innerWidth();
        clicksCounter++;
    });
    /*******************************/
    /** */
    var formStorage = {};
    var formKey ;
    $("form[data-session] input").each(function(i, elem){
        formKey = $(elem).parents("form").data("session");
        if(sessionStorage.getItem(formKey) != null){
            formStorage = JSON.parse(sessionStorage.getItem(formKey));
            if(typeof(formStorage[$(elem).attr('name')]) != "undefined"){
                $(elem).val(formStorage[$(elem).attr('name')]);
            }
        }
        
    });
    $("form[data-session] input").blur(function(){
        formKey = $(this).parents("form").data("session");
        if($(this).val() != "" && $(this).val() != null && $(this).attr('type') != "password" && $(this).attr('type') != "submit"){
            formStorage[$(this).attr('name')] = $(this).val();
            sessionStorage.setItem(formKey, JSON.stringify(formStorage));
        }
    });
    /************************************************/
    /** hide and show elements that has 'is-hidden' class by element has 'switcher' data-visibility attr*/
   var targetVisId;
    $("[data-visibility='switcher']").click(function(){
        targetVisId = $(this).attr("data-vis-Id");
        if($("[data-target-vis-Id='"+ targetVisId +"']").hasClass("is-hidden")){
            $("[data-target-vis-Id='"+ targetVisId +"']").slideDown(200).css("display","").removeClass("is-hidden");
            $(this).children("svg").removeClass("fas fa-caret-down").addClass("fas fa-caret-up");
        }else{
            $("[data-target-vis-Id='"+ targetVisId +"']").slideUp(200, function(){ $(this).addClass("is-hidden")});
            $(this).children("svg").removeClass("fas fa-caret-up").addClass("fas fa-caret-down");
        }
        
   });
   /*************************************************/
   /** save the csv mapping settings into the localStorage and set them again when the uploaded file has the same column name */
   var matchProps = {}, csvMatchKey = "prevMatching", tempSelectorId;
   /* Save Mapping every submit process */
   $("#csv-import-btn").click(function(){
       matchProps = {
                itemNum:    $("#itemNum").children("[value='"+ $("#itemNum").val() +"']").text() ,
                itemName:   $("#itemName").children("[value='"+ $("#itemName").val() +"']").text() ,
                Qty:        $("#Qty").children("[value='"+ $("#Qty").val() +"']").text() ,
                payPrice:   $("#payPrice").children("[value='"+ $("#payPrice").val() +"']").text() ,
                avrPayPrice: $("#avrPayPrice").children("[value='"+ $("#avrPayPrice").val() +"']").text() ,
                salePrice:  $("#salePrice").children("[value='"+ $("#salePrice").val() +"']").text() ,
                profitRatio: $("#profitRatio").children("[value='"+ $("#profitRatio").val() +"']").text() ,
                expDate:    $("#expDate").children("[value='"+ $("#expDate").val() +"']").text() 
            };
       localStorage.setItem(csvMatchKey, JSON.stringify(matchProps));
   });
   /** fetch prev mapping if the name of columns is the same in prev csv file */
   function checkAndSetPrevMathing(){
    if(localStorage.getItem(csvMatchKey) != null){
        matchProps = JSON.parse(localStorage.getItem(csvMatchKey));
        $(".csv-match-selector").each(function(){
            tempSelectorId = $(this).attr("id");
            $(this).children("option").each(function(){
                if($(this).text() == matchProps[tempSelectorId]){
                    $(this).prop("selected", true);
                }else{
                    $(this).prop("selected", false);
                }
            });
        });
    }
   }
   /*************************************************/
   /** لتفعيل مكتبة tinyMCE على عناصر ال÷دخال بالسمة data-text-editor */
   $("[data-tiny-editor]").tinymce({
    resize: 'both',
    min_width: 200,
    // width: '20rem',
    max_width: 800,
    min_height: 200,
    max_height: 600,
    menubar: false,
    plugins: [
      'autolink', 'lists', 'link', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'autoresize',
      'insertdatetime', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic backcolor | ' +
      'alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | removeformat'
  });
   /*************************/
   /** */
   $("[data-get-element-id]").click(function(){
    var id = $(this).attr("data-get-element-id");
    var targetAttr = $("[data-put-id-in]").attr("data-put-id-in");
    $("[data-put-id-in]").attr(targetAttr, $("[data-put-id-in]").attr(targetAttr) + id);
   });
   /**************************/
   /** لضبط روابط شريط التنقل العلوي وإعطائه active */
   var pathName = location.pathname.split("/")[3]; /** 3 in localhost but in 2 in realhost */
   var navLinkSelector = `[data-link-name='${pathName}']`;
   $(navLinkSelector).addClass("active").attr("aria-current", 'page');
   /**************************/
   /** if current url is mange items page call items.js script **
    ** this script adminstrates and organizes all items manage page
    ** functions (AJAX requests, items table contents, items operations, etc..) **
   */
   var currentUrl = location.href;
   if(currentUrl.indexOf("/items") > 0){
        /* if(currentUrl.indexOf("?do=manage") > 0){ */
            $('body').append(`<script src="items.js"></script>`);
        /* } */
   }else if(currentUrl.indexOf("/orders") > 15 ){
        if(currentUrl.indexOf("do=edit") > 20){
            $('body').append(`<script type="text/javascript" src="ordersEditor.js"></script>`);
        }else if(currentUrl.indexOf("do=info") > 20){
            $('body').append(`<script type="text/javascript" src="ordersInfo.js"></script>`);
        }
        $('body').append(`<script type="text/javascript" src="orders.js"></script>`);
       /*  $('body').append(`<script type="text/javascript" src="ordersInfo.js"></script>`); */
        /* "tinymce.min.js" */
    }else if(currentUrl.indexOf("posts/?do=view") >= 10 || currentUrl.indexOf("posts/index.php?do=view") >= 10 ){ /** On real host should be indexOf(".ly") */
        $('body').append(`<script type="text/javascript" src="posts.js"></script>`);
    }
   /*************************************************/
   /*************************************************/
   sidebarCanvas();
   checkAndSetPrevMathing();

   $(window).resize(function () {  
       sidebarWidth = $('.sidebar-costum').innerWidth();
       sidebarCanvas();
   });
   /*************************************************/
      /*************************************************/
      $(document).ajaxSend(function(a , b , option){
        if(option.async){
            $("body").css("cursor",'progress');
        }else{
            waitLoadingEffect();
        }
       });
       $(document).ajaxComplete(function(a , b , option){
        if(option.async){
            setTimeout(()=>{$("body").css("cursor","default");},1500); /** setTimeout is just temperory while development to simulate request time */
        }else{
            setTimeout(()=>{waitLoadingEffect(0)},1500); /** setTimeout is just temperory while development to simulate request time */
        }
       });
    });
    /**************************************/
    /** my Functions */
    /** */
    function tempAlert(text, type="warning", duration=4000){
        var alert = `<div class='alert alert-`+ type+ ` alert-dismissible temp-alert'>`+ text+ `
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
        $("<div>",{class:"temp-alert-container"}).html(alert).appendTo('body').slideDown(300);
        setTimeout(function(){
            $(".temp-alert-container").slideUp(500, function(){
                                                    $(this).remove();});
        },duration);
    }
    /**************************************/
    /** */
    function waitLoadingEffect(trigger = 'start', colorClass = 'dark'){
        if(trigger == 'start'){
            var waitHTML = `<div id='loading-main-container' class='simple-overlay position-fixed'>
                                <div class='simple-overlay'></div>
                                <div class='spinners-container'>
                                    <div class="spinner-grow text-${colorClass}" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-${colorClass}" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-${colorClass}" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-${colorClass}" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-${colorClass}" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>`;
            $("body").prepend(waitHTML);
        }else{
            $("#loading-main-container").remove();
        }
    }
    /**************************************/
    /** */
    async function myConfirmModal(MSGconfirm){
        return new Promise((resolve) => {
                var myConfirmModalHTML = `<div id="myConfirm-main-cont" class="myConfirm-overlay-background">
                                            <div class="myConfirm-modal">
                                                <div class="myConfirm-modal-header">
                                                    <button class="btn btn-close mb-1" data-myConfirm-close="close"></button>
                                                </div>
                                                <div class="myConfirm-msg-cont my-1">
                                                    <h5 id="myConfirm-msg" class="col-form-label">${MSGconfirm} ?</h5>
                                                </div>
                                                <hr>
                                                <div class="myConfirm-btn-cont row justify-content-end mt-1">
                                                    <button id="myConfirm-no" class="btn btn-secondary max-w-max-cont col mx-1" data-myConfirm-close="close">${lang['CANCEL']}</button>
                                                    <button id="myConfirm-yes" class="btn btn-primary max-w-max-cont col mx-1" data-myConfirm-close="close">${lang['CONTINUE']}</button>
                                                </div>
                                            </div>
                                        </div>`;
                $('body').append(myConfirmModalHTML);
                // $("#myConfirm-msg").text(MSGconfirm);
                $("#myConfirm-no").click(function(){
                    resolve(false);
                });
                $("#myConfirm-yes").click(function(){
                    resolve(true);
                });
                $('[data-myConfirm-close]').click(function(){
                    myConfirmRemover();
                });
        });
    }
    function myConfirmRemover(time=100){
        setTimeout(function(){
            $("#myConfirm-main-cont").remove();
        },time);
    }
    /***************************************/
    /** plugins area */
    $.fn.myConfirm = async function(){
                        var confirmMSG = (typeof(lang[$(this).data('confirm-msg')]) != 'undefined') ? lang[$(this).data('confirm-msg')] : 'Are you sure' ;
                        
                        return myConfirmModal(confirmMSG);
                    }
    $.fn.printThis = function() {
        /* var pageHade = $("head");
        var elemToPrint=$(this);
        var linksHref = location.toString();
        pageHade.children("link").each(function(i, elem){
            elem.attr("href", linksHref+ $(this).attr("href"));
        })
        newWin= window.open();
        newWin.document.write( "<head>"+ pageHade.html()+ "</head><body>"+ elemToPrint.prop("outerHTML")+ "</body>");
        newWin.print();
        newWin.close(); */
        var elemID = "#"+ $(this).attr("data-print-element-id");
        $(elemID).clone().appendTo("#print-this");
        $("body").addClass("printing");
        window.print();
        $("body").removeClass("printing");
        $("#print-this").empty();
    }