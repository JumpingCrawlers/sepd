{{-- <div class="contenido-titulo-seccion borde-institucional mt-3">
    <span class="bg-institucional">Datos profesionales</span>
</div> --}}

<div class="row">
    <label class="col-sm-2 col-form-label">Titulación:</label>
    <label class="col-sm-9 col-form-label">
        @if (isset($usuario->datos_profesionales->titulacion)) {{ $usuario->datos_profesionales->titulacion }} @endif
    </label>
    <label class="col-sm-2 col-form-label">Especialidad:</label>
    <label class="col-sm-9 col-form-label">
        @if ($usuario->especialidades)
            @foreach ($usuario->especialidades as $usuario_especialidad)
                @if ($usuario_especialidad->especialidad)
                {{ $usuario_especialidad->especialidad->nombre }}<br>
                @endif
            @endforeach
        @endif
    </label>
</div>
