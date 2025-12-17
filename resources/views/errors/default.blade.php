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
                            <strong>Se ha producido un error inesperado.</strong>
                        </p>
                        <p>
                            La generación de la página que has solicitado ha generado un error. Por favor, vuelve a cargarla ya que es muy probable que sea una cuestión puntual.
                        </p>
                        <p>
                            Si el error se repite, puedes enviarnos cualquier comentario a tráves del <a href="{{ route('contacto') }}">formulario de contacto</a>, o con los medios de contacto que 
                            encontrarás al pie de la página.
                        </p>
                        <p>
                            Gracias por tu colaboración,<br>
                            El equipo de SEPD.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
