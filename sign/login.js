$(function(){
    'use strict'
    var muniTowns = [];

    getMuniTowns(); /** first of all we have to get municipals and towns names */
    /** */
    /** */
    $("#select-municipal").on('click','option',function(){
        var index = $(this).val();
        setTownsOptions(index);
    });
    /*******************************/
    /** */
    $("#enter-other-town").click(function(){
        var otherTownName = $("#other-town-text").val();
        $("#select-town").append(`<option value='${otherTownName}' selected="1"> ${otherTownName.toLowerCase()} </option>`);
    });
    /*******************************/
    /********************************************/
    /** functions area */
    /** */
    function setTownsOptions(index){
        $("#select-town").empty();
        if(muniTowns[index].length > 0){
            $.each(muniTowns[index], function (i, value) { 
                $("#select-town").append(`<option value='${value}'> ${value.toLowerCase()} </option>`);
            });
        }else{
            $("#select-town").append(`<option selected disabled> ----- </option>`);
        }
        $("#select-town").append(`<option data-bs-toggle="modal" data-bs-target="#otherTownModal" id="other-town-option" > ${lang['OTHER']} </option>`).prop("disabled", false);
    }
    /******************/
    /* AJAX functions */
    function getMuniTowns(){
        $.ajax({
            type: "POST",
            url: "./api/getMunicipals.api.php",
            dataType: "JSON",
            success: function (response) {
                muniTowns = response;
            }
        });
    }
    /**********************************************/
});