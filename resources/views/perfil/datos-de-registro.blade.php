<div class="container">
    <div class="col-12">
        <div class="pointer mb-4 px-0 pb-3">
            <div class="row left-bordered">
                <div class="col-12 col-md-8 col-xl-6 offset-0 offset-md-2 offset-xl-3">
                    <div id="radios" class="d-flex justify-content-around">
                        <!-- <div class="custom-control custom-radio">
                            <input type="radio" id="radio1" data-id="presencial" name="radio-btn" class="custom-control-input">
                            <label class="custom-control-label" for="radio1">Congreso SEPD</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="radio2" data-id="online" name="radio-btn" class="custom-control-input" checked>
                            <label class="custom-control-label" for="radio2">Formación</label>
                        </div> -->
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <label class="col-sm-3 col-form-label">Correo electrónico:</label>
                        <label class="col-sm-8 col-form-label">
                            {{ $usuario->email }}
                        </label>
                        <label class="col-sm-3 col-form-label">Contraseña:</label>
                        <label class="col-sm-8 col-form-label">
                            ***********
                        </label>
                        <label class="col-sm-3 col-form-label"></label>
                        <label class="col-sm-8 col-form-label">
                            <a role="button" href="/perfil/editar" class="btn" style="color:#ffffff;background-color:#db812e">Modificar datos</a>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
