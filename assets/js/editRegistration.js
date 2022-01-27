$(document).ready(function () {
    $(".registration-fieldset").hide();
    $("#select-registration-avaliator").change(function (e) { 
        e.preventDefault();
        var select = $("#select-registration-avaliator").val();
        console.log({select});
        if(select == 0) {
            $(".registration-fieldset").show();
            $("#select_edit_registrations").editable('setValue', select);
        }else{
            $(".registration-fieldset").hide();
            $("#select_edit_registrations").editable('setValue', select);
        }
    });
});