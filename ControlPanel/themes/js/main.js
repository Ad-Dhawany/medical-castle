$(function(){
    'use strict'
    //get page title
    if(typeof pageTitle != 'undefined')
    $('head').children('title').empty().append(pageTitle);
    
    //Hide placeholder on form focus
    $('[placeholder]').focus(function () { 
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () { 
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    // Add Asterisk (Star) on required field
    $('input').each(function () { 
         if ($(this).attr('required') == 'required'){
            $(this).after('<span class="asterisk">*</span>');
         }
    //add show pass icon for each password input field
         if ($(this).attr(`type`) == 'password'){
            $(this).after('<i class="fa fa-eye fs-3 show-pass"></i>');
            $(this).after("<span class='pass-stat-msg'></span>");
         }
    });
/*     $('.show-pass').each(function(){
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
        }else{
            $(toggler).parent().css('width','60px');
            // $(toggler).css("left", sidebarWidth);
            $('body').css("padding-left", sidebarWidth);
            $("a.nav-link span").removeClass("d-sm-inline"); // toggle sidebar texts display to hide text
            $(".sidebar-costum ul.nav").removeClass("align-items-sm-start").parent().removeClass("align-items-sm-start"); // to align items centerd in nav-ul into slidebar
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
            $("[data-target-vis-Id='"+ targetVisId +"']").fadeIn(150).removeClass("is-hidden");
            $(this).children(".switcher-text").children("svg").removeClass("fas fa-caret-down").addClass("fas fa-caret-up");
        }else{
            $("[data-target-vis-Id='"+ targetVisId +"']").fadeOut(150).addClass("is-hidden");
            $(this).children(".switcher-text").children("svg").removeClass("fas fa-caret-up").addClass("fas fa-caret-down");
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
   /** if current url is mange items page call items.js script **
    ** this script adminstrates and organizes all items manage page
    ** functions (AJAX requests, items table contents, items operations, etc..) **
   */
   var currentUrl = location.toString();
   if(currentUrl.indexOf("items/?do=manage") > 0 || currentUrl.indexOf("items/index.php?do=manage") > 0
    || currentUrl.indexOf("items") >= (currentUrl.length - 6) 
    || currentUrl.indexOf("items/index.php") >= (currentUrl.length - 16)){
    $('body').append(`<script src="items.js"></script>`);
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
});