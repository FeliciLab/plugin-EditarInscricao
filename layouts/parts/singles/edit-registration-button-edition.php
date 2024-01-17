<?php
//Somente o admin pode alterar a inscrição
if($entity->canUser('@control')):
?>

<a href="#" class="btn btn-success btn-registration" data-remodal-target="modal-edit-registration" title="Edite a sua inscrição">
        <?php \MapasCulturais\i::_e('Editar Inscrição') ?>
</a>

<?php

endif;

?>