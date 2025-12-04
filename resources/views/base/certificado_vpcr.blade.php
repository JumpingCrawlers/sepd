{{-- 3 Ways - Alexis Bogado - Adaptado por Alex R. --}}
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
        }
        
        .vistaDiploma{
            padding: 4rem;
            position: relative;
        }
        
        .bloqueTransversales{
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        
        .center {
            text-align: center;
        }
        
        .space {
            margin-bottom: 30px;
        }
        
        .name {
            font-size: 20pt;
        }
        
        .desc {
            font-size: 10pt;
        }
        
        .course, h6 {
            font-size: 12pt;
            text-align: left;
        }
        
        .module {
            font-size: 9pt;
        }
        
        .text {
            margin-bottom: 20px;
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
        .descripcion, .listado_competencias{
            text-align: left;
            font-size: 11pt !important;
        }
        .imagenFirma{
            max-width: 170px;
            max-height: 170px;
        }
        ul{
           padding: 0;
        }
        li{
            margin-left: 40px;            
            font-size: 11pt !important;
        }
    </style>
</head>
<body>
    
    <!-- Si se quiere imagen de fondo para el certificado -->
        {{-- <img class="imagen_fondo" src="storage/{{ $usuario_diploma->curso->diploma->imagen_fondo }}" /> --}}


    <div class="container vistaDiploma">
        <div class="center">
            <div class="col-12 space">
                <img class="img-fluid" src="storage/cursos/migrados/diplomas/logo_SEPD.jpg" />
            </div>

            {{-- <div class="col-12 name">
                {{ $usuario->tratamiento }} {{ $usuario->nombre }} {{ $usuario->apellidos }}
            </div> --}}
            

            <div class="col-12 course">
                La Sociedad Española de Patología Digestiva tras un proceso exhaustivo de valoración y evaluación recertifica al doctor/a <strong>{{ $usuario->nombre }} {{ $usuario->apellidos }}, </strong>con  <b>número de colegiado {{ $usuario->datos_profesionales->num_colegiado }}</b> en las siguientes competencias para el <b>periodo {{ $usuario->fecha_concedido_vpcr()->format('Y')}}-{{ $usuario->fecha_vpcr()->format('Y')}}.</b>
            </div>
            <br>
            <div class="col-12 listado_competencias" >
                <ul>
                    <?php $transversal = 0; ?>
                    @foreach ($competencias as $key => $competencia)
                        @if($key == 0)
                            <h6><b>Competencias Específicas: </b></h6>
                        @elseif($transversal == 0 && $competencia->competencias_tipos_id == 2)
                            <?php $transversal = 1; ?>
                            <h6 class="bloqueTransversales"><b>Competencias Transversales: </b></h6>
                        @endif
                            <li>{{ $competencia->titulo  }} </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <br><br>
        <div class="col-15 text descripcion">
            Y para que conste se expide este certificado el <strong>{{ $usuario->fecha_concedido_vpcr()->format('d-m-Y') }}</strong>
        </div>
        

       
        {{-- <div class="col-12 text ">
            Madrid, {d} de {m} de {a}
        </div> --}}

        <div class="col-12">
            <img class="img-fluid imagenFirma" src="storage/{{ str_replace('\\','/',$firmante->imagen) }}" />
        </div>

        <div class="col-12 text firma">
            {{ $firmante->nombre }} <br>
            {{ $firmante->cargo }}
        </div>

    </div>

</body>
</html>