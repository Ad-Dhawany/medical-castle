const originHref = location.origin;
$(function(){
    'use strict'
    var allNotifications, allNotificationsHTML, unreadsCount;

    getNotifications();

    /** */
    $("#notificationsList").on(`click`,`#view-all-notifs`,function(){
        if($("#all-notifications-cont").children().length < 1){
            $("#all-notifications-cont").append(allNotificationsHTML);
        }
        $("#all-notifications-cont").show(100);
        if(unreadsCount > 0){
            $(".unreads-count").text(unreadsCount);
        }else{
            $(".unreads-count").text("");
        }
    });
    /********/
    /** */
    $("body").click(function(){
        $("#all-notifications-cont").hide(100);
    });
    $("#all-notifications-cont").on('click','#all-notifications', function(e){
        e.stopPropagation();
    });
    $("#notificationsListCont").click(function(e){
        e.stopPropagation();
    });
    /** */
    $("#all-notifications-cont").on('click','#close-all-notifs', function(){
        $("#all-notifications-cont").hide(100);
    });
    /********/
    /** */
    $("[data-notifications-control]").on("mouseenter",".notifications-item",function(){
        if($(this).hasClass("unread")){
            var el = $(this)
            var isHover;
            var i = parseInt($(this).attr("data-note-ID"));
            setTimeout(function(){
                isHover = (el.is(":hover"));
                if(isHover){
                    markNotificationReader(i);
                }
            }, 2000);
        }
    });
    /*********/
    /** */
    $("[data-notifications-control]").on("click",".notifications-item",function(){
        if($(this).hasClass("unread")){
            var i = parseInt($(this).attr("data-note-ID"));
            markNotificationReader(i);
        }
    });
    /*******************/
    /***************************/
    /**functions area*/
    /** */
    function fillNotifList(){
        var notifMenuContHTML, notifTitle, notifClass, 
        dotClass, notifDate,notifTime, objDate;
        var nowDateObj = new Date(), nowDate = Date.now();
        allNotificationsHTML = `<div class="simple-overlay"></div>
                                <ul id="all-notifications" class="all-notifications">
                                    <div class="notifcations-title">
                                        <h5 class="">${lang["ALL_NOT"]} - <span id="all-notif-count">${allNotifications.length}</span> (<span class="unreads-count"></span>)</h5>
                                        <botton id="close-all-notifs" class="btn btn-close"></botton>
                                    </div>
                                    <div id="allNotificationsList">`;
        unreadsCount = 0;
        if(allNotifications.length > 0){
            $("#notificationsList").empty();
            $.each(allNotifications, function (index, notif){
                dotClass = (notif.isRead == 1)? "read" : ((notif.importance > 1) ? "important" : "");
                objDate = new Date(notif.date);
                notifDate = ((nowDate - objDate.getTime()) < 120000)? "" : (((nowDate - objDate.getTime()) < 86400000 && nowDateObj.getDate() == objDate.getDate() )? lang['TODAY'] : (((nowDate - objDate.getTime()) < 2 * 86400000 && (nowDateObj.getDate() - 1) == objDate.getDate()) ? lang['YESTERDAY'] : objDate.toISOString().split("T")[0]));
                notifTime = ((nowDate - objDate.getTime()) < 120000)? lang['NOW'] : lang['AT']+ " "+ ("0"+ objDate.getHours()).slice(-2)+ ":"+ ("0"+ objDate.getMinutes()).slice(-2);
                notifClass = 'unread ';
                if(notif.isRead == 1){
                    notifTitle = lang['READ'];
                    notifClass = ' ';
                }else if(notif.isRead == 0){
                    notifTitle = lang['UNREAD'];
                    notifClass += 'abs';
                    unreadsCount++;
                }else{
                    notifTitle = lang['UNREAD']+ `. (`+ lang['REA_BY_ANO_MEM']+ `)`;
                    unreadsCount++;
                }
                notifMenuContHTML = `<div class="notifications-item ${notifClass}" title="${notifTitle}" data-note-ID="${index}">
                                        <a class="link" href="${notif.link}" target="blank">
                                            <div class="text">
                                                <i class='dot ${dotClass}'>.</i>
                                                <h6 class="notif-msg">${notif.msg}</h6>
                                            </div>
                                            <div class="notif-footer">
                                                <p class="notif-pharmacy" title="${lang['PHA_NAM']}">${notif.pharmacy}</p>
                                                <p class="notif-date-time"><span class="notif-date">${notifDate} </span> <span class="notif-time">${notifTime}</span></p>
                                            </div>
                                        </a>
                                    </div>`;
                if(index < 12){
                    $("#notificationsList").append(notifMenuContHTML);
                }
                allNotificationsHTML += notifMenuContHTML;
            });
            $("#notificationsList").append(`<div class="notifications-item">
                                                <div id="view-all-notifs" class="notif-list-footer">
                                                    <center>View All</center>
                                                </div>
                                            </div>`);
            allNotificationsHTML += `       </div>
                                        </ul>
                                    </div>`;
        }else{
            // $("#notificationsList").append(notifMenuContHTML);
        }
        if(unreadsCount > 0){
            $(".unreads-count").text(unreadsCount);
        }else{
            $(".unreads-count").text("");
        }
    }
    /******************************/
    /**AJAX area*/
    /** Get all user notifications*/
    function getNotifications(){
        $.ajax({
            type: "POST",
            url: originHref+ "/medicalcastle/eshraf/api/getNotifications.api.php",
            /* url: originHref+ "/eshraf/api/getNotifications.api.php", */ // on real host
            data: {req: "all"},
            dataType: "JSON",
            success: function (response) {
                allNotifications = response;
                fillNotifList();
            }
        });
    }
    /**************/
    /** Mark notification as read*/
    function markNotificationReader(i){
        var noteID = allNotifications[i].noteID;
        var noteType = allNotifications[i].noteGroup;
        $.ajax({
            type: "POST",
            url: originHref+ "/medicalcastle/eshraf/api/notificationsControl.api.php",
            /* url: originHref+ "/eshraf/api/getNotifications.api.php", */ // on real host
            data: {set: "read", ID: noteID, type: noteType},
            success: function (response) {
                if(parseInt(response) === 1){
                    $("[data-note-ID='"+ i+ "']").removeClass("unread").attr("title",lang['READ']).find(".dot").addClass("read");
                    unreadsCount-- ;
                    if(unreadsCount > 0){
                        $(".unreads-count").text(unreadsCount);
                    }else{
                        $(".unreads-count").text("");
                    }
                }else{
                    // console.log(response);
                }/*
            },
            error: function(){
                tempAlert("error: 7059.  Saving Faild (can't reach the server)","danger");
                retStatus = false; */
            }
        });
    }
    /**************/
    /******************************/
});
badgeHTML = `<span id="unreads-count-badge" class="unreads-count badge rounded-pill bg-danger"></span>`;