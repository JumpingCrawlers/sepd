    {{-- Solo si hay imagen --}}
    @if (isset($pagina->partesgrafica->imagen))
    
        @switch ($pagina->tipo_slider)

        @case('2s+1i')
            <div class="imagen-slider-2x1 position-relative">
            @break
        @case('2sA+1iA')
            <div class="imagen-slider-2x1A position-relative">
            @break
        @case('3s+1i')
            <div class="imagen-slider-3x1 position-relative">
            @break
        @case('3i')
            <div class="imagen-slider position-relative">
            @break

        @endswitch
        <div class="imagen-slider-wrapper">
        
        {{-- si hay enlace, añadirlo --}}
        @if (isset($pagina->partesgrafica->enlace) && $pagina->partesgrafica->enlace != '')
        <a href="{{ $pagina->partesgrafica->enlace }}"{{ getHtmlDestino($pagina->partesgrafica->destino_enlace) }}>
        @endif

        {{-- si hay copyright, añadirlo --}}
        @if (isset($pagina->partesgrafica->copyright) && $pagina->partesgrafica->copyright != '')
        <!-- {{ $pagina->partesgrafica->copyright }} -->
        @endif
        <img src="{{ Voyager::image($pagina->partesgrafica->imagen) }}" class="img-fluid">

        @if (isset($pagina->partesgrafica->enlace) && $pagina->partesgrafica->enlace != '')
        </a>
        @endif
        
        </div>
        </div>

    @endif
