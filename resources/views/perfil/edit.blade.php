@extends('perfil.master')

@php
    if (Storage::disk(config('voyager.storage.disk'))->exists("/perfil/{$usuario->id}/{$usuario->id}.jpg"))
        $imagen_avatar = "/storage/perfil/{$usuario->id}/{$usuario->id}.jpg";
    else 
        $imagen_avatar = "/images/profile.webp";
    if (isset($usuario->tipo_socio)):
        switch($usuario->tipo_socio->internacional):
            case '1':
                $tipo_socio = 'internacional';
            break;

            default: $tipo_socio = 'otro';
        endswitch;
    else:
        $tipo_socio = 'no_socio';
    endif;

    $editar = true;
@endphp

@section('detalle-perfil')

<div class="container">
    @include('perfil.editar_clave_acceso')
    @include('perfil.editar_datos_personales')
    @include('perfil.editar_datos_profesionales')
    @include('perfil.editar_datos_informacion_profesional')
    @include('perfil.editar_datos_residencia')
    @include('perfil.editar_datos_experiencia')
    
    <div class="form-group row float-right">
        <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Guardar cambios</button>
    </div>

</div>

@endsection

@section('scripts')
    <script>
        // iniciar y programar mostrar ocultar provincia/otros
        $(document).ready(function() {
            
            {{-- Controlar coincidencia y longitud passwords --}}
            $('input[name^="password"]').on('keyup',function() {
                if ($('input[name="password"]').val() != $('input[name="password_confirmation"]').val()) {
                    if (! $('input[name="password_confirmation"]').hasClass('is-invalid')) {
                        $('input[name="password_confirmation"]').addClass('is-invalid');
                    }
                } else {
                    $('input[name="password_confirmation"]').removeClass('is-invalid');
                }
                if ($('input[name="password"]').val().length > 0 && $('input[name="password"]').val().length < 8) {
                    if (! $('input[name="password"]').hasClass('is-invalid')) {
                       $('input[name="password"]').addClass('is-invalid');
                    }
                } else {
                   $('input[name="password"]').removeClass('is-invalid');
                }
            });

            // mostrar/ocultar de inicio
            $('#provincia').val() == "000" ? $('#containerProvinciaOtros').collapse('show') : $('#containerProvinciaOtros').collapse('hide');
            
            {{-- si es tipo socio internacional, no hay provincias, se muestra provincia_otros directo --}}
            @if ($tipo_socio == "internacional")

            // mostrar provincia_otros
            $('#containerProvinciaOtros').collapse('show');  

            @else

            // al seleccionar provincia - otros => mostrar el campo provincia_otros
            $('#provincia').on('change', function (e) {
                $('#provincia').val() == "000" ? $('#containerProvinciaOtros').collapse('show') : $('#containerProvinciaOtros').collapse('hide');
            });

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
