<?php

/* 
 * Funciones de ayuda para formateo en vistas
 * 
 */

/**
 * function getHtmlIconoFlash
 * 
 * Genera el html para mostrar un icono en un mensaje flash
 * @param $icono // nombre del icono de settings
 * @returns string // html de la imagen
 */
function getHtmlIconoFlash($icono) {
    
    return '<img src="'.Voyager::image(setting('iconos.'.$icono)).'" width="18">';
}

/**
 * function getEstiloBoton
 * 
 * Devuelve el HTML para el botón con los colores recibidos o los colores por defecto
 * 
 * @param $color_texto string   // #RRGGBB codigo de color
 * @param $color_fondo string   // #RRGGBB codigo de color
 * @returns string              // codigo HTML con el estilo
 */
function getHtmlEstiloBoton($color_texto = null, $color_fondo = null) {

    $estilo_boton = ' style=color:';
    ($color_texto) ? $estilo_boton .= $color_texto : $estilo_boton .= setting('site.color_boton');
    $estilo_boton .= ';background-color:';
    ($color_fondo) ? $estilo_boton .= $color_fondo : $estilo_boton .= setting('site.bgcolor_boton');

    return $estilo_boton;
}

/**
 * function getDestino($destino)
 * 
 * Devuelve el HTML con el target
 * 
 * @param $destino  // tipo de enlace
 * @returns string  // HTML del target
 */
function getHtmlDestino($destino) {

    return ($destino == 'Nuevo' || $destino == 'N') ? $destino = ' target="_blank"' : $destino = '';
}

/**
 * function getClasePosicionSlider
 * 
 * Devuelve las clases de la posición del elemento
 */
function getPosicionElementoSlider($posicion) {

    switch($posicion) {
        case 1:
            $topBottom = ' arriba';
            $izquierdaDerecha = ' izquierda';
            break;
        case 2:
            $topBottom = ' arriba';
            $izquierdaDerecha = '';
            break;
        case 3:
            $topBottom = ' arriba';
            $izquierdaDerecha = ' derecha';
            break;
        case 4:
            $topBottom = ' abajo';
            $izquierdaDerecha = ' izquierda';
            break;
        case 5:
            $topBottom = ' abajo';
            $izquierdaDerecha = '';
            break;
        case 6:
            $topBottom = ' abajo';
            $izquierdaDerecha = ' derecha';
            break;
        default:
            $topBottom = '';
            $izquierdaDerecha = '';
    }
    
    return array($topBottom, $izquierdaDerecha);
    
}

/**
 * function getClasePosicionPastilla
 * 
 * * Devuelve las clases de la posición del elemento
 */
function getPosicionElementoPastilla($posicion) {

    switch($posicion) {
        case 1:
            $topBottom = ' arriba';
            $izquierdaDerecha = ' izquierda';
            break;
        case 2:
            $topBottom = ' arriba';
            $izquierdaDerecha = ' derecha text-right';
            break;
        case 3:
            $topBottom = ' abajo';
            $izquierdaDerecha = ' izquierda';
            break;
        case 4:
            $topBottom = ' abajo';
            $izquierdaDerecha = ' derecha text-right';
            break;
        default:
            $topBottom = ' centro text-center w-100';
            $izquierdaDerecha = '';
    }
    
    return array($topBottom, $izquierdaDerecha);
    
}

/**
 * function getValorCampoPastilla
 *
 * @param $pastilla // objeto pastilla
 * @param $campo // campo a recuperar
 * @param $diapo // datos de la diapositiva
 * @return (string, integer | string) // (texto, posicion | destino) valores del campo correspondiente
 * 
 * Devuelve el valor del campo especificado para la pastilla según la herencia de datos
 */
function getValorCampoPastilla($pastilla, $campo, $diapo = null) {

    $texto_o_enlace = null;
    $posicion_o_destino = null;

    // 1. Multimagen y no texto_cabecera ni enlace:
    //      pastilla->partegrafica->pivot || pastilla->pivot || pastilla
    // 2. Cualquier otra opción:
    //      pastilla->pivot || pastilla
    if ($pastilla->tipo == 'Multiple' && $campo != 'texto_cabecera') {
        
        return getValorDiapoPastilla($pastilla, $campo, $diapo);

    } else {

        switch($campo) {
            case "texto":
                $texto_o_enlace = $pastilla->pivot->texto;
                $posicion_o_destino = $pastilla->pivot->posicion_elementos;
                if (!$texto_o_enlace || $texto_o_enlace == '') {
                    $texto_o_enlace = $pastilla->cuerpo;
                    $posicion_o_destino = $pastilla->posicion_elementos;
                }
                break;
            case "boton":
                $texto_o_enlace = $pastilla->pivot->texto_boton;
                $posicion_o_destino = $pastilla->pivot->posicion_boton;
                if (!$texto_o_enlace || $texto_o_enlace == '') {
                    $texto_o_enlace = $pastilla->texto_boton;
                    $posicion_o_destino = $pastilla->posicion_boton;
                }
                break;
            case "texto_cabecera":
                $texto_o_enlace = $pastilla->pivot->texto_cabecera;
                $posicion_o_destino = null;
                if (!$texto_o_enlace || $texto_o_enlace == '') {
                    $texto_o_enlace = $pastilla->texto_cabecera;
                }
                break;
            case "enlace":
                $texto_o_enlace = $pastilla->pivot->enlace;
                $posicion_o_destino = $pastilla->pivot->destino_enlace;
                if (!$texto_o_enlace || $texto_o_enlace == '') {
                    $texto_o_enlace = $pastilla->enlace;
                    $posicion_o_destino = $pastilla->destino_enlace;
                }
                break;
        }

    }
    
    return array($texto_o_enlace, $posicion_o_destino);
    
}

/**
 * function getValorCampoPastilla
 *
 * @param $pastilla // objeto pastilla
 * @param $campo // campo a recuperar
 * @param $diapo // datos de la diapositiva
 * @return (string, integer | string) // (texto, posicion | destino) valores del campo correspondiente
 * 
 * Devuelve el valor del campo especificado para la diapositiva de la pastilla múltiple según la herencia de datos
 */
function getValorDiapoPastilla($pastilla, $campo, $diapo) {

    $texto_o_enlace = null;
    $posicion_o_destino = null;

    switch($campo) {
        case "texto":
            $texto_o_enlace = $diapo->pivot->texto;
            $posicion_o_destino = $diapo->pivot->posicion_elementos;
            break;
        case "boton":
            $texto_o_enlace = $diapo->pivot->texto_boton;
            $posicion_o_destino = $diapo->pivot->posicion_boton;
            break;
        case "enlace":
            $texto_o_enlace = $diapo->pivot->enlace;
            $posicion_o_destino = $diapo->pivot->destino_enlace;
            break;

    }
    // si el texto sigue vacío -> herencia
    if (!$texto_o_enlace || $texto_o_enlace == '') {
        switch($campo) {
            case "texto":
                if ($diapo->texto) {
                    $texto_o_enlace = $diapo->texto;
                    $posicion_o_destino = $diapo->posicion_elementos;
                } else {
                    $texto_o_enlace = $pastilla->cuerpo;
                    $posicion_o_destino = $pastilla->posicion_texto;
                }
                break;
            case "boton":
                if ($diapo->texto_boton) {
                    $texto_o_enlace = $diapo->texto_boton;
                    $posicion_o_destino = $diapo->posicion_boton;
                } else {
                    $texto_o_enlace = $pastilla->texto_boton;
                    $posicion_o_destino = $pastilla->posicion_boton;
                }
            case "enlace":
                if ($diapo->enlace) {
                    $texto_o_enlace = $diapo->enlace;
                    $posicion_o_destino = $diapo->destino_enlace;
                } else {
                    $texto_o_enlace = $pastilla->enlace;
                    $posicion_o_destino = $pastilla->destino_enlace;
                }
        }
    }
    
    return array($texto_o_enlace, $posicion_o_destino);
}

/**
 * function getColorCajaTexto
 *
 * @param $elemento // objeto que contiene los campos caja_color y caja_opacidad
 * @return string
 * 
 * Devuelve "rgba(x,x,x,x)" con los colores y la opacidad
 */
function getColorCajaTexto($elemento) {

    // RGB
    list($r, $g, $b) = array_map('hexdec', str_split(substr($elemento->caja_color,1), 2));
    // Opacidad
    $opacidad = ($elemento->caja_opacidad/100);

    return "rgba(".$r.",".$g.",".$b.",".$opacidad.")";

}

/**
 * function getSombraTexto
 *
 * @param bool // sombra inversa?
 * @return string
 * 
 * Devuelve el CSS text-shadow
 */
function getSombraTexto($inversa) {

    return ($inversa)
            ? "text-shadow:1px 1px 2px #FFF;"
            : "text-shadow:1px 1px 2px #000;";

}

/**
 * function getHtmlNoticias($noticias)
 * 
 * Devuelve el HTML con las últimas noticias
 * 
 * @param $noticias  // array con las úlitmas noticias
 * @returns string  // HTML del scroll de 
 */
function getHtmlNoticias($noticias) {
    
    $htmlContenido = '';
    $conectado = Auth::user();
    
    // HTMLs comunes
    $htmlContenido = '<div class="content-noticias">';
    
    foreach ($noticias as $noticia) {
        $htmlContenido.= '<div class="noticia col-xs-12">';
//        if (!$conectado) {
//            $htmlContenido.= '<a data-toggle="modal" data-target="#modalLogin" href="#" class="fecha pt-2">';
//        } else {
//            $htmlContenido.= '<a href="noticias/'.$noticia->id.'" target="_blank" class="fecha pt-2">';
//        }
        // El control de acceso se hace en NoticiaController@show
        $htmlContenido.= '<a href="noticias/'.$noticia->id.'" class="fecha pt-2">';
        $htmlContenido.= '<div class="noticia-fecha">';
        $htmlContenido.= '<strong class="titulo">'.$noticia->fecha->format('d-m-Y').' - </strong>';
        $htmlContenido.= '</div>';
        $htmlContenido.= '<div class="noticia-texto text-justify">';
        $htmlContenido.= $noticia->titulo;
        $htmlContenido.= '</div>';
        $htmlContenido.= '</a>';
        $htmlContenido.= '</div>';
    }

    $htmlContenido.= '</div>';

    return $htmlContenido;
}

/**
 * function getHtmlMenuPastilla($cuerpoPastilla, $seccion, $url)
 * 
 * Devuelve el HTML con el menú creado
 * 
 * @param $cuerpoPastilla  // texto con las opciones de menú codificadas:
 *                              @@@Grupo#NombreGrupo@@@
 *                              @@@Texto opción@@hipervínculo@@destino@@Restringido@@@
 * @param $seccion // institucional | formacion | publicaciones | etc...
 * @param $slug // slug de la página donde se ubica la pastilla menú
 * @returns string  // HTML del menu
 */
function getHtmlMenuPastilla($cuerpoPastilla, $seccion, $slug, $pagina_codificada = false) {
    
    /* ATENCION */
    /* El HTML es similar del de filtros (puzzle/flitros/filtro_plantilla.blade.php */
    /* Cambios aquí se deberían replicar allí y viceversa */

    $htmlMenu = '';
    // para controlar los enlaces restringidos
    $conectado = Auth::user();
    // Se necesita el slug:
    //      Los enlaces con slug+ancla de la página actual se quitan ya que
    //      si hay varios grupos y se mezclan urls y anchors es necesario
    //      que todos lleven la url completa. La url completa fastidia el 
    //      funcionamiento de scroll spy. Por lo tanto, los anchors de la
    //      página actual hay que eliminarlos a la hora de montar el HTML.
    // control de inicio/final de grupo
    $grupoIniciado = false;
    // HTMLs comunes
    $htmlContainerGrupo = '<div class="container mb-2">';
    $htmlDefinicionGrupo = '<div class="row pl-3 py-3 align-items-center grupo-menu-izquierda '.$seccion.'" data-activo="false">';
    $htmlDefinicionGrupo .= '<div class="w-100 position-relative">';
    // cada grupo estará identificado por un NUM (numGrupo), sacado de la posición de inicio en el cuerpo, que obviamente será diferente para cada caso
    $htmlCollapseGrupo = '<a class="collapsed" data-toggle="collapse" href="#grupo-pastillaMenu-###numGrupo###" role="button" aria-expanded="false" aria-controls="grupo-pastillaMenu-###numGrupo###">';
    $htmlEnlaceGrupo = '<a href="###enlace###"###destino### class="nav-link p-0">';
    $htmlTituloGrupo = '<h5 class="mb-0">###tituloGrupo###</h5>';
    $htmlFlechaGrupo = '<div class="position-absolute flecha '.$seccion.'"></div>';
    $htmlFinGrupo = '</a></div></div>';
    $htmlContainerOpciones = '<div class="row container-grupoOpciones px-2 pt-2 pb-0"><div class="collapse" id="grupo-pastillaMenu-###numGrupo###">';
    $htmlContainerNavOpciones = '<nav class="navbar flex-column px-0"><nav class="nav nav-pills flex-column w-100">';
    $htmlLinkRestringido = '<div class="candado"></div>';
    $htmlCerrarNavOpciones = '</nav></nav>';
    $htmlCerrarOpciones = '</div></div>';
    $htmlCerrarGrupo = '</div>';
    
    // Recorrer el cuerpo de la pastilla formateando grupos y opciones
    // Buscar el primer código
    $posicion = strpos($cuerpoPastilla, '@@@');
    // Control de iteraciones: POSIBLE BLOQUEO?
    $iteraciones = 0;
    while ($posicion !== false && $iteraciones<200) {
        $iteraciones++;
        // controlar si es un grupo o una opción
        if ( substr($cuerpoPastilla, $posicion + 3, 6) == "Grupo#") {
            // si había grupo anterior, hay que cerrarlo
            if ($grupoIniciado) {
                $htmlMenu .= $htmlCerrarNavOpciones.$htmlCerrarOpciones.$htmlCerrarGrupo;
            }
            // dos posibilidades: tiene link / no tiene link
            $posLink = strpos($cuerpoPastilla, '@@', ($posicion + 3)) + 2;
            $posFinCodigo = strpos($cuerpoPastilla, '@@@', ($posicion + 3)) + 2;
            // si las 2 posiciones coinciden es que no tiene link
            if ($posLink == $posFinCodigo) {
                // No tiene link, es un grupo
                // abrir el grupo nuevo, recuperando el título
                $htmlMenu .= $htmlContainerGrupo.$htmlDefinicionGrupo;
                $htmlMenu .= str_replace("###numGrupo###", $posicion, $htmlCollapseGrupo);
                $htmlMenu .= str_replace("###tituloGrupo###", substr($cuerpoPastilla, $posicion + 9, strpos($cuerpoPastilla, '@@@', ($posicion + 3)) - ($posicion + 9)), $htmlTituloGrupo);
                $htmlMenu .= $htmlFlechaGrupo.$htmlFinGrupo;
                $htmlMenu .= str_replace("###numGrupo###", $posicion, $htmlContainerOpciones);
                $htmlMenu .= $htmlContainerNavOpciones;
                // marcar grupo iniciado
                $grupoIniciado = true;
            } else {
                // Tiene link, abrir y cerrar el grupo
                $htmlMenu .= $htmlContainerGrupo.$htmlDefinicionGrupo;
                $posicionEnlace = strpos($cuerpoPastilla, '@@', ($posicion + 3));
                // titulo
                $tituloGrupo = substr($cuerpoPastilla, $posicion + 9, $posicionEnlace - ($posicion + 9));
                $posicionDestino = strpos($cuerpoPastilla, '@@', ($posicionEnlace + 2));
                // enlace
                $enlaceGrupo = substr($cuerpoPastilla, $posicionEnlace + 2, $posicionDestino - ($posicionEnlace+2));
                // destino
                $destinoGrupo = substr($cuerpoPastilla, $posicionDestino + 2, 1);
                $destinoGrupo = getHtmlDestino($destinoGrupo);
                // restringido
                $enlaceRestringido = substr($cuerpoPastilla, $posicionDestino+5, 1);
                if ($enlaceRestringido == 'R' && !Auth::user() && !$pagina_codificada) {
                    $enlaceGrupo = '#" data-toggle="modal" data-target="#modalLogin"';
                    $tituloGrupo .= $htmlLinkRestringido;
                }
                $htmlMenu .= str_replace("###destino###", $destinoGrupo, str_replace("###enlace###", $enlaceGrupo, $htmlEnlaceGrupo));
                // $htmlMenu .= $htmlEnlaceGrupo);
                $htmlMenu .= str_replace("###tituloGrupo###", $tituloGrupo, $htmlTituloGrupo);
                $htmlMenu .= $htmlFinGrupo;
                $htmlMenu .= str_replace("###numGrupo###", $posicion, $htmlContainerOpciones);
                $htmlMenu .= $htmlCerrarOpciones.$htmlCerrarGrupo;
                // no hay grupo ya que se ha cerrado
                $grupoIniciado = false;
            }
        } else {
            // recoger el texto del enlace
            $textoEnlace = substr($cuerpoPastilla, $posicion + 3, strpos($cuerpoPastilla, '@@', ($posicion + 3)) - ($posicion + 3));
            // además comprobar si hay que indentar (2 caracteres ##)
            if (substr($textoEnlace, 0, 2) == '##') {
                $margen_extra = ' ml-4';
                $textoEnlace = substr($textoEnlace, 2);
            } else {
                $margen_extra = '';
            }
            // recoger el enlace y demás detalles. Como se recoge varios detalles separados con @@, guardamos la posición de inicio de cada uno
            $siguientePos = strpos($cuerpoPastilla, '@@', ($posicion + 3)) + 2;
            $enlace = substr($cuerpoPastilla, $siguientePos, strpos($cuerpoPastilla, '@@', $siguientePos) - $siguientePos);
            $siguientePos = strpos($cuerpoPastilla, '@@', $siguientePos) + 2;
            $destino = substr($cuerpoPastilla, $siguientePos, strpos($cuerpoPastilla, '@@', $siguientePos) - $siguientePos);
            $destino = getHtmlDestino($destino); // target
            $siguientePos = strpos($cuerpoPastilla, '@@', $siguientePos) + 2;
            $restringido = substr($cuerpoPastilla, $siguientePos, strpos($cuerpoPastilla, '@@', $siguientePos) - $siguientePos);
            // montar el a href
            // Pero primero comprobar si el enlace contiene la url actual 
            // (ver comentario al inicio de la función)
            if (strpos($enlace, '/'.$slug) !== false && strpos($enlace, '#') !== false) {
                $enlace = substr($enlace, strpos($enlace, '#'));
            }
            ($restringido == 'R' && !Auth::user() && !$pagina_codificada) ? 
                $htmlMenu .= '<a class="nav-link py-1'.$margen_extra.'" href="#" data-toggle="modal" data-target="#modalLogin">'.$textoEnlace.' '.$htmlLinkRestringido.'</a>' : 
                $htmlMenu .= '<a class="nav-link py-1'.$margen_extra.'" href="'.$enlace.'"'.$destino.'>'.$textoEnlace.'</a>';
        }
        // buscar el inicio del siguiente código (saltando el @@@ final => se busca 2 veces)
        $posicion = strpos($cuerpoPastilla, '@@@', $posicion + 3);
        $posicion = strpos($cuerpoPastilla, '@@@', $posicion + 3);
    }
    // si hay un grupo abierto, finalizarlo
    if ($grupoIniciado) {
        $htmlMenu .= $htmlCerrarOpciones.$htmlCerrarGrupo;
    }

    return $htmlMenu;

}

/**
 * function getHtmlContenido($texto)
 * 
 * Devuelve el HTML con los códigos del contenido "traducidos"
 * ATENCIÓN: para los códigos tambien se quita el "<p>" y el "</p>" que lo rodean
 * 
 * @param $contenido // texto con el contenido de la página. A traducir:
 *                              @@@Titulo#Texto del título@@@
 * @param $seccion // institucional | formacion | publicaciones | etc...
 * @returns string  // HTML del contenido "traducido"
 */
function getHtmlContenido($contenido, $seccion, $pagina_codificada = false) {
    
    // el contenido inicial es el original
    // se debe guardar dos variables, ya que la longitud del texto original y del final cambiarán con la traducción.
    // para no perder la última posición procesada, se busca en $contenido, se traduce en $htmlProcesado
    $htmlProcesado = $contenido;
    $htmlCodigoTraducido = '';
    // HTMLs de las traducciones
    // Título página
    $htmlContainerTituloPagina = '<div class="contenido-titulo-pagina color-'.$seccion.'">';
    $htmlFinTituloPagina = '</div>';
    // Título sección
    $htmlContainerTituloSeccion = '<div class="contenido-titulo-seccion borde-'.$seccion.'"><span class="bg-'.$seccion.'">';
    $htmlFinTituloSeccion = '</span></div>';
    // hr
    $htmlHR = '<hr class="borde-'.$seccion.'"ESTILOHR>';
    // Enlace con sus 3 variantes
    $htmlContainerGlobalEnlace = '<div class="container mb-2"><div class="row">';
    $htmlContainerImagenEnlace = '<div class="col-xx callout-right '.$seccion.'-secundario text-right">';
    $htmlContainerEnlaceEnlace = '<a URL>';
    $htmlContainerBloqueTextoEnlace = '<div class="col-xx m-0 p-0 pr-2" id="IDENLACE">';
    $htmlContainerTextoEnlace = '<div class="bg-light ml-2 px-3 py-2 d-inline-block w-100 h-100">';
    $htmlFinContainerTextoEnlace = '</div>';
    $htmlFinContainerBloqueTextoEnlace = '</div>';
    $htmlFinContainerEnlaceEnlace = '</a>';
    $htmlFinContainerSoloImagenEnlace = '</div>';
    $htmlFinContainerImagenEnlace = '</div>';
    $htmlFinContainerGlobalEnlace = '</div></div>';
    // Enlace restringido
    $htmlLinkRestringido = '<div class="candado d-inline"></div>';
    
    // Se recorre el contenido recogiendo código a código y traduciendo
    // Buscar el primer código
    $posicionInicial = strpos($contenido, '@@@');
    // Control de iteraciones: POSIBLE BLOQUEO?
    $iteraciones = 0;
    while ($posicionInicial !== false && $iteraciones<200) {
        $iteraciones++;
        // reiniciar variables de la iteración!!
        $enlaceTexto = '-';
        $enlaceImagen = '-';
        $offset = 0;
        // guardar el cógido a traducir
        $posicionFinal = strpos($contenido, '@@@', $posicionInicial + 3);
        // recuperar el código entero (lo que se "traduce")
        // inicio: posicionInicial - 3(<p>), longitud: final-inicial + <p> + @@@ + </p> (3+3+4)
        $codigo = substr($contenido, $posicionInicial - 3, $posicionFinal - $posicionInicial + 3 + 3 + 4);
        // tipo de código
        switch (strtoupper(substr($codigo, 6, strpos($codigo, '#') - 6))) {
            case "TITULOSECCION":
                // recuperar el título (omitir los 7 últimos caracteres "@@@</p>")
                $titulo = substr($codigo, strpos($codigo, '#') + 1, -7);
                $htmlCodigoTraducido = $htmlContainerTituloSeccion.$titulo.$htmlFinTituloSeccion;
                break;
            case "TITULOPAGINA":
                // recuperar el título (omitir los 7 últimos caracteres "@@@</p>")
                $titulo = substr($codigo, strpos($codigo, '#') + 1, -7);
                $htmlCodigoTraducido = $htmlContainerTituloPagina.$titulo.$htmlFinTituloPagina;
                break;
            case "HR":
                $htmlCodigoTraducido = $htmlHR;
                // comprobar estilo extra
                $estiloHR = '';
                $estiloHR .= (stripos($codigo, 'w')) ?
                        'style="width:' . substr($codigo, stripos($codigo, 'w')+1 , (stripos($codigo, 'h', 9) ?: stripos($codigo, '@', 9)) - (stripos($codigo, 'w')+1) ) . '%;'
                        : '';
                $estiloHR .= (stripos($codigo, 'h', 9)) ?
                        (
                            ($estiloHR != '') ?
                                'border-top-width:' . substr($codigo, stripos($codigo, 'h', 9)+1 , stripos($codigo, '@', 9) - (stripos($codigo, 'h',9)+1) ) . 'px;"'
                                : 'style="border-top-width:' . substr($codigo, stripos($codigo, 'h', 9)+1 , stripos($codigo, '@', 9) - (stripos($codigo, 'h',9)+1) ) . 'px;"'
                        )
                        : ( ($estiloHR != '') ? '"' : '' );
                $htmlCodigoTraducido = str_replace('ESTILOHR', $estiloHR, $htmlCodigoTraducido);
                break;
            case "SOLOIMAGENENLACE":
                // estos TRES códigos hacen lo mismo, solo varía los elementos que tienen enlace.
                $enlaceTexto = false;
                $enlaceImagen = true;
                $offset = 20;
            case "TEXTOENLACE":
                ($enlaceTexto === '-') ? $enlaceTexto = true : $enlaceTexto = $enlaceTexto;
                ($enlaceImagen === '-') ? $enlaceImagen = false : $enlaceImagen = $enlaceImagen;
                ($offset == 0) ? $offset = 15 : null;
            case "IMAGENENLACE":
                ($enlaceTexto === '-') ? $enlaceTexto = true : null;
                ($enlaceImagen === '-') ? $enlaceImagen = true : null;
                ($offset == 0) ? $offset = 16 : null;
                // enlace con imagen. Hay que recuperar el código completo <p><img title="...." src="..." alt="...." /></p>
                $codigo = getCodigoImagen($contenido, $posicionInicial);
                $imagen = getSrcCodigoImagen($codigo);
                // offset son #caracteres a saltar para recuperar la url (longitud del código)
                $detalleEnlace = getEnlaceCodigoImagen($codigo, $offset, 'imagenEnlace'.$posicionInicial, $pagina_codificada);
                $htmlCodigoTraducido = $htmlContainerGlobalEnlace;
                if ($detalleEnlace <> '') {
                    $htmlEnlaceFinal = str_replace('URL', $detalleEnlace, $htmlContainerEnlaceEnlace);
                    // si hay enlace no tiene fondo el container global, si no el del <a>
                    $fondo = 'm-0 p-0';
                } else {
                    $enlaceTexto = false;
                    $enlaceImagen = false;
                    $fondo = ' bg-light ml-2 py-2'; // no hay enlace, fondo para el container global
                }
                // controlar las columnas de bootstrap que ocupa la imagen
                // se recupera del código la posición del @@@ final (el num_columnas está en la posición -1)
                $posicion_cierre_codigo = strrpos($codigo, '@@@');
                $num_columnas = substr($codigo, $posicion_cierre_codigo-1, 1);
                if (ctype_digit($num_columnas)) {
                    $columnas_imagen = $num_columnas;
                    $columnas_resto = 12 - $num_columnas;
                } else {
                    $columnas_imagen = 3;
                    $columnas_resto = 9;
                }
                $htmlCodigoTraducido .= str_replace('col-xx', 'col-'.$columnas_imagen, $htmlContainerImagenEnlace);
                ($enlaceImagen === true) ? $htmlCodigoTraducido .= $htmlEnlaceFinal : null;
                $htmlCodigoTraducido .= '<img class="img-fluid" src="'.getSrcCodigoImagen($codigo).'">';
                ($enlaceImagen === true) ? $htmlCodigoTraducido .= $htmlFinContainerEnlaceEnlace : null;
                $htmlCodigoTraducido .= $htmlFinContainerImagenEnlace;
                $htmlCodigoTraducido .= str_replace('IDENLACE', 'imagenEnlace'.$posicionInicial, str_replace('col-xx', 'col-'.$columnas_resto, $htmlContainerBloqueTextoEnlace));
                ($enlaceTexto === true) ? $htmlCodigoTraducido .= $htmlEnlaceFinal : null;
                $htmlCodigoTraducido .= $htmlContainerTextoEnlace
                                     .  getTextoCodigoImagen($codigo, $seccion)
                                     .  $htmlFinContainerTextoEnlace;
                ($enlaceTexto === true) ? $htmlCodigoTraducido .= $htmlFinContainerEnlaceEnlace : null;
                $htmlCodigoTraducido .= $htmlFinContainerBloqueTextoEnlace;
                $htmlCodigoTraducido .= $htmlFinContainerGlobalEnlace;
                break;
            case "RESTRINGIDO":
                // recuperar el código (el procedimiento es el mismo que con TEXTO/IMAGENENLACE)
                $codigo = getCodigoImagen($contenido, $posicionInicial);
                $htmlCodigoTraducido = $codigo;
                // Si el usuario no está conectado, cambiar el href
                if (!Auth::user() && !$pagina_codificada) {
                    // buscar el contenido de href
                    $inicioHref = strpos($codigo, 'href="') + 6; // + href="
                    $finHref = strpos($codigo, '"', $inicioHref);
                    $valorHref = substr($codigo, $inicioHref, $finHref - $inicioHref);
                    // url restringida
                    $urlRestringida = '#" data-toggle="modal" data-target="#modalLogin';
//                    $htmlCodigoTraducido = str_replace($valorHref, $urlRestringida, $htmlCodigoTraducido);
                    $htmlCodigoTraducido = substr_replace($htmlCodigoTraducido, $urlRestringida, $inicioHref, strlen($valorHref));
                    // añadir el candado
                    // Es necesario cambiar el <p> a un <div> ya que <p> con <a> y <div> como hijos no está soportado en las especificaciones html
                    // Se cambia el P por DIV y se añade el DIV de candado después del A (dentro no se puede)
                    $htmlCodigoTraducido = str_replace('<p>', '<div class="py-2">', $htmlCodigoTraducido);
                    $htmlCodigoTraducido = str_replace('</p>', '</div>', $htmlCodigoTraducido);
                    $htmlCodigoTraducido = str_replace('</a>', '<div class="candado d-inline"></div></a>', $htmlCodigoTraducido);
                }
                // y eliminar el @@@Restringido# del title
                $htmlCodigoTraducido = str_replace('title="@@@Restringido#@@@"', '', $htmlCodigoTraducido);
                break;
            case "NOTICIAS":
                 $htmlCodigoTraducido = getHtmlNoticias(\App\Noticia::ultimasNoticias());
                break;
        }
        // Procesado final deposibles BR en el texto/descripcion
//        $htmlCodigoTraducido = str_replace('&lt;br&gt;', '<br>', $htmlCodigoTraducido);
        $htmlCodigoTraducido = str_replace('&lt;', '<', $htmlCodigoTraducido);
        $htmlCodigoTraducido = str_replace('&gt;', '>', $htmlCodigoTraducido);

        $htmlProcesado = str_replace($codigo, $htmlCodigoTraducido, $htmlProcesado);
        // buscar el inicio del siguiente código (a partir de la posición final)
        $posicionInicial = strpos($contenido, '@@@', $posicionFinal + 3);
    }
    
    // Procesado final de los bloques de contenido y sangrías
    // Sustituye ###Bloque### y ###FinBloque###
    $htmlInicioBloque = '<div class="callout py-0 '.$seccion.'-secundario">';
    $htmlFinBloque = '</div>';
    $htmlProcesado = str_ireplace('<p>###BLOQUE###</p>', $htmlInicioBloque, $htmlProcesado);
    $htmlProcesado = str_ireplace('<p>###FINBLOQUE###</p>', $htmlFinBloque, $htmlProcesado);
    // Sustituye ###Sangria### y ###FinSangria###
    $htmlInicioSangria = '<div class="sangria">';
    $htmlFinSangria = '</div>';
    $htmlProcesado = str_ireplace('<p>###SANGRIA###</p>', $htmlInicioSangria, $htmlProcesado);
    $htmlProcesado = str_ireplace('<p>###FINSANGRIA###</p>', $htmlFinSangria, $htmlProcesado);
    
    return $htmlProcesado;

}

/**
 * function getCodigoImagen($contenido, $inicio)
 * 
 * Devuelve el código completo (texto a substituir) de una imagen-enlace
 * Es más complicado que los casos anteriores, ya que incluye el tag <img> y el <p>
 * 
 * @param $contenido // texto con el contenido de la página. 
 * @param $inicio // posición inicial del código ImagenEnlace#
 * @returns string  // código completo <p><img ....../></p>
 */
function getCodigoImagen($contenido, $inicio) {
    
    // el contenido inicial es el original
    // Buscar el inicio del código <p>: última aparición de <p> en el substring hasta el inicio del @@@
    $inicioP = strrpos(substr($contenido, 1, $inicio), '<p>');
    $finalP = strpos($contenido, '</p>', $inicio);
    
    return substr($contenido, $inicioP, $finalP - $inicioP + 4); // añadir los 4 caracteres de </p>


}

/**
 * function getSrcCodigoImagen($codigo)
 * 
 * Devuelve el src de la imagen de un imagen-enlace
 * 
 * @param $codigo // código completo <p><img ....../></p>
 * @returns string  // src de <img>
 */
function getSrcCodigoImagen($codigo) {
    
    // buscar el src y luego el final (la primera comilla posterior)
    $inicioSrc = strpos($codigo, 'src="') + 5; // saltando el src="
    $finalSrc = strpos($codigo, '"', $inicioSrc);
    
    return substr($codigo, $inicioSrc, $finalSrc - $inicioSrc);


}

/**
 * function getTextoCodigoImagen($codigo)
 * 
 * Devuelve el html del TEXTO de la imagen de un imagen-enlace
 * 
 * @param $codigo // código completo <p><img ....../></p>
 * @param $seccion // institucional - formacion - ....
 * @param $salidaArray // bool (salida array solo con los textos, para popup)
 * @returns string  // código traducido
 */
function getTextoCodigoImagen($codigo, $seccion, $salidaArray = false) {
    
    $htmlFinal = '';
    $arrayFinal = [];

    // el texto está en el title. Buscar el contenido de title
    $inicioTxt = strpos($codigo, 'title="') + 7; // saltando el title="
    $finalTxt = strpos($codigo, '"', $inicioTxt);
    $texto = substr($codigo, $inicioTxt, $finalTxt - $inicioTxt);

    // Titulo
    $posicionFinal = strpos($texto, '##');
    if ($posicionFinal) {
        $titulo = substr($texto, 0, $posicionFinal);
    } else {
        $titulo = $texto;
    }
    if ($titulo != '-' && $titulo != '') {
        $htmlFinal .= '<div class="contenido-titulo-enlace color-'.$seccion.'">'.$titulo.'</div>';
        ($salidaArray) ? $arrayFinal['cabecera'] = $titulo : null;
        ($salidaArray) ? $arrayFinal['titulo'] = $titulo : null;
    }

    // Subtítulo
    if ($posicionFinal) {
        $posicionInicial = $posicionFinal + 2;
        $posicionFinal = strpos($texto, '##', $posicionInicial);
        if ($posicionFinal) {
            $subtitulo = substr($texto, $posicionInicial, $posicionFinal-$posicionInicial);
        } else {
            $subtitulo = substr($texto, $posicionInicial);
        }
        if ($subtitulo <> '-') {
            $htmlFinal .= '<div class="contenido-subtitulo-enlace">'.$subtitulo.'</div>';
            ($salidaArray) ? $arrayFinal['subtitulo'] = $subtitulo : null;
        }
    }
    
    // Descripcion
    if ($posicionFinal) {
        $posicionInicial = $posicionFinal + 2;
        $descripcion = substr($texto, $posicionInicial);
        if ($descripcion <> '-' && $descripcion <> '') {
            $htmlFinal .= '<div class="contenido-descripcion-enlace">'.$descripcion.'</div>';
            ($salidaArray) ? $arrayFinal['descripcion'] = $descripcion : null;
        }
    }
    
    return ($salidaArray) ? $arrayFinal : $htmlFinal;


}

/**
 * function getEnlaceCodigoImagen
 * 
 * Devuelve el href y el target de un imagen-enlace (si hay enlace)
 * 
 * @param $codigo // código completo <p><img ....../></p>
 * @param $offset // número de caracteres a saltar para el inicio del enlace
 * @param $id // id del div para recuperar las descripciones en caso de pop-up
 * @returns string  // href+target de <a> o '' si no hay enlace
 */
function getEnlaceCodigoImagen($codigo, $offset, $id, $pagina_codificada) {
    
    $targetBlank = '';

    // buscar el inicio del enlace
    $inicioEnlace = strpos($codigo, '@@@') + $offset; // saltando el @@@ImagenEnlace# o @@@TextoEnlace#
    $finalEnlace = strpos($codigo, '@@', $inicioEnlace);
    
    $url = trim(substr($codigo, $inicioEnlace, $finalEnlace - $inicioEnlace));
    
    if ($url == '' || $url == '-') {

        // no hay enlace
        return '';

    } else {

        // Primero controlar si es restringido
        // Si es restringido: url -> modal login y nada más
        $restringido = substr($codigo, $finalEnlace + 5, 1);
        if ($restringido == 'R' && !Auth::user() && $pagina_codificada) {
            $url = '#" data-toggle="modal" data-target="#modalLogin';
            // FALTA EL CANDADO o MARCAR DE ALGUNA FORMA EL ENLACE RESTRINGIDO
            $claseRestringido = ' enlace-restringido';
        } else {
            // No está restringido
            $claseRestringido = '';
            // Comprobar target
            $target = substr($codigo, $finalEnlace + 2, 1);
            // para los pop-ups: de momento imagen (pero podrá ser vídeo)
            if ($target == 'P') {
                // abrir pop-up con la imagen
                $url = "javascript:showImagen('".$url."', '".$id."')";
            } else {
                // o nuevo (_blank) o normal ('')
                ($target != 'N') ?: $targetBlank .= ' target="_blank"';
            }            
        }
        
        $htmlEnlace = 'href="'.$url.'" class="text-nodeco'.$claseRestringido.'"'.$targetBlank;

        return $htmlEnlace;

    }

}

