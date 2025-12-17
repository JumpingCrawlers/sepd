<?php

return [

    /*
     * Use this setting to enable the cookie consent dialog.
     */
        'enabled' => (function_exists('env')) ? env('COOKIE_CONSENT_ENABLED', true) : false,

    /*
     * The name of the cookie in which we store if the user
     * has agreed to accept the conditions.
     */
    'cookie_name' => 'sepd_cookie_consent',

    /*
     * Set the cookie duration in days.  Default is 365 * 20.
     */
    'cookie_lifetime' => 365 * 20,

    /*
    *   Valor de la cookie de configuracion si el usuario acepta todas las cookies
    *   
    */
    'cookie-config-value' => 'cookiesTecnicascookiesInternascookiesAnaliticas',

    /*
    *
    * Nombre de la cookie para saber si el usuario aceptar guardar los valores internos de la aplicacion
    */

    'cookies-internas' => 'cookiesInternas',


    /*
    *
    * Nombres de las cookies incluidas en cookies Internas
    */

    'cookies-internas-nombres' => ['vpcr_data', 'numeeroCGCOM', 'id', 'main_collapsed'],

     /*
    *
    * Nombres de las cookies incluidas en cookies Internas
    */

    'cookies-analiticas-nombres' => ['_ga', '_gat_gtag_UA_15302142_1', '_gid'],

    /*
    *
    * Nombre de la cookie para saber si el usuario acepta las cookies de análisis de la aplicacion
    */

    'cookies-analiticas' => 'cookiesAnaliticas',
        /*
    *
    * Nombre de la cookie para saber si el usuario acepta las cookies obligatorias de la aplicacion
    */

    'cookies-tecnicas' => 'cookiesTecnicas',
];
