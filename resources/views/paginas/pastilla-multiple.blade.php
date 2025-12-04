<div id="carouselPastilla{{ $pastilla->id }}" class="carousel carousel-{{ $pastilla->formato }} px-0" data-ride="carousel" data-interval="3000" style="overflow:hidden">
    {{-- indicador de imágenes --}}
    <ol class="carousel-indicators pastilla-multiple">
        @for ($i = 0; $i < $numDiapos; $i++)
        <li data-target="#carouselPastilla{{ $pastilla->id }}" data-slide-to="{{ $i }}"@if ($i == 0) class="active" @endif></li>
        @endfor
    </ol>
    {{-- recorrer las diapositivas --}}
    <div class="carousel-inner">
        @foreach ($pastilla->partesgraficas as $diapo)
            @php
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
                
            @endphp
            <div class="carousel-item @if ($loop->first) active @endif">
                <a href="{{ $enlace }}"{!! $destino !!} class="link-pastilla {{ $class_enlace }}">
                    {{-- imagen? --}}
                    @if ($diapo->imagen)
                        {{-- si hay copyright, añadirlo --}}    
                        @if (isset($diapo->copyright) && $diapo->copyright != '')
                            <!-- {{ $pastilla->partesgraficas[0]->copyright }} -->
                        @endif

                        <img class="img-fluid w-100" src="{{ Voyager::image($diapo->imagen) }}">
                    @endif

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
                         $texto_boton && $texto_boton != '-' &&
                         $posicion_texto == $posicion_boton )
                        <div class="elemento-pastilla position-absolute{{ $topBottom }}{{ $izquierdaDerecha }}"{!! $estilo_extra !!}>
                            {{-- caja de color para el texto: DIV contenedor --}}
                            @if ($diapo->pivot->caja)
                            <div class= "p-3 rounded" {{ 'style=background-color:'.getColorCajaTexto($diapo->pivot).';display:table' }}>
                            @endif
                            {{-- caja de color para el texto: DIV contenedor --}}

                            {!! $texto !!}
                            <button class="btn btn-sm"{{ getHtmlEstiloBoton(null, null) }}>{{ $texto_boton }}</button>

                            {{-- caja de color para el texto: cerrar DIV contenedor --}}
                            @if ($diapo->pivot->caja)
                            </div>
                            @endif
                            {{-- caja de color para el texto: cerrar DIV contenedor --}}

                        </div>
                    @else

                        {{-- posicionar texto y boton por separado --}}
                        @if ($texto)
                            <div class="elemento-pastilla position-absolute{{ $topBottom }}{{ $izquierdaDerecha }}"{!! $estilo_extra !!}>

                                {{-- caja de color para el texto: DIV contenedor --}}
                                @if ($diapo->pivot->caja)
                                <div class= "p-3 rounded" {{ 'style=background-color:'.getColorCajaTexto($diapo->pivot).';display:table' }}>
                                @endif
                                {{-- caja de color para el texto: DIV contenedor --}}

                                {!! $texto !!}

                                {{-- caja de color para el texto: cerrar DIV contenedor --}}
                                @if ($diapo->pivot->caja)
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


                    {{--
                    @if (isset($texto) && $texto != '')
                    <div class="card-text pt-5 card-img-overlay">
                        {!! $texto !!}
                    </div>
                    @endif
                    @if ($diapo->pivot->texto_boton != '' && $diapo->pivot->texto_boton != '-')
                    <div class="container">
                        <div class="btn-group position-absolute">
                            <p class="btn" {!! getHtmlEstiloBoton(null, null) !!} >{{ $diapo->pivot->texto_boton }}</p>
                        </div>
                    </div>
                    @endif
                    --}}
                </a>
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev pastilla-multiple" href="#carouselPastilla{{ $pastilla->id }}" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next pastilla-multiple" href="#carouselPastilla{{ $pastilla->id }}" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>
