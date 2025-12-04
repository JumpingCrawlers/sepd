<div class="container">
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ route('contacto') }}" id="form-contacto" autocomplete="off">
                {{ csrf_field() }}
                <div class="form-group row">
                    <p class="color-institucional">
                        Estos son nuestros datos de contacto. Además, puede enviarnos un mensaje a través de este
                        formulario:
                    </p>
                    <label for="nombre" class="color-institucional">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        autocomplete="new-nombre"
                        class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}"
                        required="required">
                    @if($errors->has('nombre'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('nombre') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group row">
                    <label for="email_contacto" class="color-institucional">E-mail</label>
                    <input type="email" name="email_contacto" value="{{ old('email_contacto') }}"
                    autocomplete="new-email_contacto"
                        class="form-control{{ $errors->has('email_contacto') ? ' is-invalid' : '' }}"
                        required="required">
                    @if($errors->has('email_contacto'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email_contacto') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group row">
                    <label for="asunto" class="color-institucional">Asunto</label>
                    <input type="text" name="asunto" value="{{ old('asunto') }}"
                        class="form-control{{ $errors->has('asunto') ? ' is-invalid' : '' }}"
                        autocomplete="off"
                        required="required">
                    @if($errors->has('asunto'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('asunto') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group row">
                    <label for="mensaje" class="color-institucional">Mensaje</label>
                    <textarea class="form-control" name="mensaje" rows="3"
                        autocomplete="off"
                        required="required">{{ old('mensaje') }}</textarea>
                    @if($errors->has('mensaje'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('mensaje') }}</strong>
                        </div>
                    @endif
                </div>
                
                @if($errors->has('g-recaptcha-response'))
                    <div class="form-group row">
                        <div class="help-block text-danger">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </div>
                    </div>
                @endif

                <div class="form-group row mb-1">
                    {!! NoCaptcha::display() !!}
                </div>
                
                <div class="form-group row mb-1">
                    <button class="btn" style="color: #FFF; background-color: #FFA43A"><span>Enviar mensaje</span></button>
                </div>

                <div class="form-group row py-0 mb-0">
                    <hr class="px-0 w-100 borde-institucional">
                </div>
                <div class="form-group row">
                    <small class="text-justify color-institucional">
                        Sus datos se incorporan a un fichero de Sociedad Española de Patología Digestiva (SEPD),
                        con la finalidad de ponernos en contacto con usted y atender a su solicitud.
                        Para más información acceda a nuestra <a href="/privacidad">política de privacidad</a>.
                        Puede ejercer todos los derechos otorgados por el RGPD (Reglamento (UE) 2016/679) en:
                        SEPD, Calle Sancho Dávila 6, 28028 Madrid, o enviando un e-mail a
                        <strong>sepd@sepd.es</strong>.
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>