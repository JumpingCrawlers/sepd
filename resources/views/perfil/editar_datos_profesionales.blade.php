<div class="contenido-titulo-seccion borde-institucional mt-4">
    <span class="bg-institucional">Datos profesionales</span>
</div>

<div class="form-group row">
    <label for="titulacion" class="col-sm-2 col-form-label">Titulación académica: *</label>
    @php
        $old_titulacion = old('titulacion', isset($usuario->datos_profesionales->titulacion) ? $usuario->datos_profesionales->titulacion : '');
    @endphp
    <div class="col-sm-6">
        <select name="titulacion" id="titulacionID" value="{{ $old_titulacion }}" class="form-control{{ $errors->has('titulacion') ? ' is-invalid' : '' }}">
            <option value>Escoge una titulación académica</option>
            <option {{ $old_titulacion == 'Medicina' ? 'selected' : '' }} value="Medicina">Medicina</option>
            <option {{ $old_titulacion == 'Enfermería' ? 'selected' : '' }} value="Enfermería">Enfermería</option>
            <option {{ $old_titulacion == 'Nutrición Humana y Dietética' ? 'selected' : '' }} value="Nutrición Humana y Dietética">Nutrición Humana y Dietética</option>
            <option {{ $old_titulacion == 'Biología' ? 'selected' : '' }} value="Biología">Biología</option>
            <option {{ $old_titulacion == 'Farmacia' ? 'selected' : '' }} value="Farmacia">Farmacia</option>
            <option {{ $old_titulacion == 'Otros' ? 'selected' : '' }} value="Otros">Otros</option>
        </select>
        <!-- <input name="titulacion" type="text" value="{{ old('titulacion', isset($usuario->datos_profesionales->titulacion) ? $usuario->datos_profesionales->titulacion : '') }}" class="form-control{{ $errors->has('titulacion') ? ' is-invalid' : '' }}" placeholder="Titulación académica"> -->
        @if ($errors->has('titulacion'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('titulacion') }}</strong>
            </div>
        @endif
    </div>
</div>

<div class="form-group row" id="especialidadID">
    <label class="col-sm-2 col-form-label">
        Especialidad: *<br />
        @if ($errors->has('especialidad'))
            <div class="invalid-feedback" style="display:block!important">
                <strong>{{ $errors->first('especialidad') }}</strong>
            </div>
        @endif
    </label>
    <div class="col-sm-6">
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

@section('scripts')
    <script>
        if ('{{ $old_titulacion }}' != 'Medicina')
            document.getElementById('especialidadID').style.display = 'none';

        $('select').on('change', function() {
            if (this.value == 'Medicina') {
                document.getElementById('especialidadID').style.display = 'block';
            } else {
                document.getElementById('especialidadID').style.display = 'none';
            }
        });
    </script>
@endsection