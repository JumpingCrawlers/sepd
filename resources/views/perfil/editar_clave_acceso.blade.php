<div class="contenido-titulo-seccion borde-institucional">
    <span class="bg-institucional">Clave de acceso</span>
</div>

<div class="form-group row mb-4">
    <label for="old_password" class="col-sm-2 col-form-label">Clave anterior:</label>
    <div class="col-sm-6">
        <input name="old_password" type="password" value="" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}">
        @if ($errors->has('old_password'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('old_password') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label">Nueva clave:</label>
    <div class="col-sm-6">
        <input name="password" type="password" value="" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
        @if ($errors->has('password'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="password_confirmation" class="col-sm-2 col-form-label">Confirmar clave:</label>
    <div class="col-sm-6">
        <input name="password_confirmation" type="password" value="" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
        @if ($errors->has('password_confirmation'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </div>
        @endif
    </div>
</div>
