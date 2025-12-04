<?php

/**
 * Funcion que controlas los accesos a la pagina de inicio
 *
 * @return void
 */
function listado_controller() {
    if (!started_request()):
        header("Location: confirmacion.php");
    elseif (!valid_cnum() && !started_request()):
        header("Location: colegiado.php?action=invalid_number");
    endif;

    // Verificar que la solicitud ha sido enviada y está en revisión
    if (estado_vpcr()) header("Location: finalizado.php");

    $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_SPECIAL_CHARS);
    $url = parse_url($_SERVER["REQUEST_URI"]);
    $url = $url["path"];
    
    if (!$action) return;
    switch ($action):
        case "enviar":
            finalizar_validacion();

            header("Location: finalizado.php");
            $url = "finalizado.php";
        break;
    endswitch;

    $url = removeqsvar($url, "action");   
    header("Location: {$url}");
}

/**
 * Finalizar proceso de validación
 * 
 * @return void
 */
function finalizar_validacion() {
    $instance = dataBase::getInstance();

    $usuarioID = $_SESSION['sepd_link_user']; // Se obtiene el id del usuario por la cookie
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener id de usuario_vpcr

    // Enviar mail al administrador
    $ch = curl_init('http://' . $_SERVER['HTTP_HOST'] . '/api/send_vpcr/' . encrypt($vpcrID) . '/' . encrypt($usuarioID));
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
 
    curl_close ($ch);

    $instance->update_usuarios_vpcrs_fecha_enviado($vpcrID, $usuarioID);
}

/**
 * Comprobar si hay alguna competencia completada dentro de un dominio
 * 
 * @param int $dominioID
 * 
 * @return bool
 */
function dominio_contains_completed_competencias($dominioID) {
    $instance = dataBase::getInstance();

    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener id de usuario_vpcr
    $result = $instance->select_competencias_completadas_by_dominio_id($vpcrID, $dominioID);

    return $result;
}
