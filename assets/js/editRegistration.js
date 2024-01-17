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
        $("#select_edit_registration").editable('setValue', select);
    }
    // var dataPostSendAudit = {
    //     "user_id": 25414,
    //     "object_id": "55544111",
    //     "object_type": "Registratrion",
    //     "action": "Criou",
    //     "message": "Criou um registro",
    //     "key": "field_4455",
    //     "value": "Adionando valores"
    // }
    // jQuery.ajax({
    //     url:'http://localhost:5000/audit',
    //     type: "OPTIONS",
    //     data: dataPostSendAudit,
    //     dataType: "text",
    //     contentType: "text/plain; charset=utf-8",
    //     success: function(res){
    //         console.log({res})
    //     },
    //     error: function(err){
    //         console.log({err})
    //     }
        
    // })
    // .done(function() {
    //     console.log( "second success" );
    //   })
    //   .fail(function() {
    //     console.log( "error" );
    //   })
    //   .always(function() {
    //     console.log( "finished" );
    //   });    
});
