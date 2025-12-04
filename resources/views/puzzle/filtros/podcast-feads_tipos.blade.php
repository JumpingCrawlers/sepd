@php
    $nombre_grupo = 'tipo';
    $titulo_grupo = 'Tipo de contenido';
    $grupo = $tipos;
    $seccion = 'prensa';
@endphp

{{-- @include('puzzle.filtros.filtro_plantilla') --}}
{{-- ATENCION --}}
{{-- Este código es similar al del helper getHtmlMenuPastilla (helpers/views.php) --}}
{{-- Cambios en el HTML aquí deberían modificarse también allí y viceversa.       --}}

<div id="grupo-{{ $nombre_grupo }}" class="row pl-3 py-3 align-items-center grupo-menu-izquierda {{ $seccion }}" data-activo="false">

    <div class="w-100 position-relative">
        <a class="collapsed" data-toggle="collapse" href="#filtro{{ ucfirst($nombre_grupo) }}" role="button" aria-expanded="false" aria-controls="filtro{{ $nombre_grupo }}">
            <h5 class="mb-0">{{ $titulo_grupo }}</h5>
            <div class="position-absolute flecha {{ $seccion }}"></div>
        </a>
    </div>

</div>

<div class="row collapse p-1" id="filtro{{ ucfirst($nombre_grupo) }}">
    @foreach ($grupo as $elemento)
        <div class="container">
            <div class="row">
                <div class="col-1 col-sm-2 col-md-1 px-0">
                    <input type="radio" id="{{ $nombre_grupo }}_{{ $elemento->id }}" name="tipo_contenido" value="{{ $elemento->id }}"/>
                </div>
                <div class="col-11 col-sm-10 col-md-11 px-0">
                    <label for="{{ $nombre_grupo }}_{{ $elemento->id }}" class="d-inline"> 
                        {{ $elemento->nombre }}
                    </label>
                </div>

            </div>
        </div>
    @endforeach
</div>
