<div class="contenido-titulo-seccion borde-institucional mt-4">
    <span class="bg-institucional">Especialización / MIR</span>
</div>

<div class="form-group row">
    <label for="fecha_inicio_MIR" class="col-sm-2 col-form-label">MIR: Año inicio:</label>
    <div class="col-sm-3 col-md-2 col-xl-1">
        <input name="fecha_inicio_MIR" type="year" value="{{ old('fecha_inicio_MIR', isset($usuario->datos_profesionales->fecha_inicio_MIR) ? $usuario->datos_profesionales->fecha_inicio_MIR : '') }}" class="form-control{{ $errors->has('fecha_inicio_MIR') ? ' is-invalid' : '' }}" size="2" maxlength="4" placeholder="aaaa">
        @if ($errors->has('fecha_inicio_MIR'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('fecha_inicio_MIR') }}</strong>
        </div>
        @endif
    </div>
    <label for="fecha_fin_MIR" class="col-sm-3 col-md-2 col-xl-1 col-form-label">Año Fin:</label>
    <div class="col-sm-3 col-md-2 col-xl-1">
        <input name="fecha_fin_MIR" type="year" value="{{ old('fecha_fin_MIR', isset($usuario->datos_profesionales->fecha_fin_MIR) ? $usuario->datos_profesionales->fecha_fin_MIR : '') }}" class="form-control{{ $errors->has('fecha_fin_MIR') ? ' is-invalid' : '' }}" size="2" maxlength="4" placeholder="aaaa">
        @if ($errors->has('fecha_fin_MIR'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('fecha_fin_MIR') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="residencia" class="col-sm-2 col-form-label">Nombre del centro</label>
    <div class="col-sm-6">
        <input name="residencia" type="text" value="{{ old('residencia', isset($usuario->datos_profesionales->residencia) ? $usuario->datos_profesionales->residencia : '') }}" class="form-control{{ $errors->has('residencia') ? ' is-invalid' : '' }}" placeholder="Nombre del centro">
        @if ($errors->has('residencia'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('residencia') }}</strong>
        </div>
        @endif
    </div>
</div>

