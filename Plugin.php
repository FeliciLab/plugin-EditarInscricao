<?php
namespace EditRegistration;

use MapasCulturais\i;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
    function _init () {
       $app = App::i();

        //<!-- BaseV1/layouts/parts/singles/opportunity-registrations--config.php # BEGIN -->
        $app->hook('view.partial(singles/opportunity-evaluations--committee):after', function($template){
            $app = App::i();
            $data = [];
            $theme = $this;
            $app->view->enqueueStyle('app', 'editRegistration', 'css/edtRegistrationStyle.css');
            $app->view->enqueueScript('app', 'editRegistration', 'js/editRegistration.js');
            $this->part('singles/opportunity-evaluations', ['template' => $template, 'theme' => $theme]);
            
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
}
?>