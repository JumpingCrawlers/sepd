@extends('puzzle.master')

@section('estilos')
    <style>
        .g-recaptcha.is-invalid {
            background-color: red;
            border-left: 2px solid red;
            border-top: 2px solid red;
        }
    </style>
@endsection

@section('contenido')
<div class="container mb-4">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8">
            <div class="card" id="normalLogin">
                <div class="card-header bg-institucional">
                    <h5 class="text-white" >{{ $modal_title ?? 'Registro de usuarios' }}</h5>
                </div>
                <div class="card-body">
                    @include('auth.form_registro')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
