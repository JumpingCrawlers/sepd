<!DOCTYPE html>
<html>

<head>
    <title>Diploma - {header}</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('base.styles')
</head>

@php($url_back = config('app.url_back'))

<body>
    <div class="container">
        <table class="table">
            <tr>
                <td class="text-center">
                    <img id="logo_header" src="{{ $url_back. '/storage/acreditaciones/logos/SED-header.jpg'}}" border="0" />
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-center w-90 mx-auto">
                        <h1 class="name">{nombre}</h1>
                        <p class="title mt-2 font-base">{header}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-center w-90 mx-auto">
                        <p class="text-base">
                            Formación impartida en la modalidad presencial.
                        </p>
                        <p class="text-base">
                            Programa Formativo de {horas_formativar} horas formativas, acreditado por {entidad} con número de expediente {num_expediente}, con {creditos} créditos.
                        </p>
                        <p class="font-italic font-bold text-sm mt-4">
                            Los créditos de esta actividad formativa no son aplicables a los profesionales que estén formándose como especialistas en
                            Ciencias de la Salud (residentes) por incluir dicho periodo de formación reglada oficial una relación laboral especial de
                            dedicación completa incompatible con cualquier otra actividad formativa (con excepción de los estudios de doctorado). Ley
                            44/2003, de 21 de noviembre, de Ordenación de las Profesiones Sanitarias (arts. 20.3.a. y 33.1)
                        </p>
                    </div>
                </td>
            </tr>
            {{-- 
                <td>
                    <td>
                        <div class="container-center">
                            <table class="table text-center">
                                <tr>
                                    <th width="70%">Título</th>
                                    <th width="10%">Créditos</th>
                                    <th width="20%">Nº Expediente</th>
                                </tr>
                                
                                {cells}
                            </table>
                        </div>
                        <p class="texto_acreditacion"> {datos_acreditacion}. </p>
                        <p class="texto_opcional"> {texto_opcional}. </p>
                        <h6 class="texto_advertencia">{texto_advertencia}</h6>
                    </td>
                </td>
            --}}
        </table>
        <table class="table table-logos">
            <tr>
                <td style="width: 200px">
                    <div>
                        <img class="firma" {image_firma} border="0" style="max-height: 75px"/>
                        <p class="text-xs">
                            {firma_identificador}<br />
                            {firma_cargo}<br />
                            {lugar} {fecha}
                        </p>
                    </div>
                </td>
                <td></td>
                <td>
                    <table class="table ">
                        <tr>
                            <td {logo_class}>
                                <img {image_logo} border="0"/>
                            </td>
                            <td {uems_class}>
                                <img src="{{ $url_back.'/storage/acreditaciones/logos/logo0a.png'}}" border="0"/>
                            </td>
                            <td {seaformec_class}>
                                <img src="{{ $url_back.'/storage/acreditaciones/logos/logo0b.png'}}" border="0"/>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>