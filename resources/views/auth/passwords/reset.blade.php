@php
    $pagina = null;
    $nombre_menu='institucional';
    // no se muestra el acceso en la cabecera
    $cabecera_sin_acceso_usuarios = 'Sí';
@endphp

@extends('puzzle.master')

@section('contenido')
<div class="container">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8">
            <div class="card" id="passwordReset">
                <div class="card-header bg-institucional">
                    <h5 class="text-white" >Restablecer contraseña</h5>
                </div>
                <div class="card-body">
                    @include('auth.form_password_reset')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
