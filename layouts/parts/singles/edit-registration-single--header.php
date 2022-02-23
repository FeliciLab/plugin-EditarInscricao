<?php
$day = new DateTime('now');
$canEdit = false;
/** CASO A DATA DE HOJE FOR MENOR OU IGUAL A DATA DO FIM DA INSCRIÇÃO */
if($day >= $entity->opportunity->registrationTo) {
    $canEdit = true;
}
if(!$canEdit) : 
    $this->applyTemplateHook('modal-edit-registration-hook','before');
?>
<?php endif; ?>
<?php $this->applyTemplateHook('registration-single-header','before'); ?>
<h3 class="registration-header-check">
<?php \MapasCulturais\i::_e("Comprovante de Inscrição");?>
</h3>