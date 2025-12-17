{{-- <div class="contenido-titulo-seccion borde-institucional mt-3">
    <span class="bg-institucional">Especialización / MIR</span>
</div> --}}

<div class="row">
    <label class="col-sm-3 col-form-label">Año de inicio / fin:</label>
    <label class="col-sm-8 col-form-label">
        @if (isset($usuario->datos_profesionales->fecha_inicio_MIR)) {{ $usuario->datos_profesionales->fecha_inicio_MIR . " / " . $usuario->datos_profesionales->fecha_fin_MIR  }} @endif
    </label>
    <label class="col-sm-3 col-form-label">Centro:</label>
    <label class="col-sm-8 col-form-label">
        @if (isset($usuario->datos_profesionales->residencia)) {{ $usuario->datos_profesionales->residencia }} @endif
    </label>
</div>
