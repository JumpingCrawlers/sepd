{{-- Creación de una pastilla ($pastilla).
    Puede que esté situada en una columna o que vaya suelta --}}

{{--
Tipo de pastilla:
* por un lado menú (solo para la parte de contenido, no se controla la longitud, no tiene enlace, no tiene imagen)
* por otro lado vídeos
* por otro lado imagen normal
* por otro multi-imagen, twitter, noticias, calendario
--}}
@if ($pastilla->tipo == 'Menu')
    {{-- Sin boton, formatear texto para menú --}}
    @php
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
    @endphp
@elseif ($pastilla->tipo == 'Video' || $pastilla->tipo == 'SEPD TV')
    {{-- Sin boton ni contenido, solo cabecera e imagen. Destino siempre popup, capa PLAY --}}
    @php
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
    @endphp
@elseif ($pastilla->tipo == 'Normal')
    @php
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
    @endphp
@else
    @php
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
    @endphp
@endif

{{-- HTML de la pastilla --}}
<div class="card border-0 d-flex pastilla pastilla-id-{{ $pastilla->id }} {{ $clase_pastilla }} @if (isset($margen_inf)) {{$margen_inf}} @endif">
    @if ($enlace != '-') 
        @if ($pastilla->external)    
            @if (auth()->user() && $pastilla->external)
                <a href="{{ auth()->user()->enlaceSSO($enlace) }}" {{ $destino }} class="link-pastilla">
            @else
                <a href="{{ $enlace }}" {{ $destino }} class="link-pastilla event-no-auth-sso">
            @endif
        @else
            <a href="{{ $enlace }}" {{ $destino }} class="link-pastilla">
        @endif
    @endif
        <div class="card-body p-0">
            {{-- Parte de la imagen de la pastilla --}}
            {{-- si es un vídeo, se necesita un div position-relative, para poner el vídeo en el bottom --}}
            @if (isset($hay_video) && $hay_video)
                <div class="position-relative {{ $clase_pastilla }}">
                    {!! $imagen !!}
                </div>
            {{-- si es una multipastilla, el slider va en lugar de la imagen --}}
            @elseif ($pastilla->tipo == 'Multiple')
                
            @include('paginas.pastilla-multiple')

            @else

            {{-- si hay copyright, añadirlo --}}    
            @if (isset($pastilla->partesgraficas[0]->copyright) && $pastilla->partesgraficas[0]->copyright != '')
            <!-- {{ $pastilla->partesgraficas[0]->copyright }} -->
            @endif

            {!! $imagen !!}
            
            @endif

            <div class="p-0 {{ $clase_contenido }}">
                {{-- Según tipo de pastilla => presentación del contenido --}}
                @if ($pastilla->tipo == 'Menu')

                <div class="card-text p-0">
                    @if ($texto)
                    {!! $texto !!}
                    @endif
                </div>

                @elseif ($pastilla->tipo == 'Video' || $pastilla->tipo == 'SEPD TV')

                <div class="card-header border-0 w-100 text-white {{ $pagina->menu->name }}">
                    @if ($texto_cabecera)
                    {!! $texto_cabecera !!}
                    @endif
                </div>
                <div class="row p-0{{ $clase_contenido }}">
                    {!! $play !!}
                </div>

                @elseif ($pastilla->tipo == 'Multiple')
                
                @if (isset($texto_cabecera) && $texto_cabecera)
                <div class="card-header border-0 w-100 text-white {{ $pagina->menu->name }}">
                    {!! $texto_cabecera !!}
                </div>
                @endif
                
                @elseif ($pastilla->tipo == 'Twitter')

                <div class="contenido-titulo-seccion borde-twitter">
                    <span class="bg-twitter">Tweets</span>
                </div>

                <div class="card-text p-0 pastilla-twitter">
                    @include('puzzle.twitter')
                </div>
                
                @elseif ($pastilla->tipo == 'Calendario')

                @if ($texto_cabecera)
                <div class="card-header border-0 mb-2 w-100 text-white {{ $pagina->menu->name }}">
                    {!! $texto_cabecera !!}
                </div>
                @endif
                
                <div class="card-text p-0">
                    @if ($pastilla->id!=157) 
                        @include('puzzle.calendario')
                    @else
                        @include('puzzle.calendario_investigacion', ['calendar' => \App\Calendario::calendarioInvestigacion()])
                    @endif        
                </div>
                
                @elseif ($pastilla->tipo == 'Noticias')

                @if ($texto_cabecera)
                    <div class="card-header border-0 p-0 w-100 text-white {{ $pagina->menu->name }}">
                    {!! $texto_cabecera !!}
                </div>
                @endif
                
                <div class="card-text p-0">
                    {!! getHtmlNoticias($noticias) !!}
                </div>
                
                @else {{-- pastilla normal --}}

                @if (isset($texto_cabecera) && $texto_cabecera)
                <div class="card-header border-0 w-100 text-white {{ $pagina->menu->name }}">
                    {!! $texto_cabecera !!}
                </div>
                @endif

                <div class="card-text p-2">
                    {{-- posicionar texto y boton: hay que comprobar si van al mismo sitio --}}
                    @php
                        list($topBottom, $izquierdaDerecha) = getPosicionElementoPastilla($posicion_texto);
                        list($topBottomBoton, $izquierdaDerechaBoton) = getPosicionElementoPastilla($posicion_boton);
                        // si la posición es centro hay que añadir flex y justify-content:center para la caja
                        $estilo_extra = ($posicion_texto == 5) ? 'style=display:flex;justify-content:center' : '';
                        // añadir la sombra, si hay
                        ($sombra != '')
                            ? $estilo_extra .= ($estilo_extra != '') ? $sombra : $sombra_style 
                            : null;
                    @endphp
                    @if ($texto &&
                            $texto_boton && 
                            $texto_boton != '-' &&
                            $posicion_texto == $posicion_boton )

                        <div class="elemento-pastilla position-absolute{{ $topBottom }}{{ $izquierdaDerecha }}{!! $estilo_extra !!}">

                            {{-- caja de color para el texto: DIV contenedor --}}
                            @if ($pastilla->caja)
                            <div class= "p-3 rounded" {{ 'style=background-color:'.getColorCajaTexto($pastilla).';display:table' }}>
                            @endif
                            {{-- caja de color para el texto: DIV contenedor --}}

                            {!! $texto !!}
                            <button class="btn btn-sm"{{ getHtmlEstiloBoton(null, null) }}>{{ $texto_boton }}</button>

                            {{-- caja de color para el texto: cerrar DIV contenedor --}}
                            @if ($pastilla->caja)
                            </div>
                            @endif
                            {{-- caja de color para el texto: cerrar DIV contenedor --}}

                        </div>

                    @else

                        {{-- posicionar texto y boton por separado --}}
                        @if ($texto)
                        <div class="elemento-pastilla position-absolute{{ $topBottom }}{{ $izquierdaDerecha }}"{!! $estilo_extra !!}>
                            {{-- caja de color para el texto: DIV contenedor --}}
                            @if ($pastilla->caja)
                            <div class= "p-3 rounded" {{ 'style=background-color:'.getColorCajaTexto($pastilla).';display:table' }}>
                            @endif
                            {{-- caja de color para el texto: DIV contenedor --}}

                            {!! $texto !!}

                            {{-- caja de color para el texto: cerrar DIV contenedor --}}
                            @if ($pastilla->caja)
                            </div>
                            @endif
                            {{-- caja de color para el texto: cerrar DIV contenedor --}}

                        </div>
                        @endif
                        @if ($texto_boton && $texto_boton != '-')
                        <div class="elemento-pastilla position-absolute{{ $topBottomBoton }}{{ $izquierdaDerechaBoton }}">
                            <button class="btn btn-sm"{{ getHtmlEstiloBoton(null, null) }}>{{ $texto_boton }}</button>
                        </div>
                        @endif

                    @endif
                </div>
                @endif
            </div>
        </div>
    @if ($enlace != '-') 
        </a>
    @endif
</div>

{{-- Javascripts para los diferentes tipos de pastillas --}}
@if ($pastilla->tipo == 'Twitter')
    @section('scripts')
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
    @append
@endif
