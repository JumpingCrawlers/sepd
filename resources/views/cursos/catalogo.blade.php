{{-- 3 Ways Carlos Colmenarez --}}
@extends('base.sepd')
@section('content')

    <div class="container">
        <div class="col-sm-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Cursos disponibles</h2>
            </div>
            <div class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-sm-12 mis-cursos">
                        @foreach ($cursos as $curso)
                            <a href="/cursos/{{$curso->id}}">
                                <div class="card" id="link">
                                    <div class="card-body row">
                                        <div class="col-sm-4">
                                            <div class="align-items-start">
                                                @if ($curso->imagen)
                                                    <img src="{{url(config('app.url_back'))}}storage\{{$curso->imagen}}" class="img-fluid flex-auto" width="100%">
                                                @else
                                                    <img src="{{url(config('app.url_back'))}}\storage\curso-sin-imagen.png" class="img-fluid flex-auto" width="100%">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <h5><b>{{$curso->titulo}}</b></h5>

                                            @if (($curso->fecha_inicio && $curso->fecha_inicio > 0) || ($curso->fecha_fin && $curso->fecha_fin > 0))
                                            <i class="fas fa-calendar-day"></i>
                                            @if ($curso->fecha_inicio && $curso->fecha_inicio > 0)
                                            <b>Fecha de inicio</b>: {{date("d-m-Y", strtotime($curso->fecha_inicio))}}
                                            @endif
                                            @if ($curso->fecha_fin && $curso->fecha_fin > 0)
                                            / <b>Fecha fin</b>: {{ date("d-m-Y", strtotime($curso->fecha_fin)) }}
                                            @endif
                                            <br>
                                            @endif
                                            <i class="fas fa-dollar-sign"></i>
                                            <b>Precio socios</b>: {{ $curso->precio_socio }}€ / <b>Precio no socios</b>: {{ $curso->precio_nosocio }}€
                                            <br><br>
                                            <p>{!!str_limit($curso->descripcion, 356)!!}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="col-sm-12 mt-4">
                        <div class="pagination justify-content-center">
                            <nav aria-label="Page navigation example">
                                {{ $cursos->links("pagination::bootstrap-4") }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection