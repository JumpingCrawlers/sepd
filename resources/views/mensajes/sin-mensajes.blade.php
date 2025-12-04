@extends('base.sepd')
@section('styles')
@include('styles.sin-cursos')
@endsection
@section('content')

<div class="container">
    <div class="col-sm-12">
        <div class="row py-3 mb-3 align-items-center bg-formacion">
            <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Mis mensajes</h2>
        </div>

        <div class="pointer mb-4 px-0 pb-3">
            <div class="row left-bordered">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-sm-12 fz-1-5">
                                <h2><b>¡No has hecho ninguna consulta a tutores!</b></h2>
                                Anímate y realiza tu primera consulta!
                            </div>
                            <div class="col-sm-12 mt-3 text-align-center">
                                <i class="fas fa-comment-dots" style="font-size: 16.5em;color: #e5e5e5;"></i>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <a href="{{route('formacion.mis-cursos')}}">
                                    <button type="button" class="btn btn-primary btn-lg right">Ir a mis cursos ></button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection