<?php
namespace EditRegistration;

use DateTime;
use MapasCulturais\i;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
    public function _init () {
       $app = App::i();

        $app->hook('view.render(<<*>>):before', function () use ($app) {
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $this->enqueueScript('app', 'remodal', 'js/remodal.min.js');
            $app->view->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $this->enqueueStyle('app', 'remodal', 'css/remodal/remodal.min.css');
            $this->enqueueStyle('app', 'remodal-theme', 'css/remodal/remodal-default-theme.min.css');
        });
        
        // $app->hook('view.partial(singles/opportunity-evaluations--committee):after', function($template){
        //     $data = [];
            
        // });

        $app->hook('view.partial(singles/registration-single--header):after', function($template, $app){

            $this->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $entity = $this->data['entity'];
            $opportunity = $this->data['entity'];
            $id = $this->data['entity']->id;
            $this->part('singles/edit-registration-single--header', ['entity' => $entity, 'opportunity' => $opportunity, 'id' => $id]);
        });

        $app->hook('POST(registration.alterStatusRegistration)', function () use ($app)
         {
            try {
                //
                $this->requireAuthentication();
                $app->disableAccessControl();
                $reg = $app->repo('Registration')->find($this->data['id']);
                $reg->setStatusToDraft();//metodo para alterar o status para 0  (Rascunho)
                $reg->save(true);
                $app->enableAccessControl();
                $app->redirect($reg->editUrl);
            } catch (\Exception $e) {
                dump($e);
            }
        });
        // ADICIONANDO MODAL DE CAMPOS OBRIGATÓRIOS
        $app->hook('view.partial(singles/registration-edit--fields):after', function() use($app){
            $this->part('modals/info-field--required');
        
        });
        //NA PÁGINA DA CRIAÇÃO DA OPORTUNIDADE
        $app->hook('template(opportunity.edit.registration-config):after', function() use($app){
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $this->part('singles/edit-registration-opportunity-evaluations');
        });

        $app->hook(' template(registration.view.form):end', function() use($app){
           $this->part('singles/edit-registration-message--send');
        });


        $app->hook('template(registration.view.header-fieldset):before', function() use($app){
            $day = new DateTime('now');
            $cantEdit = false;
            //A entidade é a inscrição
            $entity = $this->data['entity'];
            /** CASO A DATA DE HOJE FOR MENOR OU IGUAL A DATA DO FIM DA INSCRIÇÃO */
            if($this->data['entity']->opportunity->select_edit_registration == '1' &&
                ($day <= $this->data['entity']->opportunity->registrationTo)) {
                $cantEdit = true;
            }

            if($cantEdit)
                $this->part('singles/edit-registration-button-edition',['entity' => $entity]);
        });

        /**
         * Modal para editar inscrição na página do comprovante
         */
        $app->hook('template(registration.view.modal-edit-registration-hook):before', function () use ($app) {
            
            $infoModal = [
                'title' => 'Você editará sua inscrição.',
                'subTitle' => 'Todas as alterações feitas serão automaticamente salvas.',
                'body' => 'Ao confirmar essa ação, <strong>você irá alterar uma inscrição já enviada.</strong> Você conseguirá editar novamente os dados desta inscrição se fizer isso durante o período de incrições.',
                'buttonConfirm' => 'Confirmar'
            ];
            
            $this->part('modals/open-modal-confirm-edit-registration', ["id" => $this->data['entity']->id, "infoModal" => $infoModal, "entity" => $this->data['entity']]);
        });

         /**
         * Adicionando modal para editar inscrição na página da oportunidade
         */
        $app->hook('template(opportunity.single.modal-edit-registration):before', function($registration){
            $infoModal = [
                'title' => 'Você editará sua inscrição.',
                'subTitle' => 'Todas as alterações feitas serão automaticamente salvas.',
                'body' => 'Ao confirmar essa ação, <strong>você irá alterar uma inscrição já enviada.</strong> Você conseguirá editar novamente os dados desta inscrição se fizer isso durante o período de incrições.',
                'buttonConfirm' => 'Confirmar'
            ];
            $this->part('modals/open-modal-confirm-edit-registration', ["id" => null, "infoModal" => $infoModal, "entity" => $registration, "modalid" => $registration->id]);
        });

        /**
         * Hook para na tela de projetos ser possivel editar inscrição.
         */
        $app->hook('view.partial(singles/opportunity-registrations--form).params', function (&$__data, &$__template)  use ($app){
            $url_atual = $app->view->controller->id;
            $this->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            if($url_atual == "project"){
                $__template = 'singles/project-form-edit-registration'; 
            }
            return;
        }); 
       
    }
 
    function register () {
        $this->registerOpportunityMetadata('select_edit_registration', [
            'label' => i::__('Selecione'),
            'type' => 'select',
            'options' => (object)[
                '0' => i::__('Não'),
                '1' => i::__('Sim'),
            ]
        ]);
    }

    /**
     * Metodo para verificar a data final da inscrição
     * Compara de é maior que a data e hora atual para habilitar
     * a div de inclusao de avaliadores
     *
     * @param [DateTime] $entity->registrationTo
     * @return void
     */
    static public function getEndDateopportunity($entity) {
        $hoje = new DateTime('now');
        $canEdit = false;
        if($hoje <= $entity) {
            $canEdit = true;
        }
        return $canEdit;
    }

    public function publishAssetsEditRegistrarion() {
        $app = App::i();
        $app->view->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
        $app->view->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
    }

}
