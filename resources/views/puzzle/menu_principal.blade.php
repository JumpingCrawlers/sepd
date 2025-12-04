@php
    $nombre_menu = $nombre_menu ?? '';
@endphp
<nav class="navbar navbar-expand-lg navbar-light pb-0">

    <div id="menu-principal" class="container navbar navbar-expand-lg py-0 mb-0">

        <button class="navbar-toggler my-2" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="row d-block d-sm-block d-md-none w-75">
            <div class="container float-right pr-1">
            <form action="/buscar" method="GET" role="search">
                {{-- {{ csrf_field() }} --}}
                <div class="input-group w-100">
                    @include('puzzle.buscador', ['tamanyo' => ''])
                </div>
            </form>
            </div>
        </div>
        
        <div id="navbarSupportedContent" data-test="navbarSupportedContent" class="row text-nowrap h-100 collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item active position-relative">
                    <a class="nav-link mx-auto" href="/">Institucional</a>
                    <div class="position-absolute flecha-menu-principal institucional @if($nombre_menu != 'institucional') d-none @else d-none d-lg-block @endif"></div>
                </li>
                <li class="nav-item position-relative">
                     <a class="nav-link mx-auto" href="/formacion">Formación</a>
                     <div class="position-absolute flecha-menu-principal formacion @if($nombre_menu != 'formacion') d-none @else d-none d-lg-block @endif"></div>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="/investigacion">Investigación</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="/inicio_gyc">Gestión y Calidad</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="http://www.congresosepd.com" target="_blank">Congreso SEPD</a>
                    <div class="position-absolute flecha-menu-principal congreso @if($nombre_menu != 'congreso') d-none @else d-none d-lg-block @endif"></div>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="https://reed.es" target="_blank">Revista REED</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="/publicaciones">
                        Publicaciones
                    </a>
                    <div class="position-absolute flecha-menu-principal publicaciones @if($nombre_menu != 'publicaciones') d-none @else d-none d-lg-block @endif"></div>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="/comunicacion">
                        Comunicación
                    </a>
                    <div class="position-absolute flecha-menu-principal prensa @if($nombre_menu != 'comunicacion') d-none @else d-none d-lg-block @endif"></div>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="https://www.saludigestivo.es/" target="_blank">Población y pacientes</a>
                </li>
                <!--li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="/enfermeria">
                        Enfermería
                    </a>
                    <div class="position-absolute flecha-menu-principal prensa @if($nombre_menu != 'Enfermería') d-none @else d-none d-lg-block @endif"></div>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link mx-auto" href="/contacto">Contacto</a>
                    <div class="position-absolute flecha-menu-principal contacto @if($nombre_menu != 'contacto') d-none @else d-none d-lg-block @endif"></div>
                </li-->
            </ul>
        </div>
    </div>
</nav>