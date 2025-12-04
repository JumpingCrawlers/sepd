@php
    // no se muestra el acceso en la cabecera
    $cabecera_sin_acceso_usuarios = 'Sí';
@endphp

@extends('puzzle.master')

@section('contenido')
<div class="container mb-4">
    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            {{-- <p class="alert alert-danger">Estás accediendo a contenido exclusivo para socios. Por favor, conéctate antes de continuar:</p> --}}
            <div class="card" id="normalLogin">
                <div class="card-header bg-institucional">
                    <h5 class="text-white" >Acceso usuarios</h5>
                </div>
                <div class="card-body">
                    @include('auth.form_login')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
