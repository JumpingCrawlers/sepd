@include('vendor.cookieConsent.modal_aceptar_cookies')

<div class="consentimiento-cookies js-cookie-consent p-3">

    <span class="cookie-consent__message">
        <p class="text-white">
            Este sitio web le informa de que hace uso de cookies propias y de terceros con la 
            finalidad de recopilar datos estadísticos anónimos de uso de la web, así 
            como la mejora del funcionamiento y personalización de la experiencia de 
            navegación del usuario. Si continúa navegando, consideraremos que acepta su uso. 
            Pulse en Aceptar para verificar que está de acuerdo con el uso de Cookies. 
            Para cambiar la configuración u obtener más información sobre Cookies, 
            visite la <a href="/cookies">POLÍTICA DE COOKIES</a> de esta página web.
        </p>
    </span>

    <div class="w-100 text-right">
        <button class="btn js-cookie-consent-agree cookie-consent__agree" {{ getHtmlEstiloBoton() }}>
            Aceptar
        </button>
        <button class="btn js-cookie-consent-agree cookie-consent__agree" {{ getHtmlEstiloBoton() }} id="cookieConfig">
            Configurar Cookies
        </button>
    </div>

</div>

