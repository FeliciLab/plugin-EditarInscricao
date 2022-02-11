<?php
use DateTime;

if($this->isEditable()):
//limitDate sendo FALSE, a data de inscrição ainda nao se venceu
$this->jsObject['limitDate'] =  false;
$today = new DateTime('now');
$registrationTo = $this->data->entity->registrationTo;

if($today < $registrationTo):
//trocando o valor da data vencida
$this->jsObject['limitDate'] =  TRUE;

?>

<div id="opportunity-conf-avaliator" class="edit-registration-fieldset">
    <label>Deseja habilitar edição para o Candidato?</label>
    <small ng-click="editbox.open('id-da-caixa', $event)" title="Click para mais informações" class="registration-help" style="cursor: pointer; border-bottom: #c3c3c3;">
        <i class="fa fa-question-circle-o" aria-hidden="true"></i>
    </small>
    <br>
    <select name="" id="select-registration-avaliator" >
        <option value="">--Selecione--</option>
        <option value="1" selected>Sim</option>
        <option value="0">Não</option>
    </select>
    <span class="js-editable" id="select_edit_registrations" data-edit="select_edit_registration" data-original-title="Cor preferida" data-emptytext="Selecione" style="display: none;">
        <?php echo $this->select_edit_registration; ?>
    </span>
    <edit-box id="id-da-caixa" position="right" title="Habilitar edição" spinner-condition="data.processando" cancel-label="Fechar" close-on-cancel='true'>
        <p>
            Ao habilitar edição para o usuário. O Administrador não poderá incluir um avaliador antes do prazo final da inscrição.
        </p>
    </edit-box>
</div>

<?php

endif;
endif;
?>