@extends('base.sepd')
@section('content')

    <div class="container">
        <div class="col-sm-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Mis cursos</h2>
            </div>
            <div class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-sm-12 mis-cursos">
                        @if (isset($usuario_cursos))
                            @foreach ($usuario_cursos as $usuario_curso)
                                @php
                                    $url_curso = route('curso.hacer', $usuario_curso->curso->id);
                                    $external_url = $usuario_curso->curso->external_url;
                                    $url_target = '';
                                    // if ($external_url) {
                                        // $url_target = '_blank';
                                        // $url_curso = $external_url . $usuario_curso->usuario->encrypt_manual_gestivos;
                                    // }
                                @endphp
                                <div class="card">
                                    <div class="card-body row">
                                        <div class="col-sm-4">
                                            <div class="align-items-start">
                                                <a href="{{ $url_curso }}" target="{{ $url_target }}">
                                                    @if ($usuario_curso->curso->imagen)
                                                        <img src="{{url(config('app.url_back'))}}storage\{{$usuario_curso->curso->imagen}}" class="img-fluid flex-auto" width="100%">
                                                    @else
                                                        <img src="{{url(config('app.url_back'))}}\storage\curso-sin-imagen.png" class="img-fluid flex-auto" width="100%">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-8">
                                            <a href="{{ $url_curso }}" target="{{ $url_target }}">
                                                <h5><b>{{$usuario_curso->curso->titulo}}</b></h5>
                                                @if (($usuario_curso->curso->fecha_inicio && $usuario_curso->curso->fecha_inicio > 0) || ($usuario_curso->curso->fecha_fin && $usuario_curso->curso->fecha_fin > 0))
                                                <i class="fas fa-calendar-day"></i>
                                                @if ($usuario_curso->curso->fecha_inicio && $usuario_curso->curso->fecha_inicio > 0)
                                                <b>Fecha de inicio</b>: {{ date("d-m-Y",strtotime($usuario_curso->curso->fecha_inicio)) }}
                                                @endif
                                                @if ($usuario_curso->curso->fecha_fin && $usuario_curso->curso->fecha_fin > 0)
                                                / <b>Fecha fin</b>: {{ date("d-m-Y", strtotime($usuario_curso->curso->fecha_fin)) }}
                                                @endif
                                                @endif
                                                {{-- <div class="alert alert-primary m-10" role="alert">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                    <b>CURSO EXCLUSIVO PARA SOCIOS</b>
                                                </div> --}}
                                                    
                                                @php ($usuario_diploma = (Auth::user()->usuario_diplomas->where("curso_id", $usuario_curso->curso->id)->first()))
                                                @if (!$usuario_diploma && !$usuario_curso->obtiene_diploma())
                                                    <div class="alert alert-warning mt-3" role="alert">
                                                        
                                                        @if ($usuario_curso->curso->periodo && $usuario_curso->curso->periodo > 0)
                                                            <i class="fas fa-clock"></i>
                                                            {{-- 3 Ways - Carlos Colmenarez // Días restantes del curso --}}
                                                            @if ($usuario_curso->curso->diasRestantes($usuario_curso->created_at, $usuario_curso->curso->periodo) < 1)
                                                                Ha finalizado tu periodo para realizar el curso.<br>
                                                            @else
                                                                Quedan <b>{{$usuario_curso->curso->diasRestantes($usuario_curso->created_at, $usuario_curso->curso->periodo)}}</b> días para que finalice tu período de realización del curso.<br>
                                                            @endif
                                                            {{-- FIN // Días restantes del curso --}}
                                                        @elseif ($usuario_curso->curso->fecha_fin && $usuario_curso->curso->fecha_fin > 0)
                                                            @if ($usuario_curso->curso->autoevaluaciones()->count() > 0)
                                                                @if (count($usuario_curso->autoevaluaciones_hechas()) < $usuario_curso->curso->autoevaluaciones()->count())
                                                                    <i class="fas fa-clock"></i>
                                                                    @if ($usuario_curso->tiempo_finalizado())
                                                                        El curso ha finalizado.<br>
                                                                    @else
                                                                        @if ($usuario_curso->dias_restantes() <= 0)
                                                                        El curso ha finalizado.<br>
                                                                        @else
                                                                        Quedan {{ $usuario_curso->dias_restantes() }} días para que finalice el curso.<br>
                                                                        @endif 
                                                                    @endif                                                           
                                                                @endif
                                                            @endif
                                                        @endif
                                                        
                                                        @if ($usuario_curso->curso->autoevaluaciones()->count() > 0)
                                                            @if (count($usuario_curso->autoevaluaciones_hechas()) < $usuario_curso->curso->autoevaluaciones()->count())
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <b>AUTOEVALUACIÓN PENDIENTE</b>
                                                            @elseif(strtoupper($usuario_curso->autoevaluaciones_hechas()[0]->last()->estado) == "SUSPENSO")
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <b>{{ strtoupper($usuario_curso->autoevaluaciones_hechas()[0]->last()->estado) }}</b>
                                                                
                                                            @endif
                                                        @endif
                                                    </div>
                                                        
                                                    <b>Progreso del curso</b> <span class="right">{{ $usuario_curso->porcentaje_progreso() }}%</span>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped @if ($usuario_curso->porcentaje_progreso() < 100) bg-info progress-bar-animated @endif" role="progressbar" style="width: {{ $usuario_curso->porcentaje_progreso() }}%" aria-valuenow="{{ $usuario_curso->porcentaje_progreso() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                @elseif ($external_url)
                                                    <div class="mt-3 mb-3"></div>
                                                    <b>Progreso del curso</b> <span class="right">100%</span>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                @else
                                                    <div class="mt-3 mb-3"></div>
                                                    <b>Progreso del curso</b> <span class="right">100%</span>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                @endif
                                            </a>

                                            @if ($usuario_diploma)
                                                <div class="col-sm-12 mt-3 pr-0">
                                                    <a href="{{ route('formacion.acreditaciones') }}">
                                                        <button class="btn btn-primary btn-sm right"><i class="fas fa-award"></i> Ver mis acreditaciones ></button>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-sm-12 mt-4">
                        <div class="pagination justify-content-center">
                            <nav aria-label="Page navigation example">
                                {{ $usuario_cursos->links("pagination::bootstrap-4") }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection