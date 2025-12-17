{{-- 3 Ways - Alexis Bogado --}}
<!DOCTYPE html>
<html>
<head>
    <title>Diploma - PDF</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css">
    <style type="text/css" media="all">
        @page { margin:0 }

        body{
            background-repeat: no-repeat; 
            background-position: center;
            font-family: 'Helvetica';   
            font-size: 9pt;
            /* position: fixed; */
        }
        
        .vistaDiploma{
            padding: 3rem 4rem;
            position: relative;
        }
        
        .bloqueFechaFirmantes{
            margin-top: 1rem;
            margin-left: 0;
            margin-bottom: 1rem;
        }
        
        .bloqueFechaFirmantes img{
            padding-top: 2rem;
            margin-left: 0;
        }
        
        .center {
            text-align: center;
        }
        
        .space {
            margin-bottom: 20px;
        }
        
        .space-10 {
            margin-bottom: 10px;
        }
        .space.module{
            margin-bottom: 10px;
        }
        
        .name {
            font-size: 18pt;
        }
        
        .desc {
            font-size: 10pt;
        }
        
        .course {
            font-size: 12pt;
        }
        
        .module {
            font-size: 9pt;
        }
        
        .text {
            margin-bottom: 20px;
            width: 90%;
        }
        
        .left {
            float: left;
        }

        .right {
            float: right;
        }

        .text-align-right {
            text-align: right;
        }
        
        .p {
            margin-left: 10px;
        }
        
        .hr_diploma{
            border-top: 3px solid #c3c2c2;
        }

        .fecha {
            margin-left: 360px;
        }

        .firma {
            margin-left: 70px;
        }

        .comentario {
            margin-left: 290px;
            margin-top: -20px;
        }

        .bottom {
            color: rgb(82, 86, 89);
            font-size: 8pt;
            float: right;
            position: absolute;
            bottom: 0;
            padding: 10px;
        }

        .relative {
            position: relative;
        }
        
        .container, .imagen_fondo{
            position: absolute;
            width: 100%;
        }
        .logo{
            max-width: 200px;
            max-height: 80px;
        }
        .img-patrocinado{
            display: inline-block;
            padding: 0 5px;
            max-width: 200px;
            max-height: 80px;
        }
        .img-firma {
            margin-top: -30px !important;
            max-width: 170px !important;
            height: auto;
        }
        .logo-acreditador {
            max-width: 200px;
        }
    </style>
</head>

<body>
    
    @if ($usuario_diploma->curso->diploma_id && $usuario_diploma->curso->diploma->imagen_fondo)
        <img class="imagen_fondo" src="{{ $rutaWeb }}/storage/{{ $usuario_diploma->curso->diploma->imagen_fondo }}" />
    @endif

    <div class="container vistaDiploma">
        <div class="center">
            <div class="col-12 space-10">
                <?php $imgVDiploma = ($usuario_diploma->curso->diploma_id ? ($usuario_diploma->curso->diploma->logo ? $usuario_diploma->curso->diploma->logo : 'cursos/migrados/diplomas/logo_SEPD.jpg') : 'cursos/migrados/diplomas/logo_SEPD.jpg'); ?>
                <img class="img-fluid logo" src="{{ $rutaWeb }}/storage/{{ $imgVDiploma }}" />
            </div>

            <div class="col-12 name">
                {{ $usuario_diploma->usuario->tratamiento }} {{ $usuario_diploma->usuario->nombre }} {{ $usuario_diploma->usuario->apellidos }}
            </div>
            
            <div class="col-12 space">
                @if ($usuario_diploma->curso->diploma_id)
                    @if ($usuario_diploma->curso->diploma->encabezado)
                        {{ $usuario_diploma->curso->diploma->encabezado }}
                    @endif
                @else
                    @if ($usuario_diploma->bloque_id)
                        Ha cursado desde el {inicio} hasta el {fin}, las Actividades del Programa de Formación Continuada:
                    @else
                        {{-- Ha superado con éxito, a fecha {fin}, las Actividades del Programa de Formación Continuada: --}}
                        Ha cursado desde el {inicio} hasta el {fin}, las Actividades del Programa de Formación Continuada:
                    @endif
                @endif
            </div>

            <div class="col-12 course">
                @if ($usuario_diploma->curso->diploma_id)
                    @if ($usuario_diploma->curso->diploma->nombre_curso)
                        {{ $usuario_diploma->curso->diploma->nombre_curso }}
                    @else
                        {{ $usuario_diploma->curso->titulo }}
                    @endif
                @else
                    {{ $usuario_diploma->curso->titulo }}
                @endif
            </div>

            <div class="col-12 module space">
                <br>
                <!-- {modulo}&nbsp; -->
            </div>
        </div>

        <div class="col-12 text">
            @if ($usuario_diploma->curso->diploma_id)
                {!! str_replace("\n", "<br />", $usuario_diploma->curso->diploma->cuerpo) !!}
            @else
                Esta actividad docente{{ $usuario_diploma->curso->creditos ? '{txt_expediente}' : '' }} está acreditada por la Comisión de Formación Continuada de las Profesiones Sanitarias de la Comunidad de Madrid-Sistema Nacional de Salud{{ $usuario_diploma->curso->creditos ? '{txt_creditos}' : '' }}. Los créditos de esta actividad formativa no son aplicables a los profesionales, que participen en la misma, y que estén formándose como especialistas en Ciencias de la Salud, es decir, los internos residentes de las profesiones citadas.
            @endif
        </div>


        
        
        @if ($usuario_diploma->curso->diploma_id)
            @if ($usuario_diploma->curso->diploma->img_foot && ($usuario_diploma->curso->diploma->logo_acreditadores != 1))
                <div class="col-12 text fecha" style="margin-top:-25px">
                    {{ $usuario_diploma->curso->diploma->lugarFecha }}
                </div>

                <div class="col-12 relative" style="overflow:hidden;margin-top:-15px">
                    <img width="110%" src="{{ $rutaWeb }}/storage/{{ $usuario_diploma->curso->diploma->img_foot }}" />
                </div>

                <div class="col-12 text firma">
                    {{ $usuario_diploma->curso->diploma->firmante }}<br>
                    {{ $usuario_diploma->curso->diploma->cargo_firmante }}
                </div>
            @else
                <div class="bloqueFechaFirmantes">
                    <div class="right">
                        <div class="col-12">
                            {{ $usuario_diploma->curso->diploma->lugarFecha }}
                        </div>

                        <div class="col-12">
                            <h4>
                                @if ($usuario_diploma->curso->diploma->img_acreditacion && ($usuario_diploma->curso->diploma->logo_acreditadores != 1))
                                    <img class="img-fluid logo-acreditador" src="{{ $rutaWeb }}/storage/{{ $usuario_diploma->curso->diploma->img_acreditacion }}" />
                                @elseif (($usuario_diploma->curso->diploma->logo_acreditadores == 1) && ($usuario_diploma->curso->acreditadores->count() > 0))
                                    <div>
                                        @foreach ($usuario_diploma->curso->acreditadores as $acreditador)
                                            <img class="img-fluid logo-acreditador" src="{{ $rutaWeb }}/storage/{{ $acreditador->logo }}" />
                                        @endforeach
                                    </div>
                                @elseif ($usuario_diploma->curso->diploma->logo_acreditadores == 1)
                                    <img class="img-fluid" src="{{ $rutaWeb }}/storage/diplomas/logo_acreditador.jpg" />
                                @endif
                            </h4>
                        </div>
                    </div>

                    <div class="firmantes">
                        @if($usuario_diploma->curso->diploma->diploma_firmantes)
                            @foreach ($usuario_diploma->curso->diploma->diploma_firmantes as $diploma_firmante)
                                <div class="col-12">
                                    <img class="img-firma" src="{{ $rutaWeb }}/storage/{{ $diploma_firmante->firmante->imagen }}">
                                </div>

                                <div class="col-12 desc">
                                    {{ $diploma_firmante->firmante->nombre }}
                                </div>

                                <div class="col-12 ">
                                    <i>{{ $diploma_firmante->firmante->cargo }}</i>
                                </div>
                            @endforeach
                        @elseif($usuario_diploma->curso->diploma->firmante)
                            <div class="col-12">
                                <img class="img-firma" src="{{ $rutaWeb }}/storage/{{ $usuario_diploma->curso->diploma->firma_firmante }}">
                            </div>

                            <div class="col-12 desc">
                                {{ $usuario_diploma->curso->diploma->firmante }}
                            </div>

                            <div class="col-12 ">
                                <i>{{ $usuario_diploma->curso->diploma->cargo_firmante }}</i>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="col-12 text fecha">
                Madrid, {d} de {m} de {a}
            </div>

            <div class="col-12">
                <img class="img-fluid" src="{{ $rutaWeb }}/storage/cursos/migrados/diplomas/pie.jpg" />
            </div>

            <div class="col-12 text firma">
                Dr. Fernando Carballo <br>
                Presidente de la SEPD

                <div class="comentario">
                    Enseñanza no reglada y sin carácter oficial
                </div>
            </div>
        @endif

        <div class="center">
            @if ($usuario_diploma->curso->diploma_id)
                @if ($usuario_diploma->curso->diploma->logo_patrocinadores)
                    <div class="col-12">
                        Patrocinado por:
                    </div>
                    <br />
                    <div class="col-12">
                        @if ($usuario_diploma->curso->diploma->img_anunciante)
                            <h4>
                                <img class="img-fluid" src="{{ $rutaWeb }}/storage/{{ $usuario_diploma->curso->diploma->img_anunciante }}" />
                            </h4>
                        @elseif ($usuario_diploma->curso->diploma->logo_patrocinadores == 1)
                            @if ($usuario_diploma->curso->patrocinadores->count() > 0)
                                <div>
                                    @foreach ($usuario_diploma->curso->patrocinadores as $patrocinador)
                                        <img class="img-patrocinado img-fluid" src="{{ $rutaWeb }}/storage/{{ $patrocinador->logo }}" />
                                    @endforeach
                                </div>
                            @endif
                        @elseif ($usuario_diploma->curso->diploma->logo_patrocinadores !== 1 && $usuario_diploma->curso->diploma->img_patrocinadores)
                            @php
                                $img_patrocinadores = json_decode($usuario_diploma->curso->diploma->img_patrocinadores);
                            @endphp
                            @if (is_array($img_patrocinadores) && count($img_patrocinadores) > 0)
                                @foreach ($img_patrocinadores as $img_patrocinador)
                                    <div>
                                        <img class="img-patrocinado img-fluid" src="{{ $rutaWeb }}/storage/{{ $img_patrocinador }}" />
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div> 
                @endif
            @endif
        </div>

        <div class="center">
            @if ($usuario_diploma->curso->diploma_id)
                @if($usuario_diploma->curso->diploma->logo_organizadores && $usuario_diploma->curso->diploma->logo_organizadores == 1)
                    @if ($usuario_diploma->curso->organizadores->count() > 0)
                        <div class="col-12">
                            Organizadores:
                        </div>
                        <br />
                        <div class="col-12">
                            <h4>
                                @foreach ($usuario_diploma->curso->organizadores as $organizador)
                                    <img class="img-fluid" src="{{ $rutaWeb }}/storage/{{ $organizador->logo }}" /><br />
                                @endforeach
                            </h4>
                        </div>
                    @endif    
                @elseif ($usuario_diploma->curso->diploma->logo_organizadores !== 1 && $usuario_diploma->curso->diploma->img_organizadores)
                    @php
                        $img_organizadores = json_decode($usuario_diploma->curso->diploma->img_organizadores);
                    @endphp
                    @if (is_array($img_organizadores) && count($img_organizadores) > 0)
                        <div class="col-12">
                            Organizadores:
                        </div>
                        <br />
                        <div class="col-12">
                            <h4>
                                @foreach ($img_organizadores as $img_organizador)
                                    <img class="img-fluid" src="{{ $rutaWeb }}/storage/{{ $img_organizador }}" /><br />
                                @endforeach
                            </h4>
                        </div>
                    @endif  
                @endif
            @endif
        </div>

        <div class="center">
            @if ($usuario_diploma->curso->diploma_id)
                @if($usuario_diploma->curso->diploma->logo_avaladores && $usuario_diploma->curso->diploma->logo_avaladores == 1)
                    @if ($usuario_diploma->curso->avaladores->count() > 0)
                        <div class="col-12">
                            Avaladores:
                        </div>
                        <br />
                        <div class="col-12">
                            <h4>
                                @foreach ($usuario_diploma->curso->avaladores as $avalador)
                                    <img class="img-fluid" src="{{ $rutaWeb }}/storage/{{ $avalador->logo }}" /><br />
                                @endforeach
                            </h4>
                        </div>
                    @endif                          
                @elseif ($usuario_diploma->curso->diploma->logo_avaladores !== 1 && $usuario_diploma->curso->diploma->img_avaladores)
                    @php
                        $img_avaladores = json_decode($usuario_diploma->curso->diploma->img_avaladores);
                    @endphp
                    @if (is_array($img_avaladores) && count($img_avaladores) > 0)
                        <div class="col-12">
                            Avaladores:
                        </div>
                        <br />
                        <div class="col-12">
                            <h4>
                                @foreach ($img_avaladores as $img_avalador)
                                    <img class="img-fluid" src="{{ $rutaWeb }}/storage/{{ $img_avalador }}" /><br />
                                @endforeach
                            </h4>
                        </div>
                    @endif  
                @endif
            @endif
        </div>
    </div>

    <div class="bottom text-right">
      <div class="text-left">
        <a href="https://sepd.es/formacion/validador-diploma">https://sepd.es/formacion/validador-diploma</a>
      </div>
        Código: {{ $usuario_diploma->id }}
    </div>
</body>
</html>