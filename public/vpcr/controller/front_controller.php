<?php
/**
 * parámetros de configuración de VPC-R
 * 3 Ways - Carlos Colmenarez
 * @return int
 */
//Mínimo de competencias específicas a validar
$competencias_e=6;
//Mínimo de competencias transversales a validar
$competencias_t=1;
 //Puntación máxima permitida
$max_ptos_competencias=70;

function max_puntos_competencias() {
	global $max_ptos_competencias;
	return $max_ptos_competencias;
}
function min_competencias_especificas() {
	global $competencias_e;
	return $competencias_e;
}

function min_competencias_transversales() {
	global $competencias_t;
	return $competencias_t;
}

/**
 * Funcion que controlas los accesos a la pagina de inicio
 *
 * @return void
 */
function front_controller(){
	if (!logged_in()) header("Location: /login?route=vpcr");

	$currentAction = "";
	$url = parse_url($_SERVER['REQUEST_URI']);
	$url = $url['path'];
	$url = trim( $url,'/' );

	$url = explode('/',$url);
	$home = "http://" . $_SERVER['HTTP_HOST']. "/vpcr/solicitud/colegiado.php";

	$lastIndex = sizeof($url) - 1;
//	die($url[$lastIndex]);
	if(isset($url[$lastIndex]))
	{
		switch ($url[$lastIndex]) {
			// case "update": include('model/update_competencias.php'); break; // Actualizar titulos con el length original
			// case 'save': include('model/saveCompetencias.php'); break;// Estas rutas ya no se usan fueron reemplazas por seeders
			// case 'save2': include('model/saveCompetenciasTransversales.php'); break; Estas rutas ya no se usan fueron reemplazas por seeders
			case 'colegiado.php':
				recertificacion_controller();
			break;

			case 'validar.php':
				validar_controller();
			break;

			case 'listado.php':
				listado_controller();
			break;

			case 'confirmacion.php':
				confirmacion_controller();
			break;

			case 'api.php':
				api_controller();
			break;

			case 'finalizado.php':
				finalizado_controller();
			break;


			default:
				header("Location: $home");
			break;
		}
	}
	else{
		header("Location: $home");
	}
}

/**
 * Comprobar si el usuario está logueado
 * 
 * @return void
 */
function logged_in() {
	if (!isset($_SESSION['sepd_link_user']) || empty($_SESSION['sepd_link_user'])):
		if (isset($_COOKIE['vpcr_data']))
			$_SESSION['sepd_link_user'] = decrypt($_COOKIE['vpcr_data']);
		else return false;
	endif;

	if (!get_user_name()) return false;
	return true;
}

/**
 * Comprobar si tiene un número de colegiado válido
 * 
 * @return bool
 */
function valid_cnum() {
	$num_colegiado = get_user_num_colegiado();
	if ($num_colegiado):
        if(APP_DEBUG){
            return true;
        }
        $status = numero_CGCOM($num_colegiado);
        return correct_number($status, true);

	endif;
	return false;
}

/**
 * Comprobar si ya ha iniciado una solicitud de VPC-R
 * 
 * @return bool
 */
function started_request() {
	$user_vpcr = get_user_vpcr();
	if ($user_vpcr):
		//if (time() > strtotime($user_vpcr["fecha_caducidad"])) return false;

		return true;
	endif;

	return false;
}

/**
 * Comprobar si ha finalizado la solicitud de VPC-R
 * 
 * @return bool
 */
function estado_vpcr() {
	$status = array(
		"pending" => "Su solicitud está siendo revisada, le enviaremos una respuesta vía e-mail",
		"deleted" => "Su solicitud no cumple con los requisitos establecidos, para obtener la recertificación en aparato digestivo. Para obtener más información sobre el proceso y motivo, póngase en contacto con nosotros a través del siguiente correo: <a href='mailto:validacion@sepd.es'>validacion@sepd.es</a>",
		"aproved" => "Su solicitud ha sido aprobada <br><br> <a href='#' onclick='actualizarTramoVpc();' ><span class='btn btn-success'>Descargar certificado</span></a>"

	);

	$user_vpcr = get_user_vpcr()->fetch_assoc();

	// Check if fecha_concedido column is set
	// then the message for user is aproved
	if(isset($user_vpcr["fecha_concedido"])) return $status["aproved"];

	if (!$user_vpcr) return false;
	elseif (!isset($user_vpcr["fecha_enviado"]) || empty($user_vpcr["fecha_enviado"])) return false;
	elseif ((isset($user_vpcr["fecha_envio_observaciones"]) && !empty($user_vpcr["fecha_envio_observaciones"])) && (strtotime($user_vpcr["fecha_envio_observaciones"]) > strtotime($user_vpcr["fecha_enviado"]))) return false;
	
	// Check if fecha_rechazado column is set
	// then the message for user is deleted
	if(isset($user_vpcr["fecha_rechazado"])) return $status["deleted"];

	return $status["pending"];
}

/**
 * Funcion que extrae el nombre del usuario de la sesion
 *
 * @return string
 */
function get_user_name()
{
	$instance = dataBase::getInstance();
	$usuarioID = $_SESSION['sepd_link_user'];
	$nombre = $instance->select_usuario_name($usuarioID);
	return $nombre;
}

/**
 * Funcion que extrae el nombre completo del usuario de la sesion
 *
 * @return string
 */
function get_user_name_completo()
{
	$instance = dataBase::getInstance();
	$usuarioID = $_SESSION['sepd_link_user'];
	$nombre = $instance->select_usuario_name_completo($usuarioID);
	return $nombre;
}

/**
 * Función que extrae el número de colegiado del usuario de la sesión
 * 
 * @return int
 */
function get_user_num_colegiado() {
	$instance = dataBase::getInstance();
	$usuarioID = $_SESSION['sepd_link_user'];
	$num_colegiado = $instance->select_usuario_num_colegiado($usuarioID);
	return $num_colegiado;
}

/**
 * Función que elimina el número de colegiado del usuario de la sesión
 * 
 * @return int
 */
function update_user_num_colegiado($num_colegiado = NULL) {
	$instance = dataBase::getInstance();
	$usuarioID = $_SESSION['sepd_link_user'];
	$result = $instance->update_usuario_num_colegiado($usuarioID, $num_colegiado);
	return $result;
}

/**
 * Función que extrae los datos de un usuario vpcr
 * 
 * @return void
 */
function get_user_vpcr() {
	$instance = dataBase::getInstance();
	$usuarioID = $_SESSION['sepd_link_user'];
	$result = $instance->select_user_vpcr($usuarioID);
	return $result;
}

/**
 * Función que inserta usuario vpcr
 * 
 * @return int
 */
function insert_user_vpcr() {
	$instance = dataBase::getInstance();
	$usuarioID = $_SESSION['sepd_link_user'];
	$result = $instance->insert_user_vpcr($usuarioID);
	return $result;
}

/**
 * Función que inserta usuario vpcr
 * 
 * @return Array
 */
function get_usuarios_competencias() {
	$instance = dataBase::getInstance();
	$vpcrID = get_user_vpcr()->fetch_assoc()["id"];
	$result = $instance->select_usuarios_competencias($vpcrID);
	return $result;
}

/**
 * Función para obtener los puntos totales de las competencias
 * completadas por el usuario
 * 
 * @return int
 */
function get_usuarios_competencias_puntos_totales($not_id = 0) {
	$instance = dataBase::getInstance();
	$vpcrID = get_user_vpcr()->fetch_assoc()["id"];
	$usuarios_competencias = $instance->select_all_usuarios_competencias($vpcrID, $not_id);
	$puntos_totales = 0;

	foreach ($usuarios_competencias as $usuario_competencia):
		$puntos_totales += ($usuario_competencia->puntosConocimientos + $usuario_competencia->puntosHabilidades + $usuario_competencia->puntosAptitudes + $usuario_competencia->puntosMeritos);
	endforeach;

	return $puntos_totales;
}