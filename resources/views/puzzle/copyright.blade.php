@include('vendor.cookieConsent.modal_aceptar_cookies')

<p>
    &copy; 2018  {{ setting('site.title') }}  
    &middot; <a href="{{ request()->getSchemeAndHttpHost() }}/privacidad">Privacidad</a> 
    &middot; <a href="{{ request()->getSchemeAndHttpHost() }}/contacto">Contacto</a>
    &middot; <a href="{{ request()->getSchemeAndHttpHost() }}/mapa_web">Mapa web</a>
    &middot; <a href="{{ request()->getSchemeAndHttpHost() }}/legal">Aviso legal</a>
    &middot; <a href="#" id="modalCookiesButton">Política de Cookies</a>
</p>
<p>
    <span> Última actualización: {{ App\Sitio::ultima_act()}} <span>
</p>    

