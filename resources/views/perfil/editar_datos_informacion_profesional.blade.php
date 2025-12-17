<div class="contenido-titulo-seccion borde-institucional mt-3">
    <span class="bg-institucional">Datos de interés</span>
</div>

<div class="row">
    <label class="col-sm-2 col-form-label">Áreas de interés:<br><br><span class="small">(mantener pulsada la tecla Ctrl para seleccionar/deseleccionar varias)</span></label>
    <label class="col-sm-6 col-form-label">
        <select name="area_inter[]" size="10" multiple="multiple" class="form-control">
            @foreach (App\Area::orderBy('nombre')->get() as $areas)
                <option value="{{ $areas->id }}" @if($usuario->hasAreaIntereses($areas->id) || (is_array(old('area_inter')) && in_array($areas->id, old('area_inter')))){{ 'selected' }}@endif>{{ $areas->nombre }}</option>
            @endforeach
        </select>
    </label>
</div>