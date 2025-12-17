@extends('puzzle.master')

@section('contenido')
<div class="container">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8">
            <div class="card" id="recordarPassword">
                <div class="card-header bg-institucional">
                    <h5 class="text-white" >¿Olvidaste la contraseña?</h5>
                </div>
                <div class="card-body">
                    @include('auth.form_password_reset_email')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
