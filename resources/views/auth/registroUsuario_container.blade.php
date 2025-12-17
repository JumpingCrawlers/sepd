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
                        @include('auth.registroUsuario')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('estilos')
    {!! NoCaptcha::renderJs() !!}
@endsection

@section('scripts')
@endsection