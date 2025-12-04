@php
    $nombre_grupo = 'areagestion';
    $titulo_grupo = 'Área del proyecto';
    $grupo = json_decode(json_encode(array(
        array(
            "id" => "investigacionRegistros",
            "nombre" => "Investigación: registros"
        ),
        array(
            "id" => "investigacionEncuestas",
            "nombre" => "Investigación: encuestas"
        ),
        array(
            "id" => "investigacionDocumentos",
            "nombre" => "Investigación: documentos consenso y otros proyectos de investigación"
        ),
        array(
            "id" => "investigacionProyectos",
            "nombre" => "Investigación: proyectos de investigación de interés"
        ),
        array(
            "id" => "excelenciaGestion",
            "nombre" => "Gestión clínica"
        ),
        array(
            "id" => "excelenciaCalidad",
            "nombre" => "Calidad"
        ),
        
    )), FALSE);
    $seccion = 'cid';
@endphp

{{-- Gestión especial de las áreas de gestión (es un parámetro url) --}}
@include('puzzle.filtros.filtro_plantilla', ['areaGestion' => $areaGestion, 'areaCalidad' => $areaCalidad])
