<div class="contenido-titulo-seccion borde-institucional mb-4">
    <span class="bg-institucional">Datos profesionales</span>
</div>
<div class="form-group row">
    <label for="sociedad" class="col-sm-2 col-form-label">Sociedad Nacional *</label>
    <div class="col-sm-10">
        <select name="sociedad" class="form-control @if (isset($errors)) {{ $errors->has('sociedad') ? ' is-invalid' : '' }} @endif">
        @foreach ($sociedades as $sociedad)
            <option value="{{ $sociedad->id }}" {{ (old('sociedad') == $sociedad->id) ? 'selected' : '' }}>
                @if ($sociedad->name == 'Miembro Internacional') Sociedad Nacional sin acuerdo con la SEPD @else {{ $sociedad->name }} @endif -- {{ $sociedad->cuota }}&euro;
            </option>
        @endforeach
        </select>
        @if ($errors->has('sociedad'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('sociedad') }}</strong>
        </div>
        @endif
    </div>
</div>
