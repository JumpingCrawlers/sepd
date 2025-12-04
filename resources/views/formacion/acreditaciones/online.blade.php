{{-- 3 Ways - Alexis Bogado --}}
@extends('formacion.acreditaciones.mostrar')
@section('acreditaciones')

@if ($usuario_diplomas->count() > 0)
<div class="table-responsive">
    <table class="table table-striped table-hover" id="diplomas">
        <thead>
            <tr id="table-head">
                <th>Nombre del curso</th>
                <th>Fin</th>
                <th>Créditos</th>
                <th>Diploma</th>
            </tr>
        </thead>
        <tbody id="table-body">
            @php ($current = 0) @endphp
            @foreach ($usuario_diplomas as $usuario_diploma)

            @if ($current != $usuario_diploma->curso_id)
            @php ($current = $usuario_diploma->curso_id) @endphp
            @php ($fecha_ultima_actividad = $usuario_diploma->fecha_ultima_actividad()) @endphp
            <tr>
                <td><i class="fas fa-file-contract"></i> <a href="{{ route('curso.hacer', $usuario_diploma->curso->id)}}">{{ $usuario_diploma->curso->titulo }}</a></td>
                <td style="white-space: nowrap;" data-ultima-actividad="{{ $fecha_ultima_actividad }}">{{ $fecha_ultima_actividad }}</td>
                <td><b>{{ (is_null($usuario_diploma->curso->creditos) ? '0.00' : $usuario_diploma->curso->creditos) }}</b></td>
                <td>
                    @if ($usuario_diploma->curso->encuesta_id > 0 && !$usuario_diploma->curso->external_curso_id)
                        @if ($user->encuesta($usuario_diploma->curso)->count() > 0)
                            <a class="btn btn-primary btn-sm open-modal" href="#" role="button" data-id="0" contenido="diploma:{{ $usuario_diploma->id }}"><i class="fas fa-eye"></i> Visualizar</a>
                            <a class="btn btn-secondary btn-sm" href="/diploma/{{ $usuario_diploma->id }}" target="__blank" download="{{ $usuario_diploma->id }}.pdf">
                                <i class="fas fa-download"></i>
                            </a>
                        @else
                            <a class="btn btn-danger btn-sm" href="{{ route('formacion.encuesta', $usuario_diploma->curso->id) }}" role="button"><i class="fas fa-vote-yea"></i> Encuesta</a>
                        @endif
                    @else
                        <a class="btn btn-primary btn-sm open-modal" href="#" role="button" data-id="0" contenido="diploma:{{ $usuario_diploma->id }}"><i class="fas fa-eye"></i> Visualizar</a>
                        <a class="btn btn-secondary btn-sm" href="/diploma/{{ $usuario_diploma->id }}" target="__blank" download="{{ $usuario_diploma->id }}.pdf">
                            <i class="fas fa-download"></i>
                        </a>
                    @endif
                </td>
            </tr>

            @endif

            @endforeach

            @if($certificados)
                                        @foreach ($certificados as $certificado)
                                            
                                                <tr>
                                                    <td><i class="fas fa-file-contract"></i> <a href="{{ route('curso.hacer', $certificado->curso_id)}}">{{ $certificado_curso[$certificado->curso_id]->titulo }}</a></td>

                                                    <td style="white-space: nowrap;" data-external="{{ $certificado->created_at }}">
                                                        {{ $certificado->created_at->format('d-m-Y') }}
                                                        {{-- {{ date("d-m-Y", strtotime($certificado->created_at)) }} --}}
                                                    </td>
                                                    
                                                        
                                                    <td><b>0.00</b></td>

                                                    
                                                    <td>
                     @php
                                                            $filePath = 'diplomas-certificados/' . $certificado->nombre_diploma;
                                                            $publicUrl = asset('storage/' . $filePath);
                                                        @endphp
                    
                        <a class="btn btn-primary btn-sm open-modal" href="" role="button" data-id="0" contenido="{{ $publicUrl }}"><i class="fas fa-eye"></i> Visualizar</a>
                        <a class="btn btn-secondary btn-sm" href="{{ $publicUrl }}" target="__blank" download="{{ $publicUrl }}">
                            <i class="fas fa-download"></i>
                        </a>
                    
                </td>
            </tr>

            

                @endforeach
            @endif
        </tbody>
        <thead class="thead-light">
            <tr>
                <th colspan="1"></th>
                <th>TOTAL</th>
                <th colspan="3" id="credits">{{ number_format($creditos, 2) }}</th>
            </tr>
        </thead>
    </table>

    <div class="pagination justify-content-center">
        <nav aria-label="Page navigation example">
            {{ $usuario_diplomas->links("pagination::bootstrap-4") }}
        </nav>
    </div>
</div>
@else
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