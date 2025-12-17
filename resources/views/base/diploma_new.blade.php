<!DOCTYPE html>
<html>

<head>
    <title>Diploma - PDF</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('base.styles')

    <style>
        body {
            min-height: 100%;
        }
        .container {
            min-height: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="table">
            @if ($imagen_fondo)    
                <tr>
                    <td>
                        <img class="imagen_fondo" src="{{ $imagen_fondo }}" />
                    </td>
                </tr>
            @endif
        </table>

        <table class="table w-90 mx-auto">
            <tr>
                <td class="text-center">
                    <img class="img-fluid logo" src="{{ $logo }}" />
                </td>
            </tr>
            <tr>
                <td >
                    <div class="text-center title font-xl pt-1 pb-1">
                        {!! $name !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-center font-xxs pb-1">
                        {!! $encabezado !!}
                    </div>
                </td>
            </tr>
            <tr>        
                <td>
                    <div class="text-center font-base pb-1">
                        {!! $title !!}
                    </div>
                </td>
            </tr>
        </table>

        <div class="text-center w-90 mx-auto">
            <span class="font-xxs">
                {!! str_replace('{page-break}', '<div class="page-break"></div>', $text1) !!}
            </span>
        </div>

        @if (
            $img_acreditacion &&
            basename($img_acreditacion) !== 'logo_acreditador.jpg' &&
            $diploma->logo_acreditadores == 1
        )
            <table class="table">
                <tr>
                    <td class="text-center">{{ $lugarFecha }}</td>
                </tr>
                <tr>
                    <td class="text-center">
                        <img class="table-logo_img" src="{{ $img_acreditacion }}">
                    </td>
                </tr>
            </table>
        @endif
        
        @if ($diploma)
            @if ($diploma->img_foot && ($diploma->logo_acreditadores != 1))
                <table class="table w-90 mx-auto">
                    <tr>
                        <td>
                            <div class="w-full text-xs fecha mt-1">
                                {{ $lugarFecha }}
                            </div>
            
                            <div class="w-full relative">
                                <img width="110%" src="{{ $rutaWeb }}/storage/{{ $diploma->img_foot }}" />
                            </div>
            
                            <div class="w-full text-xs firma">
                                {{ $diploma->firmante }}<br>
                                {{ $diploma->cargo_firmante }}
                            </div>
                        </td>
                    </tr>
                </table>
            @else
                <table class="table w-90 mx-auto">
                    <tr>
                        <td>
                            <div class="firmantes">
                                {{--
                                    @if($diploma->diploma_firmantes)
                                    @foreach ($diploma->diploma_firmantes as $diploma_firmante)
                                        <div class="div-firma">
                                            <div class="w-full">
                                                <img class="img-firma" src="{{ $rutaWeb }}/storage/{{ $diploma_firmante->firmante->imagen }}">
                                            </div>
            
                                            <div class="text-xs">
                                                {{ $diploma_firmante->firmante->nombre }}
                                            </div>
            
                                            <div class="text-xs">
                                                <i>{{ $diploma_firmante->firmante->cargo }}</i>
                                            </div>
                                        </div>
                                    @endforeach
                                --}}
                                @if($diploma->firmante)
                                    <div class="div-firma">
                                        <div class="w-full">
                                            <img class="img-firma" src="{{ $rutaWeb }}/storage/{{ $diploma->firma_firmante }}">
                                        </div>
            
                                        <div class="text-xs pl-2">
                                            {{ $diploma->firmante }}
                                        </div>
            
                                        <div class="text-xs pl-2">
                                            <i>{{ $diploma->cargo_firmante }}</i>
                                        </div>
                                    </div>
                                @endif
                                @foreach ($diploma->firmas_adicionales ?? [] as $diploma_firmante)
                                    @if (isset($diploma_firmante['firmar']) && $diploma_firmante['firmante'] && $diploma_firmante['cargo_firmante'])
                                        <div class="div-firma">
                                            <div class="w-full">
                                                <img class="img-firma" src="{{ $rutaWeb }}/storage/{{ $diploma_firmante['firmar'] }}">
                                            </div>

                                            <div class="text-xs">
                                                {{ $diploma_firmante['firmante'] }}
                                            </div>

                                            <div class="text-xs">
                                                <i>{{ $diploma_firmante['cargo_firmante'] }}</i>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="text-right">
                                <div class="text-xs">
                                    {{ $diploma->lugarFecha }}
                                </div>
        
                                <div class="w-full">
                                    <h4>
                                        @if ($diploma->img_acreditacion && ($diploma->logo_acreditadores != 1))
                                            <img class="img-fluid logo-acreditador" src="{{ $rutaWeb }}/storage/{{ $diploma->img_acreditacion }}" />
                                        @elseif (($diploma->logo_acreditadores == 1) && ($usuario_diploma && $usuario_diploma->curso->acreditadores->count() > 0))
                                            <div>
                                                @foreach ($usuario_diploma->curso->acreditadores as $acreditador)
                                                    <img class="img-fluid logo-acreditador" src="{{ $rutaWeb }}/storage/{{ $acreditador->logo }}" />
                                                @endforeach
                                            </div>
                                        @elseif ($diploma->logo_acreditadores == 1 && basename($img_acreditacion) !== 'logo_acreditador.jpg')
                                            <img class="img-fluid" src="{{ $rutaWeb }}/storage/diplomas/logo_acreditador.jpg" />
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
        @else
            <table class="table">
                <tr>
                    <td>
                        <div class="w-full text-xs">
                            Madrid, {d} de {m} de {a}
                        </div>
            
                        <div class="w-full">
                            <img class="img-fluid" src="{{ $rutaWeb }}/storage/cursos/migrados/diplomas/pie.jpg" />
                        </div>
            
                        <div class="w-full text-xs firma">
                            Dr. Fernando Carballo <br>
                            Presidente de la SEPD
            
                            <div class="comentario">
                                Enseñanza no reglada y sin carácter oficial
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endif

        <table class="table w-90 mx-auto">
            <tr>
                <td>
                    <table class="table">
                        @if (count($patrocinadores))
                            <tr>
                                <td>
                                    Patrocinado por:
                                </td>
                            </tr>
                            <tr>
                                @foreach ($patrocinadores as $patrocinador)
                                    <td>
                                        <img class="table-logo_img" src="{{ $patrocinador }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    </table>
                </td>
                <td>
                    <table class="table">
                        @if (count($organizadores))
                            <tr>
                                <td>
                                    Organizadores por:
                                </td>
                            </tr>
                            <tr>
                                @foreach ($organizadores as $organizador)
                                    <td>
                                        <img class="table-logo_img" src="{{ $organizador }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
        
        <table class="table w-90 mx-auto">
            <tr>
                <td>
                    <table class="table text-center">
                        @if (count($avaladores))
                            <tr>
                                <td class="text-center">
                                    Avaladores por:
                                </td>
                            </tr>
                            <tr>
                                @foreach ($avaladores as $avalador)
                                    <td>
                                        <img class="table-logo_img" src="{{ $avalador }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table> 
        @if ($code)                
            <div class="absolute bottom-0 text-right text-xs w-90 mx-auto" style="right: 0; left: 0;">
                <div class="text-left">
                    <a href="https://sepd.es/formacion/validador-diploma">https://sepd.es/formacion/validador-diploma</a>
                </div>

                Código: {{ $code }}
            </div>
        @endif
    </div>
</body>

</html>