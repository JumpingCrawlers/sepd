    <div class="container">
        <div class="row">
            @if (session('success'))
                <div class="col-2">
                    <img src="{{ Voyager::image(setting('iconos.ok')) }}" class="img-fluid" alt="Solicitud enviada">
                </div>
                <div class="col-9 text-justify">
                    <h2><b>Solicitud enviada</b></h2>
                    <p>La solicitud se ha enviado correctamente y está siendo revisada, será contactado en breve.</p>
                    <a href="{{ config('app.url') }}" class="btn" style="color:#ffffff;background-color:#db812e;float:right">Ir a la página principal</a>
                </div>
            @else
                <div class="col-10 offset-1">
                    <form role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        {{-- guardar la página para volver al finalizar el proceso --}}
                        <input type="hidden" name="pagina" value="@if (isset($pagina)){{ $pagina->slug }}@endif" >
                        <div class="form-group row">
                            <p>Si tienes problemas para acceder rellena este formulario y te enviaremos por correo electrónico los datos que necesitas para conectarte.</p>
                        </div>
                        <div class="form-group row">
                            @if ($errors->has('registro'))
                            <div class="alert alert-danger">
                                <strong>{!! $errors->first('registro') !!}</strong>
                            </div>
                            @endif
                            <label for="nombre">Nombre completo *</label> 
                            <input id="nombre_completo" type="text" name="nombre_completo" value="{{ old('nombre_completo') }}" class="form-control{{ $errors->has('nombre_completo') ? ' is-invalid' : '' }}" required="required" autofocus="autofocus">
                            @if ($errors->has('nombre_completo'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('nombre_completo') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="nif">NIF (letra incluída, sin espacio ni guión) *</label>
                            <input id="nif" type="text" name="nif" value="{{ old('nif') }}" class="form-control{{ $errors->has('nif') ? ' is-invalid' : '' }}" required="required">
                            @if ($errors->has('nif'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('nif') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-12 p-0">
                                <label @if($errors->has('es_socio'))class="form-control is-invalid border-0 p-0"@endif>¿Eres socio? *</label>
                                
                                @if ($errors->has('es_socio'))
                                <div class="invalid-feedback mb-2 mt-0">
                                    <strong>{{ $errors->first('es_socio') }}</strong>
                                </div>
                                @endif
                            </div>
                            <div class="col-12 p-0">
                                <input type="radio" name="es_socio" id="socio_si" value="1" required="required" @if(old('es_socio') == '1'){{ 'checked' }}@endif>&nbsp;<label for="socio_si">Sí</label>
                            </div>
                            <div class="col-12 p-0">
                                <input type="radio" name="es_socio" id="socio_no" value="0" required="required" @if(old('es_socio') == '0'){{ 'checked' }}@endif>&nbsp;<label for="socio_no">No</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mail">Email *</label>
                            <input id="mail" type="mail" name="mail" value="{{ old('mail') }}" class="form-control{{ $errors->has('mail') ? ' is-invalid' : '' }}" required="required">
                            @if ($errors->has('mail'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('mail') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="telefono">Número de teléfono *</label>
                            <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}" class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}" required="required">
                            @if ($errors->has('telefono'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('telefono') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="observaciones">Observaciones</label>
                            <textarea id="observaciones" name="observaciones" class="form-control{{ $errors->has('observaciones') ? ' is-invalid' : '' }}">{{ old('observaciones') }}</textarea>
                            @if ($errors->has('observaciones'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('observaciones') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="g-recaptcha mt-3{{ $errors->has('captcha') ? ' is-invalid' : '' }}" data-sitekey="6LevFvYUAAAAAMqfnbNQaY-iHa2nN_YQ7ntGmyLi"></div>
                            @if ($errors->has('captcha'))
                            <div class="invalid-feedback" style="display: block">
                                <strong>{{ $errors->first('captcha') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <i><b>*</b> Campos obligatorios</i>
                        </div>

                        <div class="form-group row">
                            <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Enviar</button>
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
            @endif
        </div>
    </div>

