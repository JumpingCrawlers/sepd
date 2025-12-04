<div class="container">
    <div class="row">
        <div class="col-10 offset-1">
            <form role="form" method="POST" action="{{ route('registroUsuario') }}" id="form-register">
                {{ csrf_field() }}
                {{-- guardar la página para volver al finalizar el proceso --}}
                <input type="hidden" name="pagina" value="@if (isset($pagina)){{ $pagina->slug }}@else login @endif" >
                <div class="form-group row">
                    <p>Puede registrarse como usuario de la plataforma de la SEPD, para disfrutar de contenido exclusivo y registrarse en cursos.</p>
                </div>
                <div class="form-group row">
                    @if ($errors->has('registro'))
                    <div class="alert alert-danger">
                        <strong>{!! $errors->first('registro') !!}</strong>
                    </div>
                    @endif
                    <label for="nombre">Nombre (*)</label> 
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" required="required" autofocus="autofocus">
                    @if ($errors->has('nombre'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('nombre') }}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="apellidos">Apellidos (*)</label>
                    <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos') }}" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" required="required">
                    @if ($errors->has('apellidos'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('apellidos') }}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="nif">NIF (letra incluída, sin espacio ni guión) (*)</label>
                    <input id="nif" type="text" name="nif" value="{{ old('nif') }}" class="form-control{{ $errors->has('nif') ? ' is-invalid' : '' }}" required="required">
                    @if ($errors->has('nif'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('nif') }}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="email">Email (*)</label>
                    <input id="email" type="text" name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required="required">
                    @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="password">Contraseña (*)</label>
                    <input id="password" type="password" name="password" value="{{ old('password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required="required">
                    @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="password_confirm"> Confirmar contraseña (*)</label>
                    <input id="password_confirm" type="password" name="password_confirm" value="{{ old('password_confirm') }}" class="form-control{{ $errors->has('password_confirm') ? ' is-invalid' : '' }}" required="required">
                    @if ($errors->has('password_confirm'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password_confirm') }}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group row">
                    @if ($errors->has('g-recaptcha-response'))
                        <div class="help-block text-danger">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </div>
                    @endif
                </div>
                
                <div class="form-group row">
                    {!! NoCaptcha::displaySubmit('form-register', 'Enviar', ['class' => 'btn text-white', 'style' => getHtmlEstiloBoton('', '')]) !!}
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
