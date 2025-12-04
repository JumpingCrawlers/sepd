<div class="container">
    <div class="row">
        <div class="col-10 offset-1">
            <form role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">

                    <label for="email">E-mail</label> 
                    <input
                        type="email"
                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        name="email"
                        value="{{ $email ?? old('email') }}"
                        required="required"
                        autofocus="autofocus"
                    >

                    @if ($errors->has('email'))

                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>

                    @endif
                </div>

                <div class="form-group row">
                    <label for="password">Contraseña</label>
                    <input
                        type="password"
                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        name="password"
                        required="required"
                    >

                    @if ($errors->has('password'))

                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>

                    @endif
                </div>

                <div class="form-group row">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input
                        type="password"
                        class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                        name="password_confirmation"
                        required="required"
                    >

                    @if ($errors->has('password_confirmation'))

                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </div>

                    @endif
                </div>

                <div class="form-group row">
                    <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Restablecer contraseña</button>
                </div>

            </form>
        </div>
    </div>
</div>
