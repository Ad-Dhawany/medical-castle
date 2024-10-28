$(function(){
    'use strict'
    var allPosts=[], tmpMainSrc = location.origin+ "/MedicalCastle/media/uploaded/posts/news_tmp/news.webp" ;
    var postsAttachments, lastViewedPostIndex = 0, reqUrlParamObject = new URLSearchParams(window.location.search);
    if(reqUrlParamObject.has("id")){
        getPost(reqUrlParamObject.get('id'));
    }else{
        getPosts();
    }
    getPostsAttachments();
    setTimeout(function(){
        setPostsHeights();
        setNavIconClass();
    },500);
        /**trigers area */
        /** */
    $(".posts-main-cont").on('click','.thump-cont',function(){
        var pathArray = $(this).children("img").attr('src').split("_mini"),
            src = pathArray[0]+ pathArray[1],
            targetID = "#"+ $(this).parent().attr("data-img-target-id");
        $(targetID).removeClass("animate").attr('src', src /* $(this).children("img").attr('src') */);
            setTimeout(function(){
                $(targetID).addClass("animate");
            },20);
    });
    /*************/
    /** */
    $(".posts-main-cont").on('click',".thumps-nav-btn",function(){
        var scrollOpretor = $(this).attr("data-nav-opr")+ "=200";
        var scrollDirection = ($(window).outerWidth() > 768)? "scrollTop" : "scrollLeft";
        $(this).siblings(".thumps-list-cont").animate({
            [scrollDirection]: scrollOpretor
        });
    });
    /*************/
    /** to show more posts when the user scrolls down to the last shown post */
    $(window).scroll(function(){
        if(lastViewedPostIndex < allPosts.length){
            if(window.scrollY >= $("#post-"+ allPosts[lastViewedPostIndex - 1].postID).position().top){
                fillPostsMenu(lastViewedPostIndex);
            }else return;
        }else return;
    });
    /*************/
    /** */
    $(window).resize(function () {
        setTimeout(function(){
            setPostsHeights();
            setNavIconClass();
        }, 50);
    });
    /*************************/
    /** functions area */
    /** AJAX get items to show them into the items table*/
   /** */
   function getPost(postID = ""){
        $.ajax({
             type: "POST",
             url: "../../api/getPosts.api.php",
             data: {req: "post", id: postID},
             dataType: "json",
             async: false,
             success: function (response) {
                 allPosts[0] = response;
             }
        });
    }
    /***************/
   /** */
   function getPosts(){
        $.ajax({
             type: "POST",
             url: "../../api/getPosts.api.php",
             dataType: "json",
             async: false,
             success: function (response) {
                 allPosts = response;
             }
        });
    }
    /***************/
   /** */
   function getPostsAttachments(){
        $.ajax({
             type: "POST",
             data: {req: "attach"},
             url: "../../api/getPosts.api.php",
             dataType: "json",
             success: function (response) {
                 postsAttachments = response;
                 fillPostsMenu();
             }
        });
    }
    /***************/
    /********************************/
    /** */
    function fillPostsMenu(start=0){
        var end = start + 6, mainSrc, postAttachs, thumpsNavHtml, thumpsListClass,
            thumpsHtml, tabContClass, attachMiniPath, attachPathArr, attachsCount,
            hideOnWideScreenClass, objDate, postDate, postTime, nowDate = Date.now();;
        lastViewedPostIndex = (allPosts.length < end) ? allPosts.length : end;
        for(let i=start; i<lastViewedPostIndex; i++) {
            mainSrc = (allPosts[i].filePath == null)? tmpMainSrc : allPosts[i].filePath;
            postAttachs = $.grep(postsAttachments, function(e){
                return e.postID == allPosts[i].postID;
            });
            attachsCount = postAttachs.length;
            if(attachsCount > 1){
                if(attachsCount > 2){
                    hideOnWideScreenClass = (attachsCount < 4)? "hide-md" : "" ;
                    thumpsNavHtml = `<div data-nav-opr="-" class="thumps-nav-btn thumps-nav-up ${hideOnWideScreenClass}"><i class="fas fa-chevron-up"></i></div>
                                    <div data-nav-opr="+" class="thumps-nav-btn thumps-nav-down ${hideOnWideScreenClass}"><i class="fas fa-chevron-down"></i></div>`;
                    thumpsListClass = "";
                }else{
                    thumpsNavHtml = "";
                    thumpsListClass = "height-below-threshold";
                }
                tabContClass = "col-md-9";
                thumpsHtml = `<div class="col-12 col-md-3 order-2 order-md-1 main-thumps-container">
                                ${thumpsNavHtml}
                                <div class="position-relative thumps-list-cont" data-nav-target="">
                                    <ul class="nav nav-tabs d-flex d-md-block thumps-list ${thumpsListClass}" data-img-target-id="main-img-${allPosts[i].postID}">`;
                $.each(postAttachs, function (ind, attachment) {
                    attachPathArr = attachment.filePath.split(".");
                    attachMiniPath = attachPathArr[0]+ "_mini."+ attachPathArr[1];
                    /* attachMiniPath = attachPathArr[0]+ "."+ attachPathArr[1]+ "."+ attachPathArr[2]+ "_mini."+ attachPathArr[3]; */ //at real host
                    thumpsHtml+= `<li class="thump-cont col-md-12">
                                    <img src="${attachMiniPath}" />
                                </li>`;
                     
                });                    
                thumpsHtml+=       `</ul>
                                </div>
                            </div>`;
            }else{
                tabContClass = "";
                thumpsHtml = "";
            }
            objDate = new Date(allPosts[i].updatedDate);
            postDate = ((nowDate - objDate.getTime()) < 120000)? "" : (((nowDate - objDate.getTime()) < 86400000)? lang['TODAY'] : (((nowDate - objDate.getTime()) < 2 * 86400000) ? lang['YESTERDAY'] : objDate.toISOString().split("T")[0]));
            postTime = ((nowDate - objDate.getTime()) < 120000)? lang['NOW'] : lang['AT']+ " "+ ("0"+ objDate.getHours()).slice(-2)+ ":"+ ("0"+ objDate.getMinutes()).slice(-2);
            $("#posts-main-cont").append(`<div id="post-${allPosts[i].postID}" class="card post-cont">
                                
                                <div class="post-images-cont d-flex flex-wrap">
                                    <div class="tab-content col-12 ${tabContClass} order-1 order-md-2">
                                        <div class="main-img-cont" >
                                            <img id="main-img-${allPosts[i].postID}" class="main-img animate" src="${mainSrc}" />
                                        </div>
                                    </div>
                                    
                                    ${thumpsHtml}
                                    
    
                                </div>
                                <div class="details">
                                    <h3 class="post-title">${allPosts[i].postTitle}</h3>
                                    <div class="post-description">${allPosts[i].postContent}</div>
                                </div>
                                <div class="post-footer">
                                    <div class="d-inline post-controls-cont">
                                        <a href="./?do=delete&ID=${allPosts[i].postID}" class="link link-danger post-control confirm"><i class="fa fa-close"></i> Delete</a>
                                        | 
                                        <a href="./?do=edit&ID=${allPosts[i].postID}" class="link link-success post-control"><i class="fa fa-edit"></i> Edit</a>
                                    </div>
                                    <p class="post-date-time"><span class="post-date">${postDate} </span> <span class="post-time">${postTime}</span></p>
                                </div>
                            </div>`);
        }
    }
    /***************/
    /** */
    function setNavIconClass(){
        var removeUp, setUp, removeDown, setDown;
        removeUp = setUp = removeDown = setDown = "fa-chevron-";
        if($(window).outerWidth() > 768){
            removeUp += "left";
            setUp += "up";
            removeDown += "right";
            setDown += "down";
            $(".hide-md").hide();
        }else{
            removeUp += "up";
            setUp += "left";
            removeDown += "down";
            setDown += "right";
            $(".hide-md").show();
        }
        $(".thumps-nav-up").children().removeClass(removeUp).addClass(setUp);
        $(".thumps-nav-down").children().removeClass(removeDown).addClass(setDown);
    }
    /**************/
    /** */
    function setPostsHeights(){
        if($(window).outerWidth() < 768){
            $(".post-images-cont").each(function(){
                var tabContElement = $(this).children(".tab-content");
                var thumpContElement = $(this).children(".main-thumps-container");
                var tabContHeight = tabContElement.find("img.main-img").height(),
                    thumpsContHeight = thumpContElement.find("img").height() ;
                tabContElement.height(tabContHeight);
                thumpContElement.height(thumpsContHeight);
                thumpsContHeight = (typeof(thumpsContHeight) != "undefined") ? thumpsContHeight : 1;
                $(this).height(tabContHeight + thumpsContHeight);
            });
        }else{
            $(".post-images-cont").height("").children().height("");
        }
    }
    /**************/

});