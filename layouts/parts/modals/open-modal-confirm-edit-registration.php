<div class="registration-btn-edit">
    <div class="edt-registration-btns-header">
        <?php  
        //$this->part('singles/edit-registration-button-edition'); 
        $this->part('reports/button-print', ['id' => $id]); 
        ?>
    </div>
   
    <div class="remodal modal-border" data-remodal-id="modal-edit-registration">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h3><?php echo $infoModal["title"] ?></h3>
        <div>
            <h4 style="color: #F26822; font-weight: bold;">
                <?php echo $infoModal["subTitle"] ?>
            </h4>
        </div>
        <div>
            <p>
                <?php echo $infoModal["body"] ?>
            </p>

        </div>
        <br>
        <div style="float: right;">
            <form action="<?php echo $app->createUrl('registration', 'alterStatusRegistration', [$entity->id]); ?>" method="post">
                <button data-remodal-action="cancel" class="btn btn-default" title="Desistir da edição" style="margin-right: 15px;"> Voltar</button>
                <button type="submit" class="btn btn-primary" rel='noopener noreferrer'>
                    <?php \MapasCulturais\i::_e($infoModal["buttonConfirm"]); ?>
                </button>
            </form>
        </div>
    </div>
</div>