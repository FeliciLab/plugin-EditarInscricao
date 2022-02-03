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
                $app->redirect($app->request()->getReferer());
            } catch (\Exception $e) {
                dump($e);
            }
        });

        $app->hook('template(registration.view.registration-opportunity-buttons):before', function() use($app){
            $this->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $this->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');

            $infoModal = [
                'nameBtn' => 'Finalizar Inscrição',
                'titleBtn' => 'Você está enviando sua inscrição para análise',
                'titleModal' => 'Você conferiu os seus dados?'
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
            $this->part('singles/edit-registration-button-edition');
        });

        $app->hook('template(registration.view.registration-single-header):end', function () use ($app) {
            
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
