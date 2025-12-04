    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('consultas') }}">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="nombre">Nombre</label> 
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" required="required">
                        @if ($errors->has('nombre'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('nombre') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="email_contacto">E-mail</label> 
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required="required">
                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="centro">Centro</label> 
                        <input type="text" name="centro" value="{{ old('centro') }}" class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}" required="required">
                        @if ($errors->has('centro'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('centro') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="area_gestion">Área de gestión</label> 
                        <select name="area_gestion" value="{{ old('area_gestion') }}" class="form-control{{ $errors->has('area_gestion') ? ' is-invalid' : '' }}" required="required">
                            <option value="investigacion"@if(old('area_gestion')=="investigacion") selected="selected"@endif>Investigación</option>
                            <option value="calidad"@if(old('area_gestion')=="calidad") selected="selected"@endif>Calidad</option>
                            <option value="clinica"@if(old('area_gestion')=="clinica") selected="selected"@endif>Gestión clínica</option>
                        </select>
                        @if ($errors->has('area_gestion'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('area_gestion') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="consulta">Consulta</label>
                        <textarea class="form-control" name="consulta" rows="4" required="required">{{ old('consulta') }}</textarea>
                        @if ($errors->has('consulta'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('consulta') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        {!! NoCaptcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                        <div class="help-block text-danger">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row form-check">
                        <input class="form-check-input" type="checkbox" name="aceptacion" required="required">
                        <label for="aceptacion" class="form-check-label">
                            He leído y acepto la <a href="/privacidad" target="_blank">política de privacidad</a>.
                        </label>
                    </div>
                    <div class="form-group row mb-1">
                        <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Enviar mensaje</button>
                    </div>
                    <div class="form-group row py-0 mb-0">
                        <hr class="px-0 w-100 borde-institucional">
                    </div>
                    <div class="form-group row">
                        <small class="text-justify">
                            Sus datos se incorporan a un fichero de Sociedad Española de Patología Digestiva (SEPD), 
                            con la finalidad de ponernos en contacto con usted y atender a su solicitud.
                            Para más información acceda a nuestra <a href="/privacidad">política de privacidad</a>. 
                            Puede ejercer todos los derechos otorgados por el RGPD (Reglamento (UE) 2016/679) en: 
                            SEPD, Calle Sancho Dávila 6, 28028 Madrid, o enviando un e-mail a <strong>sepd@sepd.es</strong>.
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>