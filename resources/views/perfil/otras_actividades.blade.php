<div class="container">
    <div class="col-12">
        <div class="mb-4 px-0 pb-3">
            <div class="row left-bordered">
                <div class="col-12">
                    @if ($user->cargos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="diplomas">
                                <thead>
                                    <tr id="table-head">
                                        <th>Cargos Institucionales</th>
                                        <th>Fecha</th>
                                        <th>Diploma</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    @foreach ($user->cargos as $usuario_cargo)
                                    <tr>
                                        <td><i class="fa-solid fa-user-doctor"></i> <b>{{ $usuario_cargo->cargo->nombre_cargoi }}</b></td>
                                        <td style="width: 0; white-space: nowrap;">
                                            @if(!empty($usuario_cargo->fecha_inicio))
                                                {{ date("m/Y", strtotime($usuario_cargo->fecha_inicio)) }}
                                            @endif
                                            @if(!empty($usuario_cargo->fecha_fin))
                                                - {{ date("m/Y", strtotime($usuario_cargo->fecha_fin)) }}
                                            @else
                                                - Actualidad
                                            @endif
                                        </td>
                                        <td style="width: 0; white-space: nowrap;">
                                            <a class="btn btn-secondary btn-sm" href="{{ route('download.certificado-socio', ['type' => 'cargo', 'model_id' => $usuario_cargo->id]) }}" target="__blank" download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="card" id="no-content">
                            <div class="card-body">
                                <div class="text-center">
                                    <b>
                                        <p>No se han encontrado ninguna actividad.<br />Si se inscribió en alguna actividad de la SED póngase en contacto con nosotros.</p>
                                        <span style="font-size: 12px;">Tel.: 91 402 13 53</span>
                                    </b>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if($user->socio != null)
                    <div class="col-12 py-3">
                        @if ($user->socio->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="diplomas">
                                    <thead>
                                        <tr id="table-head">
                                            <th>Reconocimientos</th>
                                            <th>Fechas</th>
                                            <th>Diploma</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        @foreach ($user->socio->reconocimientos as $reconocimiento)
                                        <tr>
                                            <td><i class="fa-solid fa-user-doctor"></i> <b>{{ $reconocimiento->tipo_reconocimiento->nombre_reconocimiento }}</b></td>
                                            <td style="width: 0; white-space: nowrap;">
                                                {{ date('m/Y', strtotime($reconocimiento->created_at)) }}
                                            </td>
                                            <td style="width: 0; white-space: nowrap;">
                                                <a class="btn btn-secondary btn-sm" href="{{ route('download.certificado-socio', ['type' => 'reconocimiento', 'model_id' => $reconocimiento->id]) }}" target="__blank" download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card" id="no-content">
                                <div class="card-body">
                                    <div class="text-center">
                                        <b>
                                            <p>No se han encontrado ningun reconocimiento.</p>
                                            <span style="font-size: 12px;">Tel.: 91 402 13 53</span>
                                        </b>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                @if ($certificados_corporativos != null && !empty($certificados_corporativos))
                    <div class="col-12 py-3">
                        @if (count($certificados_corporativos) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="diplomas">
                                    <thead>
                                        <tr id="table-head">
                                            <th>Certificados Colaboradores</th>
                                            <!--th>Fechas</th-->
                                            <th>Diploma</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        @foreach ($certificados_corporativos as $data)
                                        <tr>
                                            <td><i class="fa-solid fa-user-doctor"></i> <b>{{ $data['nombre_certificado'] }}</b></td>
                                            <!--td style="width: 0; white-space: nowrap;">
                                                {{ date('m/Y', strtotime($data['fecha'])) }}
                                            </td-->
                                            <td style="width: 0; white-space: nowrap;">
                                                <a class="btn btn-secondary btn-sm" href="{{ route('certificados.descargar', ['id' => $data['id']]) }}" target="__blank" download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card" id="no-content">
                                <div class="card-body">
                                    <div class="text-center">
                                        <b>
                                            <p>No se han encontrado ningun reconocimiento.</p>
                                            <span style="font-size: 12px;">Tel.: 91 402 13 53</span>
                                        </b>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>