{{-- 3 Ways - Carlos Colmenarez --}}
@extends('base.sepd')
@section('styles')

@endsection

@section('content')
<div class="container">
    <div class="col-12">
        <a href="{{ route('curso.hacer', $curso->id) }}">
            <button type="button" class="btn btn-primary btn-md">< Volver</button>
        </a>
            <div class="alert alert-warning mt-4">
                <h4 style="text-align:center;"> No es posible inscribirse debido a que el curso se encuentra en estado:<h4>
                <h3 style="text-align:center;"><strong>{{$estado_curso}}</strong><h3>
            </div>
        </div>
    </div>
@endsection