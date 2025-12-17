                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Correo electrónico: *</label>
                    <div class="col-sm-6">
                        <input name="email" type="text" value="{{ old('email', $usuario->email) }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Nombre a mostrar" required>
                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tratamiento" class="col-sm-2 col-form-label">Tratamiento: *</label>
                    <div class="col-sm-1">
                        <input name="tratamiento" type="text" value="{{ old('tratamiento', $usuario->tratamiento) }}" class="form-control{{ $errors->has('tratamiento') ? ' is-invalid' : '' }}" placeholder="Tratamiento" maxlength="10" required>
                        @if ($errors->has('tratamiento'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('tratamiento') }}</strong>
                        </div>
                        @endif
                    </div>

                    <label for="nombre" class="col-sm-1 col-form-label">Nombre: *</label>
                    <div class="col-sm-4">
                        <input name="nombre" type="text" value="{{ old('nombre', $usuario->nombre) }}" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" placeholder="Nombre" maxlength="40" required>
                        @if ($errors->has('nombre'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('nombre') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="apellidos" class="col-sm-2 col-form-label">Apellidos: *</label>
                    <div class="col-sm-6">
                        <input name="apellidos" type="text" value="{{ old('apellidos', $usuario->apellidos) }}" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" placeholder="Apellidos" maxlength="40" required>
                        @if ($errors->has('apellidos'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('apellidos') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="archivo" class="col-sm-2 col-form-label">Foto de perfil:</label>
                    <div class="col-sm-6">
                        <input type="file" name="imagen" accept="image/*" class="{{ $errors->has('archivo') ? ' is-invalid' : '' }}">
                        @if ($errors->has('archivo'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('archivo') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

