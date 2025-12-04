@extends('puzzle.master')

@section('estilos')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sepdtv.css') }}">
    <style>
        .page-link {
            color: #4e25cc;
        }
        .page-item.active .page-link {
            background-color: #4e25cc;
            border-color: #4e25cc;
        }
    </style>
@endsection

{{-- SEPD TV puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

{{-- Los destacados de SEPD-TV son las áreas de búsqueda inicial --}}

{{-- @section('destacados')
    @include('puzzle.menu_destacados', [
        'destino' => 'sepdtv' ,
        'opciones' => $areas
    ])
@endsection --}}


@section('contenido')
    <div class="container px-0 mb-3">
        <div class="row">
            @if (isset($video))
                <div class="col-md-12">
                    <div id="videoSepdtv">
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <a href="{{ route('sepdtv') }}" class="btn bg-publicaciones text-white">< Volver al listado de vídeos</a>
                            </div>

                            <div class="col-sm-12 publicaciones flex-row w-100">
                                <div class="col-12 py-3 mb-2 align-items-center bg-publicaciones">
                                    <div class="pl-0 col-auto input-group w-100">
                                        <input id="sepdtv-reproducciones" value="{{ (($video->contador == 1) ? ($video->contador . ' reproducción') : ($video->contador . ' reproducciones')) }}" type="text" class="bg-publicaciones w-100 border-0 border-bottom text-white" readonly>
                                    </div>
                                </div>

                                <div class="col-12 mt-3 color-publicaciones">
                                    <strong>{!! $video->titulo !!}</strong>
                                </div>

                                <div class="col-12">
                                    {!! $video->subtitulo !!}
                                </div>

                                <div class="col-12 mt-2">
                                    <div id="videoPlayer" class="embed-responsive embed-responsive-16by9">
                                        <video id="instanciaVideo{{ $video->codigo }}" controls="controls" src="{{ url(config('app.url_back')) }}/storage/sepd_tv/video/{{ $video->codigo }}.mp4" poster="{{ url(config('app.url_back')) }}/storage/sepd_tv/portada/{{ $video->codigo }}.jpg" class="embed-responsive-item"></video>
                                    </div>

                                    <div class="col-12 mt-3">
                                        {!! $video->descripcion !!}
                                    </div>
                                </div>
                            </div>
                
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-4 @if (isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">
                    {{-- formulario de los filtros --}}
                    <form name="formFiltros" id="formFiltros" method="POST">
                        <div class="container">
                            <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-{{ $pagina->menu->name }}">
                                <div class="input-group w-100">
                                    @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-'.$pagina->menu->name])
                                </div>
                            </div>
                        </div>
                        <div class="container mb-2">
                            @include('puzzle.filtros.biblioteca_areas', ['tis' => false])
                        </div>
                        <input type="hidden" name="filtrosGet" id="filtrosGet">
                        <input type="hidden" name="paginaGet" id="paginaGet">
                    </form>
                </div>
                <div class="col-md-8">
                    <div id="contenidoVue">
                        <sepdtv-index ref="SepdtvIndex" token="{{ auth()->user() ? auth()->user()->encrypt_manual_gestivos : '' }}"  url-back='{{ config('app.url_back') }}' seccion='{{ $pagina->menu->name }}' url-web-antigua='{{ setting('site.url_web_antigua') }}'>
                        </sepdtv-index>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @if (isset($video))
        <script>
            document.getElementById('todos').classList.add("activo");
            window.addEventListener('popstate', function() {
                location.reload();
            });
        </script>
    @else
        {{-- JS general, vue + filtros --}}
        <script src="{{ asset('js/vue.js') }}"></script>
        <script src="{{ asset('js/filtros.js') }}"></script>
        {{-- JS específico para sepdtv --}}
        <script src="{{ asset('js/sepdtv.js') }}?v=20250325"></script>
    @endif
@endsection
