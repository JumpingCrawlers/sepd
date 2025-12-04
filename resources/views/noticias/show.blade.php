@extends('puzzle.master')

{{-- NOTICIAS puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

{{-- seccion = nombre del menú --}}
@php $seccion = $pagina->menu->name @endphp

@section('contenido-detalle')
    <div class="container" id="listaNoticias">
    <div class="row py-3 mb-2 align-items-center bg-{{ $seccion }}">
        <div class="pl-3 input-group w-100">
            <div id="noticias-encontradas" type="text" class="bg-{{ $seccion }} w-100 border-0 border-bottom text-white">{!! $noticia->titulo !!}</div>
        </div>
    </div>

    <!-- El cuerpo de la noticia -->
    <div class="noticias-index px-0">
        <div class="row mb-3">
            <div class="col-12 callout {{ $seccion }} flex-row w-100">
                <div class="d-flex flex-column align-items-start">
                    <div class="container">
                        <div class="row pt-3">
                            <div id="titulo-noticia" class="w-100 col-6"><b>{!! $noticia->autor !!}</b></div>
                            <div id="btn-volver-noticia" class="col-6 W-100 text-right"><a href="javascript:history.back()">Volver</a></div>
                            <div id="fecha-formateada" class="w-100 col-12"><em>{!! $noticia->fecha_formateada !!}</em></div>
                            <div id="texto-noticia" class="col-12 mt-2 text-justify">{!! nl2br($noticia->texto) !!}</div>

                            {{-- si la noticia tiene un enlace se muestra --}}
                            @if ($noticia->enlace != '' && $noticia->enlace != "http://")
                                <div class="col-12 text-left flex-column w-100 pt-2">
                                    <!-- Link  -->
                                    <a target='_blank' class="text-nodeco" href="{!! $noticia->enlace !!}">
                                        <img src="{!! Voyager::image(setting('iconos.enlace')) !!}" class="img-fluid" width="20px"> Ver enlace
                                    </a>
                                </div>
                            @endif
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
