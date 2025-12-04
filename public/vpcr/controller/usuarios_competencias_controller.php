<?php 


/**
 * Funcion que busca una competencia por id.
 *
 * @return void
 */
function get_usuarios_competencias_by_id($id = null)
{   
    // Si no se navego a esta ventana con un post se utiliza la ultima competencia que el usuario visualizo.
    if ($id == null) $competenciaID = $_COOKIE["id"];
    else $competenciaID = $id;

    $usuarioID = get_user_vpcr()->fetch_assoc()["id"];

    $instance = dataBase::getInstance();
    $usuarios_competencias = $instance->select_usuarios_competencias_by_id($usuarioID, $competenciaID);

    // Si el usuario no ha realizado ningun progreso en esa competencia se retorna null
    if (!isset($usuarios_competencias) || $usuarios_competencias == null){
        return null;
    }
    $listaUsuarioscompetenciasConocimientos = array();
    $listaUsuarioscompetenciasHabilidades = array();
    $listaUsuarioscompetenciasAptitudes = array();
    $listaUsuarioscompetenciasMeritos = array();
    // Los array SQL se transforman a arrays por claves para su manejo en el front una competencias 
    // puede tener conocimientos, aptitudes, habilidades y meritos asociados
    foreach($usuarios_competencias->usuariosCompetenciasConocimientos as $usuarioCC) 
    {
        $auxUsuarioCC = array("id" => $usuarioCC->id, "titulo" => $usuarioCC->titulo, "certificado" => $usuarioCC->certificado, "acreditado_por" => $usuarioCC->acreditado_por, 
        "fecha_inicio" => $usuarioCC->fecha_inicio, "fecha_fin" => $usuarioCC->fecha_fin, "valor" => $usuarioCC->valor,"valor_tipo" => $usuarioCC->valor_tipo);
        array_push($listaUsuarioscompetenciasConocimientos , $auxUsuarioCC); 
    }

    foreach($usuarios_competencias->usuariosCompetenciasHabilidades as $usuarioCH)
    {
        $usuarioCH = array("id" => $usuarioCH->id, "archivo" => $usuarioCH->archivo);
        array_push($listaUsuarioscompetenciasHabilidades , $usuarioCH); 
    }

    foreach($usuarios_competencias->usuariosCompetenciasAptitudes as $usuarioCA)
    {
        $usuarioCA = array("id" => $usuarioCA->id, "archivo" => $usuarioCA->archivo);
        array_push($listaUsuarioscompetenciasAptitudes , $usuarioCA); 
    }

    foreach($usuarios_competencias->usuariosCompetenciasMeritos as $usuarioCM)
    {
        $usuarioCM = array("id" => $usuarioCM->id, "archivo" => $usuarioCM->archivo);
        array_push($listaUsuarioscompetenciasMeritos , $usuarioCM); 
    }
    $usuarioCM = array("id" => $usuarios_competencias->id, "usuario_id" => $usuarios_competencias->vpcrID, "competencia_id" => $usuarios_competencias->competenciaID, 
    "observaciones" => $usuarios_competencias->observaciones, "puntos_conocimientos" => $usuarios_competencias->puntosConocimientos, "puntos_habilidades" => $usuarios_competencias->puntosHabilidades, "puntos_aptitudes" => $usuarios_competencias->puntosAptitudes, "puntos_meritos" => $usuarios_competencias->puntosMeritos, "usuarios_competencias_conocimientos" => $listaUsuarioscompetenciasConocimientos, "usuarios_competencias_habilidades" => $listaUsuarioscompetenciasHabilidades, "usuarios_competencias_aptitudes" => $listaUsuarioscompetenciasAptitudes, "usuarios_competencias_meritos" => $listaUsuarioscompetenciasMeritos);
    return $usuarioCM;
}

/**
 * Funcion que guarda un conocimiento y sube el archivo
 * 
 * @param int $id
 * 
 * @return void
 */
function guardar_conocimiento($id) {
    $competenciaID = $_COOKIE["id"]; // El id de la competencia siempre viene por un cookie en caso de que el usuario navegue a la ventana por su cuenta.
    $usuarioID = $_SESSION['sepd_link_user']; // Se obtiene el id del usuario por la cookie
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener id de usuario_vpcr

    $titulo = filter_input(INPUT_POST, "conocimiento{$id}_titulo", FILTER_SANITIZE_SPECIAL_CHARS);
    $certificacion = basename($_FILES["conocimiento{$id}_fileToUpload{$id}"]["name"]);
    $acreditador = filter_input(INPUT_POST, "conocimiento{$id}_acreditador", FILTER_SANITIZE_SPECIAL_CHARS);
    $fecha_inicio = filter_input(INPUT_POST, "conocimiento{$id}_fecha_inicio", FILTER_SANITIZE_SPECIAL_CHARS);
    $fecha_fin = filter_input(INPUT_POST, "conocimiento{$id}_fecha_fin", FILTER_SANITIZE_SPECIAL_CHARS);
    $valor = filter_input(INPUT_POST, "conocimiento{$id}_valor", FILTER_SANITIZE_SPECIAL_CHARS);
    $creditos = filter_input(INPUT_POST, "conocimiento{$id}_tipo_valor", FILTER_SANITIZE_SPECIAL_CHARS);

    $ruta = "../resources/{$usuarioID}/{$vpcrID}/{$competenciaID}/"."conocimientos/";

    if (!file_exists($ruta)) // Comprobar si existe la ruta, si no, se crea
        mkdir($ruta, 0777, true);

    //Se garantiza que el archivo se pueda subir al servidor.
    if (isset($certificacion) && !empty($certificacion) && !is_null($certificacion)): // Se asegura que el archivo pueda ser colocado en el servidor
        $upload = uploadFile($certificacion, $ruta, "conocimiento{$id}_fileToUpload{$id}");
        if (!$upload) return 'ERR_UPLOAD_CERTIFICADO';

        $usuario_conocimiento = new UsuarioCompetenciaConocimiento($titulo, $upload, $acreditador, $fecha_inicio, $fecha_fin, $valor, $creditos, $competenciaID, $vpcrID);
        $usuario_conocimiento->create_usuario_competencia_conocimiento($usuario_conocimiento);
    endif;
}

/**
 * Funcion que guarda una habilidad y sube el archivo
 * 
 * @param int $id
 * 
 * @return void
 */
function guardar_habilidad($id) {
    $instance = dataBase::getInstance();

    $competenciaID = $_COOKIE["id"]; // El de la competencia siempre viene por un cookie en caso de que el usuario navegue a la ventana por su cuenta.
    $usuarioID = $_SESSION['sepd_link_user']; // Se obtiene el id del usuario por la cookie
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener id de usuario_vpcr

    if (isset($_FILES["hfileToUpload{$id}"]["name"])): // Comprobar si existe el archivo
        $archivo = basename($_FILES["hfileToUpload{$id}"]["name"]);
        $ruta = "../resources/{$usuarioID}/{$vpcrID}/{$competenciaID}/habilidad/";

        if (!file_exists($ruta)) // Comprobar si existe la ruta, si no, se crea
            mkdir($ruta, 0777, true);
        
        if (isset($archivo) && !empty($archivo) && !is_null($archivo)): // Se asegura que el archivo pueda ser colocado en el servidor
            $upload = uploadFile($archivo, $ruta, "hfileToUpload{$id}");
            if (!$upload) return 'ERR_UPLOAD_CERTIFICADO';

            $usuario_habilidad = new UsuarioCompetenciaHabilidad($upload, $competenciaID, $vpcrID);
            $usuario_habilidad->create_usuario_competencia_habilidades($usuario_habilidad);
        endif;
    endif;
}

/**
 * Funcion que guarda una aptitud y sube el archivo
 * 
 * @param int $id
 * 
 * @return void
 */
function guardar_aptitud($id) {
    $instance = dataBase::getInstance();

    $competenciaID = $_COOKIE["id"]; // El de la competencia siempre viene por un cookie en caso de que el usuario navegue a la ventana por su cuenta.
    $usuarioID = $_SESSION['sepd_link_user']; // Se obtiene el id del usuario por la cookie
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener id de usuario_vpcr

    if (isset($_FILES["afileToUpload{$id}"]["name"])): // Comprobar si existe el archivo
        $archivo = basename($_FILES["afileToUpload{$id}"]["name"]);
        $ruta = "../resources/{$usuarioID}/{$vpcrID}/{$competenciaID}/aptitud/";

        if (!file_exists($ruta)) // Comprobar si existe la ruta, si no, se crea
            mkdir($ruta, 0777, true);

        
        if (isset($archivo) && !empty($archivo) && !is_null($archivo)): // Se asegura que el archivo pueda ser colocado en el servidor
            $upload = uploadFile($archivo, $ruta, "afileToUpload{$id}");
            if (!$upload) return 'ERR_UPLOAD_CERTIFICADO';

            $usuario_aptitud = new UsuarioCompetenciaAptitud($upload, $competenciaID, $vpcrID);
            $usuario_aptitud->create_usuario_competencia_aptitudes($usuario_aptitud);
        endif;
    endif;
}

/**
 * Funcion que guarda un mérito y sube el archivo
 * 
 * @param int $id
 *
 * @return void
 */
function guardar_merito($id) {
    $instance = dataBase::getInstance();

    $competenciaID = $_COOKIE["id"]; // El de la competencia siempre viene por un cookie en caso de que el usuario navegue a la ventana por su cuenta.
    $usuarioID = $_SESSION['sepd_link_user']; // Se obtiene el id del usuario por la cookie
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener id de usuario_vpcr
    
    if (isset($_FILES["mfileToUpload{$id}"]["name"])): // Comprobar si existe el archivo
        $archivo = basename($_FILES["mfileToUpload{$id}"]["name"]);
        $ruta = "../resources/{$usuarioID}/{$vpcrID}/{$competenciaID}/merito/";

        if (!file_exists($ruta)) // Comprobar si existe la ruta, si no, se crea
            mkdir($ruta, 0777, true);

        if (isset($archivo) && !empty($archivo) && !is_null($archivo)): // Se asegura que el archivo pueda ser colocado en el servidor
            $upload = uploadFile($archivo, $ruta, "mfileToUpload{$id}");
            if (!$upload) return 'ERR_UPLOAD_CERTIFICADO';

            $usuario_merito = new UsuarioCompetenciaMerito($upload, $competenciaID, $vpcrID);
            $usuario_merito->create_usuario_competencia_meritos($usuario_merito);
        endif;
    endif;
}

/**
 * Función para eliminar un conocimiento, aptitud, habilidad o mérito
 * 
 * @param string $type
 * @param int $type
 * 
 * @return void
 */
function remove_data($type, $id) {
    if (empty($type) || $id <= 0) return;
    
    $instance = dataBase::getInstance();
    
    $competenciaID = $_COOKIE["id"]; //El de la competencia siempre viene por un cookie en caso de que el usuario navegue a la ventana por su cuenta.
    $usuarioID = $_SESSION['sepd_link_user']; // Se obtiene el id del usuario por la cookie
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"]; // Obtener el usuario_vpcr.id actual

    switch ($type):
        case "conocimientos": // Eliminar conocimientos
            $conocimiento = $instance->select_usuario_competencia_conocimiento_by_id($id, $vpcrID, $competenciaID);
            if ($instance->eliminar_usuario_competencia_conocimiento($id, $vpcrID, $competenciaID)) unlink($conocimiento->certificado);
        break;

        case "habilidades": // Eliminar habilidades
            $habilidad = $instance->select_usuario_competencia_habilidad_by_id($id, $vpcrID, $competenciaID);
            if ($instance->eliminar_usuario_competencia_habilidad($id, $vpcrID, $competenciaID)) unlink($habilidad->archivo);
        break;

        case "aptitudes": // Eliminar aptitudes
            $aptitud = $instance->select_usuario_competencia_aptitud_by_id($id, $vpcrID, $competenciaID);
            if ($instance->eliminar_usuario_competencia_aptitud($id, $vpcrID, $competenciaID)) unlink($aptitud->archivo);
        break;

        case "meritos": // Eliminar méritos
            $merito = $instance->select_usuario_competencia_merito_by_id($id, $vpcrID, $competenciaID);
            if ($instance->eliminar_usuario_competencia_merito($id, $vpcrID, $competenciaID)) unlink($merito->archivo);
        break;
    endswitch;
}

/**
 * Funcion que cambia el estado de una competencia\
 * realizada por el usuario y envia un correo 
 * con destino a la SEPD
 *
 * @return void
 */
function enviar_competencia(){
    $instance = dataBase::getInstance();

    $competenciaID = $_COOKIE["id"];
    $vpcrID = get_user_vpcr()->fetch_assoc()["id"];

    $uploaded_conocimientos = [ ];
    $puntos_conocimientos = 0;
    $puntos_habilidades = 0;
    $puntos_aptitudes = 0;
    $puntos_meritos = 0;

    foreach ($_POST as $key => $value):
        if (strpos($key, "_to_delete") !== false):
            if (empty($value)) continue;

            $keyArray = explode("_", $key);
            $type = $keyArray[0];

            if (strpos($value, ",") !== false): // Si se elimina más de un elemento por tipo (Se manda id1,id2,id3)
                $valueArray = explode(",", $value);
                foreach ($valueArray as $id) remove_data($type, $id);
            else: // Si solo se elimina un elemento
                remove_data($type, (int) $value);
            endif;
        elseif (strpos($key, "puntos_") !== false): // Actualizar puntos
            $keyArray = explode("_", $key);
            $type = $keyArray[1];

            $points = empty($value) ? 0 : (int) $value;

            switch ($type):
                case "conocimientos":
                    $puntos_conocimientos = $points;
                break;

                case "habilidades":
                    $puntos_habilidades = $points;
                break;

                case "aptitudes":
                    $puntos_aptitudes = $points;
                break;

                case "meritos":
                    $puntos_meritos = $points;
                break;
            endswitch;
        elseif (strpos($key, "conocimiento") !== false): // Subir conocimientos
            $keyArray = explode("_", $key);
            $column = $keyArray[1];

            if (in_array($keyArray[0], $uploaded_conocimientos)) continue;
            array_push($uploaded_conocimientos, $keyArray[0]);

            $id = (int) substr($keyArray[0], 12);
            guardar_conocimiento($id);
        elseif (strpos($key, "habilidad") !== false): // Subir habilidades
            $id = (int) $value;
            guardar_habilidad($id);
        elseif (strpos($key, "aptitud") !== false): // Subir aptitudes
            $id = (int) $value;
            guardar_aptitud($id);
        elseif (strpos($key, "merito") !== false): // Subir méritos
            $id = (int) $value;
            guardar_merito($id);
        endif;
    endforeach;

    // Actualizar puntos
    $usuarios_competencias = $instance->select_usuarios_competencias_by_id($vpcrID, $competenciaID);
    if ($usuarios_competencias):
        $usuarios_competencias->puntos_conocimientos = $puntos_conocimientos;
        $usuarios_competencias->puntos_habilidades = $puntos_habilidades;
        $usuarios_competencias->puntos_aptitudes = $puntos_aptitudes;
        $usuarios_competencias->puntos_meritos = $puntos_meritos;

        $usuarios_competencias->update_points($usuarios_competencias);
    endif;
}

/**
 * Funcion que retorna todas las competencias que un usuario 
 * tiene asociadas a su cuenta
 *
 * @return listaUsuariosCompetencias
 */
function get_all_usuarios_competencias()
{
    $usuarioID = get_user_vpcr()->fetch_assoc()["id"];
    $instance = dataBase::getInstance();
    $lista_usuarios_competencias = $instance->select_all_usuarios_competencias($usuarioID);
    $listaUsuariosCompetencias = array();
    //Se convierte el array sql en un array por claves para el front
    foreach($lista_usuarios_competencias as $usuarios_competencias)
    {
        $listaUsuarioscompetenciasConocimientos = array();
        $listaUsuarioscompetenciasHabilidades = array();
        $listaUsuarioscompetenciasAptitudes = array();
        $listaUsuarioscompetenciasMeritos = array();
        //Cada competencia posee un array con claves para sus conocimeintos, habilidades y aptitudes.
        foreach($usuarios_competencias->usuariosCompetenciasConocimientos as $usuarioCC)
        {
            $auxUsuarioCC = array("id" => $usuarioCC->id, "titulo" => $usuarioCC->titulo, "certificado" => $usuarioCC->certificado, "acreditado_por" => $usuarioCC->acreditado_por, 
            "fecha_inicio" => $usuarioCC->fecha_inicio, "fecha_fin" => $usuarioCC->fecha_fin, "valor" => $usuarioCC->valor,"valor_tipo" => $usuarioCC->valor_tipo);
            array_push($listaUsuarioscompetenciasConocimientos , $auxUsuarioCC); 
        }

        foreach($usuarios_competencias->usuariosCompetenciasHabilidades as $usuarioCH)
        {
            $usuarioCH = array("id" => $usuarioCH->id, "archivo" => $usuarioCH->archivo);
            array_push($listaUsuarioscompetenciasHabilidades , $usuarioCH); 
        }

        foreach($usuarios_competencias->usuariosCompetenciasAptitudes as $usuarioCA)
        {
            $usuarioCA = array("id" => $usuarioCA->id, "archivo" => $usuarioCA->archivo);
            array_push($listaUsuarioscompetenciasAptitudes , $usuarioCA); 
        }

        foreach($usuarios_competencias->usuariosCompetenciasMeritos as $usuarioCM)
        {
            $usuarioCM = array("id" => $usuarioCM->id, "archivo" => $usuarioCM->archivo);
            array_push($listaUsuarioscompetenciasMeritos , $usuarioCM); 
        }
        $usuarioCM = array("id" => $usuarios_competencias->id, "usuario_id" => $usuarios_competencias->vpcrID, "competencia_id" => $usuarios_competencias->competenciaID, "updated_at" => $usuarios_competencias->updated_at, "observaciones" => $usuarios_competencias->observaciones, "puntos_conocimientos" => $usuarios_competencias->puntosConocimientos, "puntos_habilidades" => $usuarios_competencias->puntosHabilidades, "puntos_aptitudes" => $usuarios_competencias->puntosAptitudes, "puntos_meritos" => $usuarios_competencias->puntosMeritos, "usuarios_competencias_conocimientos" => $listaUsuarioscompetenciasConocimientos, "usuarios_competencias_habilidades" => $listaUsuarioscompetenciasHabilidades, "usuarios_competencias_aptitudes" => $listaUsuarioscompetenciasAptitudes, "usuarios_competencias_meritos" => $listaUsuarioscompetenciasMeritos);
        $listaUsuariosCompetencias["$usuarios_competencias->competenciaID"] = $usuarioCM;
    }
    return $listaUsuariosCompetencias;
}