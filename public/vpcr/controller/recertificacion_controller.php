<?php 

/**
 * Controlador de la navegacion del usuario
 * al llegar a la venta de colegiado.
 *
 * @return void
 */
function recertificacion_controller(){
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

    if (started_request()):
        header("Location: listado.php");
    elseif (valid_cnum() && !$action):
        header("Location: confirmacion.php");
    endif;

    $url = parse_url($_SERVER['REQUEST_URI']);
    $url = $url['path'];
    
    if (!$action) return;
    switch ($action) {
        // Actions
    } 

    $url = removeqsvar($url, "action");   
    // header("Location: {$url}");
}

/**
 * Funcion que se asegura que el usuarios que navego a esta ventana tenga
 * un numero de colegiado valido
 *
 * @return void
 */
function check()
{
    if (isset($_COOKIE["numeeroCGCOM"]) && $_COOKIE["numeeroCGCOM"] == 1)
    {
        header("Location: listado.php");
    }
    else{
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url = $url['path'];
        $url = removeqsvar($url, "action");   
        header("Location: ".$url."action=invalid_number");
    }
}

/**
 * Funcion que verifica que el numero que usuario envio 
 * sea un numero valido.
 *
 * @return void
 */
function correct_number($status, $returnResponse = false)
{
    if(APP_DEBUG){return true;}
    // if (strpos($status, 'true') !== false)
    if ($status === true) 
    {
        $config = include '..\..\..\config\cookie-consent.php';
        if(isset($_COOKIE[$config['cookie_name']]) && strpos($_COOKIE[$config['cookie_name']], $config['cookies-internas']) !== false){
            //Compruebo que la configuracion de cookies permite guardar la info del usuario            
            $cookie_name = "numeeroCGCOM";
            $cookie_value = 1;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        }
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url = $url['path'];
        $url = removeqsvar($url, "action");
               
        if ($returnResponse) {
            return true;
        }
    }
    // else if (strpos($status, 'false') !== false){
    else if ($status === false) {
        if(isset($_COOKIE[$config['cookie_name']]) && strpos($_COOKIE[$config['cookie_name']], $config['cookies-internas']) !== false){
            $cookie_name = "numeeroCGCOM";
            $cookie_value = 0;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        }
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url = $url['path'];
        $url = removeqsvar($url, "action");
        if ($returnResponse) return false;
        header("Location: colegiado.php?action=invalid_number");
    }
    else{
        if(isset($_COOKIE[$config['cookie_name']]) && strpos($_COOKIE[$config['cookie_name']], $config['cookies-internas']) !== false){
            $cookie_name = "numeeroCGCOM";
            $cookie_value = 0;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        }
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url = $url['path'];
        $url = removeqsvar($url, "action");
        if ($returnResponse) return false;
        header("Location: colegiado.php?action=no_conection");
    }
}