$(document).ready(function () {

    showORHideEvaluator();

    $("#select-registration-avaliator").change(function (e) {
        e.preventDefault();
        showORHideEvaluator();
    });

    function showORHideEvaluator() {
        var select = $("#select-registration-avaliator").val();
        if (select == 0) {
            $(".agentes-relacionados .registration-fieldset > span").show();
        } else{
            if (MapasCulturais.limitDate) {
                $(".agentes-relacionados .registration-fieldset > span").hide();
            } else {
                $(".agentes-relacionados .registration-fieldset > span").show();
            }
        }
        $("#select_edit_registrations").editable('setValue', select);
    }
});