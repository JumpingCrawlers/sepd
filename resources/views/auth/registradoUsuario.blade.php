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
                                El proceso de registro ha concluido satisfactoriamente. 
                                Ya puedes acceder con tus datos y, si así lo deseas, cambiar la contraseña desde tu perfil.
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

        <div class="row mt-5 pt-5 mb-5">
            <div class="col-10 offset-1 text-center">
                <span class="d-block h1" style="color: #D1ABFF">¿Te gustaría ser socio de la SEPD?</span>
                <p class="d-block h4" style="color: #040C55">
                    Selecciona el tipo de <span style="color: #FFA43A">socio</span> que quieres ser y disfruta de <span style="color: #FFA43A">ventajas exclusivas</span>
                </p>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}"  id="quiero-ser-socio" class="btn" style="color:#ffffff;background-color:#db812e">
                        Quiero ser socio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#quiero-ser-socio').on('click', function (event) {
            event.preventDefault();
            $('#modalLogin').find('form').attr('action', "{{ route('login') }}" + "?route=" + "{{ route('hazte_socio') }}");
            $('#modalLogin').modal('show');
        });
    </script>
@endsection
