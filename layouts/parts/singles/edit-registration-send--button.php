<button data-remodal-target="modal-info-registration-confirm" id="modal-info-registration-confirm" class="btn btn-success btn-registration" onclick="">
    <?php echo $infoModal['nameBtn']; ?>
</button>
<!-- modal de Confirmação de inscrição -->
<?php 

if($isEdit) {
    $this->part('modals/open-modal-edit-subscribe',['infoModal' => $infoModal]);
}else{
    $this->part('modals/open-modal-confirm-subscribe',['infoModal' => $infoModal]);
}
?>