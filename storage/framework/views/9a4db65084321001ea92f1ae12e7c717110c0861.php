<div class="modal fade show" id="modalSSONoAuth" tabindex="-1" role="dialog" aria-labelledby="modalNavidadTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-700" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p class="font-weight-bold">Contenido científico exclusivo para socios SEPD. Por favor, pinche en el botón “Acceso Usuarios” e introduce el e-mail y la contraseña con la que accedes al área privada de socios SEPD.
                    Si no eres socio y deseas acceder, ponte en contacto a través del email sepd@sepd.es para asociarte. Puedes consultar todas las ventajas de hacerte socio <a target="_blank" href="/ventajas_socios">aquí</a>.
                Recuerda que la cuota es gratuita para residentes de la especialidad.
                </p>
                <button type="button" class="btn mt-2" <?php echo e(getHtmlEstiloBoton()); ?> data-dismiss="modal" aria-label="Close">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modalSSONoAuthLogin" tabindex="-1" role="dialog" aria-labelledby="modalNavidadTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-700" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p class="font-weight-bold">
                    Contenido exclusivo para usuarios registrados en el site. Si ya estás registrado, por favor, accede <a href="<?php echo e(route('login')); ?>">aquí</a> e introduce tu mail y contraseña, y vuelve a intentar acceder a este enlace. Si aún no estás registrado, completa nuestro Formulario de registro.
                </p>
                <button type="button" class="btn mt-2" <?php echo e(getHtmlEstiloBoton()); ?> data-dismiss="modal" aria-label="Close">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/modal_sso_no_auth.blade.php ENDPATH**/ ?>