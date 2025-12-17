<div id="carouselPastilla<?php echo e($pastilla->id); ?>" class="carousel carousel-<?php echo e($pastilla->formato); ?> px-0" data-ride="carousel" data-interval="3000" style="overflow:hidden">
    
    <ol class="carousel-indicators pastilla-multiple">
        <?php for($i = 0; $i < $numDiapos; $i++): ?>
        <li data-target="#carouselPastilla<?php echo e($pastilla->id); ?>" data-slide-to="<?php echo e($i); ?>"<?php if($i == 0): ?> class="active" <?php endif; ?>></li>
        <?php endfor; ?>
    </ol>
    
    <div class="carousel-inner">
        <?php $__currentLoopData = $pastilla->partesgraficas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diapo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $class_enlace = '';
                // si hay enlace en pivot, sacar de ahí también el destino
                if ($diapo->pivot->enlace && $diapo->pivot->enlace != '') {
                    if ($diapo->pivot->external) {
                        if (auth()->user())
                            $enlace = auth()->user()->enlaceSSO(trim($diapo->pivot->enlace));
                        else {
                            $class_enlace = 'event-no-auth-sso';
                            $enlace = trim($diapo->pivot->enlace);
                        }
                    } else
                        $enlace = trim($diapo->pivot->enlace);
                    ($diapo->pivot->destino_enlace == 'Nuevo') ? $destino = ' target="_blank"' : $destino = '';;
                } else {
                    $enlace = trim($diapo->enlace);
                    ($diapo->destino_enlace == 'Nuevo') ? $destino = ' target="_blank"' : $destino = '';;
                }
                // si hay texto, mostrarlo
                // $texto = $diapo->pivot->texto;

                // Controlar el enlace y demás elementos según la herencia pagina-pastilla/pastilla/pastilla-partegrafica
                list($texto, $posicion_texto) = getValorCampoPastilla($pastilla, 'texto', $diapo);
                // comprobar si hay que añadir sombra en el texto
                // pero hay dos casos, si tiene caja o si no
                if ($diapo->pivot->texto_sombra_inversa) {
                    $sombra_style = 'style="'.getSombraTexto(true).'"';
                    $sombra = ';'.getSombraTexto(true);
                } elseif ($diapo->pivot->texto_sombra) {
                    $sombra_style = 'style="'.getSombraTexto(false).'"';
                    $sombra = ';'.getSombraTexto(false);
                } else {
                    $sombra_style = '';
                    $sombra = '';
                }
                list($texto_boton, $posicion_boton) = getValorCampoPastilla($pastilla, 'boton', $diapo);
                list($enlace, $destino_enlace) = getValorCampoPastilla($pastilla, 'enlace', $diapo);
                ($destino_enlace == 'Nuevo') ? $destino = ' target="_blank"' : $destino = '';
                
            ?>
            <div class="carousel-item <?php if($loop->first): ?> active <?php endif; ?>">
                <a href="<?php echo e($enlace); ?>"<?php echo $destino; ?> class="link-pastilla <?php echo e($class_enlace); ?>">
                    
                    <?php if($diapo->imagen): ?>
                            
                        <?php if(isset($diapo->copyright) && $diapo->copyright != ''): ?>
                            <!-- <?php echo e($pastilla->partesgraficas[0]->copyright); ?> -->
                        <?php endif; ?>

                        <img class="img-fluid w-100" src="<?php echo e(Voyager::image($diapo->imagen)); ?>">
                    <?php endif; ?>

                    
                    <?php
                        list($topBottom, $izquierdaDerecha) = getPosicionElementoPastilla($posicion_texto);
                        list($topBottomBoton, $izquierdaDerechaBoton) = getPosicionElementoPastilla($posicion_boton);
                        // si la posición es centro hay que añadir flex y justify-content:center para la caja
                        $estilo_extra = ($posicion_texto == 5) ? 'style=display:flex;justify-content:center' : '';
                        // añadir la sombra, si hay
                        ($sombra != '')
                            ? $estilo_extra .= ($estilo_extra != '') ? $sombra : $sombra_style 
                            : null;
                    ?>

                    <?php if($texto &&
                         $texto_boton && $texto_boton != '-' &&
                         $posicion_texto == $posicion_boton ): ?>
                        <div class="elemento-pastilla position-absolute<?php echo e($topBottom); ?><?php echo e($izquierdaDerecha); ?>"<?php echo $estilo_extra; ?>>
                            
                            <?php if($diapo->pivot->caja): ?>
                            <div class= "p-3 rounded" <?php echo e('style=background-color:'.getColorCajaTexto($diapo->pivot).';display:table'); ?>>
                            <?php endif; ?>
                            

                            <?php echo $texto; ?>

                            <button class="btn btn-sm"<?php echo e(getHtmlEstiloBoton(null, null)); ?>><?php echo e($texto_boton); ?></button>

                            
                            <?php if($diapo->pivot->caja): ?>
                            </div>
                            <?php endif; ?>
                            

                        </div>
                    <?php else: ?>

                        
                        <?php if($texto): ?>
                            <div class="elemento-pastilla position-absolute<?php echo e($topBottom); ?><?php echo e($izquierdaDerecha); ?>"<?php echo $estilo_extra; ?>>

                                
                                <?php if($diapo->pivot->caja): ?>
                                <div class= "p-3 rounded" <?php echo e('style=background-color:'.getColorCajaTexto($diapo->pivot).';display:table'); ?>>
                                <?php endif; ?>
                                

                                <?php echo $texto; ?>


                                
                                <?php if($diapo->pivot->caja): ?>
                                </div>
                                <?php endif; ?>
                                

                            </div>
                        <?php endif; ?>

                        <?php if($texto_boton && $texto_boton != '-'): ?>
                            <div class="elemento-pastilla position-absolute<?php echo e($topBottomBoton); ?><?php echo e($izquierdaDerechaBoton); ?>">
                                <button class="btn btn-sm"<?php echo e(getHtmlEstiloBoton(null, null)); ?>><?php echo e($texto_boton); ?></button>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>


                    
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <a class="carousel-control-prev pastilla-multiple" href="#carouselPastilla<?php echo e($pastilla->id); ?>" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next pastilla-multiple" href="#carouselPastilla<?php echo e($pastilla->id); ?>" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/paginas/pastilla-multiple.blade.php ENDPATH**/ ?>