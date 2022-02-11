<div class="remodal modal-border" ng-controller="OpportunityController" data-remodal-id="modal-info-registration-confirm">
        <button data-remodal-action="close" class="remodal-close"></button>
    <h3 class="remodal-title">
        <?php echo $infoModal['titleModal']; ?>
    </h3>
    <div>
        <h4 style="color: #F26822; font-weight: 800;">
            Todas as alterações foram salvas automaticamente durante a edição
        </h4>
    </div>
    <div>
        <p> 
            Você pode acessar a inscrição editada em:
            <strong>Meu Perfil > Minhas Inscrições.</strong>
        </p>
    </div>
    <br>
    <div>

        <a class="btn btn-primary" ng-click="sendRegistration()" rel='noopener noreferrer'><?php \MapasCulturais\i::_e("Visualizar Comprovante"); ?></a>

    </div>
</div>