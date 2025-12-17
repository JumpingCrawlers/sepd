{{-- <div class="contenido-titulo-seccion borde-institucional">
    <span class="bg-institucional">Datos personales</span>
</div> --}}

<div class="row">
    <label class="col-sm-2 col-form-label">Dirección:</label>
    <label class="col-sm-9 col-form-label">
        @if ($usuario->direcciones)
            {{ $usuario->direcciones->via . " " . $usuario->direcciones->direccion }}<br>
            {{ $usuario->direcciones->codigo_postal . " " . $usuario->direcciones->ciudad . ", " . (($usuario->direcciones->pais->nombre == 'España') ? $usuario->direcciones->provincia->nombre : $usuario->direcciones->provincia_otros) . " - " . $usuario->direcciones->pais->nombre }}<br>
        @endif
    </label>
    <label class="col-sm-2 col-form-label">Teléfonos:</label>
    <label class="col-sm-9 col-form-label">
        @if ($usuario->datos_basicos)
            @if ($usuario->datos_basicos->telefono != '' && $usuario->datos_basicos->telefono != '0')
            @php $separador = ' / '; @endphp
            {{ $usuario->datos_basicos->telefono }}
            @endif
            @if ($usuario->datos_basicos->movil != '' && $usuario->datos_basicos->movil != '0')
            {{ isset($separador) ? $separador. $usuario->datos_basicos->movil : $usuario->datos_basicos->movil}}
            @endif
        @endif
    </label>
    <label class="col-sm-2 col-form-label">Fecha de nacimiento:</label>
    <label class="col-sm-9 col-form-label">
        @if ($usuario->datos_basicos)
            {{ App\Model::formatea_fecha($usuario->datos_basicos->fecha_nacimiento) }}
        @endif
    </label>
    <label class="col-sm-2 col-form-label">Sexo:</label>
    <label class="col-sm-9 col-form-label">
        @if ($usuario->datos_basicos)
            @switch($usuario->datos_basicos->sexo)
                @case('a')
                Mujer
                @break
                @default
                Hombre
            @endswitch
        @endif
    </label>
</div>
