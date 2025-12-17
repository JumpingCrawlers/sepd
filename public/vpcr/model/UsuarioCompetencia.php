<?php 

/**
 * Clase usuario competencia
 *
 * @return string
 */
class UsuarioCompetencia {

    public $id;
    public $vpcrID;
    public $competenciaID;
    public $observaciones;
    public $puntosConocimientos;
    public $puntosHabilidades;
    public $puntosAptitudes;
    public $puntosMeritos;
    public $updated_at;

    public $usuariosCompetenciasAptitudes = array();
    public $usuariosCompetenciasConocimientos = array();
    public $usuariosCompetenciasHabilidades = array();
    public $usuariosCompetenciasMeritos = array();

    /**
     * Constrcutor de la Clase UsuarioCompetencia
     *
     * @return UsuarioCompetencia
     */
    public function __construct($id, $vpcrID, $competenciaID, $observaciones, $puntosConocimientos, $puntosHabilidades, $puntosAptitudes, $puntosMeritos, $updated_at)
    {
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $this->id = (int) $id;
        $this->vpcrID = (int) $vpcrID;
        $this->competenciaID = (int) $competenciaID;
        $this->observaciones = mysqli_real_escape_string($conn, $observaciones);
        $this->puntosConocimientos = (int) $puntosConocimientos;
        $this->puntosHabilidades = (int) $puntosHabilidades;
        $this->puntosAptitudes = (int) $puntosAptitudes;
        $this->puntosMeritos = (int) $puntosMeritos;
        $this->updated_at = strtotime(mysqli_real_escape_string($conn, $updated_at));

        // Un usuario_competencias siempre tiene asociado un array de aptitudes, conocimientos, habilidades y meritos, este array puede estar vacio
        $this->get_usuarios_competencias_conocimientos_by_id();
        $this->get_usuarios_competencias_habilidades_by_id();
        $this->get_usuarios_competencias_aptitudes_by_id();
        $this->get_usuarios_competencias_meritos_by_id();
    }

    /**
     * Obtener todos los conocimientos de usuarios_vpcrs_competencias 
     *
     * @return UsuarioCompetencia
     */
    public function get_usuarios_competencias_conocimientos_by_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        
        $sql = "SELECT * FROM {$instance->tableUsuariosCompetenciasConocimientos} WHERE usuarios_vpcrs_competencias_id = {$this->id}";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows)
            foreach($query as $usuario_conocimiento)
                array_push($this->usuariosCompetenciasConocimientos, new UsuarioCompetenciaConocimiento($usuario_conocimiento["titulo"], $usuario_conocimiento["certificado"], 
                $usuario_conocimiento["acreditado_por"], $usuario_conocimiento["fecha_inicio"], $usuario_conocimiento["fecha_fin"], $usuario_conocimiento["valor"], 
                $usuario_conocimiento["valor_tipo"], $this->competenciaID, $this->vpcrID, $usuario_conocimiento["id"]));
    }

    /**
     * Obtener todas las habilidades de usuarios_vpcrs_competencias 
     *
     * @return array
     */
    public function get_usuarios_competencias_habilidades_by_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        
        $sql = "SELECT * FROM {$instance->tableUsuariosCompetenciasHabilidades} WHERE usuarios_vpcrs_competencias_id = {$this->id}";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows)
            foreach($query as $usuario_habilidad)
                array_push($this->usuariosCompetenciasHabilidades, new UsuarioCompetenciaHabilidad($usuario_habilidad["archivo"], $this->competenciaID, $this->vpcrID, $usuario_habilidad["id"]));
    }

    /**
     * Obtener todas las aptitudes de usuarios_competencias 
     *
     * @return array
     */
    public function get_usuarios_competencias_aptitudes_by_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        
        $sql = "SELECT * FROM {$instance->tableUsuariosCompetenciasAptitudes} WHERE usuarios_vpcrs_competencias_id = {$this->id}";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows)
            foreach($query as $usuario_aptitud)
                array_push($this->usuariosCompetenciasAptitudes, new UsuarioCompetenciaAptitud($usuario_aptitud["archivo"], $this->competenciaID, $this->vpcrID, $usuario_aptitud["id"])); 
    }

    /**
     * Obtener todos los meritos de usuarios_competencias 
     *
     * @return array
     */
    public function get_usuarios_competencias_meritos_by_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        
        $sql = "SELECT * FROM {$instance->tableUsuariosCompetenciasMeritos} WHERE usuarios_vpcrs_competencias_id = {$this->id}";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows)
            foreach($query as $usuario_merito)
                array_push($this->usuariosCompetenciasMeritos, new UsuarioCompetenciaMerito($usuario_merito["archivo"], $this->competenciaID, $this->vpcrID, $usuario_merito["id"]));
    }

    /**
     * Cambia los puntos de usuarios_vpcrs_competencias.
     *
     * @return bool
     */
    public function update_points($objeto)
    {
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $id = $objeto->id;
        $puntos_conocimientos = $objeto->puntos_conocimientos;
        $puntos_habilidades = $objeto->puntos_habilidades;
        $puntos_aptitudes = $objeto->puntos_aptitudes;
        $puntos_meritos = $objeto->puntos_meritos;
        $updated_at = date("Y-m-d H:i:s"); 

        $sql = "UPDATE {$instance->tableUsuariosCompetencias} SET puntos_conocimientos = {$puntos_conocimientos}, puntos_habilidades = {$puntos_habilidades}, puntos_aptitudes = {$puntos_aptitudes}, puntos_meritos = {$puntos_meritos}, updated_at = '{$updated_at}' WHERE id = {$id}";
        $query = mysqli_query($conn, $sql);
        
        return (mysqli_affected_rows($conn) > 0);
    }
}