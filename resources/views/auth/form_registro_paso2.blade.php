    <div class="container">
        <div class="row">
            <div class="col-10 offset-1">
                <form role="form" method="POST" action="{{ route('register_paso2') }}">
                    {{ csrf_field() }}
                    {{-- guardar la página para volver al finalizar el proceso --}}
                    <input type="hidden" name="pagina" value="@if (isset($pagina)){{ $pagina->slug }}@endif" >
                    <input type="hidden" name="id" value="{{ $usuario->id_usuario }}">
                    <input type="hidden" name="dni" value="{{ $usuario->dni }}">

                    <div class="form-group row">
                        <p>
                            {{ $usuario->nombre . ' ' . $usuario->apellidos . ',' }}<br><br>
                            Es el socio Núm: <strong>{{ $usuario->nsocio }}</strong>.
                        </p>
                        <p>A continuación, te mostramos algunos datos que tenemos almacenados en tu perfil. <strong>Asegúrate de que el email es correcto</strong> ya que se te enviará a esta cuenta tu contraseña:</p>
                    </div>
                    <div class="form-group row">
                        @if ($errors->has('confirmacion'))
                        <div class="alert alert-danger">
                            <strong>{!! $errors->first('confirmacion') !!}</strong>
                        </div>
                        @endif
                        <label for="correo">Email</label>
                        <input id="correo" type="email" name="correo" value="@if (old('correo')){{ old('correo') }}@else{{ $usuario->email }}@endif" class="form-control{{ $errors->has('correo') ? ' is-invalid' : '' }}" required="required" autofocus="autofocus">
                        @if ($errors->has('correo'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('correo') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="centro">Hospital</label>
                        <input id="centro" type="text" name="centro" value="@if (old('centro')){{ old('centro') }}@else{{ $usuario->centro }}@endif" class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}">
                        @if ($errors->has('centro'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('centro') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="especialidad">Especialidad</label>
                        <input id="especialidad" type="text" name="especialidad" value="@if (old('especialidad')){{ old('especialidad') }}@else{{ $usuario->especialidad }}@endif" class="form-control{{ $errors->has('especialidad') ? ' is-invalid' : '' }}">
                        @if ($errors->has('especialidad'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('especialidad') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group row form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="acepto" class="form-check-input" required="required"> He leído y acepto la <a href="/privacidad" class="btn btn-link pl-0" target="_blank">Política de privacidad</a>
                        </label>
                    </div>

                    <div class="form-group row">
                        <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Confirmar datos y continuar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

