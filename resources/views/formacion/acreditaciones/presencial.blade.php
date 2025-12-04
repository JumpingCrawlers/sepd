{{-- 3 Ways - Alexis Bogado --}}
@extends('formacion.acreditaciones.mostrar')

@section('acreditaciones')
    @if($usuario_diplomas->count() > 0)
        {{-- Si tiene acreditaciones --}}
        <div class="table-responsive" >
            <table class="table table-striped table-hover" id="diplomas">
                <thead>
                    <tr id="table-head">
                        <th>Denominación</th>
                        <th>Fecha</th>
                        <th>Créditos</th>
                        <th>Diploma</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @php
                        $creditos_total = 0;
                    @endphp
                        @foreach ($usuario_diplomas as $usuario_diploma)
                            {{-- 3 Ways - Euro Fuenmayor --}}
                            {{-- Ajustado nombre de evento y creditos para los casos:
                            (a) un solo diploma por evento que engloba todas las sesiones(actividades)
                            (b) diploma pre existentes del gestor antiguo
                            y creditos totales --}}
                            @php
                                $file_pdf = public_path()."/storage/diplomas/presenciales/Diploma_{$usuario_diploma->id_evento} {$usuario_diploma->id}.pdf";
                                if(file_exists($file_pdf)){
                                    $evento = $usuario_diploma->evento;
                                    $creditos_row = $usuario_diploma->creditos;
                                } else if (is_null($usuario_diploma->sesion) && is_null($usuario_diploma->acreditacion_evento)) {
                                    $evento =  $usuario_diploma->evento;
                                    $creditos_row = $usuario_diploma->creditos;
                                } else {
                                    $evento = explode('-', $usuario_diploma->created_at)[0] > 2017 ? $usuario_diploma->sesion : 'SED '.explode('-', $usuario_diploma->created_at)[0];
                                    $creditos_row = $user->tabla_de_actividades_y_creditos_por_evento_diploma_presencial($usuario_diploma)[1];
                                }
                                $creditos_total += $creditos_row;
                        @endphp
                        <tr>
                            <td>{{ $evento }}</td>
                            <td style="white-space: nowrap;">
                                @if ($usuario_diploma->acreditacion_evento)
                                    {{ $usuario_diploma->acreditacion_evento->obtener_fecha_fin_diploma()}}
                                @elseif(is_null($usuario_diploma->acreditacion_evento) && $usuario_diploma->evento)
                                    {{ $usuario_diploma->created_at->format('d-m-Y') }}
                                @endif
                            </td>
                            <td><b>{{ $creditos_row }}</b></td>
                            <?php
                            $archivo_diploma = 'diplomas/presenciales/Diploma_'.$usuario_diploma->id_evento.'%20'.$usuario_diploma->id.'.pdf';
                            ?>
                            <td><a class="btn btn-primary btn-sm open-modal" href="#" role="button" data-id="0" tipo="pdf" contenido="{{ $archivo_diploma }}"><i class="fas fa-eye"></i> Visualizar</a></td>
                        </tr>
                    @endforeach
                </tbody>
                <thead class="thead-light">
                    <tr>
                        <th colspan="2"></th>
                        <th>TOTAL</th>
                        <th colspan="3" id="credits">{{ number_format($creditos_total, 2) }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    @else
        {{-- Si no tiene acreditaciones --}}
        <div class="card" id="no-content">
            <div class="card-body">
                <div class="text-center">
                    <b>
                        <p>No se han encontrado acreditaciones.<br />Si asistió a alguna sesión de la SED póngase en contacto con nosotros.</p>
                        <span style="font-size: 12px;">Tel.: 91 402 13 53</span>
                    </b>
                </div>
            </div>
        </div>
    @endif
@endsection