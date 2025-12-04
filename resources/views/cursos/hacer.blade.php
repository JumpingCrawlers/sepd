{{-- 3 Ways - Carlos Colmenarez y Alexis Bogado --}}
@extends('base.sepd')


@php
    $url_curso_inscripcion = route('curso.inscripcion', $curso->id);

    $external_url = $curso->external_url;

    $url_target = '';

    if ($external_url) {
        $url_target = '_blank';
        if ($usuario_curso = auth()->user()) {
            $url_curso_inscripcion = $external_url . $usuario_curso->encrypt_manual_gestivos;
        }
    }
@endphp

@section('content')
    <style>
        #descripcion_curso_content  * {
            font-family: Archivo-Light !important;
        }
    </style>
    <div id="main-modal" data-id="">
        <div id="close-modal"><i class="fas fa-angle-double-left"></i> Volver</div>
        <div id="modal-content"></div>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">{{$curso->titulo}}</h2>
            </div>

            <div data-id-curso="252" class="mb-4 px-0 pb-3" id="contenido-curso">
                <div class="row left-bordered">
                    <div class="col-sm-12">
                        
                        @if(session('payment-success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('payment-success') }}
                            </div>
                        @endif

                        <div class="card" id="blue" card-id="main">
                            <div class="card-body accordion closed" data-id="card-0" set-cookie="main_collapsed">
                                <h5 class="title collapsed pointer " id="blue-title">
                                    <i class="fas fa-plus-circle right"></i>
                                    <span id="text">Descripción</span>
                                </h5>
                                <div class="card-content collapsed" id="card-0">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="align-items-start">
                                                @if ($curso->imagen)
                                                    <img src="{{url(config('app.url_back'))}}storage\{{$curso->imagen}}" class="img-fluid flex-auto" width="100%">
                                                @else
                                                    <img src="{{url(config('app.url_back'))}}\storage\curso-sin-imagen.png" class="img-fluid flex-auto" width="100%">
                                                @endif

                                                @if ($curso->documentacion)
                                                    @php
                                                        $jsonDocumentacion = json_decode($curso->documentacion);
                                                        $file = is_array($jsonDocumentacion) && isset($jsonDocumentacion[0]) ? $jsonDocumentacion[0]->download_link : $curso->documentacion;
                                                    @endphp
                                                    @if ($file != '' && $file != '[]')
                                                        <a href="{{ url(config('app.url_back')) }}storage/{{ is_array($jsonDocumentacion) && count($jsonDocumentacion) ? $jsonDocumentacion[0]->download_link : $curso->documentacion }}" id="link" class="btn btn-md btn-primary mt-2" style="text-align: left; width: 100%;" target="_blank">
                                                            <i class="fas fa-angle-double-right"></i>
                                                            <b style="margin-left: 5px;">Documentación</b>
                                                        </a>
                                                    @endif
                                                @endif

                                                @if ($external_url)
                                                    <a href="{{ $url_curso_inscripcion }}" id="link" class="btn btn-md btn-primary mt-2" style="text-align: left; width: 100%;" target="_blank">
                                                        <i class="fas fa-angle-double-right"></i>
                                                        <b style="margin-left: 5px;">Acceder al curso</b>
                                                    </a>
                                                @endif

                                                <div class="alert alert-primary m-10" role="alert">
                                                    <i class="fas fa-{{ (($curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria') ? 'lock' : 'unlock') }}"></i>
                                                    <b style="margin-left: 5px;">{{ $curso->descripcion_estado }}</b>
                                                    @if (isset($curso->cursos_tutores) && $curso->formato == "curso")
                                                    <br><i class="fas fa-chalkboard-teacher"></i>
                                                        Tutorías:
                                                        @if ($curso->cursos_tutores->count() > 0)
                                                            @foreach($curso->cursos_tutores as $curso_tutor)
                                                            <b>{{ $curso_tutor->usuario_tutor->usuario->nombre_completo }}{{ (($curso->cursos_tutores->count() == $loop->iteration) ? '' : ', ') }}</b>
                                                            @endforeach
                                                        @else
                                                            <b>No hay tutores asignados</b>
                                                        @endif
                                                    @endif
                                                </div>
                                                @if ($curso->cursos_tutores->count() > 0)
                                                <a @if ($curso->cursos_tutores->count() === 1) href="/cursos/{{ $curso->id }}/tutorias/{{ $curso->cursos_tutores->first()->usuario_tutor->usuario->id }}" id="link" @else data-toggle="modal" data-target="#seleccionarTutores" @endif>
                                                    <button class="btn btn-md btn-outline-danger" style="text-align: left; width: 100%;">
                                                        <i class="fas fa-angle-double-right"></i>
                                                        <b style="margin-left: 5px;">Realizar consulta</b>
                                                    </button>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="align-items-start">
                                                @if (($curso->fecha_inicio && $curso->fecha_inicio > 0) || ($curso->fecha_fin && $curso->fecha_fin > 0))
                                                    <p>
                                                        <i class="fas fa-calendar-day pr-1"></i>
                                                        @if ($curso->fecha_inicio && $curso->fecha_inicio > 0)
                                                        <b>Fecha de inicio</b>: {{ date("d-m-Y", strtotime($curso->fecha_inicio)) }}
                                                        @endif
                                                        @if ($curso->fecha_fin && $curso->fecha_fin > 0)
                                                        / <b>Fecha fin</b>: {{ date("d-m-Y", strtotime($curso->fecha_fin)) }}
                                                        @endif
                                                    </p>
                                                @endif

                                                <div id="descripcion_curso_content">{!! str_replace("<img ", "<img class=\"img-fluid\"", $curso->descripcion) !!}</div>
                                                @if ($usuario_diploma && ($curso->encuesta_id > 0 && Auth::user()->encuesta($curso)->first()))
                                                    <div class="text-right">
                                                        <a  class="btn btn-primary btn-lg open-modal" href="#" role="button" data-id="0" contenido="diploma:{{ $usuario_diploma->id }}">
                                                            <i class="fas fa-file-alt"></i>
                                                            <b style="margin: 0 5px;">DIPLOMA</b>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mt-3">
                                            <div class="row">
                                                @php
                                                    $org = 0;
                                                    if ($curso->patrocinadores->count() > 0) $org++;
                                                    if ($curso->organizadores->count() > 0) $org++;
                                                    if ($curso->avaladores->count() > 0) $org++;
                                                    if ($curso->acreditadores->count() > 0) $org++;
                                                    $organizacionesColumnas = (12 / (($org > 0) ? $org : 1));
                                                @endphp

                                                @if ($curso->patrocinadores->count() > 0)
                                                    <div class="col-sm-{{ $organizacionesColumnas }}">
                                                        <b>Patrocinado por:</b>
                                                        <br />
                                                        @foreach ($curso->patrocinadores as $patrocinador)
                                                            <div class="organizador-img" style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace("\\", "/", $patrocinador->logo) }}');"></div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if ($curso->organizadores->count() > 0)
                                                    <div class="col-sm-{{ $organizacionesColumnas }}">
                                                        <b>Organizado por:</b>
                                                        <br />
                                                        @foreach ($curso->organizadores as $organizador)
                                                            <div class="organizador-img" style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace("\\", "/", $organizador->logo) }}');"></div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if ($curso->avaladores->count() > 0)
                                                    <div class="col-sm-{{ $organizacionesColumnas }}">
                                                        <b>Avalado por:</b>
                                                        <br />
                                                        @foreach ($curso->avaladores as $avalador)
                                                            <div class="organizador-img" style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace("\\", "/", $avalador->logo) }}');"></div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if ($curso->acreditado)
                                                    @if ($curso->acreditadores->count() > 0)
                                                    <div class="col-sm-{{ $organizacionesColumnas }}">
                                                        <b>Acreditado por:</b>
                                                        <br />
                                                        @foreach ($curso->acreditadores as $acreditador)
                                                            <div class="organizador-img" style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace("\\", "/", $acreditador->logo) }}');"></div>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        @if ($curso->instrucciones)
                                            <div class="col-sm-12 mt-3 mb-0">
                                                <div class="alert alert-info" role="alert" style="margin: 15px 0;">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span style="margin-left: 5px;">
                                                        <b>INSTRUCCIONES:</b> {!! $curso->instrucciones !!}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($curso->instrucciones)
                                            <div class="col-sm-12 mt-3 mb-0">
                                                <div class="alert alert-info" role="alert" style="margin: 15px 0;">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span style="margin-left: 5px;">
                                                        <b>INSTRUCCIONES:</b> {!! $curso->instrucciones !!}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach ($curso->bloques as $bloque)
                            @if ($bloque->items->count() > 0 || $bloque->cuestionarios->count() > 0)
                                <div class="card bloques" bloque-id="{{ $bloque->id }}">
                                    @php
                                        
                                        $bloqueAnterior = $curso->bloque_anterior($bloque);

                                        $bloqueAnteriorCompletado = ((Auth::user()->tiempo_finalizado($curso)) ? false : (($bloque == $bloqueAnterior || !$curso->configuracion(1)) ? true : Auth::user()->completo_bloque($bloqueAnterior, $bloque)));

                                    @endphp

                                    <div class="card-body {{ (Auth::user()->bloque_completado($bloque) ? 'card-green' : '') }} accordion closed" data-id="card-{{$loop->iteration}}" id="module-{{$loop->iteration}}">
                                        <h5 class="title collapsed">
                                            <i class="fas fa-plus-circle right"></i>
                                            <b class="fas fa-{{ (Auth::user()->bloque_completado($bloque) ? 'check' : 'angle-double-right') }} fz-0-6 pr-2"></b>
                                            <span id="text">{{$bloque->titulo}}</span>
                                        </h5>
                                        <div class="card-content collapsed" id="card-{{$loop->iteration}}">
                                            <span id="desc">
                                                {!! $bloque->descripcion !!}
                                            </span>

                                            @foreach ($bloque->items as $item)
                                                @if($curso->fecha_inicio > now())
                                                    <div class="card border-light mb-12 bloque-item">
                                                        <div class="tema-curso card-body content open-modal">
                                                            <p class="card-text">
                                                                <i class="fas fa-angle-double-right" id="status"></i>
                                                                <span style="margin: 0 5px;">{{$item->titulo}}</span>
                                                            </p>
                                                            <span id="desc" class="small">
                                                                {!! $item->descripcion !!}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="card border-light mb-12 bloque-item">
                                                        @php ($jsonItem = json_decode($item->contenido)) @endphp
                                                        @if ($bloqueAnteriorCompletado || Auth::user()->completo_item($item->id))
                                                            <div class="tema-curso card-body content open-modal" data-id="{{$item->id}}" contenido="{{ is_array($jsonItem) && count($jsonItem) ? $jsonItem[0]->download_link : $item->contenido }}">
                                                                <p class="card-text">
                                                                    <i title="{{  Auth::user()->completo_item($item->id) ? 'Completado' : (!is_null(\App\UsuarioItem::where('usuario_id', $user->id)->where('item_id', $item->id)->first()) ? 'Ya se ha revisado. Aún no se ha completado el tiempo requerido de visualización' : 'Pendiente de revisar' )}}" class="fas fa-{{  Auth::user()->completo_item($item->id) ? 'check' : (!is_null(\App\UsuarioItem::where('usuario_id', $user->id)->where('item_id', $item->id)->first()) ? 'clock' : 'angle-double-right' )}}" id="status"></i>
                                                                    <span style="margin: 0 5px;">{{$item->titulo}}</span>
                                                                </p>

                                                                <span id="desc" class="small">
                                                                    {!! $item->descripcion !!}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="bg-light card-body content">
                                                                <p class="card-text">
                                                                    <i class="fas fa-lock" id="status"></i>
                                                                    <span style="margin: 0 5px;">{{$item->titulo}}</span>
                                                                </p>
                                                                
                                                                <span id="desc" class="small">
                                                                    {!! $item->descripcion !!}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach <!-- FIN Loop Items -->
                                            
                                            {{-- Mostrar cuestionarios del curso || 3 Ways - Alexis Bogado --}}
                                            <div class="questionnaire_container">
                                                {{-- @foreach ($bloque->cuestionarios->where('id_superior', '<=', '0') as $cuestionario) --}}
                                                @foreach ($bloque->cuestionarios as $cuestionario)
                                                    @if ($curso->fecha_inicio > now())
                                                        <div class="card border-light mb-12">
                                                            <div class="card-body content autoev">
                                                                <p class="card-text">
                                                                    <i class="fas fa-angle-double-right questionnaire" id="status"></i>
                                                                    <span style="margin: 0 5px;">
                                                                        {{$cuestionario->titulo}} 
                                                                        <b class="right"></b>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Bloque anterior completado (Curso estricto) o cuestionario hecho --}} {{-- Bloque anterior completado (Curso estricto) o cuestionario hecho --}}
                                                        @if ( !$is_strict || (Auth::user()->items_bloque_completado($bloque, false) && ($bloqueAnteriorCompletado || $user->completo_cuestionario($cuestionario->id))) )
                                                            @if ($user->cuestionarios()->where('cuestionario_id', $cuestionario->id)->count() > 0) {{-- Intentos de aprobar el cuestionario (oportunidades) --}}
                                                                <div class="card border-light mb-12">
                                                                    <a href="{{ route('cuestionario', $cuestionario->id) }}" id="link" class="card-body content autoev">
                                                                        <p class="card-text">
                                                                            <i class="fas fa-{{ (($user->getLastCuestionarioById($cuestionario->id)->estado == 'aprobado') ? 'check' : 'times') }} questionnaire" id="status" ></i>
                                                                            
                                                                            <span style="margin: 0 5px;">
                                                                                {{ $cuestionario->titulo }}
                                                                                <b class="right">{{ $user->getLastCuestionarioById($cuestionario->id)->estado_cuestionario }}</b>
                                                                            </span>
                                                                        </p>
                                                                    </a>
                                                                </div>

                                                                {{-- Comprobar si el cuestionario tiene recuperación y mostrar en caso de que el cuestionario principal no esté aprobado y no tenga más oportunidades --}}
                                                                @if ($cuestionario->recuperaciones->count() > 0 && $user->getLastCuestionarioById($cuestionario->id)->estado != "aprobado" && $user->cuestionarios()->where('cuestionario_id', $cuestionario->id)->count() >= $cuestionario->oportunidades && $cuestionario->oportunidades > 0)
                                                                    @foreach ($cuestionario->recuperaciones as $recuperacion)
                                                                        @if ($bloqueAnteriorCompletado || $user->completo_cuestionario($recuperacion->id))
                                                                            {{-- Intentos de aprobar el cuestionario de recuperación (oportunidades) --}}
                                                                            @if ($user->cuestionarios()->where('cuestionario_id', $recuperacion->id)->count() > 0)
                                                                                <div class="card border-light mb-12">
                                                                                    <a href="{{ route('cuestionario', $recuperacion->id) }}" id="link" class="card-body content autoev">
                                                                                        <p class="card-text">
                                                                                            <i class="fas fa-{{ (($user->getLastCuestionarioById($recuperacion->id)->estado == 'aprobado') ? 'check' : 'times') }} questionnaire" id="status"></i>
                                                                                            
                                                                                            <span style="margin: 0 5px;">
                                                                                                {{ $recuperacion->titulo }}
                                                                                                <b class="right">{{ $user->getLastCuestionarioById($recuperacion->id)->estado_cuestionario }}</b>
                                                                                            </span>
                                                                                        </p>
                                                                                    </a>
                                                                                </div>
                                                                            @else
                                                                                <div class="card border-light mb-12">
                                                                                    <a href="{{ route('cuestionario', $recuperacion->id) }}" id="link" class="card-body content autoev">
                                                                                        <p class="card-text ">
                                                                                            <i class="fas fa-angle-double-right questionnaire" id="status"></i>

                                                                                            <span style="margin: 0 5px;">
                                                                                                {{ $recuperacion->titulo }}
                                                                                                <b class="right">{{ $recuperacion->texto_oportunidades }}</b>
                                                                                            </span>
                                                                                        </p>
                                                                                    </a>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <div class="card border-light mb-12">
                                                                                <a class="card-body content autoev">
                                                                                    <p class="card-text">
                                                                                        <i class="fas fa-lock questionnaire" id="status"></i>
                            
                                                                                        <span style="margin: 0 5px;">{{ $cuestionario->titulo }}</span>
                                                                                    </p>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @else {{-- No se ha intentado ninguna vez --}}
                                                                <div class="card border-light mb-12">
                                                                    <a href="{{ route('cuestionario', $cuestionario->id) }}" id="link" class="card-body content autoev">
                                                                        <p class="card-text">
                                                                            <i class="fas fa-angle-double-right questionnaire" id="status"></i>

                                                                            <span style="margin: 0 5px;">
                                                                                {{ $cuestionario->titulo }}
                                                                                <b class="right">{{ $cuestionario->texto_oportunidades }}</b>
                                                                            </span>
                                                                        </p>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @else {{-- Cuestionario bloqueado --}}
                                                            <div class="card border-light mb-12">
                                                                <a class="card-body content autoev">
                                                                    <p class="card-text">
                                                                        <i class="fas fa-lock questionnaire" id="status"></i>

                                                                        <span style="margin: 0 5px;">{{ $cuestionario->titulo }}</span>
                                                                    </p>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>

                                            {{-- End Mostrar cuestionarios del curso --}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach <!-- FIN Loop Bloques -->

                        @if (!$curso->external_curso_id && ($curso->encuesta_id > 0 && Auth::user()->encuesta($curso)->count() <= 0))
                            <a href="{{ route('formacion.encuesta', $curso->id) }}" id="pool-link" class="{{ (($usuario_curso->obtiene_diploma()) ? '' : 'hidden') }}">
                                <div class="alert alert-warning mt-2" id="pool-link" role="alert">
                                    <b>
                                        <i class="fas fa-exclamation-triangle" id="status"></i>
                                        Para finalizar el curso y se de por aprobado, es necesario que rellene la encuesta de satisfacción.
                                    </b>
                                </div>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if ($curso->cursos_tutores->count() > 1)
        <div class="modal" id="seleccionarTutores" tabindex="-1" role="dialog" aria-labelledby="seleccionarTutoresTitulo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="seleccionarTutoresTitulo">Seleccionar tutor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @foreach($curso->cursos_tutores as $curso_tutor)
                        <div class="card border-light mb-12">
                            <div class="card-body content tutor" href="/cursos/{{ $curso->id }}/tutorias/{{ $curso_tutor->usuario_tutor->usuario->id }}" id="link">
                                <p class="card-text">
                                    <i class="fas fa-user"></i>
                                    <b style="margin: 0 5px;">{{ $curso_tutor->usuario_tutor->usuario->nombre_completo }}</b>
                                    <i class="fas fa-angle-double-right right" style="margin: 4px 0;"></i>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('styles')
    <style>
        .autoev {
            color: #6a602d !important;
            background-color: #f5f1dd !important;
            border-color: #f0ebd0 !important;
            font-weight: bold !important;
        }
        #main-modal {
            top: 0;
        }
        .m-10 {
            margin: 10px 0;
        }

        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8) !important;
        }

        .modal-content {
            border: 0 !important;
        }

        .tutor {
            cursor: pointer;
        }

        .tutor:hover {
            background-color: #efefef;
        }

        #desc.small {
            font-size: 12px;
        }

        .card-green {
            background-color: #defbe3 !important;
        }

        .fz-0-6 {
            font-size: 0.6em;
        }

        .fa-check {
            color: green;
        }

        .hidden {
            display: none;
        }
        i.questionnaire{
            font-size: .875rem;
        }
    </style>

    <script>
        var startTime = 0;
        var inModal = false;
        /** 3 ways Euro Fuenmayor
         *  Agregado variables interval y closeModal para nuevas funcionalidades
         *  interval indica que se ha llamado a la rutina de guardar el tiempo de visualizacion del contenido de bloque de curso desde un setinterval que recurremente actualiza el tiempo
         *  closemodal indica que se ha llamado a la rutina desde un evento de cierre del modal del contenido de bloque de curso
         * */
        function saveTime(itemId, callback = null, interval = null, closeModal = null) {
            if (!inModal && interval == null && closeModal == null) return false;
            inModal = false;
            var exitTime = new Date();
            var secondsOnPage = Math.round((exitTime - startTime) / 1000);
            var timeOnPage = secondsOnPage;
            /** 3 ways Euro Fuenmayor
             *  En el caso de interval, la variable de referencia de tiempo starTime se actualiza al tiempo actual, de manera que en exisrir un nuevo llamado la acumulación sea la correspondiente con respecto a la ultima actualizacion de tiempo y no desde el momento de cargar la vista
             **/
            if (interval != null) startTime = new Date();
            $.post("/formacion/cursos/{{ $curso->id }}", { _token: "{{ csrf_token() }}", item_id: itemId, time: timeOnPage }, null).always(function() {
                if (callback != null) callback();
            }).done(function($response) {
                /** 3 ways Euro Fuenmayor
                 *  En el caso de closemoal, se realiza la logica para actualizar los iconos que indica el progreso del item del curso y se controla el fade en el cierre del modal
                 **/
                if (closeModal != null) {
                    let $refreshQuestionnaire = false;
                    var box = $(".card-body .content[data-id=\"" + itemId + "\"]");
                    if ($response.is_partial_time_item) {
                        $("#status", box).removeClass("fa-angle-double-right");
                        $("#status", box).addClass("fa-clock");
                        $("#status", box).attr('title', 'Ya se ha revisado. Aún no se ha completado el tiempo requerido de visualización');
                    }
                    if ($response.is_item_completed) {
                        $("#status", box).fadeOut("fast", function () {
                            $(this).removeClass("fa-clock");
                            $(this).removeClass("fa-angle-double-right");
                            $(this).addClass("fa-check");
                            $("#status", box).attr('title', 'Completado');
                            $(this).fadeIn("fast");
                        }).promise().done(function () {
                            // Check next module
                            const mainModule = box.parent().parent().parent();
                            const dataId = mainModule.attr("data-id");
                            let cardId = dataId.substr(5, dataId.length);
                            cardId = parseInt(cardId);
                            let toDoItem = $(".card-body #status.fa-angle-double-right", mainModule).length;
                            let lockedItems = $(".card-body #status.fa-lock", mainModule).length;
                            if ((toDoItem + lockedItems) === 0) {
                                const nextModule = $(".card-body[data-id=\"card-" + (cardId + 1) + "\"]");
                                if (!nextModule.length) {
                                    if ($("#pool-link").length) $("#pool-link").removeClass("hidden");
                                    mainModule.addClass("card-green");
                                    $("b.fas.fa-angle-double-right", mainModule).attr("class", "fas fa-check fz-0-6 pr-2");
                                    mainModule.trigger("click");
                                    return;
                                }
                                if (mainModule.hasClass("card-green")) return;
                                $.each($(".bloque-item .card-body", nextModule), function (index, item) {
                                    if ($(item).hasClass("open-modal")) return;
                                    $(item).attr("class", "tema-curso card-body content open-modal");
                                    $("i.fas.fa-lock", $(item)).attr("class", "fas fa-angle-double-right");
                                });
                                mainModule.addClass("card-green");
                                $("b.fas.fa-angle-double-right", mainModule).attr("class", "fas fa-check fz-0-6 pr-2");
                                mainModule.trigger("click").promise().done(function () {
                                    window.location.href = window.location.pathname + "#module-" + (cardId + 1);
                                    location.reload();
                                });
                            }else{
                                @if($is_strict)
                                lockedItems = $(".card-body #status.fa-lock:not(.questionnaire)", mainModule).length;
                                toDoItem = $(".card-body #status.fa-angle-double-right:not(.questionnaire)", mainModule).length;
                                const lockedItemsQuestionnaire = $(".card-body #status.fa-lock.questionnaire", mainModule);
                                if((toDoItem + lockedItems) === 0){
                                    lockedItemsQuestionnaire.removeClass('fa-lock').addClass('fa-cog fa-spin fa-3x fa-fw');
                                    $refreshQuestionnaire = true;
                                    $(".questionnaire_container").load("{{url()->current()}} .questionnaire_container", function(){
                                        lockedItemsQuestionnaire.removeClass('fa-cog fa-spin fa-3x fa-fw').addClass('fa-angle-double-right');
                                        $(this).find('.questionnaire_container').unwrap();
                                        closeModalHandler();
                                    });
                                }
                                @endif
                            }
                        });
                    }
                    setTimeout(function(){
                        if(!$refreshQuestionnaire){
                            closeModalHandler();
                        }
                    },500);
                }
            });
        }

        function closeModalHandler(){
            $('#close-modal').html('<i class="fas fa-angle-double-left"></i> Volver');
            /**
             * Handler del cierre de modal para el fade del mismo
             **/
            $("#main-modal").fadeOut("slow", function () {
                $("body").css("overflow", "auto");
                $("#modal-content", $(this)).html("");
                $("#main-modal").attr("data-id", "");
            });
        }

        window.onbeforeunload = function(event) {
            saveTime($("#main-modal").attr("data-id"), function() {
                if (typeof event == 'undefined') {
                    event = window.event;
                }
                if (event) {
                    event.returnValue = message;
                }
                return message;
            });
        };
    </script>
@endsection
