<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="Site Description">
    <meta name="author" content="Sociedad Española de Patología Digestiva (SEPD)">
    <link rel="icon" href="https://www.sepd.es/favicon.ico?v=20240102">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="rTdQEqRVhMgBwP1WaWDo4R80oswJ05EbHI1vXNK1">

    <title>Sociedad Española de Patología Digestiva (SEPD)</title>

    <!-- Estilos -->
    <link href="./index_files/app.css" rel="stylesheet">
    <link href="./index_files/sepd.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="./index_files/js(1)"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-15302142-1');

        function alertaCookies() {
            document.getElementById("modalCookiesNecesarias").style.display = 'block';
            document.getElementById("modalCookiesNecesarias").style.backgroundColor = 'rgba(0,0,0,0.5)';            
        }
    </script>
</head>

<body data-spy="scroll" data-target="#contenido-extra" data-offset="10">
    <div class="modal" id="modalCookiesNecesarias" tabindex="-1" role="dialog" aria-labelledby="modalCookiesNecesariasTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-institucional">
                    <h5 class="modal-title text-white text-center" id="modalCookiesNecesariasTitulo">Aceptar Cookies</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><a href="/">&times;</a></span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Necesitamos usar cookies para el correcto funcionamiento de la página. <br>Sin su uso no podrá usar nuestro servicios. <br>Por favor acéptelas.</p>
                    <form id="form_aceptar_cookies">
                        <div class="form-group row justify-content-center">
                            <button onclick="aceptarCookies(event)" type="submit" class="btn btn-cookies">Configurar Cookies</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
        //Añado el archivo config de la cookie
        $config = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/cookies_config'), true); 
    ?>
    <div class="modal" id="modalCookiesConfiguracion" tabindex="-1" role="dialog" aria-labelledby="modalCookiesConfiguracionTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-institucional">
                    <h5 class="modal-title text-white text-center" id="modalCookiesConfiguracionTitulo">Aceptar Cookies</h5>
                </div>
                <div class="modal-body text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <h3>Política de cookies:</h3>
                                <p>
                                    Según el artículo 22 de la Ley de Servicios de la Sociedad de la Información y Comercio Electrónico así como en el Considerando (30) RGPD informamos de que este sitio web utiliza cookies tanto propias como de terceros con diversas finalidades.<br>
                                    Una cookie es un fichero que se descarga en el ordenador/ smartphone/ tablet del usuario cuando éste accede a determinadas páginas web con la finalidad de almacenar y recuperar información sobre la navegación que se efectúa desde dicho equipo.<br>
                                    Para conocer más información sobre las cookies, puede acceder en este <a href="/cookies">enlace</a>.
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 offset-1">
                                <h5>Usted Permite</h5>
                                <form id="form_cookies">
                                <div class="form-check mb-2">
                                    <div class="row d-flex justify-content-center">
                                        <span class="col-6 d-flex align-items-center">Cookies técnicas o necesarias (obligatorio)</span>
                                        <div class="col-3">
                                            <label class="switch mb-0" for="cookiesTecnicas">    
                                            <input class="form-check-input" type="checkbox" value="<?php echo $config['cookies-tecnicas'];?>" id="cookiesTecnicas" checked disabled>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    </div><div class="form-check mb-2">
                                        <div class="row d-flex justify-content-center">
                                            <span class="col-6 d-flex align-items-center">Cookies de uso interno</span>
                                            <div class="col-3">
                                                <label class="switch mb-0" for="cookiesInternas">    
                                                <input class="form-check-input" type="checkbox" value="<?php echo $config['cookies-internas'];?>" id="cookiesInternas" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check mb-3">
                                        <div class="row d-flex justify-content-center">
                                            <span class="col-6 d-flex align-items-center">Cookies de analítica</span>
                                            <div class="col-3">
                                                <label class="switch mb-0" for="cookiesAnaliticas">    
                                                <input class="form-check-input" type="checkbox" value="<?php echo $config['cookies-internas'];?>" id="cookiesAnaliticas" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                </div>
                                    <div class="form-group row justify-content-center">
                                        <button type="submit" onclick="configurarCookies(event)" class="btn btn-cookies">Guardar Cookies</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
    </div>
    <header> 
        <div class="navbar py-0">
            <div class="container justify-content-between pr-0">
                <div class="row w-100 pr-0 position-relative">
                    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 mr-auto pl-0">
                        <div class="navbar-brand d-flex align-items-center">
                            <!-- Logo -->
                            <a href="/">
                                <img src="http://www.sepd.es/storage/settings/January2024/fqg6wgZqtO1CnDihHx6I.png" border="0" class="img-fluid" alt="Sociedad Española de Patología Digestiva (SEPD)" width="260px">
                            </a>

                            <div id="texto-cabecera" class="borde-formacion color-formacion d-flex align-items-center pl-4 pt-3">
                                Solicitud VPC-R
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 pr-0">
                        <div class="row align-items-center">
                            <div class="col redes-sociales">
                                <a href="http://twitter.com/sepdigestiva" target="_blank"><img src="./index_files/bmz11HybC4BQeJh180SO.png" width="25" height="25" border="0"></a>
                                <a href="http://www.youtube.com/saludigestivo" target="_blank"><img src="./index_files/OGKwVhuEdu376kdiJXPs.png" width="25" height="25" border="0"></a>
                                <a href="http://www.linkedin.com/company/sepd" target="_blank"><img src="./index_files/O3EmepiOmzfMzZpqS2hY.png" width="25" height="25" border="0"></a>
                                <a href="http://www.facebook.com/pages/Sociedad-Espanola-de-Patologia-Digestiva/126470870745481?" target="_blank"><img src="./index_files/jJJMZgOHgg7bnjE5puQf.png" width="25" height="25" border="0"></a>
                            </div>

                            <div class="col acceso-usuarios-container bg-gris-fondo px-0">
                                <div class="acceso-usuarios bg-contacto">                          
                                    <button class="navbar-toggler text-nowrap" type="button" onclick="show_drop()" data-target="#perfilUsuario" aria-controls="navbarHeader" aria-expanded="true" aria-label="Opciones de usuario">
                                        Hola, <?php echo get_user_name();?> <span class="caret"></span>
                                    </button>

                                    <div class="dropdown-menu bg-formacion" id="perfilUsuario">
                                        <hr class="borde-formacion-secundario">

                                        <li>
                                            <a href="/perfil" class="text-white">
                                                Mi perfil
                                            </a>
                                        </li>

                                        <hr class="borde-formacion-secundario">

                                        <li>
                                            <a href="/logout" class="text-white">Desconectar</a>
                                        </li>
                                    </div>
                                </div>

                                <div class="hazte-socio bg-gris-fondo text-center text-nowrap px-4">
                                    <a href="/hazte_socio" class="color-formacion">Hazte socio</a>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(!isset($_COOKIE[$config['cookie_name']]) || (isset($_COOKIE[$config['cookie_name']]) && strpos($_COOKIE[$config['cookie_name']], $config['cookies-internas']) === false)){
                    ?>
                    <?php if(!APP_DEBUG) {?>
                        <script>
                            alertaCookies();
                        </script>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <iframe src="/vpc-r/menu_bar" id="iframe_id" frameborder="0" width="100%" style="height: 7em"></iframe>

        <div class="container px-0">
            <div class="row my-2 px-3">
                <div class="col-sm-8 text-left"></div>
            </div>
        </div>
    </header>

    <script>
        window.onload = function() {
            document.getElementById("iframe_id").contentWindow.document.body.onclick = function(e) {
                e.preventDefault();
                // const href = e.path[0].href;
                const href = e.target.href;
                if (href) window.location.href = href;
            }
        }

        function aceptarCookies(e){
            e.preventDefault();
            document.getElementById("modalCookiesConfiguracion").style.display = 'block';
            document.getElementById("modalCookiesConfiguracion").style.backgroundColor = 'rgba(0,0,0,0.5)';
            document.getElementById("modalCookiesNecesarias").style.display = 'none';
        }

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function configurarCookies(e){
            e.preventDefault();
            let cValue = "";

            if(document.getElementById('cookiesTecnicas').checked == true){
                cValue +="cookiesTecnicas"
            }
            if(document.getElementById('cookiesInternas').checked == true){
                cValue +="cookiesInternas"
            }
            if(document.getElementById('cookiesAnaliticas').checked == true){
                cValue +="cookiesAnaliticas"
            }

            setCookie("<?php echo $config['cookie_name'];?>", cValue, <?php echo $config['cookie_lifetime'];?>);

            checkCookies(cValue);

            location.reload();
        }

        function checkCookies(cValue) {
            if(!cValue.includes("<?php echo $config['cookies-internas'];?>")){
                <?php $cookiesInternas = json_encode($config['cookies-internas-nombres']); ?>
                let nombres = <?php echo $cookiesInternas; ?>;
                
                nombres.forEach(cName => {
                    setCookie(cName, -1, -1);
                });
            }

            if(!cValue.includes("<?php echo $config['cookies-analiticas'];?>")){

                <?php $cookiesAnaliticas = json_encode($config['cookies-analiticas-nombres']); ?>
                let nombres = <?php echo $cookiesAnaliticas; ?>;
                
                nombres.forEach(cName => {
                    setCookie(cName, -1, -1);
                });
            }            
        }

                
    </script>

<style>
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.btn-cookies{
    background-color: #c2c2c2;
}
</style>