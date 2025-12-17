@extends('puzzle.master')

@php $seccion = $pagina->menu->name; @endphp

{{-- Biblioteca puede tener slider, depende de "tipo_slider" --}}
@section('slider')
    @include('puzzle.slider')
@endsection

@section('estilos')
    <style>
        .menu-destacados.cid.activo {
            background-color: #ffa43a;
        }

        .menu-destacados.cid {
            border: 1px solid #ffa43a;
        }

        .page-link {
            color: #4e25cc;
        }
        .page-item.active .page-link {
            background-color: #4e25cc;
            border-color: #4e25cc;
        }
    </style>
@endsection

{{-- Los destacados de la biblioteca son las áreas de búsqueda inicial --}}
{{--
    @section('destacados')
        @include('puzzle.menu_destacados', [
            'destino' => 'biblioteca' ,
            'opciones' => $areas,
        ])
    @endsection
--}}

@section('contenido')
    <div class="container px-0 mb-3">
        <div class="row">
            <div class="col-sm-4 col-lg-3">
                <form name="formFiltros" id="formFiltros" method="POST">
                    <div class="container">
                        <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-{{ $seccion }}">
                            <div class="input-group w-100">
                                @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-'.$seccion])
                            </div>
                        </div>
                    </div>
                    <div class="container mb-2">
                        @include('puzzle.filtros.biblioteca_areas', ['tis' => $tis])
                    </div>
                    <div class="container mb-2">
                        @include('puzzle.filtros.biblioteca_formatos', ['tis' => $tis])
                    </div>
                    <div class="container mb-2">
                        @include('puzzle.filtros.biblioteca_emedia', ['tis' => $tis])
                    </div>
                    <div class="container mb-2">
                        @include('puzzle.filtros.biblioteca_anyos')
                    </div>
                    <input type="hidden" name="filtrosGet" id="filtrosGet">
                    <input type="hidden" name="paginaGet" id="paginaGet">
                </form>
            </div>
            <div class="col-sm-8 col-lg-9">
                <div class="container" id="contenidoVue">
                    <biblioteca-index ref="BibliotecaIndex" url='{{ config('app.url') }}' url-back='{{ config('app.url_back') }}' iconos='{{ $iconos }}' seccion='{{ $seccion }}'>                 
                    </biblioteca-index>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    <script src="{{ asset('js/biblioteca.js') }}?v=2024"></script>
@endsection
