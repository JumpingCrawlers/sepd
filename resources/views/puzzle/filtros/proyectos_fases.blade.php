@php
    $nombre_grupo = 'fases';
    $titulo_grupo = 'Fase';
    $grupo = json_decode(json_encode(array(
        array(
            "id" => 1,
            "nombre" => "Diseño"
        ),
        array(
            "id" => 2,
            "nombre" => "Reclutamiento"
        ),
        array(
            "id" => 3,
            "nombre" => "Análisis"
        ),
        array(
            "id" => 4,
            "nombre" => "Difusión"
        ),
        array(
            "id" => 5,
            "nombre" => "Cerrado"
        ),
    )), FALSE);
    $seccion = 'cid';
@endphp

@include('puzzle.filtros.filtro_plantilla')
