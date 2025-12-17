<footer class="container text-white mt-4">
    <a href="javascript:" id="return-to-top"> <i class="glyphicon glyphicon-edit m-r-20"></i></a>
    <div class="row mb-3">
        <div class="col-sm-4 offset-sm-1 mb-3">
            <a href="https://stamp.wma.comb.es/es/seal/693" target="_blank">
                <img src="https://stamp.wma.comb.es/stamp/imglogo.ashx?INTWMA=693&lang=es&size=big" border="0"
                    alt="Web Médica Acreditada. Ver más información" longdesc="http://wma.comb.es"
                    title="Web Médica Acreditada. Ver más información" />
            </a>
            <!-- <a href="https://www.healthonnet.org/HONcode/Spanish/?HONConduct963113" target="_blank">
                <img src="<?php echo e(Voyager::image(setting('site.logo_honcode'))); ?>" border="0" class="img-fluid">
            </a> -->
            
            <?php if(auth()->guard()->guest()): ?>
            
            <?php else: ?>
                
                <p></p>
                <p></p>
            <?php endif; ?>
        </div>
        <div class="col-sm-3 font-archivo-light">
            <p>
                <strong class="font-archivo-semibold">Contacto:</strong>
                <br>C/ Sancho Dávila, 6 - 28028 Madrid
                <br>(Sede de la SEPD y de la FEAD)
                <br>Tel.: 91 402 13 53
            </p>
        </div>
        <div class="col-sm-3 font-archivo-light">
            <p>
                <strong class="font-archivo-semibold">Horario:</strong>
                <br>Lunes a jueves de 9:00 a 18:00h.
                <br>Viernes de 9:00 a 15:00 h.
            </p>

            <p>
                <strong class="font-archivo-semibold">Horario de verano:</strong>
                <br>Lunes a jueves de 9:00 a 16:00h.
                <br>Viernes de 9:00 a 15:00 h.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            
            <?php echo $__env->make('vendor.cookieConsent.modal_aceptar_cookies', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <p class="font-archivo-light text-white">
                &copy; 2018  <?php echo e(setting('site.title')); ?>  
                &middot; <a class="text-white" href="<?php echo e(request()->getSchemeAndHttpHost()); ?>/privacidad">Privacidad</a> 
                &middot; <a class="text-white" href="<?php echo e(request()->getSchemeAndHttpHost()); ?>/contacto">Contacto</a>
                &middot; <a class="text-white" href="<?php echo e(request()->getSchemeAndHttpHost()); ?>/mapa_web">Mapa web</a>
                &middot; <a class="text-white" href="<?php echo e(request()->getSchemeAndHttpHost()); ?>/legal">Aviso legal</a>
                &middot; <a class="text-white" href="#" id="modalCookiesButton">Política de Cookies</a>
            </p>
            
            <p class="font-archivo-light">
                <span> Última actualización: <?php echo e(App\Sitio::ultima_act()); ?> <span>
            </p>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/pie.blade.php ENDPATH**/ ?>