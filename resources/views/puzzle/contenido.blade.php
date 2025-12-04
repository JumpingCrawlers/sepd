<div class="container px-0 mb-3">
    <div class="row">
        @if (isset($pagina) && isset($pagina->contenido_extra) && $pagina->contenido_extra)
            @php
                // controlar que las columnas es correcto (entre 1 y 6)
                ($pagina->columnas_extra < 1 || $pagina->columnas_extra > 6) ? $columnas_extra = 3 : $columnas_extra = $pagina->columnas_extra;
                ($pagina->posicion_extra == "derecha") ? $posicion = " order-last"  : $posicion = "";
            @endphp
            <div id="contenido-extra" class="col-sm-{{ $columnas_extra }}{{ $posicion }} @if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">
                @foreach ($pagina->pastillas_contenido as $pastilla)

                    @php $margen_inf = 'mb-3'; @endphp
                    @include('paginas.pastilla')

                @endforeach
            </div>

        @else
            @php
                $columnas_extra = 0;
            @endphp
        @endif
        <div class="col-sm-{{ 12 - $columnas_extra }} pl-4" id="contenido-detalle">
            @yield('contenido-detalle')
        </div>
    </div>
</div>
