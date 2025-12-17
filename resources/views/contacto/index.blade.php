@extends('puzzle.master')

@section('estilos')
    <style>
        #menu-principal {
            border-color: #040C55;
        }
        #contenido-detalle .row .col-sm-6:not(.pl-4) {
            color: #040C55;
        }
        #contenido-detalle .col-sm-6 p,
        #contenido-detalle .col-sm-6 p strong {
            color: #040C55!important;
        }
    </style>
@endsection

{{-- Todas las páginas pueden tener slider, depende de "tipo_slider" --}}
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

    {{-- Gestión especial para el formulario de contacto --}}
    @if ($posFormulario = strpos($pagina->contenido, '@@@Contacto#@@@'))
        
        {!! getHtmlContenido(substr($pagina->contenido, 0, $posFormulario), $pagina->menu->name) !!}

        @if (isset($enviado) && $enviado)
            <p class="alert alert-success">Tu mensaje ha sido enviado correctamente. En la dirección de correo que has introducido recibirás una copia como confirmación.</p>
            <a href="{{ route('contacto') }}" class="btn"{{ getHtmlEstiloBoton('', '') }}>Quiero enviar otro mensaje</a>
        @else
            @include('contacto.form_contacto')
        @endif
        {!! getHtmlContenido(substr($pagina->contenido, $posFormulario + 15), $pagina->menu->name) !!}
    @else
        {!! getHtmlContenido($pagina->contenido, $pagina->menu->name) !!}
    @endif

@endsection

@section('contenido')

    @include('puzzle.contenido')

@endsection

@section('scripts')
    {!! NoCaptcha::renderJs('es') !!}
@endsection