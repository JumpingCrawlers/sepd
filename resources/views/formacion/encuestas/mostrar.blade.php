{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')
@section('styles')
<style>
    table {
        width: 100%;
        border-spacing: 0 10px;
        border-collapse: separate;
        margin-bottom: 0 !important;
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

    #send {
        vertical-align: top !important;
    }

    .respuesta-container{
        border: 1px dashed  lightgrey;
        padding: 3px;
        height: auto
    }
    .respuesta-input-top-fix{
        position: relative;
        top: -2px
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
                <form class="row left-bordered" method="POST" action="{{ route('encuesta.enviar', $curso->id) }}">
                    {{ csrf_field() }}
                    <div class="col-12">
                        @if (!empty(session('error-message')))
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> <b>{{ session('error-message') }}</b>
                        </div>
                        @endif

                        <div class="alert alert-success" role="alert">
                            <h3><b>ENHORABUENA, ha aprobado el curso.</b></h3>
                            Para finalizar completamente debe responder a la siguiente encuesta.
                        </div>
                    </div>
                    <p class="ml-3">{{$curso->encuesta->texto_cabecera}}</p>
                    <div class="col-12 table-responsive">
                        @foreach (\App\EncuestaCategoria::all() as $categoria)
                            @if($curso->encuesta->preguntas->where('categoria_id', $categoria->id)->where('deleted_at', null)->count() > 0)
                                <div class="row mb-4">
                                    <div class="col-md-2 table-active justify-content-center d-flex align-items-center ml-3">
                                        <b>{{$categoria->titulo}}</b>
                                    </div>
                                    <div class="col-md-9">
                                        @foreach ($curso->encuesta->preguntas->where('deleted_at', null) as $pregunta)
                                            @if($pregunta->categoria_id==$categoria->id)
                                                <div class="row mb-1" style="font-size: 14px">
                                                    <div class="ml-3"><b>{{$pregunta->pregunta}}</b></div>
                                                </div>
                                                <div class="row">
                                                    <div class="ml-3 d-flex w-100">
                                                        @if ($pregunta->respuestas->where('deleted_at', null)->count() > 0)
                                                            @foreach ($pregunta->respuestas->where('deleted_at', null) as $respuesta)
                                                                <div class="respuesta-container mr-2 p-1 align-items-center d-flex justify-content-center">
                                                                    <input type="radio" name="preg_{{ $pregunta->id }}" id="preg_{{ $respuesta->id }}" value="{{ $respuesta->id }}" required>
                                                                    <span class="ml-1"> {{$respuesta->respuesta}} </span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                           <textarea class="form-control" name="preg_{{ $pregunta->id }}" placeholder="Escribe tu respuesta aquí"></textarea>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        {{--Nueva sección para preguntas sin categorias - 3ways Euro Fuenmayor--}}
                        @if($curso->encuesta->preguntas->where('categoria_id', null)->where('deleted_at', null)->count() > 0)
                            <div class="row mb-4">
                                <div class="col-md-2 table-active justify-content-center d-flex align-items-center ml-3">
                                    <b>-</b>
                                </div>
                                <div class="col-md-9">
                                    @foreach ($curso->encuesta->preguntas->where('deleted_at', null)->where('categoria_id', null) as $pregunta)
                                        <div class="row mb-1" style="font-size: 14px">
                                            <div class="ml-3"><b>{{$pregunta->pregunta}}</b></div>
                                        </div>
                                        <div class="row">
                                            <div class="ml-3 d-flex w-100">
                                                @if ($pregunta->respuestas->where('deleted_at', null)->count() > 0)
                                                    @foreach ($pregunta->respuestas->where('deleted_at', null) as $respuesta)
                                                        <div class="respuesta-container mr-2 p-1 align-items-center d-flex justify-content-center">
                                                            <input type="radio" name="preg_{{ $pregunta->id }}" id="preg_{{ $respuesta->id }}" value="{{ $respuesta->id }}" required>
                                                            <span class="ml-1"> {{$respuesta->respuesta}} </span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <textarea class="form-control" name="preg_{{ $pregunta->id }}" placeholder="Escribe tu respuesta aquí"></textarea>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-9 info-text text-left mt-4">
                        Esta encuesta se ha diseñado para conocer su opinión sobre la formación que ha recibido a través de nuestra plataforma de formación on-line y obedece a un compromiso de mejora en la calidad de la oferta formativa de la SEPD, los datos recabados en la misma serán almacenados de forma anónima y analizados para mejorar la plataforma y sus cursos.
                    </div>

                    <div class="col-3 text-right mt-4" id="send">
                        <button type="submit" class="btn btn-primary">Enviar <i class="fas fa-angle-double-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection