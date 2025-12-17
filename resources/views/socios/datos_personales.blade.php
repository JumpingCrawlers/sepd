<div class="contenido-titulo-seccion borde-institucional mb-4">
    <span class="bg-institucional">Datos personales</span>
</div>

@auth
    <div class="form-group row">
        <label for="tratamiento" class="col-sm-2 col-form-label">Tratamiento *</label>
        <div class="col-sm-2">
            <input name="tratamiento" type="text" value="{{ old('tratamiento') }}" class="form-control{{ $errors->has('tratamiento') ? ' is-invalid' : '' }}" placeholder="Tratamiento" required>
            @if ($errors->has('tratamiento'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('tratamiento') }}</strong>
            </div>
            @endif
        </div>

        <div class="col-sm-6">
            <input type="text" value="Nombre *   {{ Auth::user()->nombre }}" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" required disabled style="cursor: initial; border: none; background-color: white">
            @if ($errors->has('nombre'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="apellidos" class="col-sm-2 col-form-label">Apellidos *</label>
        <div class="col-sm-10">
            <input type="text" value="{{ Auth::user()->apellidos }}" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" required disabled style="cursor: initial; border: none; background-color: white">
            @if ($errors->has('apellidos'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('apellidos') }}</strong>
                </div>
            @endif
        </div>
    </div>
@endauth

@guest
    <div class="form-group row">
        <label for="tratamiento" class="col-sm-2 col-form-label">Tratamiento *</label>
        <div class="col-sm-2">
            <input name="tratamiento" type="text" value="{{ old('tratamiento') }}" class="form-control{{ $errors->has('tratamiento') ? ' is-invalid' : '' }}" placeholder="Tratamiento" required>
            @if ($errors->has('tratamiento'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('tratamiento') }}</strong>
            </div>
            @endif
        </div>

        <label for="nombre" class="col-sm-2 col-form-label">Nombre *</label>
        <div class="col-sm-6">
            <input name="nombre" type="text" value="{{ old('nombre') }}" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" placeholder="Nombre" required>
            @if ($errors->has('nombre'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('nombre') }}</strong>
            </div>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="apellidos" class="col-sm-2 col-form-label">Apellidos *</label>
        <div class="col-sm-10">
            <input name="apellidos" type="text" value="{{ old('apellidos') }}" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" placeholder="Apellidos" required>
            @if ($errors->has('apellidos'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('apellidos') }}</strong>
            </div>
            @endif
        </div>
    </div>
@endguest

<div class="form-group row">
    <label for="via" class="col-sm-2 col-form-label">Vía *</label>
    <div class="col-sm-10">
        <select class="form-control{{ $errors->has('via') ? ' is-invalid' : '' }}" name="via">
            <option value=""@if(old('via')=="") selected="selected"@endif>Vía...</option>
            <option value="Alameda"@if(old('via')=="Alameda") selected="selected"@endif>Alameda</option>
            <option value="Aldea"@if(old('via')=="Aldea") selected="selected"@endif>Aldea</option>
            <option value="Apartado"@if(old('via')=="Apartado") selected="selected"@endif>Apartado</option>
            <option value="Avenida"@if(old('via')=="Avenida") selected="selected"@endif>Avenida</option>
            <option value="Barrio"@if(old('via')=="Barrio") selected="selected"@endif>Barrio</option>
            <option value="Bloque"@if(old('via')=="Bloque") selected="selected"@endif>Bloque</option>
            <option value="Calle"@if(old('via')=="Calle") selected="selected"@endif>Calle</option>
            <option value="Camino"@if(old('via')=="Camino") selected="selected"@endif>Camino</option>
            <option value="Caserio"@if(old('via')=="Caserio") selected="selected"@endif>Caserio</option>
            <option value="Chalet"@if(old('via')=="Chalet") selected="selected"@endif>Chalet</option>
            <option value="Colonia"@if(old('via')=="Colonia") selected="selected"@endif>Colonia</option>
            <option value="Crta."@if(old('via')=="Crta.") selected="selected"@endif>Crta.</option>
            <option value="Cuesta"@if(old('via')=="Cuesta") selected="selected"@endif>Cuesta</option>
            <option value="Edificio"@if(old('via')=="Edificio") selected="selected"@endif>Edificio</option>
            <option value="Glorieta"@if(old('via')=="Glorieta") selected="selected"@endif>Glorieta</option>
            <option value="Grupo"@if(old('via')=="Grupo") selected="selected"@endif>Grupo</option>
            <option value="Lugar"@if(old('via')=="Lugar") selected="selected"@endif>Lugar</option>
            <option value="Manzana"@if(old('via')=="Manzana") selected="selected"@endif>Manzana</option>
            <option value="Mercado"@if(old('via')=="Mercado") selected="selected"@endif>Mercado</option>
            <option value="Municipio"@if(old('via')=="Municipio") selected="selected"@endif>Municipio</option>
            <option value="Plaza"@if(old('via')=="Plaza") selected="selected"@endif>Plaza</option>
            <option value="Parque"@if(old('via')=="Parque") selected="selected"@endif>Parque</option>
            <option value="Pasaje"@if(old('via')=="Pasaje") selected="selected"@endif>Pasaje</option>
            <option value="Paseo"@if(old('via')=="Paseo") selected="selected"@endif>Paseo</option>
            <option value="Plaza"@if(old('via')=="Plaza") selected="selected"@endif>Plaza</option>
            <option value="Poblado"@if(old('via')=="Poblado") selected="selected"@endif>Poblado</option>
            <option value="Poligono"@if(old('via')=="Poligono") selected="selected"@endif>Poligono</option>
            <option value="Prolong."@if(old('via')=="Prolong.") selected="selected"@endif>Prolong.</option>
            <option value="Puente"@if(old('via')=="Puente") selected="selected"@endif>Puente</option>
            <option value="Rambla"@if(old('via')=="Rambla") selected="selected"@endif>Rambla</option>
            <option value="Ronda"@if(old('via')=="Ronda") selected="selected"@endif>Ronda</option>
            <option value="Travesía"@if(old('via')=="Travesia") selected="selected"@endif>Travesía</option>
            <option value="Urb."@if(old('via')=="Urb.") selected="selected"@endif>Urb.</option>
        </select>
        @if ($errors->has('via'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('via') }}</strong>
        </div>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="direccion" class="col-sm-2 col-form-label">Direccion *</label>
    <div class="col-sm-10">
        <input name="direccion" type="text" value="{{ old('direccion') }}" class="form-control{{ $errors->has('direccion') ? ' is-invalid' : '' }}" placeholder="Direccion" required>
        @if ($errors->has('direccion'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('direccion') }}</strong>
        </div>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="localidad" class="col-sm-2 col-form-label">Localidad *</label>
    <div class="col-sm-10">
        <input name="localidad" type="text" value="{{ old('localidad') }}" class="form-control{{ $errors->has('localidad') ? ' is-invalid' : '' }}" placeholder="Localidad" required>
        @if ($errors->has('localidad'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('localidad') }}</strong>
        </div>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="pais" class="col-sm-2 col-form-label">País *</label>
    <div class="col-sm-10">
        <select name="pais" id="pais" class="form-control @if (isset($errors)) {{ $errors->has('pais') ? ' is-invalid' : '' }} @endif">
            <option value="">País...</option> 
            @foreach (App\Pais::orderBy('nombre')->get() as $pais)
                @php if ($pais->nombre == "España" && $tipo_socio == "internacional") continue; @endphp
                <option value="{{ $pais->id }}" title="{{ $pais->nombre }}"{{ ((old('pais') == $pais->id) ? ' selected="selected"' : '') }}>{{ $pais->nombre }}</option> 
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
    <label for="cp" class="col-sm-2 col-form-label">Código postal *</label>
    <div class="col-sm-2">
        <input name="cp" type="text" value="{{ old('cp') }}" class="form-control{{ $errors->has('cp') ? ' is-invalid' : '' }}" placeholder="Código postal" required>
        @if ($errors->has('cp'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('cp') }}</strong>
        </div>
        @endif
    </div>
    <label for="provincia" class="col-sm-2 col-form-label">Provincia *</label>
    <div class="col-sm-6">
        {{-- Si es internacional no hay provincias, solo otros --}}
        @if ($tipo_socio != "internacional")
            @include('puzzle.datos.provincias', ['nombre_select' => "provincia", 'valor_defecto' => ''])
        @endif
        @if ($errors->has('provincia'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('provincia') }}</strong>
        </div>
        @endif
        <div class="collapse" id="containerProvinciaOtros">
            <div class="form-group row mb-0">
                <div class="col">
                    <input name="provincia_otros" type="text" value="{{ old('provincia_otros') }}" class="form-control{{ $errors->has('provincia_otros') ? ' is-invalid' : '' }}" placeholder="Provincia">
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
    <label for="telefono" class="col-sm-2 col-form-label">Teléfono @if ($tipo_socio != "internacional") fijo @else * @endif</label>
    <div class="col-sm-10">
        <input name="telefono" type="text" value="{{ old('telefono') }}" class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}" placeholder="Teléfono fijo">
        @if ($errors->has('telefono'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('telefono') }}</strong>
        </div>
        @endif
    </div>
</div>
@if ($tipo_socio != "internacional")
<div class="form-group row">
    <label for="telefono_movil" class="col-sm-2 col-form-label">Teléfono móvil *</label>
    <div class="col-sm-10">
        <input name="telefono_movil" type="text" value="{{ old('telefono_movil') }}" class="form-control{{ $errors->has('telefono_movil') ? ' is-invalid' : '' }}" placeholder="Teléfono móvil">
        @if ($errors->has('telefono_movil'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('telefono_movil') }}</strong>
        </div>
        @endif
    </div>
</div>
@endif

@auth
    <div class="form-group row">
        <label for="correo" class="col-sm-2 col-form-label">Correo electrónico *</label>
        <div class="col-sm-10">
            <input name="correo" type="email" value="{{ old('correo', Auth::user()->email) }}" class="form-control{{ $errors->has('correo') ? ' is-invalid' : '' }}" placeholder="Correo electrónico"  required>
            @if ($errors->has('correo'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('correo') }}</strong>
                </div>
            @endif
        </div>
    </div>
@endauth

@guest
    <div class="form-group row">
        <label for="correo" class="col-sm-2 col-form-label">Correo electrónico *</label>
        <div class="col-sm-10">
            <input name="correo" type="email" value="{{ old('correo') }}" class="form-control{{ $errors->has('correo') ? ' is-invalid' : '' }}" placeholder="Correo electrónico">
            @if ($errors->has('correo'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('correo') }}</strong>
            </div>
            @endif
        </div>
    </div>
@endguest

<div class="form-group row">
    <label for="nacimiento" class="col-sm-2 col-form-label">Fecha de nacimiento *</label>
    <div class="col-sm-10">
        <input name="nacimiento" type="date" value="{{ old('nacimiento') }}" class="form-control{{ $errors->has('nacimiento') ? ' is-invalid' : '' }}" placeholder="Fecha de nacimiento">
        @if ($errors->has('nacimiento'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('nacimiento') }}</strong>
        </div>
        @endif
    </div>
</div>

@auth
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">NIF</label>
        <label class="col-sm-9 col-form-label">{{ Auth::user()->dni }}</label>
    </div>
@endauth

@guest
    <div class="form-group row">
        <label for="nif" class="col-sm-2 col-form-label">NIF *</label>
        <div class="col-sm-10">
            <input name="nif" type="text" value="{{ old('nif') }}" class="form-control{{ $errors->has('nif') ? ' is-invalid' : '' }}" placeholder="NIF">
            @if ($errors->has('nif'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('nif') }}</strong>
            </div>
            @endif
        </div>
    </div>
@endguest

<div class="form-group row">
    <label for="sexo" class="col-sm-2 col-form-label">Sexo *</label>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sexo" id="sexo_1" value="o" required="required"{{ old('sexo') == 'o' ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="sexo_1">Hombre</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sexo" id="sexo_2" value="a" required="required"{{ old('sexo') == 'a' ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="sexo_2">Mujer</label>
        </div>
    </div>
</div>
