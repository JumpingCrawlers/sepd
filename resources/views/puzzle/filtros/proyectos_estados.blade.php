@php
    $nombre_grupo = 'estados';
    $titulo_grupo = 'Estado';
    $grupo = json_decode(json_encode(array(
        array(
            "id" => 1,
            "nombre" => "En curso"
        ),
        array(
            "id" => 2,
            "nombre" => "Históricos"
        ),
    )), FALSE);
    $seccion = 'cid';
@endphp

{{-- Gestión especial de los estados de proyecto (es un parámetro url) --}}
{{-- Pasar una variable nueva para identificarla solo en este caso (estados) --}}
@include('puzzle.filtros.filtro_plantilla', ['estado_proyecto' => $estadoProyecto])
