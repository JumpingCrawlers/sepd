


<?php if($pastilla->tipo == 'Menu'): ?>
    
    <?php
        $enlace = '-';
        $texto_boton = '-';
        $imagen = '';
        $texto_cabecera = '-';
        // se le pasa al HTML, además del cuerpo, la sección en la que estamos y el slug de la página
        $texto = getHtmlMenuPastilla($pastilla->cuerpo, $pagina->menu->name, $pagina->slug, $pagina_codificada);
        $clase_pastilla = 'pastilla-menu';
        $clase_contenido = '';
        // el menú no tiene margen inferior porque ya tiene el del último link
        $margen_inf = 'mb-0';
    ?>
<?php elseif($pastilla->tipo == 'Video' || $pastilla->tipo == 'SEPD TV'): ?>
    
    <?php
        $texto_boton = '-';
        $texto = '';
        $destino = '';
        // enlace
        switch($pastilla->tipo) {
            case "Video":
                $arrayData["tipo"] = 1;
                $arrayData["cabecera"] = "Archivos de vídeo";
                $arrayData["url"] = "https://www.youtube.com/embed/" . $pastilla->video->enlace;
                $arrayData["titulo"] = $pastilla->video->titulo;
                $arrayData["subtitulo"] = '';
                $arrayData["descripcion"] = nl2br($pastilla->video->texto);
                // por si no hay imagen
                $imagen = '<iframe class="w-100 h-100" src="' . $arrayData["url"] . '?rel=0&showinfo=0" frameborder="0"></iframe>';
                break;
            case "SEPD TV":
                $arrayData["tipo"] = 2;
                $arrayData["cabecera"] = "SEPD TV";
                $arrayData["url"] = config('app.url')."/storage/sepd_tv/video/" . $pastilla->sepdtv->codigo . ".mp4";
                $arrayData["titulo"] = $pastilla->sepdtv->titulo;
                $arrayData["subtitulo"] = $pastilla->sepdtv->subtitulo;
                $arrayData["descripcion"] = nl2br($pastilla->sepdtv->descripcion);
                // por si no hay imagen
                $imagen = '<video class="w-100"><source src="' . $arrayData["url"] . '" /></video>';
                break;
        }
        // arranca al cargar
        $arrayData["autoplay"] = '1';
        // json de los parámetros y preparar
        $strData = json_encode($arrayData);
        $enlace = 'javascript:playVideo('.$strData.')';
        list($texto_cabecera, $dummy) = getValorCampoPastilla($pastilla, 'texto_cabecera');
        // CSS especial para videos
        $clase_pastilla = 'pastilla-'.$pastilla->formato;
        $clase_contenido = " card-img-overlay";
        // la imagen, igual que las normales
        switch ($pastilla->partesgraficas->count() >= 1) {
            case true:
                // si la pastilla tiene una imagen se utiliza esa
                $imagen = '<img class="img-fluid w-100" src="'.asset(Voyager::image($pastilla->partesgraficas[0]->imagen)).'">';
                $hay_video = false;
                break;
            default:
                // no hay imagen -> video: necesita position-relative en el body, absolute en el video/iframe (en sepd.scss)
                $hay_video = true;
        }
        $play = '<img class="boton-play" src="'.asset(Voyager::image(setting('iconos.play'))).'">';
    ?>
<?php elseif($pastilla->tipo == 'Normal'): ?>
    <?php
        $clase_pastilla = 'pastilla-'.$pastilla->formato;
        $clase_contenido = " card-img-overlay";
        // Controlar el enlace y demás elementos según la herencia pagina-pastilla/pastilla/pastilla-partegrafica
        list($texto_cabecera, $dummy) = getValorCampoPastilla($pastilla, 'texto_cabecera');
        list($texto, $posicion_texto) = getValorCampoPastilla($pastilla, 'texto');
        // comprobar si hay que añadir sombra en el texto
        // pero hay dos casos, si tiene caja o si no
        if ($pastilla->texto_sombra_inversa) {
            $sombra_style = 'style="'.getSombraTexto(true).'"';
            $sombra = ';'.getSombraTexto(true);
        } elseif ($pastilla->texto_sombra) {
            $sombra_style = 'style="'.getSombraTexto(false).'"';
            $sombra = ';'.getSombraTexto(false);
        } else {
            $sombra_style = '';
            $sombra = '';
        }
        list($texto_boton, $posicion_boton) = getValorCampoPastilla($pastilla, 'boton');
        list($enlace, $destino_enlace) = getValorCampoPastilla($pastilla, 'enlace');
        ($destino_enlace == 'Nuevo') ? $destino = ' target="_blank"' : $destino = '';
        // imagen
        if ($pastilla->partesgraficas->count() == 1) {
            $imagen = '<img class="img-fluid w-100" src="'.asset(Voyager::image($pastilla->partesgraficas[0]->imagen)).'" alt="Card image">';
        } else {
            $imagen = '';
        }
    ?>
<?php else: ?>
    <?php
        // Multi-imagen | Twitter | Noticias | Calendario
        // no hay enlace 'global' ni imagen general (excepto calendario, que tiene enlace)
        $enlace = ($pastilla->tipo == 'Calendario') ? $enlace = $pastilla->enlace : '-';
        $destino = '';
        $imagen = '';
        if ($pastilla->tipo == 'Multiple') {
            $clase_contenido = ' card-img-overlay h-25';
            $numDiapos = $pastilla->partesgraficas->count();
        } else {
            $clase_contenido = ' card-img-overlay';
        }
        $texto_boton = '';
        $clase_pastilla = 'pastilla-'.$pastilla->formato;
        list($texto_cabecera, $dummy) = getValorCampoPastilla($pastilla, 'texto_cabecera');
        $texto = '';
    ?>
<?php endif; ?>


<div class="card border-0 d-flex pastilla pastilla-id-<?php echo e($pastilla->id); ?> <?php echo e($clase_pastilla); ?> <?php if(isset($margen_inf)): ?> <?php echo e($margen_inf); ?> <?php endif; ?>">
    <?php if($enlace != '-'): ?> 
        <?php if($pastilla->external): ?>    
            <?php if(auth()->user() && $pastilla->external): ?>
                <a href="<?php echo e(auth()->user()->enlaceSSO($enlace)); ?>" <?php echo e($destino); ?> class="link-pastilla">
            <?php else: ?>
                <a href="<?php echo e($enlace); ?>" <?php echo e($destino); ?> class="link-pastilla event-no-auth-sso">
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo e($enlace); ?>" <?php echo e($destino); ?> class="link-pastilla">
        <?php endif; ?>
    <?php endif; ?>
        <div class="card-body p-0">
            
            
            <?php if(isset($hay_video) && $hay_video): ?>
                <div class="position-relative <?php echo e($clase_pastilla); ?>">
                    <?php echo $imagen; ?>

                </div>
            
            <?php elseif($pastilla->tipo == 'Multiple'): ?>
                
            <?php echo $__env->make('paginas.pastilla-multiple', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php else: ?>

                
            <?php if(isset($pastilla->partesgraficas[0]->copyright) && $pastilla->partesgraficas[0]->copyright != ''): ?>
            <!-- <?php echo e($pastilla->partesgraficas[0]->copyright); ?> -->
            <?php endif; ?>

            <?php echo $imagen; ?>

            
            <?php endif; ?>

            <div class="p-0 <?php echo e($clase_contenido); ?>">
                
                <?php if($pastilla->tipo == 'Menu'): ?>

                <div class="card-text p-0">
                    <?php if($texto): ?>
                    <?php echo $texto; ?>

                    <?php endif; ?>
                </div>

                <?php elseif($pastilla->tipo == 'Video' || $pastilla->tipo == 'SEPD TV'): ?>

                <div class="card-header border-0 w-100 text-white <?php echo e($pagina->menu->name); ?>">
                    <?php if($texto_cabecera): ?>
                    <?php echo $texto_cabecera; ?>

                    <?php endif; ?>
                </div>
                <div class="row p-0<?php echo e($clase_contenido); ?>">
                    <?php echo $play; ?>

                </div>

                <?php elseif($pastilla->tipo == 'Multiple'): ?>
                
                <?php if(isset($texto_cabecera) && $texto_cabecera): ?>
                <div class="card-header border-0 w-100 text-white <?php echo e($pagina->menu->name); ?>">
                    <?php echo $texto_cabecera; ?>

                </div>
                <?php endif; ?>
                
                <?php elseif($pastilla->tipo == 'Twitter'): ?>

                <div class="contenido-titulo-seccion borde-twitter">
                    <span class="bg-twitter">Tweets</span>
                </div>

                <div class="card-text p-0 pastilla-twitter">
                    <?php echo $__env->make('puzzle.twitter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                
                <?php elseif($pastilla->tipo == 'Calendario'): ?>

                <?php if($texto_cabecera): ?>
                <div class="card-header border-0 mb-2 w-100 text-white <?php echo e($pagina->menu->name); ?>">
                    <?php echo $texto_cabecera; ?>

                </div>
                <?php endif; ?>
                
                <div class="card-text p-0">
                    <?php if($pastilla->id!=157): ?> 
                        <?php echo $__env->make('puzzle.calendario', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php else: ?>
                        <?php echo $__env->make('puzzle.calendario_investigacion', ['calendar' => \App\Calendario::calendarioInvestigacion()], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>        
                </div>
                
                <?php elseif($pastilla->tipo == 'Noticias'): ?>

                <?php if($texto_cabecera): ?>
                    <div class="card-header border-0 p-0 w-100 text-white <?php echo e($pagina->menu->name); ?>">
                    <?php echo $texto_cabecera; ?>

                </div>
                <?php endif; ?>
                
                <div class="card-text p-0">
                    <?php echo getHtmlNoticias($noticias); ?>

                </div>
                
                <?php else: ?> 

                <?php if(isset($texto_cabecera) && $texto_cabecera): ?>
                <div class="card-header border-0 w-100 text-white <?php echo e($pagina->menu->name); ?>">
                    <?php echo $texto_cabecera; ?>

                </div>
                <?php endif; ?>

                <div class="card-text p-2">
                    
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
                            $texto_boton && 
                            $texto_boton != '-' &&
                            $posicion_texto == $posicion_boton ): ?>

                        <div class="elemento-pastilla position-absolute<?php echo e($topBottom); ?><?php echo e($izquierdaDerecha); ?><?php echo $estilo_extra; ?>">

                            
                            <?php if($pastilla->caja): ?>
                            <div class= "p-3 rounded" <?php echo e('style=background-color:'.getColorCajaTexto($pastilla).';display:table'); ?>>
                            <?php endif; ?>
                            

                            <?php echo $texto; ?>

                            <button class="btn btn-sm"<?php echo e(getHtmlEstiloBoton(null, null)); ?>><?php echo e($texto_boton); ?></button>

                            
                            <?php if($pastilla->caja): ?>
                            </div>
                            <?php endif; ?>
                            

                        </div>

                    <?php else: ?>

                        
                        <?php if($texto): ?>
                        <div class="elemento-pastilla position-absolute<?php echo e($topBottom); ?><?php echo e($izquierdaDerecha); ?>"<?php echo $estilo_extra; ?>>
                            
                            <?php if($pastilla->caja): ?>
                            <div class= "p-3 rounded" <?php echo e('style=background-color:'.getColorCajaTexto($pastilla).';display:table'); ?>>
                            <?php endif; ?>
                            

                            <?php echo $texto; ?>


                            
                            <?php if($pastilla->caja): ?>
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
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php if($enlace != '-'): ?> 
        </a>
    <?php endif; ?>
</div>


<?php if($pastilla->tipo == 'Twitter'): ?>
    <?php $__env->startSection('scripts'); ?>
        <script>
            $(document).ready(function() { 

                $(window).resize(function() {
                    altoPastillaTw();
                });
                
                setTimeout(function(){ cambiarCss(); altoPastillaTw() }, 250);
                var widgetCSS = "" +
                ".timeline-Tweet-text{font-size: 0.85em !important;line-height: 15px !important}";

                function cambiarCss(){
                    var w = document.getElementById("twitter-widget-1");
                    if (w) {
                        w.contentDocument;
                        var s = document.createElement("style");
                        s.innerHTML = widgetCSS;
                        s.type = "text/css";
                        w.head.appendChild(s);
                    }
                }
                
                function altoPastillaTw(){//le da la altura a la pastilla para el overflow
                    $(".pastilla-twitter").height($("#contenido-extra").height()-60);
                }
            });       
        </script>
    <?php $__env->appendSection(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/paginas/pastilla.blade.php ENDPATH**/ ?>