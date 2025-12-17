{{-- 3 Ways Alex --}}
@extends('base.sepd')
@section('content')
<style>   
    .badge-red {
        background-color: #d10000;
        color: #fff;
        font-size: 10px;
        vertical-align: middle;
        padding-top: 5px;
    } 
</style>
    <div class="container">
        @if($usuarios_cursos)  
        <div class="col-sm-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Mensajes</h2>
            </div>

            <div data-id-curso="252" class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-12">
                        @foreach($usuarios_cursos as $usuario_curso)
                            @if($usuario_curso->mensajes->count())  
                                <div class="card" id="blue">
                                    <div class="card-body accordion" data-id="card-{{$usuario_curso->curso->id}}">
                                        <h5 class="title" id="blue-title">
                                            <span id="text">{{$usuario_curso->curso->titulo}}</span>
                                            <i class="fas fa-minus-circle right"></i>
                                            <!-- <span class="badge badge-red">5623</span> -->
                                        </h5>
                                        <div class="card-content" id="card-{{$usuario_curso->curso->id}}">
                                            <!-- <span id="desc">Tiene <b>5623</b> mensajes  en <b>{{$usuario_curso->mensajes->count()}}</b> conversaciones.</span> -->
                                            
                                                @foreach($usuario_curso->mensajes as $mensaje)
                                                    <?php 
                                                        if($mensaje->emisor == Auth::user()->id){  
                                                            $datosUsuario = $mensaje->datosReceptor;
                                                        }else{
                                                            $datosUsuario = $mensaje->datosEmisor;
                                                        }
                                                    ?>
                                                    <div class="card border-light mb-12">
                                                        <div class="card-body"  data-id="1">
                                                            <a id="link" href="/cursos/{{$usuario_curso->curso_id}}/tutorias/{{$datosUsuario->id}}">
                                                                <p class="card-text">
                                                                    <i class="fas fa-user"></i>
                                                                    <span style="margin: 0 5px;">{{$datosUsuario->nombre}} {{$datosUsuario->apellidos}} <!-- <span class="badge badge-red">5000</span> --></span><br>
                                                                    <i class="fas fa-clock"></i>
                                                                    <span style="margin: 0 5px;">{{date('d/m/Y h:i', strtotime($mensaje->created_at))}}</span>
                                                                </p>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach  
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                       
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection