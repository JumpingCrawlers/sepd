{{-- Solo si hay slider en la $pagina con al menos una diapositiva --}}
@if ($pagina->slider)
@php $numDiapos = $pagina->slider->partesgraficas->count() @endphp
@if ($numDiapos > 0)
{{-- cabecera del slider --}}
<div id="carouselPrincipal" class="carousel carousel{{ $formato_carousel }} px-0" data-ride="carousel"
  style="overflow:hidden">
  {{-- indicador de diapositiva: tantos como diapositivas --}}
  <ol class="carousel-indicators">
    @for ($i = 0; $i < $numDiapos; $i++) <li data-target="#carouselPrincipal" data-slide-to="{{ $i }}" @if ($i==0)
      class="active" @endif>
      </li>
      @endfor
  </ol>
  {{-- recorrer las diapositivas --}}
  <div class="carousel-inner">
    @foreach ($pagina->slider->partesgraficas as $diapo)
    <div class="carousel-item @if ($loop->first) active @endif">
      {{-- imagen? --}}
      @if ($diapo->imagen)
      <img src="{{ Voyager::image($diapo->imagen) }}">
      @endif
      <div class="container">
        {{-- posicion y alineación de elementos
        1=>top-left; 2=>top-center... --}}
        @php
        list($topBottom, $izquierdaDerecha) = getPosicionElementoSlider($diapo->pivot->posicion_elementos);
        list($topBottomBoton, $izquierdaDerechaBoton) = getPosicionElementoSlider($diapo->pivot->posicion_boton);
        $boton_aparte = ($topBottom != $topBottomBoton || $izquierdaDerecha != $izquierdaDerechaBoton);
        // si posición es derecha, hay que añadir un float:right a la caja
        $float_caja = ($izquierdaDerecha == ' derecha') ? ';float:right' : '';
        $float_boton = ($izquierdaDerecha == ' derecha') ? ' float-right' : '';
        // inicializar estilo extra (solo para alineación derecha)
        $estilo_texto_derecha = '';
        @endphp
        @switch($diapo->pivot->texto_align)
        @case('Left')
        @php $textoAlign = ' text-left'; @endphp
        @break
        @case('Right')
        @php
        $textoAlign = ' text-right';
        // si además, la posición es derecha, los elementos de la caja han de ser flex y justify-content:end
        $estilo_texto_derecha = ($izquierdaDerecha == ' derecha') ? ';display:flex;justify-content:flex-end' : '';
        @endphp
        @break
        @default
        @php $textoAlign = ''; @endphp
        @endswitch
        <div class="carousel-caption{{ $topBottom }}{{ $textoAlign }}{{ $izquierdaDerecha }}">
          {{-- guardar el color del texto, si hay, y la sombra, si hay --}}
          @php
            ($diapo->pivot->texto_color)
            ? $estilos_texto = ' style="color:'.$diapo->pivot->texto_color.$estilo_texto_derecha
            : $estilos_texto = ' style="'.$estilo_texto_derecha;
            (($diapo->pivot->texto_sombra_inversa)
            ? $estilos_texto .= ';'.getSombraTexto(true)
            : ($diapo->pivot->texto_sombra))
            ? $estilos_texto .= ';'.getSombraTexto(false)
            : null;
            $estilos_texto .= '"';
          @endphp
          {{-- caja de color para el texto: DIV contenedor --}}
          @if ($diapo->pivot->caja)
            <div class="p-3 rounded" {{ 'style=background-color:' .getColorCajaTexto($diapo->pivot).';display:table'.$float_caja }}>
          @endif
          {{-- caja de color para el texto: DIV contenedor --}}

          {{-- titulo de la diapo --}}
          @if ($diapo->pivot->titulo)
            <h3{!! $estilos_texto !!}>{!! $diapo->pivot->titulo !!}</h3>
          @endif
            <p{!! $estilos_texto !!}>{!! $diapo->pivot->texto !!}</p>

            {{-- configurar el botón, si hay --}}
            @if (!$boton_aparte && $diapo->pivot->boton_texto)
              @php
                $enlace = $diapo->pivot->enlace;
                $class_sso = ''; 
                if ($diapo->pivot->external) {
                  if (auth()->user())
                    $enlace = auth()->user()->enlaceSSO($enlace);
                  else 
                    $class_sso = 'event-no-auth-sso';
                }
              @endphp
              <p class="mb-0 {{ $float_boton }}">
                <a
                  class="btn {{ $class_sso }}"
                  href="{{ $enlace }}"
                  role="button" {{ getHtmlEstiloBoton($diapo->pivot->boton_color, $diapo->pivot->boton_bgcolor) }}
                  {{ getHtmlDestino($diapo->pivot->destino_enlace) }}>
                  {{ $diapo->pivot->boton_texto }}
                </a>
              </p>
            @endif
            {{-- caja de color para el texto: cerrar DIV contenedor --}}
          @if ($diapo->pivot->caja)
            </div>
          @endif
          {{-- caja de color para el texto: cerrar DIV contenedor --}}
        </div>

        @if ($boton_aparte && $diapo->pivot->boton_texto)
          {{-- Si el botón está aparte, además se alinea a izquierda o derecha el texto
          ya que el texto no tiene un atributo de alinear específico --}}
          @switch($izquierdaDerechaBoton)
            @case(' izquierda')
              @php $izquierdaDerechaBoton .= ' text-left'; @endphp
            @break
            @case(' derecha')
              @php $izquierdaDerechaBoton .= ' text-right'; @endphp
            @break
          @endswitch

          <div class="carousel-caption{{ $topBottomBoton }}{{ $izquierdaDerechaBoton }}">    
            <p class="mb-0{{ $float_boton }}">
              @php
                $enlace = $diapo->pivot->enlace;
                $class_sso = ''; 
                if ($diapo->pivot->external) {
                  if (auth()->user())
                    $enlace = auth()->user()->enlaceSSO($enlace);
                  else 
                    $class_sso = 'event-no-auth-sso';
                }
              @endphp
              <a
                class="btn {{ $class_sso }}"
                href="{{ $enlace }}"
                role="button"
                diapo-id="{{ $diapo->id }}"
                {{ getHtmlEstiloBoton($diapo->pivot->boton_color, $diapo->pivot->boton_bgcolor) }}
                {{ getHtmlDestino($diapo->pivot->destino_enlace) }}>
                {{ $diapo->pivot->boton_texto }}
              </a>
            </p>
          </div>
        @endif
      </div>
    </div>
    @endforeach
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
@auth
@if(\App\UsuarioSocio::where('usuario_id',
  auth()->id())->first()&&(!request()->is('formacion')&&!request()->is('investigacion')&&!request()->is('inicio_gyc')&&!request()->is('publicaciones')))
  <div class="alert alert-warning" role="alert">
    La información sobre medicamentos está dirigida exclusivamente al profesional destinado a prescribirlos o
    dispensarlos, por lo que se requiere una formación especializada para su correcta interpretación.
</div>
@endif
@endauth

@endif
@endif

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
</script>