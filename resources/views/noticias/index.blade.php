@extends('puzzle.master')

{{-- NOTICIAS puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

@section('destacados')

    @include('puzzle.destacados')

@endsection


{{-- seccion = nombre del menú --}}
@php $seccion = $pagina->menu->name @endphp

@section('contenido')

<div class="container px-0 mb-3">
    <div class="row">
        <div class="col-sm-4 col-lg-3 mb-3 @if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">
            {{-- formulario de los filtros --}}
            <form name="formFiltros" id="formFiltros" method="POST">
                <div class="container">
                    <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-{{ $seccion }}">
                        <div class="input-group w-100">
                            @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-'.$seccion])
                        </div>
                    </div>
                </div>

                <div class="container">
                    @include('puzzle.filtros.noticias_anyos', ['seccion' => $seccion])
                </div>

                <input type="hidden" name="filtrosGet" id="filtrosGet">
                <input type="hidden" name="paginaGet" id="paginaGet">
            </form>

            {{-- loop de las plantillas de la página (si las hay y si estan activadas), en este caso los valores de ancho y posición no se usan --}}
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
        <div class="col-sm-8 col-lg-9 pl-4">
            <div class="container" id="contenidoVue">
                <noticia-index ref="NoticiaIndex" seccion='{{ $seccion }}' url-web-antigua='{{ setting('site.url_web_antigua') }}' iconos='{{ $iconos }}' >
                    
                </noticia-index>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para noticias --}}
    <script src="{{ asset('js/noticias.js?v=2') }}"></script>
@endsection
