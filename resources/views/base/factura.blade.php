<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Some Random Title</title>
    <style>
        body{
            font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace !important;
            letter-spacing: -0.3px;
        }
        .invoice-wrapper{ width: 100%; margin: auto; }
        .nav-sidebar .nav-header:not(:first-of-type){ padding: 1.7rem 0rem .5rem; }
        .logo{ font-size: 50px; }
        .sidebar-collapse .brand-link .brand-image{ margin-top: -33px; }
        .content-wrapper{ margin: auto !important; }
        .billing-company-image { width: 50px; }
        .billing_name { text-transform: uppercase; }
        .billing_address { text-transform: capitalize; }
        .table{ width: 100%; border-collapse: collapse; }
        th{ text-align: left; padding: 10px; }
        td{ padding: 10px; vertical-align: top; }
        .row{ display: block; clear: both; }
        .text-right{ text-align: right; }
        .table-hover thead tr{ background: #eee; }
        .table-hover tbody tr:nth-child(even){ background: #fbf9f9; }
        address{ font-style: normal; }
    </style>
</head>
<body>
    <div class="row invoice-wrapper">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <td>
                                @php
                                    $image = base64_encode(file_get_contents(public_path('/Logos/FEAD_logotipo.jpg')));
                                @endphp
                                <img src="data:image/jpg;base64,{{ $image }}" width="300px"/>
                            </td>
                            <td class="text-right">
                                <strong>Fecha: {{ $factura->created_at->format('d/m/Y') }}</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br><br>
            <div class="row invoice-info">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <td>
                                <div class="">
                                    <address>
                                        CIF: G81469637 <br>
                                        C/ Sancho Dávila, 6 <br>
                                        28028 Madrid <br>
                                        T: (34) 91 402 13 53 <br>
                                        fundacion@saludigestivo.es <br>
                                        www.saludigestivo.es <br>
                                    </address>
                                </div>
                            </td>
                            <td>
                                <div class="text-right">
                                    <b>Factura: {{ $code }} Cliente</b><br>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>DESCRIPCIÓN</th>
                                <th>UDS</th>
                                <th>PRECIO UNITARIO</th>
                                <th style="text-align: right">TOTAL IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $factura->concepto ?? 'N/D' !!}</td>
                                <td>1</td>
                                <td>{{ $factura->precio }} €</td>
                                <td class="text-right">{{ $factura->precio }} €</td>
                            </tr>
                            <tr style="border-top: 1px solid #333">
                                <td colspan="3" class="text-right">Base imponible</td>
                                <td class="text-right"><strong>{{ $factura->precio }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">IVA (0%)</td>
                                <td class="text-right"><strong>0 €</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">TOTAL FACTURA</td>
                                <td class="text-right"><strong>{{ $factura->precio }} €</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <br><br><br>
            <div>
                <small><small>
                    Según la L.O. de Protección de datos de Cáracter Personal, le indicamos que la información que Vd. nos
                    facilita se va a incorporar a ficheros automatizados cuyas finalidades son la gestión contable y fiscal,
                    la facturación de servicios, la realización de presupuestos, la gestión comercial de clientes por parte
                    de la FEAD la cual es titular de dichos ficheros. Se informa que dicha información puede ser cedida a
                    encargados del tratamiento. Puede ejercer el derecho de acceso, rectificación, cancelación y oposición
                    al tratamiento de sus datos mediante escrito (adjuntando fotocopia del DNI) dirigido a la FEAD - c/
                    Sancho Dávila, 6 – 28028 Madrid o a la siguiente dirección de correo electrónico
                    fundacion@saludigestivo.es , indicando en la línea de "Asunto" el derecho que desea ejercitar.
                </small></small>
            </div>
        </div>
    </div>    
</body>
</html>