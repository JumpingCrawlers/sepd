<?php
/**
 * 
 *
 * @return void
 */
function finalizado_controller() {
    if (!started_request()):
        header("Location: confirmacion.php");
    elseif (!valid_cnum() && !started_request()):
        header("Location: colegiado.php?action=invalid_number");
    endif;

    // Verificar que la solicitud ha sido enviada y está en revisión
    if (!estado_vpcr()) header("Location: listado.php");

	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
    $url = parse_url($_SERVER['REQUEST_URI']);
    $url = $url['path'];

    if (!$action) return;
    switch ($action):
        // Actions
    endswitch;
}