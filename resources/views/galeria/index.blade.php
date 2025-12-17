@extends('puzzle.master')

@section('slider')

    @include('puzzle.slider')

@endsection

@php $seccion = $pagina->menu->name; @endphp

@section('contenido')

<div class="container px-0 mb-3">
    <div class="row">
        <div class="col-sm-3 @if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante) flotante @endif">

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
        <div class="col-sm-12">
            <div class="container" id="contenidoVue">

                <galeria-index ref="GaleriaIndex" url='{{ config('app.url') }}' url-back='{{ config('app.url_back') }}' iconos='{{ $iconos }}' seccion='{{ $seccion }}'>            
                </galeria-index>

            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para galeria --}}
    <script src="{{ asset('js/galeria.js') }}"></script>
@endsection

@section('estilos')
    <link href="{{ asset('css/galeria.css') }}" rel="stylesheet">
@endsection