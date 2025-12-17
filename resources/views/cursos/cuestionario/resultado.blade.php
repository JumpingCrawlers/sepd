{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')
@section('styles')
<style>
    table {
        width: 100%;
        margin-top: 2em;
        margin-bottom: 0 !important;
        border-spacing: 10px;
        border-collapse: separate;
        border: 1px solid #cccccc;
        border-radius: 6px;
    }

    td, th {
        border: 0 !important;
        vertical-align: middle !important;
        text-align: center;
    }
    
    .text-left {
        text-align: left;
    }
    
    .text-right {
        text-align: right;
    }

    td {
        margin: 0 !important;
        padding: 0 !important;
    }

    .info-text {
        color: #8a8a8a;
        font-size: 9.5pt;
    }

    .justify-content-center {
        display: grid;
        justify-content: center;
    }

    #send {
        vertical-align: top !important;
        text-align: center;
    }
</style>
@endsection

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">{{ $cuestionario->bloque->curso->titulo }}</h2>
            </div>

            <div data-id-curso="252" class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-12">
                        <a href="/formacion/cursos/{{ $cuestionario->bloque->curso->id }}">
                            <button class="btn btn-primary">
                                <i class="fas fa-angle-double-left"></i>
                                Volver a la ficha de curso
                            </button>
                        </a>
                    </div>
                    
                    <div class="col-12 justify-content-center table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="table-active" colspan="3">
                                        {{ strtoupper($usuario_cuestionario->estado) }}
                                        @if (($usuario_cuestionario->estado == "suspenso" && ((($cuestionario->oportunidades - $oportunidades) > 0) || $cuestionario->oportunidades == 0)) && !Auth::user()->tiempo_finalizado($cuestionario->bloque->curso))
                                            <br>
                                            @if ($cuestionario->oportunidades == 0) Las oportunidades son ilimitadas. @else Tienes @if (($cuestionario->oportunidades - $oportunidades) > 1) {{ ($cuestionario->oportunidades - $oportunidades) }} oportunidades más. @else una última oportunidad. @endif @endif
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left">Número de respuestas acertadas</td>
                                    <td width="50px"></td>
                                    <td>{{ $respuestas["correctas"] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left">Número de respuestas falladas</td>
                                    <td width="50px"></td>
                                    <td>{{ count($respuestas["incorrectas"]) }}</td>
                                </tr>
                                @if (count($respuestas["incorrectas"]) > 0)
                                <tr>
                                    <td class="text-left">Respuestas con fallo</td>
                                    <td width="50px"></td>
                                    <td>
                                        @foreach($respuestas["incorrectas"] as $respuesta_incorrecta)
                                            {{ $respuesta_incorrecta }}{{ ((count($respuestas["incorrectas"]) == $loop->iteration) ? '' : ', ') }}
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-left">Respuestas sin contestar</td>
                                    <td width="50px"></td>
                                    <td>{{ ($cuestionario->preguntas->count() - (intval($respuestas["correctas"]) + intval(count($respuestas["incorrectas"])))) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left">Total de respuestas</td>
                                    <td width="50px"></td>
                                    <td>{{ (intval($respuestas["correctas"]) + intval(count($respuestas["incorrectas"]))) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-left">Porcentaje de aciertos</td>
                                    <td width="50px"></td>
                                    <td>{{ round((($respuestas["correctas"] * 100) / ($cuestionario->preguntas->count() <= 0 ? 1 : $cuestionario->preguntas->count()))) }} %</td>
                                </tr>
                                <tr>
                                    <td class="text-left">Porcentaje exigido para aprobar</td>
                                    <td width="50px"></td>
                                    <td>{{ $cuestionario->porcentaje }} %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if (!Auth::user()->tiempo_finalizado($cuestionario->bloque->curso))
                        @if (($cuestionario->oportunidades - $oportunidades) > 0 || $cuestionario->oportunidades == 0 && $usuario_cuestionario->estado == "suspenso")
                            <div class="col-12 mt-4" id="send">
                                <a href="/formacion/cuestionario/{{ $cuestionario->id }}">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-angle-double-left"></i>
                                        Realizar test de nuevo
                                    </button>
                                </a>
                            </div>
                        @elseif (isset($cuestionario_recuperacion_id) && $cuestionario_recuperacion_id)
                            <div class="col-12 mt-4" id="send">
                                <a href="/formacion/cuestionario/{{ $cuestionario_recuperacion_id }}">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-angle-double-left"></i>
                                        Recuperación Evaluación Final
                                    </button>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection