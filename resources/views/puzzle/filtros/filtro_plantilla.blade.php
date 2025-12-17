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
            {{-- Gestión especial para Biblioteca: TIS marcar/desmarcar TIS y Videoteca --}}
            {{-- Gestión especial para Proyectos: Área de gestión y Estado --}}
            @php
                if (isset($tis) && $tis) {
                    if ($nombre_grupo == "formato" && $elemento->id == 15) {
                        $chequeado = 'checked';
                        $restriccion = 'disabled';
                        $clase_etiqueta = 'color-gris-fondo';
                    } elseif ($nombre_grupo == "emedia" && $elemento->id == "webcast") {
                        $chequeado = '';
                        $restriccion = 'disabled';
                        $clase_etiqueta = 'color-gris-fondo';
                    } else {
                        $chequeado = '';
                        $restriccion = '';
                        $clase_etiqueta = '';
                    }
                }elseif ((isset($areaGestion) && $areaGestion) || (isset($areaCalidad) && $areaCalidad)) {
                    if (
                        (isset($areaGestion) && $areaGestion && $areaGestion == $elemento->id) ||
                        (isset($areaCalidad) && $areaCalidad && $areaCalidad == $elemento->id) ||
                        (isset($_GET['idgestion']) && $_GET['idgestion'] == $elemento->id)
                    ) {
                        $chequeado = 'checked';
                        $restriccion = '';
                        $clase_etiqueta = '';
                    } else {
                        $chequeado = '';
                        $restriccion = '';
                        $clase_etiqueta = '';
                    }
                } elseif (isset($estado_proyecto) && $estado_proyecto) {
                    if ($estado_proyecto == $elemento->id) {
                        $chequeado = 'checked';
                        $restriccion = '';
                        $clase_etiqueta = '';
                    } else {
                        $chequeado = '';
                        $restriccion = '';
                        $clase_etiqueta = '';
                    }
                } else {
                    $chequeado = '';
                    $restriccion = '';
                    $clase_etiqueta = '';
                }
            @endphp
            <div class="col-1 col-sm-2 col-md-1 px-0">
                <input type="checkbox" id="{{ $nombre_grupo }}_{{ $elemento->id }}" name="{{ $nombre_grupo }}_{{ $elemento->id }}" {{ $chequeado }} {{ $restriccion }}/>
            </div>
            <div class="col-11 col-sm-10 col-md-11 px-0">
                <label for="{{ $nombre_grupo }}_{{ $elemento->id }}" class="d-inline {{ $clase_etiqueta }}"> 
                    {{ $elemento->nombre }}
                </label>
            </div>

        </div>
    </div>
    @endforeach

</div>
