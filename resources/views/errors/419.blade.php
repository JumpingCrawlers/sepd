@extends('puzzle.master')

@section('contenido')
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-10 offset-1">
            <div class="row">
                <div class="col-4 text-right">
                    <img src="{{ Voyager::image(setting('site.imagen404')) }}" border="0" class="img-fluid" alt="La página ha expirado">
                </div>
                <div class="col-8">
                    <div class="callout borde-institucional pl-3 pb-3">
                        <h4 class="color-institucional">Oops!</h4>
                        <p>
                            <strong>La página ha expirado</strong>
                        </p>
                        <p>
                            Por tu seguridad, después de un periodo de inactividad, las páginas que contienen formularios, como por ejemplo el de conexión, caducan.
                            Esto evita vulnerabilidades en nuestro servidor.
                        </p>
                        <p>
                            Perdona las molestias que esto significa, pero solo tienes que recargar la página. 
                        </p>
                        <p>
                            Si quieres, puedes enviarnos cualquier comentario a tráves del <a href="{{ route('contacto') }}">formulario de contacto</a>, o con los medios de contacto que 
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
