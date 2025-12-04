@php
    $route_login = route('login');
    if (isset($route))
        $route_login = $route_login . "?route={$route}";
@endphp

<div class="container">
    <div class="row">
        <div class="col-10 offset-1 mb-3">
            <p class="h5">Inicia sesión con tu correo electrónico y contraseña.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-10 offset-1" style="color: #040b54">
            <form method="POST" action="{{ $route_login }}" class="row" id="modal-form-login">
                {{ csrf_field() }}
  
                <div class="form-group | col-12">  
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control{{ isset($errors) && $errors->has('email') ? ' is-invalid' : '' }}" required="required"
                        autofocus="autofocus">
                    @if (isset($errors) && $errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group | col-12">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required="required"
                        class="form-control{{ isset($errors) && $errors->has('password') ? ' is-invalid' : '' }}">
                    @if (isset($errors) && $errors->has('password'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group | col-12">
                    <div class="row">
                        <div class="col-12 col-md-9 mb-2">
                            <label class="form-check-label" style="padding-left: 16px; cursor: pointer">
                                <input style="top: -1px" type="checkbox" name="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}> Recuerda mis datos de inicio de sesión
                            </label>
                        </div>

                        <div class="col-12 col-md-3 mb-2">
                            <button type="submit" class="btn - w-100" {{ getHtmlEstiloBoton('', '') }}>
                                Entrar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group | col-12">
                    <a href="{{ route('password.request') }}" class="btn-link pl-0 d-block mb-1">¿Has olvidado tu contraseña?</a>
                    <a href="{{ route('register') }}" class="btn-link pl-0 d-block mb-0">¿Tienes problemas para acceder?</a>
                </div>

                <div class="col-12">
                    <hr>
                </div>
                
                <div class="form-group col-12">
                    <div class="row">
                        <div class="col-12">
                            <p class="h5">¿No tienes cuenta con nosotros?</p>
                        </div>
                        <div class="col-12 mb-2">
                            <p class="h5">Completa nuestro formulario de registro y disfruta de los servicios que ponemos a tu disposición.</p>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('registroUsuario') }}" class="btn btn-link text-white borde-medio borde-institucional bg-institucional text-center">
                                NUEVO USUARIO
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>