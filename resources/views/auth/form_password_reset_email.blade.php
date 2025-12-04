    <div class="container">
        <div class="row">
            <div class="col-10 offset-1">

                @if (session('status'))

                <div class="alert alert-success row">
                    {{ session('status') }}
                </div>

                @endif

                <form role="form" method="POST" action="{{ url('/password/email') }}">
                    {!! csrf_field() !!}


                    <div class="form-group row">
                        <p>Si has olvidado tu contraseña, introduce tu e-mail y te enviaremos un correo con un enlace a una página donde podrás restablecerla.</p>
                        <label for="email">E-mail</label> 
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required="required" autofocus="autofocus">
                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group row">
                        <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Enviar enlace</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

