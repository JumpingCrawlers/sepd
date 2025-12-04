@php
    $nombre_grupo = 'areagestion';
    $titulo_grupo = 'Área de gestión';
    $grupo = json_decode(json_encode(array(
        array(
            "id" => "investigacion",
            "nombre" => "Investigación"
        ),
        array(
            "id" => "clinica",
            "nombre" => "Gestión clínica"
        ),
        array(
            "id" => "calidad",
            "nombre" => "Calidad"
        ),
        
    )), FALSE);
    $seccion = 'cid';
@endphp

{{-- Gestión especial de las áreas de gestión (es un parámetro url) --}}
@include('puzzle.filtros.filtro_plantilla', ['areagestion' => $areagestion])
