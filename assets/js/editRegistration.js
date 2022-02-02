$(document).ready(function () {
    //$(".registration-header").hide();
    $("#select-registration-avaliator").change(function (e) { 
        e.preventDefault();
        var select = $("#select-registration-avaliator").val();
        if(select == 0) {
            $(".agentes-relacionados .registration-fieldset > span").show();
            $("#select_edit_registrations").editable('setValue', select);
        }else{
            $(".agentes-relacionados .registration-fieldset > span").hide();
            $("#select_edit_registrations").editable('setValue', select);
        }
    });
});
$(function () {
    //ocutando o botão de add avaliador por padrão
    //limitDate sendo false é por que a data de inscrição ja se venveu
    if(MapasCulturais.limitDate) {
        $(".agentes-relacionados .registration-fieldset > span").hide();       
    }else{
        $(".agentes-relacionados .registration-fieldset > span").show();
    }
});