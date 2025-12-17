{{-- 3 Ways Carlos Colmenarez --}}
@extends('base.sepd')

@section('styles')
 @include('styles.aula')
@endsection

@section('content')
<div class="container">
    <div class="col-12">
        <div class="row py-3 mb-3 align-items-center bg-formacion">
            <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Aula virtual</h2>
        </div>

        <div class="pointer mb-4 px-0 pb-3">
            <div class="row border-blue">
                <div class="col-sm-8">
                    <a href="{{route('formacion.mis-cursos')}}">
                        <button type="button" class="btn btn-light btn-lg btn-block big">
                            <i class="fas fa-book big"></i><br>
                            <span class="big">Mis cursos</span>
                        </button>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{route('mensajes.usuario')}}">
                        <button type="button" class="btn btn-light btn-lg btn-block big">
                            <i class="fas fa-comments big"></i><br>
                            <span class="big">Mensajes con tutores</span>
                        </button>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{route('formacion.acreditaciones')}}">
                        <button type="button" class="btn btn-light btn-lg btn-block big">
                            <i class="fas fa-file-contract big"></i><br>
                            <span class="big">Historial de acreditaciones</span>
                        </button>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{route('cursos')}}">
                        <button type="button" class="btn btn-light btn-lg btn-block big">
                            <i class="fas fa-chalkboard-teacher big"></i><br>
                            <span class="big">Cursos disponibles</span>
                        </button>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{route('formacion.calculadora')}}">
                        <button type="button" class="btn btn-light btn-lg btn-block big">
                            <i class="fas fa-calculator big"></i><br>
                            <span class="big">Calculadora</span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection