<?php 
    use MapasCulturais\App;
    use MapasCulturais\Entities\Registration;

    $userRelation = $entity->evaluationMethodConfiguration->getUserRelation($app->user);
    $registrations = $app->repo('Registration')->findByOpportunityAndUser($entity, $app->user);
    $day = new DateTime('now');

    $infoModal = [
        'title' => 'Você editará sua inscrição.',
        'subTitle' => 'Todas as alterações feitas serão automaticamente salvas.',
        'body' => 'Ao confirmar essa ação, <strong>você irá alterar uma inscrição já enviada.</strong> Você conseguirá editar novamente os dados desta inscrição se fizer isso durante o período de incrições.',
        'buttonConfirm' => 'Confirmar'
    ];

if  ($entity->isRegistrationOpen()): ?>
    <?php if ($app->auth->isUserAuthenticated()): ?>
        <?php if (count($registrations) > 0) : ?>
            <?php $this->part('modals/open-modal-confirm-edit-registration', ["id" => null, "infoModal" => $infoModal, "entity" => reset($registrations)]); ?> 
        <?php endif ?>
        <!-- // SE O USUARIO TIVER PERMISSÃO PARA MODIFICAR A ENTIDADE -->
        <?php if (!($entity->canUser('modify')) && empty($userRelation)) : ?>
            <form class="registration-form clearfix">
            <p class="registration-help white-top" style="font-size: 14px;"><?php \MapasCulturais\i::_e("Para iniciar sua inscrição, selecione o agente responsável. Ele deve ser um agente individual (pessoa física), com um CPF válido preenchido.");?></p>
                <div class="registration-form-content project-buttons-edit">
                    <div class="registration-form-content-input">
                        <div id="select-registration-owner-button_<?php echo $entity->id; ?>" class="input-text"
                            ng-click="editbox.open('editbox-select-registration-owner_<?php echo $entity->id; ?>', $event)">
                            <strong>Agente: </strong>
                            {{data.registration.owner ? data.registration.owner.name : data.registration.owner_default_label}}
                            <small style="color: #9E9E9E;"> (clique para alterar) </small>
                        </div>
                        <edit-box class="editbox-select-registration-owner" id="editbox-select-registration-owner_<?php echo $entity->id; ?>" position="top" title="<?php \MapasCulturais\i::esc_attr_e("Selecione o agente responsável pela inscrição.");?>" cancel-label="<?php \MapasCulturais\i::esc_attr_e("Cancelar");?>" close-on-cancel='true' spinner-condition="data.registrationSpinner">
                            <find-entity id='find-entity-registration-owner_<?php echo $entity->id; ?>' entity="agent" no-results-text="<?php \MapasCulturais\i::esc_attr_e("Nenhum agente encontrado");?>" select="setRegistrationOwner" opportunityid="<?php echo $entity->id; ?>" api-query='data.relationApiQuery.owner' spinner-condition="data.registrationSpinner"></find-entity>
                            <strong><?php \MapasCulturais\i::_e("Apenas são visíveis os agentes publicados.");?> <a target="_blank" href="<?php echo $app->createUrl('panel', 'agents') ?>"><?php \MapasCulturais\i::_e("Ver mais.");?></a></strong>
                        </edit-box>
                        <?php if($entity->registrationLimitPerOwner == 0 || count($registrations) < $entity->registrationLimitPerOwner): ?>
                            <div>
                                <a class="btn btn-primary btn-register-opportunity" style="color: #ffffff;" ng-click="register(<?php echo $entity->id; ?>)" rel='noopener noreferrer'><?php \MapasCulturais\i::_e("Fazer inscrição");?></a>
                            </div>
                            <?php if(count($registrations) == 0): ?>
                                <div>
                                    <a href="<?=$entity->singleUrl;?>" class="btn btn-access-opportunity" style="color: #ffffff;" rel='noopener noreferrer' title="Acessar inscrições"><?php \MapasCulturais\i::_e("Mais informações");?></a>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if(count($registrations) > 0): ?>
                            <?php if($day <= $entity->registrationTo && count($registrations) == 1): ?>
                                <div>
                                    <?php $this->part('singles/edit-registration-button-edition'); ?>
                                </div>
                            <?php endif;?>
                            <div>
                                <?php if(count($registrations) > 1): ?>
                                    <a href="<?=$entity->singleUrl;?>" class="btn btn-access-opportunity" style="color: #ffffff;" rel='noopener noreferrer' title="Acessar inscrições"><?php \MapasCulturais\i::_e("Acessar Inscrição");?></a>
                                <?php endif;?>
                                <?php if(count($registrations) == 1): ?>
                                    <a href="<?=$entity->singleUrl;?>" class="btn btn-access-opportunity" style="color: #ffffff;" rel='noopener noreferrer' title="Acessar inscrições"><?php \MapasCulturais\i::_e("Acessar Inscrição");?></a>
                                <?php endif;?>
                            </div>
                        <?php endif;?>

                    </div>
                </div>
            </form>
        <?php endif;?>
    <?php else: ?>
    <hr>
    <div>
    </div>
    <div>
        <p>
            <i class="fa fa-info-circle" aria-hidden="true" style="border-radius: 200px solid black;"></i>
            <?php \MapasCulturais\i::_e("Para iniciar sua inscrição, você precisa acessar o Mapa através de uma conta. Entre com seu login de agente ou crie uma nova conta se for sua primeira vez por aqui:");?>
        </p>
    </div>
            <a class="btn btn-primary" ng-click="setRedirectUrl()" <?php echo $this->getLoginLinkAttributes() ?>
            style="float: left; margin-left: 0px !important;">
            <?php \MapasCulturais\i::_e("Fazer login ou criar conta");?>
            </a>
    <?php endif;?>
<?php endif;?>