<?php
$day = new DateTime('now');
$canEdit = false;
/** CASO A DATA DE HOJE FOR MENOR OU IGUAL A DATA DO FIM DA INSCRIÇÃO */
if($day >= $entity->opportunity->registrationTo) {
    $canEdit = true;
}
if(!$canEdit) : 
?>
<div class="registration-btn-edit registration-fieldset">
    <a href="#" class="btn btn-success btn-registration" data-remodal-target="modal-edit-registration" title="Edite a sua inscrição">
        Editar Inscrição
    </a>
    <div class="remodal modal-border" data-remodal-id="modal-edit-registration">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h3>Você editará sua inscrição.</h3>
        <div>
            <h4 style="color: #F26822; font-weight: bold;">
                Todas as alterações feitas serão automaticamente salvas
            </h4>
        </div>
        <div>
            <p>
                Ao confirmar essa ação, <strong>você irá alterar uma inscrição já enviada.</strong> Você conseguirá editar novamente os dados desta inscrição se fizer isso durante o período de incrições.
            </p>

        </div>
        <br>
        <div style="float: right;">
            <form action="<?php echo $app->createUrl('registration', 'alterStatusRegistration', [$entity->id]); ?>" method="post">
                <button data-remodal-action="cancel" class="btn btn-default" title="Desistir da edição" style="margin-right: 15px;"> Voltar</button>
                <button type="submit" class="btn btn-primary" rel='noopener noreferrer'>
                    <?php \MapasCulturais\i::_e("Confirmar"); ?>
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->applyTemplateHook('registration-single-header','before'); ?>
<h3 class="registration-header-check">
    <?php $this->applyTemplateHook('registration-single-header', 'begin'); ?>
    <?php \MapasCulturais\i::_e("Comprovante de Inscrição");?>
    <?php $this->applyTemplateHook('registration-single-header', 'end'); ?>
</h3>