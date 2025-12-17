{{-- 3 Ways Carlos Colmenarez --}}
@extends('base.sepd')
@section('styles')
<style>
 table {
        width: 100%;
        margin-top: 2em;
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
        text-align: center;
    }

    .dotted {
        border-bottom: 1px dotted #cccccc !important;
    }

    .justify-content-center {
        display: grid;
        justify-content: center;
    }

    label {
        cursor: pointer;
    }
</style>
@endsection

@section('scripts')
@if ($cuestionario->tiempo_limite > 0)
<script>
    $(document).ready(function() {
        var limitTime = {{ $cuestionario->tiempo_limite }};
        //$("#time").html(parseTime(limitTime));

        var availableTime = setInterval(function() {
            limitTime--;
            //$("#time").html(parseTime(limitTime));
            if (limitTime < 1) sendAnswers();
        }, 1000);

        function sendAnswers(interval) {
            clearInterval(availableTime);
            $("#cuestionario-form").submit();
        };
    });

    /*function parseTime(seconds) {
		var hours = parseInt(Math.floor(((seconds % 31536000) % 86400) / 3600), 10);
		var minutes = parseInt(Math.floor((((seconds % 31536000) % 86400) % 3600) / 60), 10);
		var seconds = parseInt((((seconds % 31536000) % 86400) % 3600) % 60, 10);
		return ((hours < 10) ? "0" + hours : hours) + ":" + ((minutes < 10) ? "0" + minutes : minutes)+ ":" + ((seconds < 10) ? "0" + seconds : seconds);
    };*/
</script>
@endif
@endsection

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">{{$curso->titulo}}</h2>
            </div>
            <div data-id-curso="252" class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    @if ($cuestionario->tiempo_limite > 0)
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Dispones de <b>{{ gmdate("H:i:s", $cuestionario->tiempo_limite) }}</b> para realizar el cuestionario.
                        </div>
                    </div>
                    @endif
                    <div class="col-12">
                        <a href="/formacion/cursos/{{$curso->id}}">
                            <button class="btn btn-primary">
                                <i class="fas fa-angle-double-left"></i>
                                Volver a la ficha de curso
                            </button>
                        </a>
                    </div>
                    <form class="row table-responsive" id="cuestionario-form" action="{{ route('cuestionario.enviar', $cuestionario->id) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-12 justify-content-center">
                            @foreach ($cuestionario->preguntas as $pregunta)
                                @php ($askId = $loop->iteration)
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="table-active" width="5%">{{$askId}}</th>
                                            <th class="text-left dotted" width="1%"></th>
                                            <th class="text-left dotted pl-0">{{$pregunta->pregunta}}</th>
                                        </tr>
                                        @foreach ($pregunta->respuestas->sortBy('orden') as $respuesta)
                                            <tr>
                                                <th width="5%">{{ range('a', 'z')[($loop->iteration - 1)] }}) </th>
                                                <td class="text-left" width="1%"><input type="radio" name="respuesta_{{ $askId }}" id="respuesta_{{ $askId }}_{{ $loop->iteration }}" value="{{ $respuesta->id }}"></td>
                                                <td class="text-left"><label for="respuesta_{{ $askId }}_{{ $loop->iteration }}">{{ $respuesta->respuesta }}</label></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                        <div class="col-12 mt-4" id="send">
                            <input type="submit" class="btn btn-primary" value="Enviar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection