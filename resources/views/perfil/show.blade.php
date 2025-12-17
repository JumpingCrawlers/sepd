@extends('perfil.master')

@php
    if (Storage::disk(config('voyager.storage.disk'))->exists("/perfil/{$usuario->id}/{$usuario->id}.jpg"))
        $imagen_avatar = "/storage/perfil/{$usuario->id}/{$usuario->id}.jpg";
    else 
        $imagen_avatar = "/images/profile.webp";
    $tab = isset($tab) ? trim($tab) : null;
@endphp

@section('detalle-perfil')
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-lg-3 mb-3 ">
                <ul class="perfil-sections">
                    <li>
                        <a href="{{ route('perfil') }}" data-tab="data-tab-1" class="tab {{ is_null($tab) || Request::is('perfil/datos-*') ? 'active' : '' }}" >
                            Mi Perfil
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perfil', ['tab' => 'perfil-datos-de-registro']) }}" type="button" data-tab="data-tab-3" class="tab {{ Request::is('perfil/perfil-datos-de-registro') ? 'active' : '' }}">
                            Datos de registro
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perfil', ['tab' => 'cursos-realizados']) }}" type="button" data-tab="data-tab-3" class="tab {{ Request::is('perfil/cursos-realizados') ? 'active' : '' }}">
                        {{-- <button type="button" data-tab="data-tab-3" class="tab {{ Request::is('perfil/cursos-realizados') ? 'active' : '' }}" onclick="setUrlPath('perfil/cursos-realizados')"> --}}
                            Cursos realizados
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perfil', ['tab' => 'otras-actividades']) }}" type="button" data-tab="data-tab-4" class="tab {{ Request::is('perfil/otras-actividades') ? 'active' : '' }}">
                        {{-- <button type="button" data-tab="data-tab-4" class="tab {{ Request::is('perfil/otras-actividades') ? 'active' : '' }}" onclick="setUrlPath('perfil/otras-actividades')"> --}}
                            Otras actividades
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-8 col-lg-9 pl-3">
                <div class="data-tab-content" id="data-tab-1"
                    @if (!(is_null($tab) || Request::is('perfil/datos-*')))
                        style="display: none;"
                    @endif
                    >
                    <div class="tabs">
                        <ul class="tab-links">
                            <li class="tab {{ is_null($tab) || Request::is('perfil/datos-personales')  || !Request::is('perfil/datos*')   ? 'active' : '' }}"><a href="#tab_datos_personales">Datos personales</a></li>
                            <li class="{{ Request::is('perfil/datos-profesionales') ? 'active' : '' }}"><a href="#tab_datos_profesionales">Datos profesionales</a></li>
                            <li class="{{ Request::is('perfil/datos-informacion-profesional') ? 'active' : '' }}"><a href="#tab_datos_informacion_profesional">Datos de interés</a></li>
                            <li class="{{ Request::is('perfil/datos-residencia') ? 'active' : '' }}"><a href="#tab_datos_residencia">Especialización / MIR</a></li>
                            <li class="{{ Request::is('perfil/datos-experiencia-profesional') ? 'active' : '' }}"><a href="#tab_datos_experiencia">Experiencia profesional</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab_datos_personales" class="tab {{ is_null($tab) || Request::is('perfil/datos-personales*') || !Request::is('perfil/datos*') ? 'active' : '' }}">
                                @include('perfil.datos_personales')
                            </div>
                            <div id="tab_datos_profesionales" class="tab {{ Request::is('perfil/datos-profesionales*') ? 'active' : '' }}">
                                @include('perfil.datos_profesionales')
                            </div>
                            <div id="tab_datos_informacion_profesional" class="tab {{ Request::is('perfil/datos-informacion-profesional') ? 'active' : '' }}">
                                @include('perfil.datos_informacion_profesional')
                            </div>
                            <div id="tab_datos_residencia" class="tab {{ Request::is('perfil/datos-residencia') ? 'active' : '' }}">
                                @include('perfil.datos_residencia')
                            </div>
                            <div id="tab_datos_experiencia" class="tab {{ Request::is('perfil/datos-experiencia-profesional') ? 'active' : '' }}">
                                @include('perfil.datos_experiencia')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-tab-content" id="data-tab-3"
                    @if (!(Request::is('perfil/perfil-datos-de-registro')))
                        style="display: none;"
                    @endif
                    >
                    @include('perfil.datos-de-registro')
                </div>
                <div class="data-tab-content" id="data-tab-3"
                    @if (!(Request::is('perfil/cursos-realizados')))
                        style="display: none;"
                    @endif
                    >
                    @include('perfil.historial_acreditaciones')
                </div>
                <div class="data-tab-content" id="data-tab-4"
                    @if (!(Request::is('perfil/otras-actividades')))
                        style="display: none;"
                    @endif
                    >
                    @include('perfil.otras_actividades')
                </div>
            </div>
        </div>
        <script>
            function setUrlPath (path) {
                const url = new URL(window.location.href);
                url.pathname = path;
                window.history.replaceState(null, '', url.toString());
            }
        </script>
    </div>
@endsection