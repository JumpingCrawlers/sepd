<div class="contenido-titulo-seccion borde-institucional mt-4">
    <span class="bg-institucional">Datos personales</span>
</div>

<div class="form-group row">
    <label for="sexo" class="col-sm-2 col-form-label">Sexo: *</label>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sexo" id="sexo_1" value="o" required="required"{{ old('sexo', ($usuario->datos_basicos->sexo ?? '')) == 'o' ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="sexo_1">Hombre</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sexo" id="sexo_2" value="a" required="required"{{ old('sexo', ($usuario->datos_basicos->sexo ?? '')) == 'a' ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="sexo_2">Mujer</label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="nacimiento" class="col-sm-2 col-form-label">Fecha de nacimiento:</label>
    <div class="col-sm-6">
        <input name="nacimiento" type="date" value="{{ old('nacimiento', substr(($usuario->datos_basicos->fecha_nacimiento ?? ''),0,10)) }}" class="form-control{{ $errors->has('nacimiento') ? ' is-invalid' : '' }}" placeholder="Fecha de nacimiento">
        @if ($errors->has('nacimiento'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('nacimiento') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="via" class="col-sm-2 col-form-label">Vía: *</label>
    <div class="col-sm-6">
        <select class="form-control{{ $errors->has('via') ? ' is-invalid' : '' }}" name="via">
            <option value=""@if(old('via', ($usuario->direcciones->via ?? ''))=="") selected="selected"@endif>Vía...</option>
            <option value="Alameda"@if(old('via', ($usuario->direcciones->via ?? ''))=="Alameda") selected="selected"@endif>Alameda</option>
            <option value="Aldea"@if(old('via', ($usuario->direcciones->via ?? ''))=="Aldea") selected="selected"@endif>Aldea</option>
            <option value="Apartado"@if(old('via', ($usuario->direcciones->via ?? ''))=="Apartado") selected="selected"@endif>Apartado</option>
            <option value="Avenida"@if(old('via', ($usuario->direcciones->via ?? ''))=="Avenida") selected="selected"@endif>Avenida</option>
            <option value="Barrio"@if(old('via', ($usuario->direcciones->via ?? ''))=="Barrio") selected="selected"@endif>Barrio</option>
            <option value="Bloque"@if(old('via', ($usuario->direcciones->via ?? ''))=="Bloque") selected="selected"@endif>Bloque</option>
            <option value="Calle"@if(old('via', ($usuario->direcciones->via ?? ''))=="Calle") selected="selected"@endif>Calle</option>
            <option value="Camino"@if(old('via', ($usuario->direcciones->via ?? ''))=="Camino") selected="selected"@endif>Camino</option>
            <option value="Caserio"@if(old('via', ($usuario->direcciones->via ?? ''))=="Caserio") selected="selected"@endif>Caserio</option>
            <option value="Chalet"@if(old('via', ($usuario->direcciones->via ?? ''))=="Chalet") selected="selected"@endif>Chalet</option>
            <option value="Colonia"@if(old('via', ($usuario->direcciones->via ?? ''))=="Colonia") selected="selected"@endif>Colonia</option>
            <option value="Crta."@if(old('via', ($usuario->direcciones->via ?? ''))=="Crta.") selected="selected"@endif>Crta.</option>
            <option value="Cuesta"@if(old('via', ($usuario->direcciones->via ?? ''))=="Cuesta") selected="selected"@endif>Cuesta</option>
            <option value="Edificio"@if(old('via', ($usuario->direcciones->via ?? ''))=="Edificio") selected="selected"@endif>Edificio</option>
            <option value="Glorieta"@if(old('via', ($usuario->direcciones->via ?? ''))=="Glorieta") selected="selected"@endif>Glorieta</option>
            <option value="Grupo"@if(old('via', ($usuario->direcciones->via ?? ''))=="Grupo") selected="selected"@endif>Grupo</option>
            <option value="Lugar"@if(old('via', ($usuario->direcciones->via ?? ''))=="Lugar") selected="selected"@endif>Lugar</option>
            <option value="Manzana"@if(old('via', ($usuario->direcciones->via ?? ''))=="Manzana") selected="selected"@endif>Manzana</option>
            <option value="Mercado"@if(old('via', ($usuario->direcciones->via ?? ''))=="Mercado") selected="selected"@endif>Mercado</option>
            <option value="Municipio"@if(old('via', ($usuario->direcciones->via ?? ''))=="Municipio") selected="selected"@endif>Municipio</option>
            <option value="Plaza"@if(old('via', ($usuario->direcciones->via ?? ''))=="Plaza") selected="selected"@endif>Plaza</option>
            <option value="Parque"@if(old('via', ($usuario->direcciones->via ?? ''))=="Parque") selected="selected"@endif>Parque</option>
            <option value="Pasaje"@if(old('via', ($usuario->direcciones->via ?? ''))=="Pasaje") selected="selected"@endif>Pasaje</option>
            <option value="Paseo"@if(old('via', ($usuario->direcciones->via ?? ''))=="Paseo") selected="selected"@endif>Paseo</option>
            <option value="Plaza"@if(old('via', ($usuario->direcciones->via ?? ''))=="Plaza") selected="selected"@endif>Plaza</option>
            <option value="Poblado"@if(old('via', ($usuario->direcciones->via ?? ''))=="Poblado") selected="selected"@endif>Poblado</option>
            <option value="Poligono"@if(old('via', ($usuario->direcciones->via ?? ''))=="Poligono") selected="selected"@endif>Poligono</option>
            <option value="Prolong."@if(old('via', ($usuario->direcciones->via ?? ''))=="Prolong.") selected="selected"@endif>Prolong.</option>
            <option value="Puente"@if(old('via', ($usuario->direcciones->via ?? ''))=="Puente") selected="selected"@endif>Puente</option>
            <option value="Rambla"@if(old('via', ($usuario->direcciones->via ?? ''))=="Rambla") selected="selected"@endif>Rambla</option>
            <option value="Ronda"@if(old('via', ($usuario->direcciones->via ?? ''))=="Ronda") selected="selected"@endif>Ronda</option>
            <option value="Travesía"@if(old('via', ($usuario->direcciones->via ?? ''))=="Travesia") selected="selected"@endif>Travesía</option>
            <option value="Urb."@if(old('via', ($usuario->direcciones->via ?? ''))=="Urb.") selected="selected"@endif>Urb.</option>
        </select>
        @if ($errors->has('via'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('via') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="direccion" class="col-sm-2 col-form-label">Dirección: *</label>
    <div class="col-sm-6">
        <input name="direccion" type="text" value="{{ old('direccion', ($usuario->direcciones->direccion ?? '')) }}" class="form-control{{ $errors->has('direccion') ? ' is-invalid' : '' }}" placeholder="Direccion" required>
        @if ($errors->has('direccion'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('direccion') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="ciudad" class="col-sm-2 col-form-label">Localidad: *</label>
    <div class="col-sm-6">
        <input name="ciudad" type="text" value="{{ old('ciudad', ($usuario->direcciones->ciudad ?? '')) }}" class="form-control{{ $errors->has('ciudad') ? ' is-invalid' : '' }}" placeholder="Localidad" required>
        @if ($errors->has('ciudad'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('ciudad') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="cod_postal" class="col-sm-2 col-form-label">Código postal: *</label>
    <div class="col-sm-1">
        <input name="cod_postal" type="text" value="{{ old('cod_postal', ($usuario->direcciones->codigo_postal ?? '')) }}" class="form-control{{ $errors->has('cod_postal') ? ' is-invalid' : '' }}" placeholder="Código postal" required>
        @if ($errors->has('cod_postal'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('cod_postal') }}</strong>
        </div>
        @endif
    </div>
    <label for="provincia" class="col-sm-1 col-form-label">Provincia: *</label>
    <div class="col-sm-4">
        {{-- Si es internacional no hay provincias, solo otros --}}
        @if ($tipo_socio != "internacional")
            @include('puzzle.datos.provincias', [ 
                'nombre_select' => 'provincia',
                'valor_defecto' => ((($usuario->direcciones->pais->nombre ?? '') == 'España') ? str_pad(($usuario->direcciones->provincia_id ?? ''), 3, "0", STR_PAD_LEFT)."_".($usuario->direcciones->provincia->nombre ?? '') : '000')
            ])
        @endif
        @if ($errors->has('provincia'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('provincia') }}</strong>
        </div>
        @endif
        <div class="collapse" id="containerProvinciaOtros">
            <div class="form-group row mb-0">
                <div class="col">
                    <input name="provincia_otros" type="text" value="{{ old('provincia_otros', ($usuario->direcciones->provincia_otros ?? '')) }}" class="form-control{{ $errors->has('provincia_otros') ? ' is-invalid' : '' }}" placeholder="Provincia">
                    @if ($errors->has('provincia_otros'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('provincia_otros') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="pais" class="col-sm-2 col-form-label">País: *</label>
    <div class="col-sm-6">
        <select name="pais" id="pais" class="form-control @if (isset($errors)) {{ $errors->has('pais') ? ' is-invalid' : '' }} @endif">
            <option value="">País...</option> 
            @foreach (App\Pais::all() as $pais)
                <option value="{{ $pais->id }}" title="{{ $pais->nombre }}"{{ ((old('pais', ($usuario->direcciones->pais_id ?? '')) == $pais->id) ? ' selected="selected"' : '') }}>{{ $pais->nombre }}</option> 
            @endforeach
        </select>
        @if ($errors->has('pais'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('pais') }}</strong>
        </div>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="telefono" class="col-sm-2 col-form-label">Teléfono{{ ($tipo_socio != "internacional") ? ' fijo:' : ': *'}}</label>
    <div class="col-sm-6">
        <input name="telefono" type="text" value="{{ old('telefono', ($usuario->datos_basicos->telefono ?? '')) }}" class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}" placeholder="Teléfono fijo">
        @if ($errors->has('telefono'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('telefono') }}</strong>
        </div>
        @endif
    </div>
</div>
@if ($tipo_socio != "internacional")
<div class="form-group row">
    <label for="telefono_movil" class="col-sm-2 col-form-label">Teléfono móvil: *</label>
    <div class="col-sm-6">
        <input name="telefono_movil" type="text" value="{{ old('telefono_movil', ($usuario->datos_basicos->movil ?? '')) }}" class="form-control{{ $errors->has('telefono_movil') ? ' is-invalid' : '' }}" placeholder="Teléfono móvil">
        @if ($errors->has('telefono_movil'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('telefono_movil') }}</strong>
        </div>
        @endif
    </div>
</div>
@endif


