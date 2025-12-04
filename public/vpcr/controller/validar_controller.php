<?php 

/**
 * Controlador de la navegacion del usuario
 * al llegar a la venta de validacion.
 *
 * @return void
 */
function validar_controller(){
    if (!started_request()):
        header("Location: confirmacion.php");
    elseif (!valid_cnum() && !started_request()):
        header("Location: /vpcr");
    endif;

    // Verificar que la solicitud ha sido enviada y está en revisión
    if (estado_vpcr()) header("Location: finalizado.php");

	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
    $url = parse_url($_SERVER['REQUEST_URI']);
    $url = $url['path'];

	if (!$action) return; 
    switch ($action) {
        case 'enviar':
            enviar_competencia();
            header("Location: listado.php");
            $url = "listado.php";
        break;
    }

    $url = removeqsvar($url, "action");   
    header("Location: {$url}");
}

/**
 * Funcion que remueve las variables del url.
 *
 * @return void
 */
function removeqsvar($url, $varname) {
    list($urlpart, $qspart) = array_pad(explode('?', $url), 2, '');
    parse_str($qspart, $qsvars);
    unset($qsvars[$varname]);
    $newqs = http_build_query($qsvars);
    return $urlpart . '?' . $newqs;
}