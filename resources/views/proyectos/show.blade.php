@extends('puzzle.master')

@section('estilos')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/proyectos.css') }}?v=2024">
@endsection

{{-- Proyectos puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

{{-- seccion = nombre del menú --}}
@php $seccion = $pagina->menu->name @endphp

@section('contenido-detalle')
    <div class="container">
    <div class="row py-3 mb-2 align-items-center bg-{{ $seccion }}">
        <div class="pl-3 input-group w-100">
            <div id="cabecera-proyecto" type="text" class="bg-{{ $seccion }} w-100 border-0 border-bottom text-white font-weight-bold">{!! $proyecto->titulo !!}</div>
        </div>
    </div>

    <!-- El cuerpo del proyecto -->
    <div class="px-0">
        <div class="row mb-3">
            <div class="col-12 callout {{ $seccion }} flex-row w-100">
                <div class="d-flex flex-column align-items-start">
                    <div class="container">
                        <div class="row pt-3">
                            <div class="col-12 pr-0">
                                <div class="w-100 text-right">
                                    <a href="javascript:history.back()">Volver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-md-4 col-lg-3 px-0">
                                <img src="{{ $proyecto->url_miniatura }}" class="img-fluid">
                                <div class="mt-2 px-0 pt-1 w-100 text-white text-center {{ $proyecto->datos_fase['clase_css'] }}">
                                    Fase: {{ $proyecto->datos_fase['descripcion'] }}
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-9 pr-0">
                                <div class="mb-2 text-justify">{!! $proyecto->resumen !!}</div>
                                <div class="mb-2 text-justify">{!! $proyecto->descripcion !!}</div>

                                {{-- enlaces de los proyectos --}}
                                <ul>
                                @if ($proyecto->url_formularios != '')
                                    <li>
                                        <div class="col-12 text-left flex-column w-100 pt-2">
                                            <a target='_blank' class="text-nodeco" href="{{ $proyecto->url_formularios }}">
                                                Cuestionario de encuesta
                                            </a>
                                        </div>
                                    </li>
                                @endif
                                @if ($proyecto->url_resultados != '')
                                    <li>
                                        <div class="col-12 text-left flex-column w-100 pt-2">
                                            <a target='_blank' class="text-nodeco" href="{{ $proyecto->url_resultados }}">
                                                Resultados (PowerBI)
                                            </a>
                                        </div>
                                    </li>
                                @endif
                                @if ($proyecto->url_productos != '')
                                    <li>
                                        <div class="col-12 text-left flex-column w-100 pt-2">
                                            <a target='_blank' class="text-nodeco" href="{{ $proyecto->url_productos }}">
                                                Productos (artículo científico)
                                            </a>
                                        </div>
                                    </li>
                                @endif
                            </div>
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
