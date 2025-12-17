@php
    $tipo = request('tipo') ?? 'online';
@endphp

<div class="row">
    <div class="col-12">
        <div class="pointer mb-4 px-0 pb-3">
            <div class="row left-bordered">
                <div class="col-12">
                    <div id="radios" class="d-flex justify-content-end mb-4">
                        <div class="custom-control custom-radio mr-2">
                            <input type="radio" id="radio-presencial" data-id="presencial" name="radio-btn" class="custom-control-input" @if($tipo == 'presencial') checked @endif>
                            <label class="custom-control-label" for="radio-presencial">Congreso SEPD</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="radio-online" data-id="online" name="radio-btn" class="custom-control-input" @if($tipo == 'online') checked @endif>
                            <label class="custom-control-label" for="radio-online">Formación</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
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
                                    @if($tipo == 'online')
                                        @php
                                            $current = 0
                                        @endphp
                                        
                                        @foreach ($usuario_diplomas as $usuario_diploma)
                                            @if ($current != $usuario_diploma->curso_id)
                                                @php
                                                    $current = $usuario_diploma->curso_id;
                                                    $fecha_ultima_actividad = $usuario_diploma->fecha_ultima_actividad();
                                                @endphp
                                                <tr>
                                                    <td><i class="fas fa-file-contract"></i> <a href="{{ route('curso.hacer', $usuario_diploma->curso->id)}}">{{ $usuario_diploma->curso->titulo }}</a></td>

                                                    <td style="white-space: nowrap;" data-ultima-actividad="{{ $fecha_ultima_actividad }}">{{ $fecha_ultima_actividad }}</td>

                                                    <td><b>{{ (is_null($usuario_diploma->curso->creditos) ? '0.00' : $usuario_diploma->curso->creditos) }}</b></td>
                                                    <td>
                                                        @if ($usuario_diploma->curso->encuesta_id > 0)
                                                            @if ($user->encuesta($usuario_diploma->curso)->count() > 0)
                                                                <a class="btn btn-secondary btn-sm" href="/diploma/{{ $usuario_diploma->id }}" target="__blank" download>
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @else
                                                                <a class="btn btn-danger btn-sm" href="{{ route('formacion.encuesta', $usuario_diploma->curso->id) }}" role="button"><i class="fas fa-vote-yea"></i> Encuesta</a>
                                                            @endif
                                                        @else
                                                            <a class="btn btn-secondary btn-sm" href="/diploma/{{ $usuario_diploma->id }}" target="__blank" download>
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
                                                        <a class="btn btn-secondary btn-sm" href="{{$publicUrl}}" target="__blank" download>
                                                                <i class="fas fa-download"></i>
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                            
                                        @endforeach
                                        @endif
                                    @else
                                        @php
                                            $creditos_total = 0
                                        @endphp
                                        @foreach ($usuario_diplomas as $usuario_diploma)
                                            @php
                                                $file_pdf = public_path()."/storage/diplomas/presenciales/Diploma_{$usuario_diploma->id_evento} {$usuario_diploma->id}.pdf";
                                                
                                                if(file_exists($file_pdf)) {
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
                                                    <td>
                                                        {{ $evento }}
                                                    </td>
                                                    <td style="white-space: nowrap;">
                                                        @if ($usuario_diploma->acreditacion_evento)
                                                            {{ $usuario_diploma->acreditacion_evento->obtener_fecha_fin_diploma()}}
                                                        @elseif(is_null($usuario_diploma->acreditacion_evento) && $usuario_diploma->evento)
                                                            {{ $usuario_diploma->created_at->format('d-m-Y') }}
                                                        @endif
                                                    </td>
                                                    <td><b>{{ $creditos_row }}</b></td>

                                                    @php
                                                        $archivo_diploma = "/storage/diplomas/presenciales/Diploma_{$usuario_diploma->id_evento} {$usuario_diploma->id}.pdf";
                                                        if(!file_exists($file_pdf))
                                                            $archivo_diploma = url(config('app.url')). '/diploma/P_' . $usuario_diploma->id.'.pdf'
                                                    @endphp
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm" href="{{ $archivo_diploma }}" target="__blank" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        @endforeach
                                        @php
                                            $creditos = $creditos_total;
                                        @endphp
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

                            @if($tipo == 'online')
                                <div class="pagination justify-content-center">
                                    <nav aria-label="Page navigation example">
                                        {{ $usuario_diplomas->links("pagination::bootstrap-4") }}
                                    </nav>
                                </div>
                            @endif
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
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Obtener todos los radio buttons con el nombre 'radio-btn'
            const radios = document.querySelectorAll('input[name="radio-btn"]');
            
            // Agregar un evento de cambio a cada radio button
            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    // Obtener el valor del atributo data-id del radio seleccionado
                    const tipo = this.getAttribute('data-id');
                    // Redirigir a la misma URL con el parámetro 'tipo'
                    window.location.href = `${window.location.pathname}?tipo=${tipo}`;
                });
            });
        });
    </script>
</div>