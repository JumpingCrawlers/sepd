@extends('puzzle.master')

{{-- Todas las páginas pueden tener slider, depende de "tipo_slider" --}}
@section('estilos')
    <style>
        #page-quiero-ser-socio {
            color:#ffffff;
            background-color:#db812e;
            position: absolute;
            right: 1.5rem;
            top: 5px;
        }

        table {
            max-width: 100%;
        }
    </style>
@endsection

@section('slider')

    @include('puzzle.slider')

@endsection

{{-- Del mismo modo, todas las paginas pueden tener pastillas, depende de 
     si tienen pastillas asignadas (se muestran en 2 ó 3 columnas.    --}}

@section('pastillas')

    @include('puzzle.pastillas')

@endsection

{{-- También con los destacados --}}

@section('destacados')

    @include('puzzle.destacados')

@endsection

@section('contenido-detalle')
    @if ($pagina->slug == 'ventajas_socios')
        @if (url()->current() != route('hazte_socio') && (!Auth::user() || !Auth::user()->socio))
            <a href="{{ route('hazte_socio') }}"  id="page-quiero-ser-socio" class="btn">
                Quiero ser socio
            </a>
        @endif
    @endif
    {!! getHtmlContenido($pagina->contenido, $pagina->menu->name, $pagina_codificada) !!}

@endsection

@section('contenido')

    @include('puzzle.contenido')

@endsection
