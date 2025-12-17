@extends('puzzle.master')

@section('estilos')
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

@section('slider')

    @include('puzzle.slider')

@endsection

@section('contenido')
<div class="container px-0 mb-3">
    <div class="row">
        <div class="col-sm-3 @if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">

            <form name="formFiltros" id="formFiltros" class="" method="POST">
                <div class="container">
                    <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-prensa">
                        <div class="input-group w-100">
                            @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-prensa'])
                        </div>
                    </div>
                </div>

                <div class="container">
                    @include('puzzle.filtros.dossier_anyos')
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

        {{-- sección donde se muestran los artículos --}}
        <div class="col-sm-9 pl-3">
            <div class="container px-0" id="contenidoVue">

                <dossier-index ref="DossierIndex" url='{{ config('app.url') }}' url-back='{{ config('app.url_back') }}' iconos='{{ $iconos }}'>
                </dossier-index>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para dossier --}}
    <script src="{{ asset('js/dossier.js') }}"></script>
@endsection

