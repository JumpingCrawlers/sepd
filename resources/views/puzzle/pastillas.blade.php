{{-- Solo si hay pastillas --}}

@if ($pagina->pastillas->count() > 0)

    {{-- El bloque de pastillas no tiene mb-3 porque se mete en cada pastilla!!!!!  --}}
    {{-- Como las pastillas se puede mostrar en columnas verticales u horizontales  --}}
    {{-- para responsive, hay que meter el margen en cada pastilla  --}}
    <div class="container px-0 mb-3">
        {{-- Hay que controlar las pastillas dobles  --}}
        <div class="row">
            @php
                $columnas = 12 / $pagina->columnas_pastillas;
            @endphp
            @foreach ($pagina->pastillas_en_columnas as $columna_pastillas)

                @include('paginas.columna_pastillas')

            @endforeach
        </div>
    </div>

@endif
