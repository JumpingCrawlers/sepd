@extends('puzzle.master')

@section('slider')

    @include('puzzle.slider')

@endsection


@section('contenido')


<div class="container px-0 mb-3">
    <div class="row">
        <div class="col-sm-3 @if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">
            {{-- formulario de los filtros --}}
            <form name="formFiltros" id="formFiltros" method="POST">
                <div class="container">
                    <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-prensa">
                        <div class="input-group w-100">
                            @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-prensa'])
                        </div>
                    </div>
                </div>

                <div class="container">
                    @include('puzzle.filtros.podcasts_anyos')
                </div>

                <input type="hidden" name="filtrosGet" id="filtrosGet">
                <input type="hidden" name="paginaGet" id="paginaGet">
            </form>

            {{-- loop de las plantillas de la página, en este caso los valores de ancho y posición no se usan --}}
            @if ($pagina->contenido_extra > 0)
                @foreach ($pagina->pastillas_contenido as $pastilla)
                    <div class="mt-3">

                        @php $margen_inf = ''; @endphp
                        @include('paginas.pastilla')

                    </div>
                @endforeach
            @endif
        </div>

        {{-- sección donde se muestran las noticias --}}
        <div class="col-sm-9">
            <div class="container" id="contenidoVue">

                <podcast-index ref="PodcastIndex" url-web-antigua='{{ setting('site.url_web_antigua') }}' iconos='{{ $iconos }}'>
                    
                </podcast-index>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para podcasts --}}
    <script src="{{ asset('js/podcasts.js') }}"></script>
@endsection