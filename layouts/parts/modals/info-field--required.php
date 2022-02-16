<?php $this->applyTemplateHook('remodal-info-required', 'before'); ?>
<div class="remodal modal-border-danger " data-remodal-id="remodal_info_field_required">
  <button data-remodal-action="close" class="remodal-close"></button>
  <div class="remodal-content-body">
    <h1 class="text-title-modal-border-danger" id="title_modal_required_info">Você esqueceu de preencher alguns campos.</h1>
    <p class="text-body-modal-border-danger" id="subTitle_modal_required_info">
      Para prosseguir com sua inscrição, é necessário preencher todos os campos marcados como obrigatórios
    </p>
    
      <span class="sub-title-remodal" id="infoField_modal_required_info">Você precisa preencher o(s) seguinte(s) campo(s):</span>
   
    <p id="info-erros-required-fields" class="info-erros-required-fields"></p>
  </div>
  <div>
  
    <button data-remodal-action="cancel"  class="btn btn-default" title="Fechar aviso">Voltar</button>
  </div>
</div>
<?php $this->applyTemplateHook('remodal-info-required', 'after'); ?>
