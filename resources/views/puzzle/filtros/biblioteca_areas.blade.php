@php
    $nombre_grupo = 'areas';
    $titulo_grupo = 'Area de interés';
    $grupo = \App\Area::orderBy('nombre')->get();
    $seccion = isset($seccion) ? $seccion : 'formacion';
@endphp

@include('puzzle.filtros.filtro_plantilla_areas', ['tis' => $tis])