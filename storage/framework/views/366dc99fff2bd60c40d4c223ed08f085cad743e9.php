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
</style>

<div class="container" style="color: #040C55">
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
                <?php echo e(csrf_field()); ?>

                <div class="form-check mb-2">
                    <div class="row d-flex justify-content-center">
                        <span class="col-6 d-flex align-items-center">Cookies técnicas o necesarias (obligatorio)</span>
                        <div class="col-3">
                            <label class="switch mb-0" for="cookiesTecnicas">    
                            <input class="form-check-input" type="checkbox" value="<?php echo e(config('cookie-consent.cookies-tecnicas')); ?>" id="cookiesTecnicas" checked disabled>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div><div class="form-check mb-2">
                    <div class="row d-flex justify-content-center">
                        <span class="col-6 d-flex align-items-center">Cookies de uso interno</span>
                        <div class="col-3">
                            <label class="switch mb-0" for="cookiesInternas">    
                            <input class="form-check-input" type="checkbox" value="<?php echo e(config('cookie-consent.cookies-internas')); ?>" id="cookiesInternas" checked>
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
                            <input class="form-check-input" type="checkbox" value="<?php echo e(config('cookie-consent.cookies-analiticas')); ?>" id="cookiesAnaliticas" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    <button type="submit" class="btn"<?php echo e(getHtmlEstiloBoton('', '')); ?>>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery) {
            $("#form_cookies").submit(function( event ) {
                event.preventDefault();
        
                let cValue = "";
        
                if($("#cookiesTecnicas").is(':checked')){
                    cValue +="cookiesTecnicas"
                }
                if($("#cookiesInternas").is(':checked')){
                    cValue +="cookiesInternas"
                }
                if($("#cookiesAnaliticas").is(':checked')){
                    cValue +="cookiesAnaliticas"
                }
        
                setCookie('<?php echo e(config('cookie-consent.cookie_name')); ?>', cValue, <?php echo e(config('cookie-consent.cookie_lifetime')); ?>);
        
                checkCookies(cValue);
        
                $('#modalAceptarCookies').modal('toggle');
            });
        
            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value + '; ' + 'expires=' + date.toUTCString() +';path=/<?php echo e(config('session.secure') ? ';secure' : null); ?>';
            }
        
            function checkCookies(cValue) {
        
                if(!cValue.includes('<?php echo e(config('cookie-consent.cookies-internas')); ?>')){
        
                    let nombres = '<?php echo e(json_encode(config('cookie-consent.cookies-internas-nombres'))); ?>';
                    nombres = nombres.replace(/&quot;/g, '');
                    nombres = nombres.replace('[', '');
                    nombres = nombres.replace(']', '');
                    
                    let nombresArray = nombres.split(',');
                    
                    nombresArray.forEach(cName => {
                        setCookie(cName, -1, -1);
                    });
                }
        
                if(!cValue.includes('<?php echo e(config('cookie-consent.cookies-analiticas')); ?>')){
        
                    let nombres = '<?php echo e(json_encode(config('cookie-consent.cookies-analiticas-nombres'))); ?>';
                    nombres = nombres.replace(/&quot;/g, '');
                    nombres = nombres.replace('[', '');
                    nombres = nombres.replace(']', '');
        
                    let nombresArray = nombres.split(',');
        
                    nombresArray.forEach(cName => {
                        setCookie(cName, -1, -1);
                    });
                }            
            }
        
            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for(var i = 0; i <ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
        }
    });

</script><?php /**PATH C:\laragon\www\sepd.es\resources\views/vendor/cookieConsent/form_cookies.blade.php ENDPATH**/ ?>