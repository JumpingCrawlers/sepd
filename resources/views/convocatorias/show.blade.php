@extends('puzzle.master')

@section('contenido')
<div class="container px-0 mt-2 mb-3 border border-primary">
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">{!! $convocatoria->titulo !!}</div>

                <div class="card-body">
                    <p>{!! $convocatoria->lugar !!}</p>
                    <p>{!! $convocatoria->texto !!}</p>
                    <p><a href="{{ $convocatoria->enlace }}">{!! $convocatoria->enlace !!}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
