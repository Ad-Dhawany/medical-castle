$(function(){

    /* this script adminstrates and organizes all items manage page
    ** functions (AJAX requests, items table contents, items operations, etc...)
   */
    var allItems, itemsKey = "All-Items",visItems=[],hideItems=[],trashItems;
    ajaxItems();
    /** AJAX get items to show them into the items table*/
   
   function ajaxItems(){
        $.ajax({
             /*type: "POST", */
             url: "../api/getitems.api.php",
             dataType: "json",
             success: function (response) {
                 allItems = response;
                 getVisItems();
                 getHideItems();
                 localStorage.setItem(itemsKey,JSON.stringify(response));
             }
         });
        $.ajax({
         type: "POST",
         data: {'vis':'trash'},
         url: "../api/getitems.api.php",
         dataType: "json",
         success: function (response) {
             trashItems = response;
         }
        });
    }
   /**  */

   function getVisItems(){
    $.each(allItems,function(i){
        if(parseInt(allItems[i].visibility) == 1){
            visItems.push(allItems[i]);
        }
    });
   }
   function getHideItems(){
    $.each(allItems,function(i){
        if(allItems[i].visibility == 0){
            hideItems.push(allItems[i]);
        }
    });
   }
   /**********/

   /** function to manage items table options and contents*/
   function setItemsTableContents(){
    var rowsNum = parseInt($("#num-show-items").val()),
        pageNum = parseInt($(".page-number.active").attr("data-page-num")),
        orderBy = $("#items-order-by").val(),
        orderDirection = $(".items-order-dir.active").attr('id') ,
        contentsType = $(".view-items.active").attr('id'),
        prevContents = $("tbody#items-info").html(),
        condArray = (orderDirection == "ord-asc")? [-1, 1] : [1, -1] ;
        
        if(contentsType == "vis-all"){
            $("#delete-item-cont").hide(50);
            $("#publish-item-cont").hide(50);
            $("#trash-item-cont").show(50);
            $("#hide-item-cont").show(50);
            fillContents(allItems);
        }else if(contentsType == "vis-visibile"){
            $("#delete-item-cont").hide(50);
            $("#publish-item-cont").hide(50);
            $("#trash-item-cont").show(50);
            $("#hide-item-cont").show(50);
            fillContents(visItems);
        }else if(contentsType == "vis-hidden"){
            $("#hide-item-cont").hide(50);
            $("#delete-item-cont").hide(50);
            $("#trash-item-cont").show(50);
            $("#publish-item-cont").show(50);
            fillContents(hideItems);
        }else if(contentsType == "vis-trash"){
            $("#trash-item-cont").hide(50);
            $("#hide-item-cont").hide(50);
            $("#delete-item-cont").show(50);
            $("#publish-item-cont").show(50);
            fillContents(trashItems);
        }
        /** */
        if(visItems.length == 0){
            $("#vis-visibile").addClass("point-none");
        }else{
            $("#vis-visibile").removeClass("point-none");
        }
        if(hideItems.length == 0){
            $("#vis-hidden").addClass("point-none");
        }else{
            $("#vis-hidden").removeClass("point-none");
        }
        if(trashItems.length == 0 || trashItems == false){
            $("#vis-trash").addClass("point-none");
        }else{
            $("#vis-trash").removeClass("point-none");
        }
        /** */
        /** */
        function orderShowingItems(itemsArray){
            if(orderBy == "name"){
                itemsArray.sort((a,b) => (a.itemName < b.itemName)? condArray[0] : ((a.itemName > b.itemName)? condArray[1] :  0));
            }else if(orderBy == "add"){
                itemsArray.sort((a,b) => (a.itemID < b.itemID)? condArray[0] : ((a.itemID > b.itemID)? condArray[1] :  0));
            }else if(orderBy == "pay"){
                itemsArray.sort((a,b) => (a.payPrice < b.payPrice)? condArray[0] : ((a.payPrice > b.payPrice)? condArray[1] :  0));
            }else if(orderBy == "sale"){
                itemsArray.sort((a,b) => (a.salePrice < b.salePrice)? condArray[0] : ((a.salePrice > b.salePrice)? condArray[1] :  0));
            }else if(orderBy == "exp"){
                itemsArray.sort((a,b) => (a.expDate1 < b.expDate1)? condArray[0] : ((a.expDate1 > b.expDate1)? condArray[1] :  0));
            }else if(orderBy == "qty"){
                itemsArray.sort((a,b) => (a.Qty < b.Qty)? condArray[0] : ((a.Qty > b.Qty)? condArray[1] :  0));
            }
            return itemsArray;
        }
        /** */
        function fillContents(itemsArray){
            var start = (pageNum - 1) * rowsNum,
                end = pageNum * rowsNum,
                ofWord;
            itemsArray = orderShowingItems(itemsArray);
            /* if(start == 0){ $("#prev-page").addClass("disabled"); } // duplicated into organizePagination() function */
            if(start >= itemsArray.length){ return ;}
            if(end > itemsArray.length){
                end = itemsArray.length;
                $("#next-page").addClass("disabled");
            }
            $("tbody#items-info").empty();
            for(let i=start; i < end; i++){
                $("tbody#items-info").append(`<tr><td class='select-item-td'><input type='radio' name='selectItem' class='select-item' value='`+ itemsArray[i].itemID +`'></td>
                                            <td>`+ itemsArray[i].itemNum + `</td>
                                            <td><a class='text-decoration-none link-costum-dark' target='blank' title='More Info.' href='?do=info&ID=`+ itemsArray[i].itemID+ `'><b>`+ itemsArray[i].itemName + `</b></a></td>
                                            <td>`+ itemsArray[i].Qty + `</td>
                                            <td>`+ itemsArray[i].payPrice + `</td>
                                            <td>`+ itemsArray[i].avrPayPrice + `</td>
                                            <td>`+ itemsArray[i].salePrice + `</td>
                                            <td>`+ parseFloat(itemsArray[i].profitRatio).toFixed(2) +`% , (`+ parseFloat(itemsArray[i].avrProfitRatio).toFixed(2) +`%)</td>
                                            <td>`+ itemsArray[i].expDate1 + `</td></tr>`);
            }
            organizePagination(pageNum, Math.ceil(itemsArray.length / rowsNum)); /** to organize the new pagination nav order */
            /** reset location description (eg. 1:50 of 1730)*/
            ofWord = $("#of-word").text(); /**this for languages purpose. get 'of' text (eg. in arabic will be 'من')  */
            $("#location-descr").empty().append((start + 1) + `:`+ end + ` <span id='of-word'>`+ ofWord+ '</span> '+ itemsArray.length);
        }
        /**********/
   }
   /****/
   /** */
   function organizePagination(activePageNum,lastPageNum){
    var prevNavText = $("#prev-page").children("a").text(),
        nextNavText = $("#next-page").children("a").text(),
        start = (activePageNum > 2)? activePageNum - 1 : 2,
        paginationHTML, middleOptionsCond;
    if(activePageNum >= lastPageNum - 1){
        start = (lastPageNum - 3 > 1)? lastPageNum - 3 : ((lastPageNum - 2 > 1)? lastPageNum - 2 : start);
    }
    middleOptionsCond = (activePageNum == 2)? 3 : ((activePageNum == 1)? 4 : 2);
    paginationHTML = `<li class="page-item page-nav pointer `+ ((activePageNum == 1)? `point-none disabled` : ``) +`" id="prev-page">
                        <a class="page-link">`+ prevNavText +`</a>
                    </li>
                    <li class="page-item pointer page-number `+ ((activePageNum == 1)? `active point-none` : ``) +`" data-page-num="1" id="first-page">
                        <a class="page-link">1</a>
                    </li>`;
    if(lastPageNum > 2){
        for(let j=start ; (j< activePageNum + middleOptionsCond && j < lastPageNum) ; j++){
        paginationHTML += `<li data-page-num="`+ j +`" class="page-item page-number pointer `+ ((activePageNum == j)? `active point-none` : ``) +`">
                                    <a class="page-link">`+ j +`</a>
                                </li>`;
        }
    }
    if(lastPageNum > 1){
        paginationHTML += `<li data-page-num="`+ lastPageNum +`" class="page-item page-number pointer `+ ((activePageNum == lastPageNum)? `active point-none` : ``) +`" id="last-page">
                               <a class="page-link">`+ lastPageNum +`</a>
                            </li>`;
    }
    paginationHTML += `<li class="page-item pointer page-nav `+ ((lastPageNum == activePageNum)? `point-none disabled` : ``)  +`" id="next-page">
                            <a class="page-link">`+ nextNavText +`</a>
                        </li>`;
    $("#items-table-pagination").empty().append(paginationHTML);

   }
   /**************************************/
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
        $(".result-items").append(`<tr><td>`+ matchedItem.itemNum + `</td>
                                <td><a class='text-decoration-none link-costum-dark' target='blank' title='More Info.' href='?do=info&ID=`+ matchedItem.itemID+ `'><b>`+ matchedItem.itemName + `</b></a></td>
                                <td>`+ matchedItem.Qty + `</td>
                                <td>`+ matchedItem.payPrice + `</td>
                                <td>`+ matchedItem.avrPayPrice + `</td>
                                <td>`+ matchedItem.salePrice + `</td></tr>`);
    });
   }
   /**************************************/
   /** events area to trigger 'setItemsTableContents()' function */
   $("#items-order-by").on('change',function(){
        $("#first-page").addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        setItemsTableContents();
   });
   $(".items-order-dir").click(function(){
        $(this).addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        $("#first-page").addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        setItemsTableContents();
    });
   $(".view-items").click(function(){
        $(this).addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        $("#first-page").addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        setItemsTableContents();
    });
   $("#items-table-pagination").on('click', '.page-number', function(){
        var newTbodyHeight, prevTbodyHeight = $("#items-info").height();
        $(this).addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        setItemsTableContents();
        newTbodyHeight = $("#items-info").height();
        if(newTbodyHeight > prevTbodyHeight){
            window.scrollBy(0, (newTbodyHeight - prevTbodyHeight));
        }
    });
    $("#items-table-pagination").on('click', '.page-nav', function(){
        var currentPage = parseInt($(".page-number.active").attr("data-page-num")),
            step = ($(this).attr("id") == "prev-page")? -1 : 1 ,
            nextPage = currentPage + step,
            newTbodyHeight, prevTbodyHeight = $("#items-info").height();
        $("[data-page-num='"+ nextPage +"']").addClass("active").addClass("point-none").siblings().removeClass("active").removeClass("point-none");
        setItemsTableContents();
        newTbodyHeight = $("#items-info").height();
        if(newTbodyHeight > prevTbodyHeight){
            window.scrollBy(0, (newTbodyHeight - prevTbodyHeight));
        }
    });
   $("#num-show-items").children().click(function(){
        setItemsTableContents();
    });
    /**********/
    /*****************************************************/
    /** set Edit Item btn href to the selected item ID*/
    $("#items-info").on('click', 'td.select-item-td input.select-item', function(){
        var hrefEdit    = './?do=edit&ID='+ $(this).val(),
            hrefHide    = './?do=visibility&op=hidden&ID='+ $(this).val(),
            hrefTrash   = './?do=visibility&op=trash&ID='+ $(this).val(),
            hrefDelete  = './?do=visibility&op=delete&ID='+ $(this).val(),
            hrefPublish = './?do=visibility&op=visibile&ID='+ $(this).val();
        $("#edit-item-btn").attr('href', hrefEdit);
        $("#hide-item-btn").attr('href', hrefHide);
        $("#trash-item-btn").attr('href', hrefTrash);
        $("#delete-item-btn").attr('href', hrefDelete);
        $("#publish-item-btn").attr('href', hrefPublish);
    });
    /***********/
    /** */
    $("#search-by-name").on('input', function(){
        if($(this).val().length > 2){
            $("#item-num-results").hide();
            $("#item-name-results").show(50);
            itemSearch($(this).val());
        }else{
            $("#item-name-results").hide(50);
        }
    }).focus(function(){
        if($(this).val().length > 2){
            $("#item-num-results").hide();
            $("#item-name-results").show(50);
            itemSearch($(this).val());
        }else{
            $("#item-name-results").hide(50);
        }
    });

    $("#search-by-num").on('input', function(e){
        if($(this).val().length > 0){
            $("#item-name-results").hide();
            $("#item-num-results").show(50);
            itemSearch($(this).val(), 'num');
        }else{
            $("#item-num-results").hide(50);
        }
    }).focus(function(){
        if($(this).val().length > 0){
            $("#item-name-results").hide();
            $("#item-num-results").show(50);
            itemSearch($(this).val(), 'num');
        }else{
            $("#item-num-results").hide(50);
        }
    });

    $("body").click(function(){
        $(".item-search-results").hide(50);
    });
    $(".item-search-results, .search-input").click(function(e){
        e.stopPropagation();
    });
    /**************/

    /******************************************/
});