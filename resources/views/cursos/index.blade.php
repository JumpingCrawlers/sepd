@auth
@endauth
@php $miga_pan = "-"; @endphp

@extends('puzzle.master')

@section('slider')
    @include('puzzle.slider')
@endsection

@section('contenido')
    <div class="container px-0 mb-3">
        <div class="row">
            <div class="col-sm-4 col-lg-3">

                <form name="formFiltros" id="formFiltros" method="POST">
                    <input type="hidden" name="tipo"
                        value="{{ basename(url()->current()) == 'cursos' ? 'disponibles' : basename(url()->current()) }}">
                    <div class="container">
                        <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-formacion">
                            <div class="input-group w-100">
                                @include('puzzle.buscador', [
                                    'tamanyo' => 'w-87',
                                    'fondo' => 'bg-formacion',
                                ])
                            </div>
                        </div>
                    </div>

                    <div class="container mb-2">
                        @include('puzzle.filtros.biblioteca_areas', ['tis' => $tis, 'seccion' => 'formacion'])
                    </div>

                    <div class="container mb-2">
                        @include('puzzle.filtros.cursos_formatos')
                    </div>

                    <div class="container mb-2">
                        @include('puzzle.filtros.cursos_anyos')
                    </div>

                    <div class="container mb-3">
                        @include('puzzle.filtros.cursos_paises')
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

                    <cursos-index ref="CursosIndex" token="{{ auth()->user() ? auth()->user()->encrypt_manual_gestivos : '' }}" url-web-antigua='{{ setting('site.url_web_antigua') }}'>

                    </cursos-index>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        #listaCursos .callout-right.formacion-secundario {
            border-right-color: #D0BEF2 !important;
        }
        #listaCursos a {
            color: #040C55
        }
        #listaCursos .page-item.active .page-link {
            background-color: #D0BEF2;
        }
        #listaCursos .pagination .page-link {
            border: 2px solid #4E25CC;
        }
        #listaCursos .pagination {
            justify-content: center
        }
    </style>
    {{-- JS general, vue + filtros --}}
    {{-- JS específico para cursos --}}
    <script type="text/javascript" src="{{ asset('js/filtros.js?v=3') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/cursos.js?v=2025') }}"></script>
@endsection
