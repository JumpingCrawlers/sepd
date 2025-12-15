<div class="navbar py-0">
    <div class="container justify-content-between lg-pr-0">
        <div class="row position-relative">

            {{-- Mensajes flash, multiusos --}}
            @if ($flash && $flash['tipo'] == 'mensaje')

            <div id="alerta-flash-mensaje" class="alert alert-success fade show" role="alert"> {!! $flash['mensaje'] !!} </div>

            @elseif ($flash && $flash['tipo'] == 'alerta')

            <div id="alerta-flash-alerta" class="alert alert-warning fade show" role="alert"> {!! $flash['mensaje'] !!} </div>

            @elseif ($flash && $flash['tipo'] == 'error')

            <div id="alerta-flash-error" class="alert alert-danger fade show" role="alert"> {!! $flash['mensaje'] !!} </div>

            @endif
            {{-- Fin alertas --}}

            <style>
                .tooltip-container {
                    position: relative;
                    display: inline-block;
                    cursor: help;
                }
                .tooltip-text {
                    visibility: hidden;
                    width: 120px;
                    background-color: #7400BB;
                    color: #fff;
                    text-align: center;
                    border-radius: 6px;
                    padding: 5px;
                    position: absolute;
                    z-index: 1;
                    bottom: -90%;
                    left: 50%;
                    transform: translateX(-50%);
                    opacity: 0;
                    transition: opacity 0.3s;
                }
                .tooltip-container:hover .tooltip-text {
                    visibility: visible;
                    opacity: 1;
                }
            </style>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mr-auto pr-0">
                <div class="row">
                    <!-- enlaces -->
                    <div class="container pt-2 spacebt bg-custom">
                        <ul class="buttonsleft">
                            <li class="active"><a href="/" class="linkcab">PROFESIONALES</a></li>
                            <li><a href="https://gruposdigestivosepd.es/" class="linkcab">GRUPOS DE TRABAJO</a></li>
                            <li><a href="https://www.saludigestivo.es/" class="linkcab">POBLACION</a></li>
                        </ul>
                    </div>
                    <div class="container">
                        <!-- Logo -->
                        <a href="/" >
                            <img src="{{ Voyager::image(setting('site.logo')) }}" border="0" class="img-fluid" alt="{{ setting('site.title') }}" width="260px">
                        </a>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mx-auto pl-0 ">
                <div class="row py-2 d-none d-sm-none d-md-block bg-custom">
                    <div class="container mr-0 pr-0 padtop">
                    <form action="/buscar" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="input-group w-100 bg-white searched">
                            @include('puzzle.buscador', ['tamanyo' => ''])
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mx-auto pr-0">
                <div class="row align-items-center d-flex flex justify-content-end w-full w-100">
                    
                    <div class="acceso-usuarios-container px-2 | flex d-flex py-2 bg-custom w-full w-100 rounded-0">
                        <div class="position-relative layout-login d-flex align-items-center justify-content-end sm-justify-content-center">
                            <a href="javascript:void(0)" id="btnLogin" class="dropdown-toggle navbar-toggler btnperfil" type="button" data-toggle="dropdown" data-target="#perfilUsuario" aria-controls="navbarHeader" aria-expanded="false" aria-label="Opciones de usuario" style="margin: 0 !important;padding: 8px 6px !important;font-size: 12px;">
                                @guest
                                <i class="fa fa-arrow-alt-circle-right"></i> LOGIN
                                @else
                                <i class="fa fa-arrow-alt-circle-down"></i> LOGOUT
                                @endguest 

                            </a>
                            <div class="dropdown-menu dropdown-menu--header" id="perfilUsuario">
                                    @guest
                                        <li>
                                            <a href="/login" data-toggle="modal" data-target="#modalLogin">Iniciar sesión</a>
                                        </li>
                                        <li>
                                            <a href="/registro-usuario">Nuevo usuario</a>
                                        </li>
                                        
                                        
                                    @else
                                        <li>
                                            <a href="/perfil">Mi perfil</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Desconectar
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                        
                                    @endguest
                                </div>
                            @if(!$esSocio)    
                            <a href="/hazte_socio" class="btnperfil">
                                <i class="fa fa-user"></i> + HAZTE SOCIO
                            </a>
                            @else
                            <a href="/hazte_socio" class="btnperfil">
                                <i class="fa fa-user"></i> ERES SOCIO
                            </a>
                            @endif
                            
                            <a href="/contacto" class="btnperfil">
                                <i class="fa fa-envelope"></i> CONTACTO
                            </a>        
                        </div>
                        
                    </div>
                    <div class="redes-sociales">
                        @include('puzzle.redes_sociales')
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('btnLogin').addEventListener('click', function() {
    const menu = document.getElementById('perfilUsuario');
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
});
</script>
<style>
    .dropdown-menu--header {
        border: 1px solid #333;
        border-radius: 0px;
        padding: 12px;
    }
    .dropdown-menu--header a {
        font-family: Archivo-Light, sans-serif;
        color: #040b54;
    }
    .linkcab{
        color: #2c2c2c;
        text-decoration: none;
        font-size: 12px;
        padding: 0px 12px;
    }
    .linkcab:hover{
        color: #040b54;
        text-decoration: none;
    }
    .spacebt{
        justify-content: left !important;
        font-family: Archivo-Light, sans-serif;
    }
    .layout-login{
        width: 100%;
        font-family: Archivo-Light, sans-serif;
    }
    
    .btnperfil:hover,.hovered{
        color: #fff;
        background-color: #4A09AC;
        border-radius: 5px;
    }
    .layout-login a:hover{
       text-decoration: none;
    }
    .bg-custom{
        background-color: #f6f6f6;
        height: 53px;
    }
    .searched{
        padding-right: 10px;
        border-bottom: 1px solid #000;
    }
    .searched input,.searched button{
        border-bottom: 0px;
    }
    .padtop{
        padding-top: 5px;
    }
    .buttonsleft{
        display: flex;
        list-style: none;
        padding-left: 0;
        height: 100%;
        margin-bottom: 0;

    }
    .active{
        border-radius: 10px 10px 0px 0px;
        background-color: #fff;
    }
    .buttonsleft li:hover{
        border-radius: 15px 15px 0px 0px;
        background-color: #fff;
    }
    .buttonsleft li{
        padding-top: 6px;

    }
    .btnperfil{
        text-align: center;
        padding: 5px 6px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        font-size: 12px;
        color: #808080;
        margin:0px 2px;
    }
    .hovered{
        color: #fff;
    }

</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
      const btn = document.getElementById('btnLogin');
      const menu = document.getElementById('perfilUsuario');
      let isOpen = false;
      let isPinned = false; // se activa cuando haces clic
      let closeTimeout;

      function openMenu() {
        
        menu.classList.add('show');
        btn.classList.add('hovered');
        isOpen = true;
      }

      function closeMenu() {
        
        menu.classList.remove('show');
        btn.classList.remove('hovered');
        isOpen = false;
        isPinned = false;
      }

      function scheduleClose() {
        if (isPinned) return; // no cerrar si fue fijado por clic
        
        
          if (!isPinned) closeMenu();
        
      }

      // Hover abre el menú
      btn.addEventListener('mouseenter', openMenu);
      menu.addEventListener('mouseenter', openMenu);

      // Hover fuera lo cierra si no está “fijado”
      btn.addEventListener('mouseleave', scheduleClose);
      menu.addEventListener('mouseleave', scheduleClose);

      // Clic alterna “fijar” el menú
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (isPinned) {
          closeMenu();
        } else {
          openMenu();
          isPinned = true; // fijar abierto hasta clic fuera
        }
      });

      // Cerrar si se hace clic fuera
      document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
          closeMenu();
        }
      });
    });

</script>