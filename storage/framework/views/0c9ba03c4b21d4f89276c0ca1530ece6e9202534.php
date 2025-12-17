<?php
    $route_login = route('login');
    if (isset($route))
        $route_login = $route_login . "?route={$route}";
?>

<div class="container">
    <div class="row">
        <div class="col-10 offset-1 mb-3">
            <p class="h5">Inicia sesión con tu correo electrónico y contraseña.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-10 offset-1" style="color: #040b54">
            <form method="POST" action="<?php echo e($route_login); ?>" class="row" id="modal-form-login">
                <?php echo e(csrf_field()); ?>

  
                <div class="form-group | col-12">  
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>"
                        class="form-control<?php echo e(isset($errors) && $errors->has('email') ? ' is-invalid' : ''); ?>" required="required"
                        autofocus="autofocus">
                    <?php if(isset($errors) && $errors->has('email')): ?>
                        <div class="invalid-feedback">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group | col-12">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required="required"
                        class="form-control<?php echo e(isset($errors) && $errors->has('password') ? ' is-invalid' : ''); ?>">
                    <?php if(isset($errors) && $errors->has('password')): ?>
                        <div class="invalid-feedback">
                            <strong><?php echo e($errors->first('password')); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group | col-12">
                    <div class="row">
                        <div class="col-12 col-md-9 mb-2">
                            <label class="form-check-label" style="padding-left: 16px; cursor: pointer">
                                <input style="top: -1px" type="checkbox" name="remember" class="form-check-input" <?php echo e(old('remember') ? 'checked' : ''); ?>> Recuerda mis datos de inicio de sesión
                            </label>
                        </div>

                        <div class="col-12 col-md-3 mb-2">
                            <button type="submit" class="btn - w-100" <?php echo e(getHtmlEstiloBoton('', '')); ?>>
                                Entrar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group | col-12">
                    <a href="<?php echo e(route('password.request')); ?>" class="btn-link pl-0 d-block mb-1">¿Has olvidado tu contraseña?</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-link pl-0 d-block mb-0">¿Tienes problemas para acceder?</a>
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
                            <a href="<?php echo e(route('registroUsuario')); ?>" class="btn btn-link text-white borde-medio borde-institucional bg-institucional text-center">
                                NUEVO USUARIO
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\sepd.es\resources\views/auth/form_login.blade.php ENDPATH**/ ?>