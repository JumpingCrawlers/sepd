@extends('puzzle.master')

@section('estilos')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/proyectos.css') }}?v=2024">
    <style>
        .page-link {
            color: #ffa43a;
        }
        .page-item.active .page-link {
            background-color: #ffa43a;
            border-color: #ffa43a;
        }
    </style>
@endsection

@section('slider')

    @include('puzzle.slider')

@endsection

@section('contenido')
<div class="container px-0 mb-3">
    <div class="row">
        <div class="col-sm-4 col-lg-3">

            <form name="formFiltros" id="formFiltros" method="POST">

                <div class="container">
                    <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-cid">
                        <div class="input-group w-100">
                            @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-cid'])
                        </div>
                    </div>
                </div>

                <div class="container mb-2">
                    @include('puzzle.filtros.biblioteca_areas', ['tis' => $tis, 'seccion' => 'cid'])
                </div>

                <div class="container mb-2">
                    @include('puzzle.filtros.proyectos_fases')
                </div>

                <div class="container mb-2">
                    @include('puzzle.filtros.proyectos_gestion', ['areaGestion' => $areaGestion, 'areaCalidad' => $areaCalidad])
                </div>

                <div class="container mb-3">
                    @include('puzzle.filtros.proyectos_estados', ['estadoProyecto' => $estadoProyecto])
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
        <div class="col-sm-8 col-lg-9 pl-4">
            <div class="container pl-0" id="contenidoVue">
                
                <proyectos-index ref="ProyectosIndex" url-web-antigua='{{ setting('site.url_web_antigua') }}'>
                    
                </proyectos-index>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para proyectos --}}
    <script src="{{ asset('js/proyectos.js?v=2024') }}"></script>
    
@endsection
