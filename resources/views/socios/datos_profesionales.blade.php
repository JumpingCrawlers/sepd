<div class="contenido-titulo-seccion borde-institucional mb-4">
    <span class="bg-institucional">Datos profesionales</span>
</div>

<div class="form-group row">
    <label for="titulacion" class="col-sm-2 col-form-label">Titulación académica *</label>
    <div class="col-sm-10">
        <select name="titulacion" value="{{ old('titulacion') }}" class="form-control{{ $errors->has('titulacion') ? ' is-invalid' : '' }}" onchange="titulacionChange(this)">
            <option value>Escoge una titulación académica</option>
            <option value="Medicina">Medicina</option>
            <option value="Enfermería">Enfermería</option>
            <option value="Nutrición Humana y Dietética">Nutrición Humana y Dietética</option>
            <option value="Biología">Biología</option>
            <option value="Farmacia">Farmacia</option>
            <option value="Otros">Otros</option>
        </select>
        @if ($errors->has('titulacion'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('titulacion') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row" id="especialidadID">
    <label class="col-sm-2 col-form-label">Especialidad *</label>
    <div class="col-sm-10">
        @foreach (App\Especialidad::whereNull('especialidad_padre')->where('habilitado', 1)->orderBy('order','asc')->get() as $especialidad)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="especialidad[]" id="especialidad_{{ $especialidad->id }}" value="{{ $especialidad->id }}" @if(is_array(old('especialidad')) && in_array($especialidad->id, old('especialidad'))){{ 'checked' }}@endif>
            <label class="form-check-label" for="especialidad_{{ $especialidad->id }}">{{ $especialidad->nombre }}</label>

            @foreach ($especialidad->subespecialidades->where('habilitado', 1) as $subespecialidad)
            <div class="offset-1">
                <input class="form-check-input" type="checkbox" name="especialidad[]" id="subespecialidad_{{ $subespecialidad->id }}" value="{{ $subespecialidad->id }}" @if(is_array(old('especialidad')) && in_array($subespecialidad->id, old('especialidad'))){{ 'checked' }}@endif>
                <label class="form-check-label" for="subespecialidad_{{ $subespecialidad->id }}">{{ $subespecialidad->nombre }}</label>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

@if ($tipo_socio=="formacion")
<div class="form-group row">
    <label for="fecha_inicio_mir" class="col-sm-2 col-form-label">Año inicio MIR *</label>
    <div class="col-sm-2">
        <input name="fecha_inicio_mir" type="year" value="{{ old('fecha_inicio_mir') }}" class="form-control{{ $errors->has('fecha_inicio_mir') ? ' is-invalid' : '' }}" size="2" maxlength="4" placeholder="aaaa">
        @if ($errors->has('fecha_inicio_mir'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('fecha_inicio_mir') }}</strong>
        </div>
        @endif
    </div>
    <label for="fecha_fin_mir" class="col-sm-2 col-form-label">Año fin MIR *</label>
    <div class="col-sm-2">
        <input name="fecha_fin_mir" type="year" value="{{ old('fecha_fin_mir') }}" class="form-control{{ $errors->has('fecha_fin_mir') ? ' is-invalid' : '' }}" size="2" maxlength="4" placeholder="aaaa">
        @if ($errors->has('fecha_fin_mir'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('fecha_fin_mir') }}</strong>
        </div>
        @endif
    </div>
</div>
@endif

@if ($tipo_socio=="numerario")
<div class="form-group row">
    <label for="publico" class="col-sm-2 col-form-label">Centro de trabajo</label>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="publico" id="publico_1" value="1">
            <label class="form-check-label" for="publico_1">Público</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="publico" id="publico_2" value="0">
            <label class="form-check-label" for="publico_2">Privado</label>
        </div>
    </div>
</div>
@endif
<div class="form-group row">
    <label for="centro" class="col-sm-2 col-form-label">Nombre del centro *</label>
    <div class="col-sm-10">
        <input name="centro" type="text" value="{{ old('centro') }}" class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}" placeholder="Nombre del centro">
        @if ($errors->has('centro'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('centro') }}</strong>
        </div>
        @endif
    </div>
</div>
@if ($tipo_socio=="numerario")
<div class="form-group row">
    <label for="cargo" class="col-sm-2 col-form-label">Cargo actual</label>
    <div class="col-sm-10">
        <input name="cargo" type="text" value="{{ old('cargo') }}" class="form-control{{ $errors->has('cargo') ? ' is-invalid' : '' }}" placeholder="Cargo actual">
        @if ($errors->has('cargo'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('cargo') }}</strong>
        </div>
        @endif
    </div>
</div>
@endif

@if ($tipo_socio=="formacion")
<div class="form-group row">
    <label for="nombre_tutor" class="col-sm-2 col-form-label">Nombre del tutor *</label>
    <div class="col-sm-10">
        <input name="nombre_tutor" type="text" value="{{ old('nombre_tutor') }}" class="form-control{{ $errors->has('nombre_tutor') ? ' is-invalid' : '' }}" placeholder="Nombre del tutor">
        @if ($errors->has('nombre_tutor'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('nombre_tutor') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="telefono_tutor" class="col-sm-2 col-form-label">Teléfono del tutor *</label>
    <div class="col-sm-10">
        <input name="telefono_tutor" type="text" value="{{ old('telefono_tutor') }}" class="form-control{{ $errors->has('telefono_tutor') ? ' is-invalid' : '' }}" placeholder="Teléfono del tutor">
        @if ($errors->has('telefono_tutor'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('telefono_tutor') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="email_tutor" class="col-sm-2 col-form-label">Correo electrónico del tutor *</label>
    <div class="col-sm-10">
        <input name="email_tutor" type="text" value="{{ old('email_tutor') }}" class="form-control{{ $errors->has('email_tutor') ? ' is-invalid' : '' }}" placeholder="Correo electrónico del tutor">
        @if ($errors->has('email_tutor'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('email_tutor') }}</strong>
        </div>
        @endif
    </div>
</div>
@endif

<script>
    document.getElementById('especialidadID').style.display = 'none';
    
    $('select').on('change', function() {
        if (this.value == 'Medicina') {
            document.getElementById('especialidadID').style.display = 'block';
        } else {
            document.getElementById('especialidadID').style.display = 'none';
        }
    });
</script>