{{-- 3 Ways Alex --}}
@extends('base.sepd')
@section('content')
<style>
    #modal-header {
        text-align: center;
        margin-top: 15px;
        font-size: 24pt;
    }

    #msg-author {
        font-weight: bold;
    }

    #modal-messages {
        margin: 10px 20px;
    }

    #modal-messages > #user-message {
        border: 1px solid #3097d1;
        border-radius: 5px;
        height: auto;
        margin-bottom: 10px;
        padding: 10px 15px;
        min-height: 5.4rem;
    }

    #modal-messages > #user-message > span.user.tutor {
        background-color: #004363 !important;
        float: right;
        border-bottom-right-radius: 0;
        margin: 0;
        border-left: 2px solid #3097d1;
        border-right: 0;
        border-bottom-left-radius: 5px;
        margin: -10px -15px 15px 15px;
    }

    #modal-messages > #user-message > span.user {
        font-size: 15px;
        background-color: #0083c29e;
        color: white;
        padding: 5px 20px;
        border-right: 2px solid #3097d1;
        float: left;
        height: auto;
        max-width: 200px;
        min-width: 200px;
        word-break: break-all;
        border-bottom: 2px solid #3097d1;
        border-bottom-right-radius: 5px;
        margin: -10px 15px 15px -15px;
    }

    #modal-messages > #user-message > span#message {
        
    }

    h5.title {
        margin: -20px;
        margin-bottom: 20px;
        padding: 20px;
        background-color: #fafafa;
        transition: .5s;
    }

    h5.title.collapsed {
        display: block!important;
        margin-bottom: -20px;
        transition: .5s;
    }

    span#text {
        width: 1000px;
        word-break: break-word;
    }

    .card, .card.border-light.mb-12 {
        margin-top: 10px;
    }

    .collapsed {
        display: none;
    }

    .card.border-light.mb-12 > .card-body {
        padding: 10px;
    }

    .right {
        float: right;
    }

    #desc {
        color: #949494;
    }

    .card-body .content:hover {
        background-color: #fbfbfb;
    }

    #main-modal {
        background-color: #000000f5;
        height: 100%;
        width: 100%;
        overflow: hidden;
        position: fixed;
        z-index: 99999;
        display: none;
        color: #fff;
    }

    #main-modal .col-custom-2 {
        margin: 20px 35px;
    }

    #main-modal .col-custom-9 {
        padding-top: 15px;
        text-align: center;
        margin: -2px 40px;
    }

    #main-modal #modal-content #modal-header #close-modal {
        border: 1px solid;
        padding: 5px 100px;
        cursor: pointer;
        border-radius: 5px;
        color: #00adff;
        font-size: 12pt;
    }

    #main-modal #modal-content #modal-header #close-modal:hover {
        background-color: #00adff;;
        border-color: #00adff;
        color: #fff;
    }

    #main-modal #modal-content #modal-header #close-modal:active {
        box-shadow: 0 0 0px 5px #00adff3d;
    }

    #main-modal > #modal-content {
        height: 100%;
        width: 100%;
    }

    #main-modal > #modal-content > iframe {
        border-width: 0;
    }

    .badge-red {
        background-color: #d10000;
        color: #fff;
        font-size: 10px;
        vertical-align: middle;
        padding-top: 5px;
    }
    .card-group .card.border-light {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .card-group .btn-red {
        background-color: #fafafa;
        font-size: 12pt;
        color: #d10000;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-color: #e6e5e5;
        border-left: 0;
        margin-top: 10px;
    }

    .unread {
        color: #d10000;
    }
</style>
    <div class="container">
        @if($curso)
        <?php
            $receptor="";
        ?>
        <div class="col-sm-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Consultas sobre {{ $curso->titulo }}</h2>
            </div>
            <div class="pointer mb-4 px-0 pb-3">
                <h3>Mensajes</h3>

                <div data-id-curso="252" class="pointer mb-4 px-0 pb-3">
                    <div class="row left-bordered">
                    <div class="col-12">
                        <a href="{{ route('mensajes.usuario') }}">
                            <button class="btn btn-primary" style="margin-left: 20px;"><i class="fas fa-angle-double-left"></i> Volver a Mensajes</button>
                        </a>
                    </div>
                    <div class="col-12">
                        <div id="modal-messages">
                            @if ($mensajes->count() > 0)
                                @foreach($mensajes as $mensaje)                                
                                            <div id="user-message">
                                    @if($mensaje->emisor == (Auth::user()->id))                                   
                                        <?php
                                            $receptor = $mensaje->receptor;
                                        ?>
                                                <span class="user">
                                                        <i class="fas fa-user"></i>
                                                        <span style="margin: 0 5px;">{{$mensaje->datosEmisor->nombre}} {{$mensaje->datosEmisor->apellidos}}</span><br>
                                                        <i class="fas fa-clock"></i>
                                                        <span style="margin: 0 5px;">{{date('d/m/Y h:i', strtotime($mensaje->created_at))}}</span>
                                                </span>      
                                    @else
                                        <?php
                                            $receptor = $mensaje->emisor;
                                        ?>
                                            <span class="user tutor">
                                                    <i class="fas fa-user"></i>
                                                    <span style="margin: 0 5px;">{{$mensaje->datosEmisor->nombre}} {{$mensaje->datosEmisor->apellidos}}</span><br>
                                                    <i class="fas fa-clock"></i>
                                                    <span style="margin: 0 5px;">{{date('d/m/Y h:i', strtotime($mensaje->created_at))}}</span>
                                            </span>
                                    @endif
                                    <span id="message">
                                        {{$mensaje->mensaje}}
                                    </span>
                                </div>
                                    
                                @endforeach
                            @else
                                @php ($receptor = $usuario_id)
                            @endif

                            
                            <form role="form"
                            class="form-edit-add"
                            if="formMensaje"
                            action="{{ route('storeMensaje', ['curso'=> $curso->id, 'receptor' => $receptor]) }}"
                            method="POST" enctype="multipart/form-data">
                                <div class="input-group mb-3">
                                    <textarea name="mensaje" class="form-control" placeholder="Escribe tu mensaje aquí..."></textarea>
                                    <div class="input-group-append">
                                        <input class="btn btn-primary" type="submit">
                                    </div>
                                </div>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        @endif
    </div>

@endsection