@extends('base.sepd')
@php use Illuminate\Support\Str; @endphp

@section('title', $proyecto->titulo)

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2>{{ $proyecto->titulo }}</h2>
        </div>
        <div class="card-body">
            <h3>{{$proyecto->nombre}}</h3>
            @php
                $cursoMasAntiguo = $cursos->sortBy('fecha_inicio')->first();
            @endphp
            <p><strong>Fecha de inicio: {{ $cursoMasAntiguo 
            ? \Carbon\Carbon::parse($cursoMasAntiguo->fecha_inicio)->format('d/m/Y') 
            : 'No hay cursos.' }}</strong> </p>
            @if($proyecto->descripcion)
                <p><strong>Descripción:</strong></p>
                <p>{!! html_entity_decode($proyecto->descripcion) !!}</p>
            @endif
        </div>
    </div>

    <h4>Cursos asociados al proyecto</h4>

    @if($cursos->count())
        @foreach ($cursos as $index => $curso)
    <div class="curso-catologo pointer mb-4 pt-3 pb-3" data-id-curso="{{ $curso->id }}">
        <a href="{{ route('curso.mostrar', $curso->id) }}" class="text-nodeco" target="_blank">
            <div class="container">
                <div class="row">
                    <div class="col-4 callout-right formacion-secundario">
                        @if ($curso->acreditado)
                            <div class="bg-success text-center ml-0 w-100 px-3 pt-1 text-white d-table">Acreditado</div>
                        @endif
                        @if ($curso->imagen)
                            <img src="{{url(config('app.url_back'))}}storage/{{$curso->imagen}}" class="img-fluid flex-auto" width="100%">
                        @else
                            <img src="{{url(config('app.url_back'))}}storage/curso-sin-imagen.png" class="img-fluid flex-auto" width="100%">
                        @endif
                        
                    </div>
                    <div class="col-8">
                        <div class="align-items-start">
                            <b>{{ $curso->titulo }}</b>

                            @if ($curso->formato == 'curso')
                                <p>Fecha de inicio: {{ $curso->fecha_formateada }}</p>
                            @else
                                <p>Fecha de creación: {{ $curso->fecha_creacion_formateada }}</p>
                            @endif

                            @if (!empty($curso->periodo) && $curso->formato == 'curso')
                                <p>Duración: {{ $curso->periodo }} días desde el inicio del curso.</p>
                            @endif

                            @if ($curso->configuracion_socios)
                                <p><b>Exclusivo para socios</b></p>
                            @endif

                            <p><b>{{ html_entity_decode($curso->descripcion_estado, ENT_QUOTES | ENT_HTML5, 'UTF-8') }}</b></p>

                            @if (!is_null($curso->descripcion))
                                <p>{!! nl2br(e(Str::limit(strip_tags(html_entity_decode($curso->descripcion, ENT_QUOTES | ENT_HTML5, 'UTF-8'), 250)))) !!}</p>
                            @endif

                            @if (!empty($curso->descripcion_contenido))
                                <p class="font-italic">{{ html_entity_decode($curso->descripcion_contenido, ENT_QUOTES | ENT_HTML5, 'UTF-8') }}</p>
                            @endif

                            @if ($curso->creditos > 0 && (!empty($curso->fecha_fin) && $curso->fecha_fin >= \Carbon\Carbon::now()))
                                <p>Acreditación: <strong>{{ $curso->creditos }} créditos</strong></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach

    @else
        <p>No hay cursos asociados a este proyecto.</p>
    @endif
</div>
@endsection
