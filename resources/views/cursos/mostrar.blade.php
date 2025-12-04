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
    <div class="container">
        <div class="col-sm-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">{{ $curso->titulo }}</h2>
            </div>

            <div data-id-curso="{{ $curso->id }}" class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-sm-4">
                        <div class="align-items-start">
                            @if ($curso->imagen)
                                <img src="{{ url(config('app.url_back')) }}storage\{{ $curso->imagen }}"
                                    class="img-fluid flex-auto" width="100%">
                            @else
                                <img src="{{ url(config('app.url_back')) }}\storage\curso-sin-imagen.png"
                                    class="img-fluid flex-auto" width="100%">
                            @endif
                            <div class="alert alert-primary" role="alert" style="margin: 10px 0;">
                                <i
                                    class="fas fa-{{ $curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria' ? 'lock' : 'unlock' }}"></i>
                                <b style="margin-left: 5px;">{{ $curso->descripcion_estado }}</b>
                                @if (isset($curso->cursos_tutores) && $curso->formato == 'curso')
                                    <br><i class="fas fa-chalkboard-teacher"></i>
                                    Tutorías:
                                    @if ($curso->cursos_tutores->count() > 0)
                                        @foreach ($curso->cursos_tutores as $curso_tutor)
                                            <b>{{ $curso_tutor->usuario_tutor->usuario->nombre_completo }}
                                                {{ $curso->cursos_tutores->count() == $loop->iteration ? '' : ', ' }}</b>
                                        @endforeach
                                    @else
                                        <b>No hay tutores asignados</b>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="align-items-start">
                            <p>
                                @if (($curso->fecha_inicio && $curso->fecha_inicio > 0) || ($curso->fecha_fin && $curso->fecha_fin > 0))
                                    <i class="fas fa-calendar-day pr-1"></i>
                                    @if ($curso->fecha_inicio && $curso->fecha_inicio > 0)
                                        <b>Fecha de inicio</b>: {{ date('d-m-Y', strtotime($curso->fecha_inicio)) }}
                                    @endif
                                    @if ($curso->fecha_fin && $curso->fecha_fin > 0)
                                        / <b>Fecha fin</b>: {{ date('d-m-Y', strtotime($curso->fecha_fin)) }}
                                    @endif
                                    <br>
                                @endif

                                @if ($curso->precio_socio && $curso->precio_socio > 0)
                                    <i class="fas fa-dollar-sign" style="margin-left: 2px;"></i> <b
                                        style="margin-left: 7px;">Precio socios</b>: {{ $curso->precio_socio }}€
                                @endif

                                @if ($curso->precio_nosocio && $curso->precio_nosocio > 0)
                                    / <b>Precio no socios</b>: {{ $curso->precio_nosocio }}€
                                @endif
                            </p>

                            <p>{!! str_replace('<img', "<img class=\"img-fluid\"", $curso->descripcion) !!}</p>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="row">
                            @php
                                $org = 0;
                                if ($curso->patrocinadores->count() > 0) {
                                    $org++;
                                }
                                if ($curso->organizadores->count() > 0) {
                                    $org++;
                                }
                                if ($curso->avaladores->count() > 0) {
                                    $org++;
                                }
                                if ($curso->acreditadores->count() > 0) {
                                    $org++;
                                }
                                $organizacionesColumnas = 12 / ($org > 0 ? $org : 1);

                            @endphp

                            @if ($curso->patrocinadores->count() > 0)
                                <div class="col-sm-{{ $organizacionesColumnas }}">
                                    <b>Patrocinado por:</b>
                                    <br />
                                    @foreach ($curso->patrocinadores as $patrocinador)
                                        <div class="organizador-img"
                                            style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace('\\', '/', $patrocinador->logo) }}');">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($curso->organizadores->count() > 0)
                                <div class="col-sm-{{ $organizacionesColumnas }}">
                                    <b>Organizado por:</b>
                                    <br />
                                    @foreach ($curso->organizadores as $organizador)
                                        <div class="organizador-img"
                                            style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace('\\', '/', $organizador->logo) }}');">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($curso->avaladores->count() > 0)
                                <div class="col-sm-{{ $organizacionesColumnas }}">
                                    <b>Avalado por:</b>
                                    <br />
                                    @foreach ($curso->avaladores as $avalador)
                                        <div class="organizador-img"
                                            style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace('\\', '/', $avalador->logo) }}');">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($curso->acreditado)
                                @if ($curso->acreditadores->count() > 0)
                                    <div class="col-sm-{{ $organizacionesColumnas }}">
                                        <b>Acreditado por:</b>
                                        <br />
                                        @foreach ($curso->acreditadores as $acreditador)
                                            <div class="organizador-img"
                                                style="background-image:url('{{ url(config('app.url_back')) }}storage/{{ str_replace('\\', '/', $acreditador->logo) }}');">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <br />

                    <div class="col-sm-12">
                        <div class="row justify-content-md-center">
                            <div class="col-12 col-sm-6 col-md-9" style="margin-bottom: 5px;">
                                @if ($curso->informacion)
                                    @php($jsonDocumentacion = json_decode($curso->informacion))
                                    @php($file = is_array($jsonDocumentacion) && isset($jsonDocumentacion[0]) ? $jsonDocumentacion[0]->download_link : $curso->informacion)
                                    @if ($file != '' && $file != '[]')
                                        <a href="{{ url(config('app.url_back')) }}storage/{{ $file }}"
                                            target="_blank">
                                            <button type="button" class="btn btn-primary btn-lg">
                                                <i class="fas fa-info-circle"></i>
                                                <b style="margin: 0 5px;">MÁS INFORMACIÓN</b>
                                            </button>
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                @auth
                                    @if ((Auth::user()->usuario_cursos->where('curso_id', $curso->id)->count() <= 0 &&
                                            $curso->descripcion_estado != 'Pendiente de lanzamiento' &&
                                            $curso->descripcion_estado != 'Cerrado' &&
                                            $curso->descripcion_estado != 'Próxima convocatoria' &&
                                            $curso->registroAbierto() &&
                                            $curso->hay_plazas() ) || $external_url)
                                        <a href="{{ $url_curso_inscripcion }}" target="{{ $url_target }}">
                                            <button type="button" class="btn btn-primary btn-lg">
                                                <i class="fas fa-edit"></i>
                                                <b
                                                    style="margin: 0 5px;">{{ $curso->formato == 'publicacion' || $curso->formato == 'recurso' ? 'ACCESO' : 'INSCRIPCIÓN' }}</b>
                                            </button>
                                        </a>
                                    @endif
                                @endauth
                                @guest
                                    @if ($curso->registroAbierto() && $curso->hay_plazas())
                                        <a href="{{ $url_curso_inscripcion }}" target="{{ $url_target }}">
                                            <button type="button" class="btn btn-primary btn-lg">
                                                <i class="fas fa-edit"></i>
                                                <b
                                                    style="margin: 0 5px;">{{ $curso->formato == 'publicacion' || $curso->formato == 'recurso' ? 'ACCESO' : 'INSCRIPCIÓN' }}</b>
                                            </button>
                                        </a>
                                    @endif
                                @endguest
                                <!-- Si no hay plazas en los expedientes mostrar aforo completo -->
                                @if (!$curso->hay_plazas())
                                    <h4 class="text-danger">El curso está completo</h4>
                                @endif
                            </div>
                        </div>
                    </div>

                    @foreach ($curso->bloques as $bloque)
                        <div class="col-sm-12 bloques">
                            <div class="card">
                                <div class="card-body accordion closed" data-id="card-{{ $loop->iteration }}">
                                    <h5 class="title collapsed">
                                        <i class="fas fa-plus-circle right"></i>
                                        <span id="text">{{ $bloque->titulo }}</span>
                                    </h5>
                                    <div class="card-content collapsed" id="card-{{ $loop->iteration }}" data-toggle="modal" data-target="#inscripcionModal">
                                        @foreach ($bloque->items as $item)
                                            <div class="card border-light mb-12">
                                                <div class="tema-curso card-body content">
                                                    <p class="card-text">
                                                        <i class="fas fa-angle-double-right" id="status"></i>
                                                        <span style="margin: 0 5px;">{{ $item->titulo }}</span>
                                                    </p>
                                                    <span id="desc">
                                                        {!! $item->descripcion !!}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach <!-- FIN Loop Items -->

                                        {{-- ->where('id_superior', '<=', '0') --}}
                                        @foreach ($bloque->cuestionarios as $cuestionario)
                                            <div class="card border-light mb-12">
                                                <a class="card-body content autoev">
                                                    <p class="card-text">
                                                        <i class="fas fa-angle-double-right" id="status"></i>
                                                        <span style="margin: 0 5px;">
                                                            {{ $cuestionario->titulo }}
                                                        </span>
                                                    </p>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach <!-- FIN Loop Bloques -->
                </div>
            </div>
        </div>
    </div>

    @if ($curso->registroAbierto())
        <div class="modal fade" id="inscripcionModal" tabindex="-1" role="dialog" aria-labelledby="inscripcionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pt-0">
                    <div class="modal-body text-align-center">
                        <div class="modal-header p-0 mb-1" style="border: 0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="font-size: 1.3em">&times;</span>
                            </button>
                        </div>

                        @if ($curso->formato == 'publicacion')
                            <h4>Para poder ver el contenido de esta publicación debes estar inscrito</h4>
                        @elseif ($curso->formato == 'recurso')
                            <h4>Para poder ver el contenido de este recurso multimedia debes estar inscrito</h4>
                        @else
                            <h4>Para poder ver el contenido de este curso debes estar inscrito</h4>
                        @endif

                        <br>
                        <a href="{{ route('curso.inscripcion', $curso->id) }}">
                            <button type="button"
                                class="btn btn-primary btn-lg">{{ $curso->formato == 'publicacion' || $curso->formato == 'recurso' ? 'Acceder' : 'Inscribirme' }}</button>
                        </a>
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
    </style>
@endsection
