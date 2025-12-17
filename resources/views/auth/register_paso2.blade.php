@extends('puzzle.master')

@section('contenido')
<div class="container mb-4">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8">
            <div class="card" id="normalLogin">
                <div class="card-header bg-institucional">
                    <h5 class="text-white" >Registro de usuarios</h5>
                </div>
                <div class="card-body">
                    @include('auth.form_registro_paso2')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
