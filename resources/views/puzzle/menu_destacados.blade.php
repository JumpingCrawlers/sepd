<div class="container mt-2 mb-3 menu-destacados">
    @foreach ($opciones as $fila)
        <div class="row text-center align-items-stretch mb-2 menu-destacados-fila">
            @foreach ($fila as $opcion)
                <div @if (isset($opcion['id'])) {{ 'id=' . $opcion['id'] }} @endif
                    class="col-sm @if ($loop->last) mr-auto @else mr-1 @endif px-0 py-1 d-flex menu-destacados {{ $pagina->menu->name }}@if (isset($opcion['clase_css'])) {{ $opcion['clase_css'] }} @endif">
                    <div class="container align-self-center px-0">
                        <a href="{{ '/' . $destino . '/' . ($opcion['id'] != 'todos' ? $opcion['id'] : '') }}" role="button"
                            class="btn py-0">{{ $opcion['texto'] }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
