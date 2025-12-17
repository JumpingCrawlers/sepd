@extends('puzzle.master')

@section('contenido')
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-10 offset-1">
            <div class="row">
                <div class="col-4 text-right">
                    {{-- <img src="{{ Voyager::image(setting('site.imagen404')) }}" border="0" class="img-fluid" alt="Página no encontrada"> --}}
                    <img src="{{ asset('Logos/SEPD_LOGOS_11.png') }}" border="0" class="mw-100" alt="Registro finalizado" height="230">
                </div>
                <div class="col-8">
                    <div class="callout borde-institucional pl-3 pb-3">
                        <h4 class="color-institucional">Registro finalizado</h4>
                        <p>
                            <strong>Ya puedes conectarte y acceder a contenido exclusivo.</strong>
                        </p>
                        <p>
                            El proceso de registro ha concluido satisfactoriamente. Te hemos enviado un correo electrónico a tu dirección con los datos de acceso.
                            Ya puedes acceder con ellos y, si así lo deseas, cambiar la contraseña desde tu perfil.
                        </p>
                        <p>
                            Para cualquier aclaración o enviarnos cualquier comentario, puedes contactar con nosotros a tráves de este <a href="{{ route('contacto') }}">formulario</a>,
                            o con los medios de contacto que encontrarás al pie de la página.
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
