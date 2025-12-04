<link href="{{ asset('css/app.css') }}?v=20240131" rel="stylesheet">
<link href="{{ asset('css/sepd.css') }}?v=20240133" rel="stylesheet">
@yield('estilos')

@include('puzzle.menu_principal')

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
                <li class="nav-link activo bg-formacion">
                    <a class="nav-link" target="_self">Solicitud VPC-R</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="menuSecundarioMovil">
            {!! menu($nombre_menu, 'menusepd.bootstrap_sepd_movil', ['listaIdsActivos' => $lista_ids_activos]) !!}
        </div>
    </nav>
</div>

@include('puzzle.breadcrumb')
