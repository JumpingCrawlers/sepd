
<?php if($pagina->slider): ?>
<?php $numDiapos = $pagina->slider->partesgraficas->count() ?>
<?php if($numDiapos > 0): ?>

<div id="carouselPrincipal" class="carousel carousel<?php echo e($formato_carousel); ?> px-0" data-ride="carousel"
  style="overflow:hidden">
  
  <ol class="carousel-indicators">
    <?php for($i = 0; $i < $numDiapos; $i++): ?> <li data-target="#carouselPrincipal" data-slide-to="<?php echo e($i); ?>" <?php if($i==0): ?>
      class="active" <?php endif; ?>>
      </li>
      <?php endfor; ?>
  </ol>
  
  <div class="carousel-inner">
    <?php $__currentLoopData = $pagina->slider->partesgraficas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diapo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="carousel-item <?php if($loop->first): ?> active <?php endif; ?>">
      
      <?php if($diapo->imagen): ?>
      <img src="<?php echo e(Voyager::image($diapo->imagen)); ?>">
      <?php endif; ?>
      <div class="container">
        
        <?php
        list($topBottom, $izquierdaDerecha) = getPosicionElementoSlider($diapo->pivot->posicion_elementos);
        list($topBottomBoton, $izquierdaDerechaBoton) = getPosicionElementoSlider($diapo->pivot->posicion_boton);
        $boton_aparte = ($topBottom != $topBottomBoton || $izquierdaDerecha != $izquierdaDerechaBoton);
        // si posición es derecha, hay que añadir un float:right a la caja
        $float_caja = ($izquierdaDerecha == ' derecha') ? ';float:right' : '';
        $float_boton = ($izquierdaDerecha == ' derecha') ? ' float-right' : '';
        // inicializar estilo extra (solo para alineación derecha)
        $estilo_texto_derecha = '';
        ?>
        <?php switch($diapo->pivot->texto_align):
        case ('Left'): ?>
        <?php $textoAlign = ' text-left'; ?>
        <?php break; ?>
        <?php case ('Right'): ?>
        <?php
        $textoAlign = ' text-right';
        // si además, la posición es derecha, los elementos de la caja han de ser flex y justify-content:end
        $estilo_texto_derecha = ($izquierdaDerecha == ' derecha') ? ';display:flex;justify-content:flex-end' : '';
        ?>
        <?php break; ?>
        <?php default: ?>
        <?php $textoAlign = ''; ?>
        <?php endswitch; ?>
        <div class="carousel-caption<?php echo e($topBottom); ?><?php echo e($textoAlign); ?><?php echo e($izquierdaDerecha); ?>">
          
          <?php
            ($diapo->pivot->texto_color)
            ? $estilos_texto = ' style="color:'.$diapo->pivot->texto_color.$estilo_texto_derecha
            : $estilos_texto = ' style="'.$estilo_texto_derecha;
            (($diapo->pivot->texto_sombra_inversa)
            ? $estilos_texto .= ';'.getSombraTexto(true)
            : ($diapo->pivot->texto_sombra))
            ? $estilos_texto .= ';'.getSombraTexto(false)
            : null;
            $estilos_texto .= '"';
          ?>
          
          <?php if($diapo->pivot->caja): ?>
            <div class="p-3 rounded" <?php echo e('style=background-color:' .getColorCajaTexto($diapo->pivot).';display:table'.$float_caja); ?>>
          <?php endif; ?>
          

          
          <?php if($diapo->pivot->titulo): ?>
            <h3<?php echo $estilos_texto; ?>><?php echo $diapo->pivot->titulo; ?></h3>
          <?php endif; ?>
            <p<?php echo $estilos_texto; ?>><?php echo $diapo->pivot->texto; ?></p>

            
            <?php if(!$boton_aparte && $diapo->pivot->boton_texto): ?>
              <?php
                $enlace = $diapo->pivot->enlace;
                $class_sso = ''; 
                if ($diapo->pivot->external) {
                  if (auth()->user())
                    $enlace = auth()->user()->enlaceSSO($enlace);
                  else 
                    $class_sso = 'event-no-auth-sso';
                }
              ?>
              <p class="mb-0 <?php echo e($float_boton); ?>">
                <a
                  class="btn <?php echo e($class_sso); ?>"
                  href="<?php echo e($enlace); ?>"
                  role="button" <?php echo e(getHtmlEstiloBoton($diapo->pivot->boton_color, $diapo->pivot->boton_bgcolor)); ?>

                  <?php echo e(getHtmlDestino($diapo->pivot->destino_enlace)); ?>>
                  <?php echo e($diapo->pivot->boton_texto); ?>

                </a>
              </p>
            <?php endif; ?>
            
          <?php if($diapo->pivot->caja): ?>
            </div>
          <?php endif; ?>
          
        </div>

        <?php if($boton_aparte && $diapo->pivot->boton_texto): ?>
          
          <?php switch($izquierdaDerechaBoton):
            case (' izquierda'): ?>
              <?php $izquierdaDerechaBoton .= ' text-left'; ?>
            <?php break; ?>
            <?php case (' derecha'): ?>
              <?php $izquierdaDerechaBoton .= ' text-right'; ?>
            <?php break; ?>
          <?php endswitch; ?>

          <div class="carousel-caption<?php echo e($topBottomBoton); ?><?php echo e($izquierdaDerechaBoton); ?>">    
            <p class="mb-0<?php echo e($float_boton); ?>">
              <?php
                $enlace = $diapo->pivot->enlace;
                $class_sso = ''; 
                if ($diapo->pivot->external) {
                  if (auth()->user())
                    $enlace = auth()->user()->enlaceSSO($enlace);
                  else 
                    $class_sso = 'event-no-auth-sso';
                }
              ?>
              <a
                class="btn <?php echo e($class_sso); ?>"
                href="<?php echo e($enlace); ?>"
                role="button"
                diapo-id="<?php echo e($diapo->id); ?>"
                <?php echo e(getHtmlEstiloBoton($diapo->pivot->boton_color, $diapo->pivot->boton_bgcolor)); ?>

                <?php echo e(getHtmlDestino($diapo->pivot->destino_enlace)); ?>>
                <?php echo e($diapo->pivot->boton_texto); ?>

              </a>
            </p>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
  <a class="carousel-control-prev" href="#carouselPrincipal" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Anterior</span>
  </a>
  <a class="carousel-control-next" href="#carouselPrincipal" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Siguiente</span>
  </a>
</div>
<?php if(auth()->guard()->check()): ?>
<?php if(\App\UsuarioSocio::where('usuario_id',
  auth()->id())->first()&&(!request()->is('formacion')&&!request()->is('investigacion')&&!request()->is('inicio_gyc')&&!request()->is('publicaciones'))): ?>
  <div class="alert alert-warning" role="alert">
    La información sobre medicamentos está dirigida exclusivamente al profesional destinado a prescribirlos o
    dispensarlos, por lo que se requiere una formación especializada para su correcta interpretación.
</div>
<?php endif; ?>
<?php endif; ?>

<?php endif; ?>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('a[diapo-id]');
    buttons.forEach(function (button) {
      button.addEventListener('click', function (event) {

        event.preventDefault();

        try {

          const diapoId = button.getAttribute('diapo-id');
          
          console.log(diapoId);

          gtag('event', 'CLICK_BUTTON_BANNER', {
            banner_id: diapoId,
            event_callback: function() {
              window.location.href = button.getAttribute('href');
            }
          });
          
        } catch (error) {
          console.error(error.message);
          
          window.location.href = button.getAttribute('href');
        }
      });
    });
  });
</script><?php /**PATH C:\laragon\www\sepd.es\resources\views/paginas/slider.blade.php ENDPATH**/ ?>