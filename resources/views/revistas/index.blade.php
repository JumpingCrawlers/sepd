@extends('puzzle.master')

@php $seccion = $pagina->menu->name; @endphp

@section('slider')

    @include('puzzle.slider')

@endsection


@section('contenido')


<div class="container px-0 mb-3">
    @auth
        @if(\App\UsuarioSocio::where('usuario_id', auth()->id())->first())
            <div class="alert alert-warning" role="alert">
                La información sobre medicamentos está dirigida exclusivamente al profesional destinado a prescribirlos o dispensarlos, por lo que se requiere una formación especializada para su correcta interpretación.
            </div>

        @endif
    @endauth

    <div class="row">
        <div class="col-lg-3 col-col-md-5 col-sm-5 col-xs-12 mb-3 @if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">
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
                    @include('puzzle.filtros.revistas_anyos')
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
        <div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">
            <div class="container" id="contenidoVue">
                <revistas-index
                    ref="RevistasIndex"
                    url='{{ config('app.url') }}'
                    url-back='{{ config('app.url_back') }}'
                    seccion='{{ $seccion }}'
                    iconos='{{ $iconos }}'
                    estilo-boton='{{ getHtmlEstiloBoton() }}'>
                </revistas-index>
            </div>
        </div>
    </div>
</div>

    <style>
        #filtroAnyos div.container{ display: none; }
        #filtroAnyos div.container:nth-child(1),
        #filtroAnyos div.container:nth-child(2),
        #filtroAnyos div.container:nth-child(3) {
            display: block !important;
        }
    </style>

@endsection

@section('scripts')

    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para revistas --}}
    <script src="{{ asset('js/revistas.js') }}"></script>
@endsection