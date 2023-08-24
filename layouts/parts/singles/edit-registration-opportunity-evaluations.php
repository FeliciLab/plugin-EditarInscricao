<?php

if($this->isEditable()):
    //limitDate sendo FALSE, a data de inscrição ainda nao se venceu
    $this->jsObject['limitDate'] =  false;
    $today = new \DateTime('now');

    $registrationTo = $this->data->entity->registrationTo;

    if($today < $registrationTo):
    //trocando o valor da data vencida
    $this->jsObject['limitDate'] =  TRUE;
?>
<div id="opportunity-conf-avaliator" class="edit-registration-fieldset">
    <?php $this->applyTemplateHook('edit-registration-enabled','begin'); ?>
    <label>
        <?php \MapasCulturais\i::_e("Deseja habilitar edição para o Candidato?"); ?>
    </label>
    <p class="registration-help">
        <?php \MapasCulturais\i::_e("Habilitar esse recurso, dispõe para o usuário poder editar a sua inscrição no período que a inscrição tiver aberta."); ?>
        
    </p>
    <small ng-click="editbox.open('id-da-caixa', $event)" title="Click para mais informações" class="registration-help" style="cursor: pointer; border-bottom: #c3c3c3;">
        <i class="fa fa-question-circle-o" aria-hidden="true"></i>
    </small>
    <br>
    <select name="" id="select-registration-avaliator" >
        <option value="1" <?php echo  $this->data->entity->select_edit_registration == 1 ? 'selected' : ''; ?>>Sim</option>
        <option value="0" <?php echo  $this->data->entity->select_edit_registration == 0 ? 'selected' : ''; ?>>Não</option>
    </select>
    <span class="js-editable" id="select_edit_registrations" data-edit="select_edit_registration" data-emptytext="Selecione" style="display: none;">
        <?php echo isset($this->data->entity->select_edit_registration) ? $this->data->entity->select_edit_registration : '0'; ?>
    </span>
    <edit-box id="id-da-caixa" position="right" title="Habilitar edição" spinner-condition="data.processando" cancel-label="Fechar" close-on-cancel='true'>
        <p>
        <?php \MapasCulturais\i::_e("Ao habilitar edição para o usuário. O Administrador não poderá incluir um avaliador antes do prazo final da inscrição."); ?>
        </p>
    </edit-box>
    <?php $this->applyTemplateHook('edit-registration-enabled','end'); ?>
</div>
<?php
    endif;
endif;
?>