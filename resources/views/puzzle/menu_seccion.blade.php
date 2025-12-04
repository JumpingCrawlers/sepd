@php
    $nombre_menu = $nombre_menu ?? '';
    
@endphp
<div class="container px-0">
    <nav class="navbar navbar-expand-lg navbar-light p-0 {{ $nombre_menu }}">
        
        <a class="navbar-toggler border-0 submenu-text pt-2" data-toggle="collapse" data-target="#menuSecundarioMovil" aria-controls="menuSecundarioMovil" aria-expanded="false" aria-label="Toggle navigation">
            @php
            if (isset($pagina)) {
                $menu_display_name = $pagina->menu->role->display_name;
                $menu_name = $pagina->menu->name;
            } else {
                $menu_display_name = ucfirst($nombre_menu);
                $menu_name = $nombre_menu;
            }
            @endphp
            {{ $menu_display_name }} &nbsp; <span class="flecha {{ strtolower($menu_name) }} float-right mt-1"></span>
        </a>

        {{-- Comprobar que se llama con una página para recuperar el menú. Si no, vacío --}}
        @php
            if (isset($pagina)) {
                $lista_ids_activos = $pagina->lista_ids_activos;
            } else {
                $lista_ids_activos = '';
            }
        @endphp
        {{-- Se crea el menú para "ordenador" y otro para moviles, sin hover --}}
        <div class="collapse navbar-collapse" id="menuSecundario">
            <ul class="navbar-nav mr-auto" role="menu">

                @guest
                    {!! menu($nombre_menu, 'menusepd.bootstrap_sepd', ['listaIdsActivos' => $lista_ids_activos]) !!}
                @endguest

                @auth
                    @if (isset($pagina) && (isset($formacion) && $formacion || !is_null($pagina) && $pagina->slug == "cursos"))
                        <li class="nav-link {{ (($pagina->slug == "aula") ? 'activo bg-formacion' : '' ) }}">
                            <a class="nav-link" href="{{ (($pagina->slug == "aula") ? '#!' : route('formacion.aula')) }}" target="_self">Aula virtual</a>
                        </li>
                        <li class="nav-link {{ (($pagina->slug == "mis_cursos") ? 'activo bg-formacion' : '' ) }}">
                            <a class="nav-link" href="{{ route('formacion.mis-cursos') }}" target="_self">Mis cursos</a>
                        </li>
                        <li class="nav-link {{ (($pagina->slug == "formacion") ? 'activo bg-formacion' : '' ) }}">
                            <a class="nav-link" href="{{ (($pagina->slug == "formacion") ? '#!' : route('mensajes.usuario')) }}" target="_self">Mensajes con tutores</a>
                        </li>
                        <li class="nav-link {{ (($pagina->slug == "cursos") ? 'activo bg-formacion' : '' ) }}">
                            <a class="nav-link" href="{{ (($pagina->slug == "cursos") ? '#!' : route('cursos')) }}" target="_self">Cursos disponibles</a>
                        </li>
                        <li class="nav-link {{ (($pagina->slug == "historial_acreditaciones") ? 'activo bg-formacion' : '' ) }}">
                            <a class="nav-link" href="{{ (($pagina->slug == "historial_acreditaciones") ? '#!' : route('formacion.acreditaciones')) }}" target="_self">Historial de acreditaciones</a>
                        </li>
                        <li class="nav-link {{ (($pagina->slug == "calculadoras") ? 'activo bg-formacion' : '' ) }}">
                            <a class="nav-link" href="{{ (($pagina->slug == "calculadoras") ? '#!' : route('formacion.calculadora')) }}" target="_self">Calculadoras</a>
                        </li>
                    @else
                        {!! menu($nombre_menu, 'menusepd.bootstrap_sepd', ['listaIdsActivos' => $lista_ids_activos]) !!}
                    @endif
                @endauth
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="menuSecundarioMovil">
            {!! menu($nombre_menu, 'menusepd.bootstrap_sepd_movil', ['listaIdsActivos' => $lista_ids_activos]) !!}
        </div>
    </nav>
    <style>
        /* .navbar.comunicacion .navbar-nav.mr-auto { */
        .navbar.comunicacion ul.navbar-nav[role="menu"] {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            width: 100%;
            line-height: 1;
            font-size: .8rem;
            text-align: center;
        }

        .navbar.comunicacion ul.navbar-nav[role="menu"] li:nth-child(-n+6) {
            border-bottom: 1px solid #4e25cc;
            padding-bottom: .2rem;
            padding-top: .2rem;
        }
        .navbar.comunicacion ul.navbar-nav[role="menu"] li.nav-link {
            padding: .3rem .2rem !important;
        }

    </style>
</div>
