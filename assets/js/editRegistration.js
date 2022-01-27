$(document).ready(function () {
    alert('deu');
    $(".registration-fieldset").hide();
    $("#select-registration-avaliator").change(function (e) { 
        e.preventDefault();
        var select = $("#select-registration-avaliator").val();
        if(select == 0) {
            $(".registration-fieldset").show();
        }else{
            $(".registration-fieldset").hide();
        }
    });
});