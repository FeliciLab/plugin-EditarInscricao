<div class="remodal modal-border" ng-controller="OpportunityController" data-remodal-id="modal-info-registration-confirm">
        <button data-remodal-action="close" class="remodal-close"></button>
    <h3 class="remodal-title">
    <?php print_r($infoModal['titleModal']); ?>
    </h3>
    <div>
        <h4 style="color: #F26822; font-weight: bold;">
        Você conferiu os seus dados? 
        </h4>
    </div>
    <div>
        <p>Ao confirmar, <b>você poderá editá-la somente</b> durante o período de inscrições.
        </p>
        <p>
            Para editar sua inscrição, acesse o menu <br>
            <b>Meu Perfil > Minhas Inscrições. </b>
        </p>
    </div>
    <br>
    <div style="float: right;">
    <button data-remodal-action="cancel" class="btn btn-default" title="Sair da resposta" style="margin-right: 15px;"> Voltar</button>
    
    <a class="btn btn-success btn-registration" ng-click="sendRegistration()" rel='noopener noreferrer'><?php \MapasCulturais\i::_e("Confirmar"); ?></a>
    </form>
    </div>
</div>