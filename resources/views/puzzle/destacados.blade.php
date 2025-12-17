{{-- Solo si hay destacados --}}

@if ($pagina->destacados->count() > 0)

    <div class="container bloque-destacados mb-3">
        <div class="row text-center justify-content-center">
            @foreach ($pagina->destacados as $destacado)
            
            {{-- comprobar el destino (si es un enlace externo) --}}
            @php
                $destino = '';
                if ($destacado->enlace) {
                    if ($destacado->destino_enlace == 'Nuevo') {
                        $destino = ' target="_blank"';
                    }
                } else {
                    // recuperarlo del menú item
                    $destino = ' target='.$destacado->menuitem->target;
                }
            @endphp

            <div class="col-sm border borde-medio @if ($loop->last) mr-auto @else mr-4 @endif  borde-{{ $pagina->menu->name }}">
                <a class="nav-link" href="{{ $destacado->enlace ?: $destacado->menuitem->url }}"{{ $destino }}>
                    {{-- incluir el icono, si hay --}}
                    @if ($destacado->partesgrafica)
                    <img class='icono-destacado' src="{{ Voyager::image($destacado->partesgrafica->imagen) }}">
                    @endif
                    <p style="display:inline">{{ $destacado->texto ?: $destacado->menuitem->title }}</p>
                </a>
            </div>

            @endforeach
        </div>
    </div>

@endif
