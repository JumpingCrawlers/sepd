{{-- Creación de una columna pastilla.
     Recibe todos los datos necesarios en $columna_pastillas
     Cada columna contiene n pastillas con todos sus datos
     Si hay más de una pastilla en la columna, mete margen inferior --}}

    <div class="col-md-{{ $columnas }} d-flex flex-column align-content-stretch">
        
        {{-- control del margen --}}
        @php
            ($pagina->alguna_pastilla_doble) ? $clase_para_margen = " mb-auto" : $clase_para_margen = " mb-3";
            (count($columna_pastillas) > 1) ? $margen_inf = $clase_para_margen : $margen_inf = "";
        @endphp
        @foreach ($columna_pastillas as $pastilla)

            {{-- la última pastilla no tiene margen --}}
            @if ($loop->last)
                @php
                    $margen_inf = " align-self-end";
                @endphp
            @endif
            
            @include('paginas.pastilla')
            
        @endforeach
    </div>