<?php
    $nombre_menu = $nombre_menu ?? '';
?>
<div class="modal fade" id="modalVideo" tabindex="-1" role="dialog" aria-labelledby="modalVideoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-<?php echo e($nombre_menu); ?>">
                <h5 class="modal-title text-white" id="modalVideoTitulo"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalVideoPlayer" class="embed-responsive embed-responsive-16by9">
                </div>
                <div id="modalVideoDescripcion">
                    <div class="video-titulo color-<?php echo e($nombre_menu); ?>"></div>
                    <div class="video-subtitulo"></div>
                    <div class="video-descripcion"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/modal_video.blade.php ENDPATH**/ ?>