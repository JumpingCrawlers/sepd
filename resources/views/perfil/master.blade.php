@extends('puzzle.master')

@section('contenido-detalle')

    @if (isset($editar) && $editar)

        <form method="POST" action="{{ route('perfil') }}" name="form_perfil" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        @if (isset($_GET['previousRoute']))
            <input type="hidden" name="previousRoute" value="{{ $_GET['previousRoute'] }}" />
        @endif

    @endif

    <div class="container">
        @if (isset($editar) && $editar)
            <div class="row mb-3">
                <div class="col-md-4 col-lg-2">
                    <img src="{{ $imagen_avatar }}?v={{ rand() }}" border="0" class="img-fluid" alt="{{ $usuario->nombre_completo }}">
                </div>
                <div class="col-md-8 col-lg-10">
                    <div class="contenido-titulo-pagina color-institucional">
                        {{ $usuario->nombre_completo }}
                        @if (!isset($editar) || !$editar)
                            <br>{{ $usuario->email }}
                        @endif
                    </div>
                    @include('perfil.editar_datos_basicos')
                </div>
            </div>
        @else
            <div class="perfil-container">
                <div class="perfil-container__avatar">
                    <img src="{{ $imagen_avatar }}?v={{ rand() }}" border="0" class="img-fluid" alt="{{ $usuario->nombre_completo }}">
                </div>
                <div class="perfil-container__info">
                    <div>
                        <p>{{ $usuario->nombre_completo }}</p>

                        @if (!isset($editar) || !$editar)
                            <span>
                                {{ $usuario->email }}
                            </span>
                        @endif

                        <span>Socio nº: {{ $usuario->socio ? $usuario->socio->num_socio : 'No es socio' }}</span>
                    </div>
                    @if (!(isset($editar) && $editar))
                        <div>
                            <a role="button" href="/perfil/editar" class="btn" style="color:#ffffff;background-color:#db812e">Editar perfil</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    @yield('detalle-perfil')

    @if (isset($editar) && $editar)
        </form>
    @endif

    <style>
        .perfil-container {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
        }
        .perfil-container__info {
            display: flex;
            flex-direction: column;
            font-size: 1.4rem;
            line-height: 1;
            color: #040b54;
            font-weight: 600;
        }
        .perfil-container__info * {
            margin-bottom: .4rem
        }
        .perfil-container__info span {
            font-size: 1rem;
            display: block;
            font-weight: 400;
            color: #3097d1;
        }
        .perfil-container__avatar {
            height: 120px;
            width: 120px;
            background-color: rgb(202, 202, 202);
            overflow: hidden;
            position: relative;
        }
        .perfil-container__avatar img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            
            max-height: 100%;
            max-width: 100%;
        }
    </style>

    <style>
        .perfil-sections {
            list-style: none;
            margin-left: 0;
            padding-left: 0;
            border-bottom: 2px solid #040b54 !important;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }
        .perfil-sections li {
            margin-bottom: 10px;
        }
        .perfil-sections li .tab {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: none;
            width: 100%;
            padding: .5rem 1rem;
            text-align: left;
            font-size: 1rem;
            font-weight: 500;
            border: 2px solid #040b54;
            
            background-color: #FFF;
            color: #040b54;

        }
        .perfil-sections li .tab svg {
            width: .8rem;
            fill: #040b54;
        }
        .perfil-sections li .tab.active {
            background-color: #040b54;
            color: #FFF;
        }
        .perfil-sections li .tab.active svg {
            fill: #FFF;
        }
    </style>

    <style>
        /* Estilos básicos para los tabs */
        .tab-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;

            border-top: 2px solid #040b54 !important;
            white-space: nowrap;
            overflow: auto;
        }

        .tab-links li {
        }

        .tab-links li a {
            text-decoration: none;
            /* padding: 10px; */
            display: block;
            /* background: #f1f1f1; */
            /* border-radius: 5px; */

            border-radius: 0 0 .7rem .7rem;
            padding: .4rem .4rem !important;
            color: #040b54;
            border-bottom: 2px solid #040b54;
            border-right: 2px solid #040b54;
            border-left: 2px solid #040b54;
            font-weight: 700;
        }

        .tab-links li.active a {
            background-color: #040b54;
            color: #fff;
        }

        .tab-content .tab {
            display: none;
        }

        .tab-content .tab.active {
            display: block;
            padding: 1rem;
        }

    </style>

@endsection

@section('contenido')

    @include('puzzle.contenido')

@endsection
