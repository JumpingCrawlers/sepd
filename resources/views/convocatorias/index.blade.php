@extends('puzzle.master')

{{-- CONVOCATORIAS puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection


@section('contenido')

<div class="container mt-2 mb-3 border border-primary">
    <div class="row mt-4">
        <div class="col-sm-9">

            Convocatorias: {{ $coleccion->total() }} (mostrando del {{ ($coleccion->perPage()*($coleccion->currentPage()-1)+1)." al ".min($coleccion->perPage()*$coleccion->currentPage(), $coleccion->total()) }})

            {{ $coleccion->links('paginacion.bootstrap-4') }}

            @foreach ($coleccion as $elemento)

            <div class="row mt-2">
                <div class="col-sm">
                    <div class="card border-left border-primary">
                        <div class="card-header">
                            <p><a href="/convocatorias/{{ $elemento->id }}">{!! $elemento->titulo !!}</a></p>
                            <p>{!! $elemento->lugar !!}</p>
                        </div>

                        <div class="card-body">
                            {!! str_limit($elemento->texto, 100) !!}
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
            
            {{ $coleccion->links('paginacion.bootstrap-4') }}
            
        </div>
    </div>
</div>
@endsection
