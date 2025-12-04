{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')
@section('styles')
<style>
.text-align-center {
    text-align: center;
}
</style>
@endsection
@section('content')
<div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">{{ $curso->titulo }}</h2>
            </div>
            {{--Sección encuesta finalizada - 3ways Euro Fuenmayor--}}
            <div class="row left-bordered">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-sm-12 fz-1-5">
                                <h2><b>¡Encuesta finalizada!</b></h2>
                                Muchas gracias por participar.
                            </div>
                            <div class="col-sm-12 mt-3 text-align-center">
                                <i class="fas fa-vote-yea" style="font-size: 16.5em;color: #e5e5e5;"></i>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <a href="{{ route('curso.hacer', $curso->id) }}">
                                    <button type="button" class="btn btn-primary btn-lg right">Ir a la ficha del curso &gt;</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection