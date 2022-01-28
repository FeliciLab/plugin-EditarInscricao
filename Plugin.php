<?php
namespace EditRegistration;

use DateTime;
use MapasCulturais\i;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
    function _init () {
       $app = App::i();

        //<!-- BaseV1/layouts/parts/singles/opportunity-registrations--config.php # BEGIN -->
        $app->hook('view.partial(singles/opportunity-evaluations--committee):after', function($template){
            $app = App::i();
            $data = [];
            //$theme = $this;
            $app->view->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $app->view->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $this->part('singles/opportunity-evaluations', ['template' => $template]);
            
        });

        $app->hook('view.partial(singles/registration-single--header):after', function($template){
           $entity = $this->data['entity'];
           $opportunity = $this->data['entity'];
           $this->part('singles/edit-registration-single--header', ['entity' => $entity, 'opportunity' => $opportunity]);
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
    public function getEndDateopportunity($entity) {
        $hoje = new DateTime('now');
        $canEdit = false;
        if($hoje <= $entity) {
            $canEdit = true;
        }
        return $canEdit;
    }
}
?>