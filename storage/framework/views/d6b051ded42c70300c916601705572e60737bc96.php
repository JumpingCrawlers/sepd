<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog" aria-labelledby="modalImagenTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-<?php echo e($nombre_menu); ?>">
                <h5 class="modal-title text-white" id="modalImagenTitulo"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100">
                <div id="modalShowImagen" class="embed-responsive">
                </div>
                <div id="modalImagenDescripcion">
                    <div class="imagen-titulo color-<?php echo e($nombre_menu); ?>"></div>
                    <div class="imagen-subtitulo font-weight-bold"></div>
                    <div class="imagen-descripcion"></div>
                </div>
            </div> 
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/modal_imagen.blade.php ENDPATH**/ ?>