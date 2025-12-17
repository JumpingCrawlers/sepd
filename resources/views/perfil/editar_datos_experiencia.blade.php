<div class="contenido-titulo-seccion borde-institucional mt-4">
    <span class="bg-institucional">Experiencia profesional</span>
</div>

<div class="form-group row">
    <label for="cargo" class="col-sm-2 col-form-label">Cargo actual: *</label>
    <div class="col-sm-6">
        <input name="cargo" type="text" value="{{ old('cargo', isset($usuario->centros->cargo) ? $usuario->centros->cargo : '') }}" class="form-control{{ $errors->has('cargo') ? ' is-invalid' : '' }}" placeholder="Cargo actual" required>
        @if ($errors->has('cargo'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('cargo') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="centro" class="col-sm-2 col-form-label">Centro de trabajo: *</label>
    <div class="col-sm-6">
        <input name="centro" type="text" value="{{ old('centro', isset($usuario->centros->centro) ? $usuario->centros->centro : '') }}" class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}" placeholder="Centro de trabajo" required>
        @if ($errors->has('centro'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('centro') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="publico" class="col-sm-2 col-form-label">Tipo de centro: *</label>
    <div class="col-sm-6">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="publico" id="publico_1" value="1" @if(old('publico', isset($usuario->centros->publico) ? $usuario->centros->publico : '') == '1') checked @endif required>
            <label class="form-check-label" for="publico_1">Público</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="publico" id="publico_2" value="0" @if(old('publico', isset($usuario->centros->publico) ? $usuario->centros->publico : '') == '0') checked @endif required>
            <label class="form-check-label" for="publico_2">Privado</label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="centro_direccion" class="col-sm-2 col-form-label">Dirección:</label>
    <div class="col-sm-6">
        <input name="centro_direccion" type="text" value="{{ old('centro_direccion', isset($usuario->centros->direccion) ? $usuario->centros->direccion : '') }}" class="form-control{{ $errors->has('centro_direccion') ? ' is-invalid' : '' }}" placeholder="Dirección">
        @if ($errors->has('centro_direccion'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('centro_direccion') }}</strong>
        </div>
        @endif
    </div>
</div>
