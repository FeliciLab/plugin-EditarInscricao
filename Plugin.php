<?php
namespace EditRegistration;

use DateTime;
use MapasCulturais\i;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
    public function _init () {
       $app = App::i();

        $app->hook('view.partial(singles/opportunity-evaluations--committee):after', function($template){
            $data = [];
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $this->part('singles/edit-registration-opportunity-evaluations', ['template' => $template]);
        });

        $app->hook('view.partial(singles/registration-single--header):after', function($template, $app){

            $this->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $entity = $this->data['entity'];
            $opportunity = $this->data['entity'];
            $id = $this->data['entity']->id;
            $this->part('singles/edit-registration-single--header', ['entity' => $entity, 'opportunity' => $opportunity, 'id' => $id]);
        });

        $app->hook('POST(registration.alterStatusRegistration)', function () use ($app) {
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
        $app->hook('template(registration.view.registration-opportunity-buttons):before', function() use($app){
            $this->part('modals/info-field--required');
        });

        $app->hook('template(registration.view.registration-opportunity-buttons):before', function() use($app){
            $this->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');

            /** AVISO DE CAMPOS OBRIGATORIOS */
            $this->enqueueScript('app', 'errorValidation', 'js/ng.errorValidation.js');
            $this->enqueueStyle('app', 'pnotify.buttons', 'css/remodal-styleCustom.css');

            /** Adicionado SweetAlert 2 para modal de loading e futuro modais no mapa. */
            $this->enqueueScript('app', 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11');

            $infoModal = [
                'nameBtn' => 'Finalizar Inscrição',
                'titleBtn' => 'Você está enviando sua inscrição para análise',
                'titleModal' => 'Você está enviando sua inscrição para análise.'
            ];
            $isEdit = false;
            if(!is_null($this->data['entity']->sentTimestamp)) {
                $infoModal['nameBtn'] = 'Finalizar Edição';
                $infoModal['titleBtn'] = 'Finalizar Edição.';
                $infoModal['titleModal'] = 'Você está finalizando sua edição.';
                $isEdit = true;
            };
            
            $this->part('singles/edit-registration-send--button', ['infoModal' => $infoModal, 'isEdit' => $isEdit]);
        });
        
        $app->hook('template(registration.view.pdf-report-btn):before', function() use($app){
            $day = new DateTime('now');
            $canEdit = false;
            /** CASO A DATA DE HOJE FOR MENOR OU IGUAL A DATA DO FIM DA INSCRIÇÃO */
            if($day >= $this->data['entity']->opportunity->registrationTo) {
                $canEdit = true;
            }
            if(!$canEdit) : 
            $this->part('singles/edit-registration-button-edition');
            endif;
        });

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
         * Hook para na tela de projetos ser possivel editar inscrição.
         */
        $app->hook('view.partial(singles/opportunity-registrations--form).params', function (&$__data, &$__template)  use ($app){
            $url_atual = $app->view->controller->id;
            if($url_atual == "project"){
                $this->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
                $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
                $__template = 'singles/project-form-edit-registration.php'; 
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
