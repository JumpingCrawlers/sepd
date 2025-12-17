@extends('socios.master')

@section('estilos')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<script src="{{'/js/app.js'}}">
@endsection

@section('contenido-detalle')
<div class="container">
    <div class="contenido-titulo-pagina color-institucional">Registro de socio
    @switch($tipo_socio)
        @case("numerario")
        Numerario
        @break
        @case("formacion")
        en Formación
        @break
        @case("internacional")
        Internacional
        @break
        @case("graduado_enfermeria")
        Graduado en enfermería
        @break
    @endswitch
    </div>
    @if(session()->has('solicitudPendiente'))
      <p class="alert alert-warning">Existe una solicitud pendiente de aprobación asociada al correo: {{session()->get('solicitudPendiente')['email']}} y DNI/NIE: {{session()->get('solicitudPendiente')['dni']}}. Por favor, contacte con Secretaría en el correo {{setting('site.email_contacto')}} para obtener más información.</p>
    @elseif(session()->has('usuarioRegistrado'))
    {{-- 3 ways Euro Fuenmayor - Mejorado mensaje que avisa que usuario ya esta registrado con el correo y/o dni en Hazte socio sin estar logeado --}}
      <p class="alert alert-warning">Existe un usuario registrado al correo: {{session()->get('usuarioRegistrado')['email']}} y DNI/NIE: {{session()->get('usuarioRegistrado')['dni']}}.</p>
      <p class="alert alert-warning">Para registrarse como Socio primero debe iniciar sesión en Acceso Usuarios y luego acceder a la página Hazte Socio</p>
    @else
    <form method="POST" action="/hazte_socio/registro" name="form_socio">
        {{ csrf_field() }}
        
            {{-- Según el tipo de socio, algunos campos se muestran, otros no.
                 Algunos controles están dentro de las vistas parciales.        --}}
            @include('socios.datos_personales')

            @include('socios.datos_profesionales')

            {{-- Socios internacionales pueden seleccionar la sociedad a la que están afiliados --}}
            @if ($tipo_socio == "internacional")
                @include('socios.sociedades_internacionales')
            @endif
            
            {{-- Socios en formación gratuitos --}}
            @if ($tipo_socio == "formacion")
            <p class="alert alert-success">Ser socio de SEPD es gratuito para médicos en formación en España.</p>
            @else
                @include('socios.modo_pago')
            @endif
            
            {{-- Socios internacionales y en formación no tienen código promocional --}}
            @if ($tipo_socio == "numerario")
            <div class="form-group row">
                <label for="socio_codigo" class="col-sm-2 col-form-label">Código promocional</label>
                <div class="col-sm-6">
                    <input name="socio_codigo" type="text" value="{{ old('socio_codigo') }}" class="form-control{{ $errors->has('socio_codigo') ? ' is-invalid' : '' }}" placeholder="Código promocional">
                    @if ($errors->has('socio_codigo'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('socio_codigo') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
            <hr class="borde-institucional">
            @endif


            @include('socios.proteccion_datos')

            <div class="form-group">
                * Datos obligatorios.
            </div>

            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" name="aceptacion" id="aceptacion" required="required" {{ old('aceptacion')!='' ? 'checked' : '' }}>
                <label for="aceptacion" class="form-check-label">
                    He leído y acepto la <a href="/privacidad" target="_blank">política de privacidad</a>.
                </label>
            </div>

            <div class="form-group row float-right pr-4">
                <button type="submit" id="Enviar" class="btn"{{ getHtmlEstiloBoton('', '') }}>Enviar</button>
            </div>

    </form>
    @endif
</div>

@endsection

@section('scripts')

    <script>
        // iniciar y programar mostrar ocultar domiciliacion/tarjeta
        // iniciar y programar mostrar ocultar provincia/otros
        $(document).ready(function() {
            // mostrar/ocultar de inicio
            $('#modo_pago_domiciliacion').is(':checked') ? $('#containerDomiciliacion').collapse('show') : $('#containerDomiciliacion').collapse('hide');
            $('#modo_pago_tarjeta').is(':checked') ? $('#containerTarjeta').collapse('show') : $('#containerTarjeta').collapse('hide');
            if ($( 'input[name=tipo_tarjeta]:checked' ).val() == "American Express") {
                $('#containerVisa').hide();
                $('#containerAmex').show();
            } else {
                $('#containerVisa').show();
                $('#containerAmex').hide();
            }
            $('#provincia').val() == "000" ? $('#containerProvinciaOtros').collapse('show') : $('#containerProvinciaOtros').collapse('hide');

            // programar los clicks de modo de pago
            $('#modo_pago_domiciliacion').on('click', function (e) {
                $('#containerDomiciliacion').collapse('show');
                $('#containerTarjeta').collapse('hide');
            });
            $('#modo_pago_tarjeta').on('click', function (e) {
                $('#containerDomiciliacion').collapse('hide');
                $('#containerTarjeta').collapse('show');
            });
            // programar el click de tipo tarjeta
            $('input[name=tipo_tarjeta]').on('click', function (e) {
                if ($(this).val() == "American Express") {
                    $('#containerVisa').hide();
                    $('#errorNumVisa').hide();
                    $('#containerAmex').show();
                } else {
                    $('#containerVisa').show();
                    $('#errorNumVisa').show();
                    $('#containerAmex').hide();
                }
            });

            {{-- si es tipo socio internacional, no hay provincias, se muestra provincia_otros directo --}}
            @if ($tipo_socio == "internacional")

            // mostrar provincia_otros
            $('#containerProvinciaOtros').collapse('show');

            @else

            // al seleccionar provincia - otros => mostrar el campo provincia_otros
            $('#provincia').on('change', function (e) {
                $('#provincia').val() == "000" ? $('#containerProvinciaOtros').collapse('show') : $('#containerProvinciaOtros').collapse('hide');
            });

            // Comprobar si el país seleccionado es España al entrar a la página para ocultar la opción 'Otros' de provincias || 3 Ways - Alexis Bogado
            changeProvinciaField();
            $('#pais').on('change', changeProvinciaField);

            function changeProvinciaField() {
                if (document.getElementById("pais").options[document.getElementById("pais").selectedIndex].text == "España") {
                    document.getElementById("containerProvinciaOtros").style.display = "none";
                    if (document.getElementById("provincia").selectedIndex < 1 || document.getElementById("provincia").selectedIndex == (document.getElementById("provincia").options.length - 1))
                        document.getElementById("provincia").selectedIndex = 0;

                    document.getElementById("provincia").style.display = "block";
                    document.getElementById("provincia").options[document.getElementById("provincia").options.length - 1].style.display = "none";
                }
                else if (document.getElementById("pais").value) {
                    document.getElementById("provincia").selectedIndex = (document.getElementById("provincia").options.length - 1);
                    document.getElementById("provincia").style.display = "none";
                    document.getElementById("containerProvinciaOtros").style.display = "block";
                }
            }
            @endif

            // no hay submit al pulsar Enter para evitar descuidos!
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
