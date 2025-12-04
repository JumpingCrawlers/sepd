<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ setting('site.description') }}">
    <meta name="author" content="{{ setting('site.autor') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20240103">
    <!-- CSRF Token. -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

    <title>{{ setting('site.title') }}</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}?v=20240132" rel="stylesheet">
    <link href="{{ asset('css/sepd.css') }}?v=20240133" rel="stylesheet">
    
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' />
    <link href="{{ asset('css/calendario.css') }}" rel="stylesheet">

    @yield('estilos')


    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if ((isset($_COOKIE[config('cookie-consent.cookie_name')]) && strpos($_COOKIE[config('cookie-consent.cookie_name')], config('cookie-consent.cookies-analiticas')) !== false) || 
        !isset($_COOKIE[config('cookie-consent.cookie_name')]))
            {{-- Añado el script de analitics si el usuario lo permite o no esta creada la cookie de consentimiento --}}
            
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-15302142-1"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                
                gtag('config', 'UA-15302142-1');
            </script>

    @endif             
       <!-- Google tag (gtag.js) -->
       <script async src="https://www.googletagmanager.com/gtag/js?id=G-9FCD78G8ZK"></script>
       <script>
           window.dataLayer = window.dataLayer || [];
           function gtag(){dataLayer.push(arguments);}
           gtag('js', new Date());
   
           gtag('config', 'G-9FCD78G8ZK');
       </script>


    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PB54WWFR');</script>
    <!-- End Google Tag Manager -->
    
</head>
<body data-spy="scroll" data-target="#contenido-extra" data-offset="10">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PB54WWFR"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <header>
    @php
        // Si hay página, recuperar el menu de ahí
        // Válido para todas las páginas. Páginas con tratamiento especial incluyen la variable $nombre_menu
        if (isset($pagina)) { 
            $nombre_menu = $pagina->menu->name;
        } else {
            $nombre_menu = '';
        }

        $user = auth()->user();
        if($user && $user->es_socio_activo()){
            $esSocio = true;
        }else{
            $esSocio = false;
        }
    @endphp

    @include('puzzle.cabecera', ['flash' => $flash])
    @include('puzzle.menu_principal', ['esSocio' => $esSocio])
    @include('puzzle.menu_seccion')
    @include('puzzle.breadcrumb')
    
    </header>

    <main role="main">
        @if(session()->has('warning'))
            <div class="container px-0 mb-3">
                <div class="alert alert-warning" role="alert">
                    {{session()->get('warning')}}
                </div>
            </div>
        @endif
        @yield('slider')
        
        @yield('pastillas')
        
        {{-- Mostramos el contenido primero o la pastilla destacados en función del valor asignado en el back  --}}
        @if (isset($pagina) && isset($pagina->posicion_destacados) && $pagina->posicion_destacados)
            @yield('contenido')
            @yield('destacados')  
        @else
            @yield('destacados')
            @yield('contenido')
        @endif
        
    </main>

    @include('puzzle.pie')
    
    <!-- Scripts -->
    <style>
        .content-noticias {
            box-sizing: border-box;
            overflow: hidden;
            padding: 0;
            margin: 0;
        }
        .content-noticias div {
            box-sizing: border-box;
            display: block;
            padding: 0;
            margin: 0;
        }
        .noticia{
            font-size: 14px;
        }
        .navbar.gyc {
            border-top: 2px solid #FFC600;
        }
        .navbar.gyc a,
        .color-gyc{
            color: #FFC600 !important;
        }
        .navbar.gyc li.bg-gyc a {
            color: white !important;
        }
        .bg-gyc,
        .pastilla .card-header.gyc {
            background-color: #FFC600 !important;
        }

    </style>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
        {{-- 3 ways - Euro Fuenmayor -------------------------------------}}
        {{-- Agregada nueva función para el scroll auto de las noticias --}}
        {{---Ajustados funciones asociadas y estilos----------------------}}
        let scrollNoticiasInterval;

        $( document ).ready(function() {
            let urlEle = $("a[href^='https://manualdigestivosepd.es/loginin/?token']");
            // let urlEle = $('a[href*="sepd-sso-plataforma-externa"]');
            
            @if(\Illuminate\Support\Facades\Auth::check() && \App\UsuarioSocio::where('usuario_id', \Illuminate\Support\Facades\Auth::id())->first())
                if(urlEle.length > 0){
                    urlEle.attr('href', 'https://manualdigestivosepd.es/loginin/?token={{( $sso_token ?? '')}}')
                    urlEle.attr('target', '__blank')
                    urlEle.removeClass('no-auth-sso')
                }
            @else
                if(urlEle.length > 0) {
                    urlEle.addClass('no-auth-sso')
                    $(document).on('click', '.no-auth-sso', function (event) {
                        event.preventDefault();
                        $('#modalSSONoAuth').modal('show');
                    });
                }
            @endif
            $('.event-no-auth-sso').on('click', function (event) {
                event.preventDefault();
                $('#modalSSONoAuthLogin').modal('show');
            });

            if ( $('.content-noticias .noticia').length > 6 ){
                scrollNoticias();
            }
            function scrollNoticias() {
                let $delay = 1000;
                let $content = $('.content-noticias');
                let $first_height = $content.find('.noticia:first').height();
                let $paddings = parseInt($content.find('.noticia:first').css('padding-top')) + parseInt($content.find('.noticia:first').css('padding-bottom'));
                let $margins = parseInt($content.find('.noticia:first').css('margin-top')) + parseInt($content.find('.noticia:first').css('margin-bottom'));
                let $animation = $delay - 40;
                $content.stop().animate({
                    scrollTop: $first_height + $paddings + $margins
                }, $animation, 'linear', function () {
                    $(this).scrollTop(0).find('.noticia:last').after($('.noticia:first', this));
                });
                scrollNoticiasInterval = setInterval(function () {
                    $first_height = $content.find('.noticia:first').height();
                    $paddings = parseInt($content.find('.noticia:first').css('padding-top')) + parseInt($content.find('.noticia:first').css('padding-bottom'));
                    $margins = parseInt($content.find('.noticia:first').css('margin-top')) + parseInt($content.find('.noticia:first').css('margin-bottom'));
                    $animation = $delay - 40;
                    $content.stop().animate({
                        scrollTop: $first_height + $paddings + $margins
                    }, $animation, 'linear', function () {
                        $(this).scrollTop(0).find('.noticia:last').after($('.noticia:first', this));
                    });
                }, $delay);
            }
            //Cuando sacas el ratón del div se activa
            $( ".content-noticias" ).mouseout(function() {
                if ( $('.content-noticias .noticia').length > 6 ){
                    scrollNoticias();
                }
            });
            //Cuando pasas el ratón por encima del div para
            $( ".content-noticias" ).mouseover(function() {
                $('.content-noticias').stop();
                clearInterval(scrollNoticiasInterval);
            });

            {{-----------------------------------------------}}
            {{-- Marcar la opción actual como seleccionada --}}
            {{-----------------------------------------------}}
            // solo si existe algun grupo izquierda
            // exceptuando la biblioteca
            if ($('.grupo-menu-izquierda').first().length > 0) {
                marcaOpcion();
            }

            {{--------------------------------------------------}}
            {{-- Función para volver arriba. Se carga siempre --}}
            {{--------------------------------------------------}}
            $(window).scroll(function() {
                if ($(this).scrollTop() >= 450) {        // If page is scrolled more than 450px
                    $('#return-to-top').fadeIn(200);    // Fade in the arrow
                } else {
                    $('#return-to-top').fadeOut(200);   // Else fade out the arrow
                }
            });
            $('#return-to-top').click(function() {      // When arrow is clicked
                $('body,html').animate({
                    scrollTop : 0                       // Scroll to top of body
                }, 500);
            });
            
            {{----------------------------------------------------------------}}
            {{-- Click en menú secundario, evitar que menú quede desplegado --}}
            {{-- ATENCIÓN: Solución temporal                                --}}
            {{----------------------------------------------------------------}}
            $('#menuSecundario .dropdown-toggle').on('click', function (e) {
                e.stopPropagation();
                e.preventDefault();
            });

            {{----------------------------}}
            {{-- Ocultar mensajes flash --}}
            {{----------------------------}}
            @if ($flash)
 
            setTimeout(function() {
                $('div[id^="alerta-flash-"]').alert('close');
            }, 2500);

            @endif

            {{-- 3ways Euro Fuenmayor - Logica para renderizar modal de navidad en función de logica según la fecha  --}}

            @if (isset($felicitacion_navidad) && $felicitacion_navidad == 'on')
                @if (isset($pagina) && $pagina->slug == 'inicio')
                    // $('#modalNavidad').modal('show');
                    // setTimeout(function() {
                        // $('#modalNavidad').modal('hide');
                    // }, 8000);
                @endif
            @endif



        });
    </script>
    {{-- Gestión de Login: si hay conexión en cabecera --}}
    @if (! isset($cabecera_sin_acceso_usuarios))

        @guest

            {{-- Si no hay usuario conectado, cargar modal --}}
            @include('puzzle.modal_login')

            {{-- si hubo error de conexión, mostrar modal --}}
            @if (isset($errors) && $errors->has('email') && ! isset($cabecera_sin_acceso_usuarios))
                <script>
                    $(window).on('load',function(){        
                        $('#modalLogin').modal('show');
                    });
                </script>
            @endif

        @else

            {{-- usuario conectado (pendiente de confirmación--}}
            @include('puzzle.modal_user')

        @endguest

    @endif
    
    @include('puzzle.modal_video')
    @include('puzzle.modal_imagen')

    {{-- 3ways Euro Fuenmayor - Logica para renderizar modal de navidad en función de logica según la fecha  --}}
    @if (isset($felicitacion_navidad) && $felicitacion_navidad == 'on')
        @include('puzzle.modal_navidad')
    @endif

    {{-- 3ways Euro Fuenmayor - SSO: Mensaje  para usuarios no logeados  --}}
    @include('puzzle.modal_sso_no_auth')

    @include('cookieConsent::index')

    <script>
        $('#cookieConfig , #modalCookiesButton').on('click',function(){        
            $('#modalAceptarCookies').modal('show');
        });

        $(window).on('load',function(){        
            $('#modalChatBot').modal('show');
        });

        $(document).ready(function() {
            $('a[data-target="#openModalRedirect"]').on('click', function(e) {
                console.log('openModalRedirect');
                e.preventDefault(); // Evitar la navegación predeterminada del enlace
                
                // Obtener la URL del atributo data-url
                var url = $(this).data('url');
                
                // Cambia el action del formulario con la URL obtenida
                $('#modal-form-login').attr('action', url);
                $('#modalLogin').modal('show');
            });
        });
    </script>
    
    <script>
        $(document).ready(function() {
            function initTabs(tabContainer) {
                // Activar el primer tab por defecto
                var $tabContainer = $(tabContainer);
                
                $tabContainer.find('.tab-links li a').on('click', function(e) {
                    e.preventDefault();
                    
                    var $this = $(this);
                    var currentAttrValue = $this.attr('href');
                    
                    // Activar el tab correspondiente
                    $tabContainer.find('.tab').removeClass('active');
                    $tabContainer.find(currentAttrValue).addClass('active');
                    
                    // Cambiar el estado de los links
                    $tabContainer.find('.tab-links li').removeClass('active');
                    $this.parent().addClass('active');
                });
            }

            // Inicializar los tabs para todas las secciones
            $('.tabs').each(function() {
                initTabs(this);
            });
        });

        // Obtener todos los botones
        const buttons = document.querySelectorAll('button[data-tab]');

        // Agregar un evento click a cada botón
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                console.log('addEventListener.click');
                // Obtener el ID del contenido que se debe mostrar
                const tabId = button.getAttribute('data-tab');
                
                buttons.forEach(btn => btn.classList.remove('active'));

                // Añadir la clase 'active' al botón clickeado
                button.classList.add('active');
    
                // Ocultar todos los contenidos
                document.querySelectorAll('.data-tab-content').forEach(content => {
                    content.style.display = 'none';
                });
                
                // Mostrar el contenido correspondiente al botón clickeado
                document.getElementById(tabId).style.display = 'block';
            });
        });
    </script>

    
    @if (request()->is('inicio'))
        @includeIf('puzzle.popup')
    @endif
    
</body>
</html>
