<?php
/**
 * 
 *
 * @return void
 */
function api_controller() {
    header("Content-type: application/json");

	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
    $url = parse_url($_SERVER['REQUEST_URI']);
    $url = $url['path'];

    if (!$action) return;    
    switch ($action):
        case "completed_competencias":
            $usuario_competencias = get_usuarios_competencias();
            $usuario_competencias["puntos_totales"] = get_usuarios_competencias_puntos_totales();

            echo json_encode($usuario_competencias);
        break;

        default:
            echo "Cannot GET '{$action}' API";
        break;
    endswitch;
}