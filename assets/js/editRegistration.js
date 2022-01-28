$(document).ready(function () {
    $("#select-registration-avaliator").change(function (e) { 
        e.preventDefault();
        var select = $("#select-registration-avaliator").val();
        console.log({select});
        if(select == 0) {
            $("#add-committee-agent").show();
            $("#select_edit_registrations").editable('setValue', select);
        }else{
            $("#add-committee-agent").hide();
            $("#select_edit_registrations").editable('setValue', select);
        }
    });
});
$(function () {
    $("#add-committee-agent").hide();
});