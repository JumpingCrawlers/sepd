{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')
@section('styles')
<style>
    li#head {
        list-style: none;
    }

    li#head > h2 {
        float: left;
    }

    li#head > #radios {
        display: flex;
        float: right;
    }

    #radios > .custom-control {
        margin: 0 0 0 10px;
    }

    .custom-control-label {
        margin-top: 3px;
    }

    #status {
        width: 5rem;
        height: 5rem;
        position: fixed;
        margin: 3rem 0 !important;
    }

    #main-modal {
        background-color: #000000c9;
        height: 100%;
        width: 100%;
        overflow: hidden;
        position: fixed;
        z-index: 99999;
        display: none;
        color: #fff;
        top: 0;
    }

    #main-modal>#close-modal {
        position: fixed;
        margin: 20px;
        border: 1px solid;
        padding: 5px 50px;
        cursor: pointer;
        border-radius: 5px;
        color: #00adff;
    }

    #main-modal>#close-modal:hover {
        background-color: #00adff;
        border-color: #00adff;
        color: #fff;
    }

    #main-modal>#close-modal:active {
        box-shadow: 0 0 0px 5px #00adff3d;
    }

    #main-modal>#modal-content {
        height: 100%;
        width: 100%;
    }

    #main-modal>#modal-content>iframe {
        border-width: 0;
    }
    .mis-cursos a:hover {
        text-decoration: none;
    }
</style>
@endsection
@section('content')
<div id="main-modal" data-id="">
    <div id="close-modal"><i class="fas fa-angle-double-left"></i> Volver</div>
    <div id="modal-content"></div>
</div>
<div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Acreditaciones</h2>
            </div>

            <div class="pointer mb-4 px-0 pb-3">
                <div class="row left-bordered">
                    <div class="col-12" style="margin-bottom: 30px;">
                        <li id="head">
                            <h2>{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h2>
                            <div id="radios">
                                <div id="link" href="{{ Request::is('formacion/acreditaciones/presencial') ? '#' : route('formacion.acreditaciones') . '/presencial' }}" class="custom-control custom-radio">
                                    <input type="radio" id="radio1" data-id="presencial" name="radio-btn" class="custom-control-input" {{ Request::is('formacion/acreditaciones/presencial') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="radio1">Congreso SEPD</label>
                                </div>
                                <div id="link" href="{{ (Request::is('formacion/acreditaciones') || Request::is('formacion/acreditaciones/online')) ? '#' : route('formacion.acreditaciones') . '/online' }}" class="custom-control custom-radio">
                                    <input type="radio" id="radio2" data-id="online" name="radio-btn" class="custom-control-input" {{ (Request::is('formacion/acreditaciones') || Request::is('formacion/acreditaciones/online')) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="radio2">Formación</label>
                                </div>
                            </div>
                        </li>
                    </div>
                    <div class="col-12">
                        @yield('acreditaciones')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection