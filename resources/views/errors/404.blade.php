@extends('puzzle.master')

@section('contenido')
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-10 offset-1">
            <div class="row">
                <div class="col-4 text-right">
                    {{-- <img src="{{ Voyager::image(setting('site.imagen404')) }}" border="0" class="img-fluid" alt="Página no encontrada"> --}}
                    <img src="{{ asset('Logos/SEPD_LOGOS_11.png') }}" border="0" class="mw-100" alt="Página no encontrada" height="230">
                </div>
                <div class="col-8">
                    <div class="callout borde-institucional pl-3 pb-3">
                        <h4 class="color-institucional">Oops!</h4>
                        <p>
                            <strong>Página no encontrada</strong>
                        </p>
                        <p>
                            No hemos encontrado el contenido que estás buscando. Es posible que ya no esté disponible, o que el enlace tuviera algún error. 
                            Utiliza por favor el menú para acceder a todas las secciones de nuestra web.
                        </p>
                        <p>
                            Puedes enviarnos cualquier comentario a tráves del <a href="{{ route('contacto') }}">formulario de contacto</a>, o con los medios de contacto que 
                            encontrarás al pie de la página.
                        </p>
                        <p>
                            El equipo de SEPD.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
