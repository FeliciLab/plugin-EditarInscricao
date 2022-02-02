<div class="remodal modal-border " data-remodal-id="modal-info-registration-confirm">
        <button data-remodal-action="close" class="remodal-close"></button>
    <h3 class="remodal-title">
    <?php print_r($infoModal['titleModal']); ?>
    </h3>
    <div>
        <h4 style="color: #F26822; font-weight: bold;">
        Todas as alterações feitas serão automaticamente salvas 
        </h4>
    </div>
    <div>
        <p>Ao confirmar, <b>você só poderá editar sua inscrição</b> dentro do período de inscrição.
        </p>
        <p>
            Você poderá verificar o status de sua inscrição em <br>
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