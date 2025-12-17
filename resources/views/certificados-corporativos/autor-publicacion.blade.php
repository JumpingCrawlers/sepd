<!DOCTYPE html>
<html>

<head>
    <title>Diploma - #</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style type="text/css">
        @font-face {
            font-family: 'Archivo';
            src: url("{{ asset('fonts/static/Archivo-Regular.ttf') }}") format("truetype");
            font-weight: normal; 
            font-style: normal; 
        }
        body, html {
            margin: 0;
            padding: 0; 
            box-sizing: border-box;
            font-size: 12px;
            font-family: 'Archivo', sans-serif;
            line-height: 20px;
        }
        img {
            max-width: 100%;
        }
        .container {
            box-sizing: border-box;
            width: 720px;
            height: 88%;
            margin: auto auto auto auto;
            position: relative;
            font-family: 'Archivo', sans-serif;
        }
        body {
            margin: 10% auto 5% auto;
        }
        .table {
            width: 100%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right
        }    
        .max-w-100 {
            max-width: 100%;
        }
        .w-90 {
            width: 90%;
        }
        .w-80 {
            width: 80%;
        }
        .w-full {
            width: 100%
        }
        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }
        .title {
            font-size: 18px;
            text-align: center;
            line-height: 20px;
        }
        .name {
            margin-top: 40px;
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 25px;
        }
        .font-italic {
            font-style: italic;
        }
        .font-bold {
            font-weight: 800;
        }
        .font-xl {
            font-size: 25px;
        }
        .font-lg {
            font-size: 20px;
        }
        .text-base {
            font-size: 18px;
        }
        .text-sm {
            font-size: 14px;
        }
        .text-xs {
            font-size: 12px;
        }
        .font-xxs {
            font-size: 12px;
        }
        .mb-0 {
            margin-bottom: 0px;
        }
        .mt-0 {
            margin-top: 0px;
        }
        .mt-1 {
            margin-top: 1rem;
        }
        .mt-2 {
            margin-top: 2rem;
        }
        .mt-3 {
            margin-top: 3rem;
        }
        .mt-4 {
            margin-top: 4rem;
        }
        .mt-5 {
            margin-top: 5rem;
        }
        .pt-1 {
            padding-top: 1rem;
        }
        .pt-2 {
            padding-top: 2rem;
        }
        .pt-3 {
            padding-top: 3rem;
        }
        .pt-4 {
            padding-top: 4rem;
        }
        .pt-5 {
            padding-top: 5rem;
        }
        .pb-1 {
            padding-bottom: 1rem;
        }
        .pb-2 {
            padding-bottom: 2rem;
        }
        .pb-3 {
            padding-bottom: 3rem;
        }
        .pb-4 {
            padding-bottom: 4rem;
        }
        .pb-5 {
            padding-bottom: 5rem;
        }
        .d-none {
            display: none;
        }
    
        .absolute {
            position: absolute
        }
        .bottom-0 {
            bottom: 0
        }
        .table-logos {
            width: 90%;
            position: absolute;
            bottom: 0;
            left: 0;
            margin: auto;
            height: 200px;
        }
        .table-logos img {
            max-width: 100%;
            max-height: 150px;
        }
        .logo_solo {
            text-align: right
        }
    
        .logo-firmante {
            max-height: 100px;
            max-width: 200px;
        }
        .logo {
            max-height: 80px;
            max-width: 200px;
        }
        .table-logo_img {
            max-width: 100%;
            max-height: 150px;
        }
    </style>

    <style>
        .direccion {
            border-right: 2px solid #5503B1;
            padding-right: 10px;
            vertical-align: top;
            color: #5503B1;
            width: 150px;
            font-size: 12px;
            position: relative;
            opacity: 0.5;
        }
        .direccion-cif {
            position: absolute;
            left: -55px;
            transform: rotate(-90deg);
            top: 170px;

        }
        .content {
            padding-left: 50px;
            height: 750px;
            vertical-align: top;
            font-family: 'Archivo', sans-serif;
        }
        .content-logo {
            padding-bottom: 50px;
            opacity: 0.5;
        }
        .no-break{
            text-align: justify;
          white-space: normal;
          overflow-wrap: break-word;
          word-break: keep-all;

        }
        .resaltado{
            font-weight: bold;
        }
        .firma img{
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <table class="table">
            <tr>
                <td colspan="2">
                    <div class="content-logo">
                        <img src="{{ asset('Logos/sepd.png') }}" width="300px">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="direccion">
                    <div>
                        Sancho Dávila, 6 <br>
                        28028 Madrid <br>
                        Tel.: 914 021 353 <br>
                        www.sepd.es <br>
                        sepd@sepd.es <br>
                    </div>
                    <div class="direccion-cif">
                        CIF: G-28486280
                    </div>
                </td>
                <td class="content">
                    <div class="certificado">
                        <!--div class="titulo text-center">
                            <strong>CERTIFICADO DE AUTOR</strong>
                        </div-->

                        <div class="contenido">
                            <p>
                                D./Dª. <span class="resaltado">{{ preg_replace('/\bDr(?:a|es|s)?\.?\s*/i', '', $nombre_firmante) }}</span> en calidad de 
                                <span class="resaltado">{{ $puesto_firmante }}</span>, con domicilio social en Madrid, calle Sancho Dávila 6<br><br>
                                <strong>CERTIFICA QUE:</strong>
                            </p>

                            <p style="text-align:center; font-size:20px; margin: 30px 0;">
                                <strong>{{ $nombre_usuario }}</strong>
                                
                            </p>

                            <p class="no-break">
                                Con NIF <span class="resaltado">{{ $dni }}</span> es autor de la publicación titulada 
                                “<span class="resaltado">{{ $titulo }}</span>”, la cual ha sido registrada con el ISBN 
                                <span class="resaltado">{{ $isbn }}</span>.
                            </p>

                            <div class="lugar-fecha">
                                Y para que así conste, a los efectos oportunos expido este certificado en<br><br>
                                Madrid a {{ $fecha }}.
                            </div>

                            <div class="firma pt-4">
                                @if(!empty($firma))
                                    <img src="https://admin.sepd.es/storage/{{ $firma }}" alt="Firma del firmante">
                                @endif
                                <div class="nombre-firmante">Fdo: {{ $nombre_firmante }}</div>
                                <div class="puesto-firmante">{{ $puesto_firmante }}</div>
                            </div>
                        </div>
                    </div>
                    </td>
            </tr>
        </table>
        @if($usuario_diploma)
        <div class="bottom text-right">
          <div class="text-left">
            <a href="https://sepd.es/formacion/validador-diploma">https://sepd.es/formacion/validador-diploma</a>
          </div>
            Código: {{ $usuario_diploma }}
        </div>
        @endif
    </div>
    
</body>

</html>

