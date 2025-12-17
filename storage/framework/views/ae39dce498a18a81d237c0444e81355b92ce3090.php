<div class="modal" id="modalAceptarCookies" tabindex="-1" role="dialog" aria-labelledby="modalAceptarCookiesTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-institucional">
                <h5 class="modal-title text-white mx-auto w-100" id="modalAceptarCookiesTitulo">Aceptar Cookies</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('vendor.cookieConsent.form_cookies', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/vendor/cookieConsent/modal_aceptar_cookies.blade.php ENDPATH**/ ?>