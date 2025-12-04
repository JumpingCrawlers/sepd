@php
    $nombre_grupo = 'formatos';
    $titulo_grupo = 'Formato';
    $formatos = array(
        array('id' => 'curso', 'nombre' => 'Curso'),
        array('id' => 'publicacion', 'nombre' => 'Publicación'),
        array('id' => 'recurso', 'nombre' => 'Recurso multimedia')
    );
    $grupo = json_decode(json_encode($formatos), FALSE);
    $seccion = 'formacion';
@endphp

@include('puzzle.filtros.filtro_plantilla')
