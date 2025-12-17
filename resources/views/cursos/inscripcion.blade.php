{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')
@section('styles')
<style>
    .table {
        width: 100%;
        margin-bottom: 0 !important;
        border: 1px solid #cccccc;
        border-radius: 6px;
    }

    .separation {
        border-spacing: 2em;
        border-collapse: separate;
    }

    .table-inside {
        border-spacing: 1em;
        border-collapse: separate;
        text-align: center;
        width: 100%;
    }

    td, th {
        border: 0 !important;
        vertical-align: middle !important;
    }
    
    .text-left {
        text-align: left;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-center {
        text-align: center;
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

    .bold {
        font-weight: bold !important;
    }

    .table-orange {
        background-color: rgb(255,144,10);
    }

    a.condiciones {
        color: #2d3b45;
        vertical-align: -webkit-baseline-middle;
    }

    .flex {
        display: inline-flex;
        flex-wrap: wrap;
        width: 100%;
        justify-content: center;
    }

    .is-invalid {
        border-color: #ff0000 !important;
    }

    .invalid-feedback {
        color: #ff0000 !important;
    }
</style>
@endsection

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">{{ $curso->titulo }}</h2>
            </div>

            <div class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-12">
                        <a href="{{ route('curso.mostrar', $curso->id) }}">
                            <button class="btn btn-primary mb-3"><i class="fas fa-angle-double-left"></i> Volver al curso</button>
                        </a>
                    </div>

                    @php ($precio = ($user->es_socio() ? $curso->precio_socio : $curso->precio_nosocio)) @endphp
                    @if ($curso->configuracion(4) || $curso->configuracion(6))
                    <div class="col-12 justify-content-center table-responsive mt-4">

                        @if(session('payment-error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('payment-error') }}
                        </div>
                        @endif

                        @if (($curso->fecha_inicio && $curso->fecha_inicio > 0) && ($curso->fecha_fin && $curso->fecha_fin > 0))
                        <p class="ml-2">
                            <i class="fas fa-calendar-day pr-1"></i> 
                            @if ($curso->fecha_inicio && $curso->fecha_inicio > 0)
                            <b>Fecha de inicio</b>: {{ date("d-m-Y", strtotime($curso->fecha_inicio)) }}
                            @endif
                            @if ($curso->fecha_fin && $curso->fecha_fin > 0)
                            / <b>Fecha fin</b>: {{ date("d-m-Y", strtotime($curso->fecha_fin)) }}
                            @endif
                        </p>
                        @endif

                        @if (!empty(session('alert-type')))
                    <div class="alert alert-{{ session('alert-type') }} alert-dismissible fade show" role="alert">
                        <li>{{ session('message') }}</li>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                         </button>
                    </div>
                @endif
                        <table class="table mb-3">
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        <table class="table-inside table-active">
                                            <tbody>
                                                @if (($curso->precio_socio && $curso->precio_socio > 0) || ($curso->precio_nosocio && $curso->precio_nosocio > 0))
                                                <tr>
                                                    @if ($curso->precio_socio && $curso->precio_socio > 0)
                                                    <td>Precio socios: <b>{{ $curso->precio_socio }} €</b></td>
                                                    @endif
                                                    
                                                    @if ($curso->precio_nosocio && $curso->precio_nosocio > 0)
                                                    <td>Precio no socios: <b>{{ $curso->precio_nosocio }} €</b></td>
                                                    @endif
                                                </tr>
                                                @endif
                                                
                                                <tr>
                                                    <th colspan="2">Curso únicamente accesible por clave promocional</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <form class="form-control" method="POST" action="{{ route('curso.inscripcion', $curso->id) }}">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td colspan="3">
                                            <table class="table-inside table-orange">
                                                <tbody>
                                                    <tr>
                                                        <td>Si dispone de clave promocional introducir aquí</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input class="form-control @if (!empty(session('error-message'))) is-invalid @endif text-center" type="text" name="clave" placeholder="Escribe tu clave promocional" />
                                                            @if (!empty(session('error-message')))
                                                            <div class="invalid-feedback">
                                                                <strong>{{ session('error-message') }}</strong>
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <table class="table-inside text-left">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <a href="/formacion/pdf/Condiciones_generales_de_contratacion.pdf" target="_blank" class="condiciones bold">Condiciones generales de contratación</a>
                                                        </td>
                                                        <td>
                                                            @if ($precio > 0)
                                                                <button type="submit" class="btn btn-primary right">Comprar</button>
                                                            @else
                                                                <button type="submit" class="btn btn-primary right">{{ ((($curso->formato == "publicacion") || ($curso->formato == "recurso")) ? 'Acceder' : 'Inscribir') }}</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </form>

                                <tr>
                                    <td colspan="3">
                                    <div class="row m-2">
                                        @php
                                            $org = 0;
                                            if ($curso->patrocinadores->count() > 0) $org++;
                                            if ($curso->organizadores->count() > 0) $org++;
                                            if ($curso->avaladores->count() > 0) $org++;
                                            if ($curso->acreditadores->count() > 0) $org++;
                                            $organizacionesColumnas = (12 / (($org > 0) ? $org : 1));
                                        @endphp

                                        @if ($curso->patrocinadores->count() > 0)
                                            <div class="col-{{ $organizacionesColumnas }}">
                                                <b>Patrocinado por:</b>
                                                
                                                <br />
                                                @foreach ($curso->patrocinadores as $patrocinador)
                                                    <img style="width: 7em" src="{{ url(config('app.url_back')) }}storage/{{ $patrocinador->logo }}"/>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if ($curso->organizadores->count() > 0)
                                        <div class="col-{{ $organizacionesColumnas }}">
                                            <b>Organizado por:</b>
                                            <br />
                                            @foreach ($curso->organizadores as $organizador)
                                                <img style="width: 7em" src="{{ url(config('app.url_back')) }}storage/{{ $organizador->logo }}"/>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if ($curso->avaladores->count() > 0)
                                        <div class="col-{{ $organizacionesColumnas }}">
                                            <b>Avalado por:</b>
                                            <br />
                                            @foreach ($curso->avaladores as $avalador)
                                            <img src="{{ url(config('app.url_back')) }}storage/{{ $avalador->logo }}" class="img-fluid" /><br />
                                            @endforeach
                                        </div>
                                        @endif

                                        @if ($curso->acreditado)
                                            @if ($curso->acreditadores->count() > 0)
                                            <div class="col-{{ $organizacionesColumnas }}">
                                                <b>Acreditado por:</b>
                                                <br />
                                                @foreach ($curso->acreditadores as $acreditador)
                                                <img src="{{ url(config('app.url_back')) }}storage/{{ $acreditador->logo }}" class="img-fluid" /><br />
                                                @endforeach
                                            </div>
                                            @endif
                                        @endif
                                    </div>
                                    </td>
                                </tr>

                                @if (!$user->solicito_clave($curso->id))
                                <form class="form-control" method="POST" action="{{ route('solicitud.clave', $curso->id) }}">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td colspan="3">
                                            <table class="table-inside table-active text-left mt-3">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="condiciones bold">¿No tiene una clave promocional?</span>
                                                        </td>
                                                        <td>
                                                            <button type="submit" class="btn btn-primary right">Solicitar clave</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </form>
                                @endif

                                
                                @if (!empty(session('key-success')))
                                <tr>
                                    <td colspan="3">
                                        <table class="table-inside table-active text-left mt-3">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="text-align-center" style="font-size: 9pt">
                                                            <strong>{{ session('key-success') }}</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @else
                        @if ($curso->configuracion(5) && !$user->es_socio())
                            <div class="container">
                                <h1>Curso exclusivo para socios</h1>
                                <h5>Para poder inscribirte en este curso es necesario darte de alta como socio de la SEPD.</h5>
                                <a href="{{ route('hazte_socio') }}">
                                    <button class="btn btn-primary btn-lg pl-5 pr-5 right">Hazte socio</button>
                                </a>
                            </div>
                        @else
                        <div class="col-12 justify-content-center table-responsive mt-4">
                            
                            @if(session('payment-error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('payment-error') }}
                            </div>
                            @endif

                            @if (($curso->fecha_inicio && $curso->fecha_inicio > 0) && ($curso->fecha_fin && $curso->fecha_fin > 0))
                            <p class="ml-2">
                                <i class="fas fa-calendar-day pr-1"></i>
                                @if ($curso->fecha_inicio && $curso->fecha_inicio > 0)
                                <b>Fecha de inicio</b>: {{ date("d-m-Y", strtotime($curso->fecha_inicio)) }}
                                @endif
                                @if ($curso->fecha_fin && $curso->fecha_fin > 0)
                                / <b>Fecha fin</b>: {{ date("d-m-Y", strtotime($curso->fecha_fin)) }}
                                @endif
                            </p>
                            @endif
                            <table class="table separation mb-3">
                                <tbody>
                                    <tr>
                                        <td>Precio del curso</td>
                                        <td width="100px"></td>
                                        <td class="text-right bold">{{ ($precio > 0 ? $precio : '0.00') }} €</td>
                                    </tr>
                                    
                                    @if ($precio > 0)
                                    <tr>
                                        <td>Promoción</td>
                                        <td width="100px"></td>
                                        <td class="text-right bold">- 0.00 €</td>
                                    </tr>
                                    @endif
                                    
                                    <tr>
                                        <td>Total a pagar</td>
                                        <td width="100px"></td>
                                        <td class="text-right bold">{{ ($precio > 0 ? $precio : '0.00') }} €</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if ($precio <= 0)
                            <div class="mt-2" id="send">Te has inscrito correctamente</div>
                            @endif
                        </div>

                        <div class="col-12 mt-2" id="send">
                            @if ($precio > 0)
                                <a href="/cursos/{{ $curso->id }}/pago">
                                    <button type="button" class="btn btn-primary">Comprar curso ></button>
                                </a>
                            @else
                                <a href="/formacion/cursos/{{ $curso->id }}">
                                    <button type="button" class="btn btn-primary">Iniciar el curso ></button>
                                </a>
                            @endif
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection