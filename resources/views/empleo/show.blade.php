@extends('puzzle.master')

{{-- NOTICIAS puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

@section('contenido-detalle')

@php $seccion = $pagina->menu->name; @endphp

    <div class="container" id="listaNoticias">
    <div class="row py-3 mb-2 align-items-center bg-{{ $seccion }}">
        <div class="pl-3 input-group w-100">
            <div id="noticias-encontradas" type="text" class="bg-{{ $seccion }} w-100 border-0 border-bottom text-white">{!! $empleo->titulo !!}</div>
        </div>
    </div>

    <!-- El cuerpo de la noticia -->
    <div class="noticias-index px-0">
        <div class="row mb-3">
            <div class="col-12 callout {{ $seccion }} flex-row w-100">
                <div class="d-flex flex-column align-items-start">
                    <div class="container">
                        <div class="row pt-3">
                            <div id="titulo-noticia" class="w-100 col-6"><b>{!! $empleo->autor !!}</b></div>
                            <div id="btn-volver-noticia" class="col-6 W-100 text-right"><a href="javascript:history.back()">Volver</a></div>
                            <div id="fecha-formateada" class="w-100 col-12"><em>{!! $empleo->fecha_formateada !!}</em></div>
                            <div id="texto-noticia" class="col-12 mt-2">{!! $empleo->texto_formateado !!}</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('contenido')
    @include('puzzle.contenido')
@endsection