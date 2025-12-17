<?php 

/**
 * Funcion que controlas los accesos a la pagina de inicio
 *
 * @return void
 */
function confirmacion_controller() {
    $numeroCGCOM = filter_input(INPUT_POST, "numeroCGCOM", FILTER_SANITIZE_SPECIAL_CHARS);
    if (!$numeroCGCOM):
        if (started_request()):
            header("Location: listado.php");
        elseif (!valid_cnum()):
            header("Location: colegiado.php?action=invalid_number");
        endif;
    endif;

    $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_SPECIAL_CHARS);
    $url = parse_url($_SERVER["REQUEST_URI"]);
    $url = $url["path"];
    if (!$action) return;
    switch ($action):
        case "validar":
            if(APP_DEBUG){
                correct_number(true);
            }else{
                $status = numero_CGCOM($numeroCGCOM);
                correct_number($status);
            }
            break;
        case "cambiar":
            header("Location: colegiado.php?action=change_number");
        break;

        case "confirmar":
            if (!get_user_num_colegiado()) update_user_num_colegiado($numeroCGCOM);
            insert_user_vpcr();
            header("Location: listado.php");
        break;

        case "actualizarTramo":
            actualizar_tramo_vpc();
        break;
    endswitch;
}

/**
 * Actualizar tramo vpc
 * 
 * @return void
 */
function actualizar_tramo_vpc() {
    $instance = dataBase::getInstance();

    $userID = get_user_vpcr()->fetch_assoc()["usuarios_id"]; // Obtener id de usuario_vpcr
    $tramo = check_vpc(get_user_num_colegiado(), true);
    if (!$tramo) return;
    $tramo = implode('-', $tramo);

    $instance->update_usuarios_datosprofesionales_vpc_tramo($userID, $tramo);
}
