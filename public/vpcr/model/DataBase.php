<?php 

/**
 * Clase Base de datos
 *
 * @return void
 */
class DataBase {
    
    private static $instace = null;
    private $conn;

    // Configuracion de la base de datos, no se debe modificar desde aqui, modificar el archivo vpcr_config.php para modificar la conexion
    private $host = DBSERVER;
    private $user = DBUSER;
    private $pass = DBPASS;
    private $name = DBNAME;
    private $port = DBPORT;

    // Variables globales con los nombres de las tablas asociadas al vpcr
    public $tableDominios = "dominios";
    public $tableCompetencias = "competencias";
    public $tableCompetenciasConocimientos = "competencias_conocimientos";
    public $tableCompetenciasHabilidades = "competencias_habilidades";
    public $tableCompetenciasAptitudes = "competencias_aptitudes";
    public $tableEvaluaciones = "competencias_evaluaciones";
    public $tableEvaluacionesHabilidades = "competencias_evaluaciones_habilidades";
    public $tableEvaluacionesAptitudes = "competencias_evaluaciones_aptitudes";
    public $tableEvaluacionesConocimientos = "competencias_evaluaciones_conocimientos";

    // Tablas para usuarios
    public $tableUsuariosCompetencias = "usuarios_vpcrs_competencias";
    public $tableUsuariosCompetenciasConocimientos = "usuarios_vpcrs_competencias_conocimientos";
    public $tableUsuariosCompetenciasHabilidades = "usuarios_vpcrs_competencias_habilidades";
    public $tableUsuariosCompetenciasAptitudes = "usuarios_vpcrs_competencias_aptitudes";
    public $tableUsuariosCompetenciasMeritos = "usuarios_vpcrs_competencias_meritos";

    /**
     * Constructor de la base de datos.
     *
     * @return void
     */
    private function __construct()
    {  
        $this->conn = mysqli_connect($this->host,$this->user,$this->pass,$this->name,$this->port);
        if( mysqli_connect_errno() > 0 ){
			die('<h1>Error en la conexión a base de datos</h1>' );
		}
        mysqli_query($this->conn, 'SET NAMES utf8');
    }

    /**
     * Esta clase es un singleton, getInstance crea en caso de 
     * que no exista una Instancia de la base de datos o retorna
     * una instancia.
     *
     * @return void
     */
    public static function getInstance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new dataBase();
        }
        return $inst;
    }
    
    /**
     * Retorna la conexion.
     *
     * @return void
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Termina la conexion.
     *
     * @return void
     */
    function close_db(){
		mysqli_close($this->conn);
    }
    
    /**
     * Selecciona todos los dominios.
     *
     * @return void
     */
    public function select_all_dominios(){
        
        $sql = "SELECT * FROM ". $this->tableDominios;
        $query = mysqli_query($this->conn, $sql);
        $dominios = array();
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $dom)
            {
                $dominio = new Dominio($dom["id"], $dom["titulo"]);
                array_push($dominios, $dominio); 
            }  
            return $dominios;
        }
        return $dominios;
    }

    /**
     * Selecciona todos las competencias transversales.
     *
     * @return void
     */
    public function select_all_transversales(){
        
        $sql = "SELECT * FROM {$this->tableCompetencias} WHERE competencias_tipos_id = 2";
        $query = mysqli_query($this->conn, $sql);
        $transversales = array();
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $transversal)
            {
                $deleted_at = $transversal["deleted_at"];
                $transversal = new Competencia($transversal["id"], $transversal["titulo"]);
                $transversal->set_deleted_at($deleted_at);
                array_push($transversales, $transversal); 
            }
            return $transversales;
        }
        return $transversales;
    }

    /**
     * Selecciona una competencia por id.
     *
     * @return void
     */
    public function select_competencia_by_id($id)
    {
        $sql = "SELECT c.* 
        FROM ".$this->tableCompetencias." as c
        where c.id = " . $id;
        $query = mysqli_query($this->conn, $sql);
        $competencia = null;
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $competencia)
            {
                $deleted_at = $competencia["deleted_at"];
                $competencia = new Competencia($competencia["id"], $competencia["titulo"]);
                $competencia->set_deleted_at($deleted_at);
            }  

            return $competencia;
        }
        return $competencia;
    }

    /**
     * Selecciona la competencia de un usuarios por
     * id de solicitud de vpcr y competencia
     *
     * @return void
     */
    public function select_usuarios_competencias_by_id($vpcrID, $competenciaID)
    {
        $sql = "SELECT * FROM {$this->tableUsuariosCompetencias} WHERE usuarios_vpcrs_id = {$vpcrID} AND competencias_id = {$competenciaID}";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if ($num_rows)
            foreach ($query as $usuario_competencia)
                return new UsuarioCompetencia($usuario_competencia["id"], $usuario_competencia["usuarios_vpcrs_id"], $usuario_competencia["competencias_id"], $usuario_competencia["observaciones"], $usuario_competencia["puntos_conocimientos"], $usuario_competencia["puntos_habilidades"], $usuario_competencia["puntos_aptitudes"], $usuario_competencia["puntos_meritos"], $usuario_competencia["updated_at"]);

        return null;
    }

    /**
     * Selecciona todas las competencias de un usuario
     *
     * @return void
     */
    public function select_all_usuarios_competencias($usuarioID, $not_id = 0)
    {
        $sql = "SELECT * FROM {$this->tableUsuariosCompetencias} WHERE usuarios_vpcrs_id = {$usuarioID}" . ($not_id > 0 ? " AND competencias_id != {$not_id}" : "");
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);
        $listaUsuariosCompetencia = array();

        if ($num_rows)
            foreach($query as $usuario_competencia)
                array_push($listaUsuariosCompetencia, new UsuarioCompetencia($usuario_competencia["id"], $usuario_competencia["usuarios_vpcrs_id"], $usuario_competencia["competencias_id"], $usuario_competencia["observaciones"], $usuario_competencia["puntos_conocimientos"], $usuario_competencia["puntos_habilidades"], $usuario_competencia["puntos_aptitudes"], $usuario_competencia["puntos_meritos"], $usuario_competencia["updated_at"]));
        
        return $listaUsuariosCompetencia;
    }

    /**
     * Selecciona un conocimientos por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function select_usuario_competencia_conocimiento_by_id($id, $usuarioID, $competenciaID)
    {
        $sql = "SELECT ucc.* FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasConocimientos} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if ($num_rows)
            foreach($query as $usuario_conocimiento)
                return new UsuarioCompetenciaConocimiento($usuario_conocimiento["titulo"], $usuario_conocimiento["certificado"], 
                $usuario_conocimiento["acreditado_por"], $usuario_conocimiento["fecha_inicio"], $usuario_conocimiento["fecha_fin"], $usuario_conocimiento["valor"], 
                $usuario_conocimiento["valor_tipo"], $competenciaID, $usuarioID, $usuario_conocimiento["id"]);

        return null;
    }

    /**
     * Selecciona una habilidad por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function select_usuario_competencia_habilidad_by_id($id, $usuarioID, $competenciaID)
    {
        $sql = "SELECT ucc.* FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasHabilidades} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if ($num_rows)
            foreach($query as $usuario_habilidad)
                return new UsuarioCompetenciaHabilidad($usuario_habilidad["archivo"], $competenciaID, $usuarioID, $usuario_habilidad["id"]);

        return null;
    }

    /**
     * Selecciona una aptitud por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function select_usuario_competencia_aptitud_by_id($id, $usuarioID, $competenciaID)
    {
        $sql = "SELECT ucc.* FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasAptitudes} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if ($num_rows)
            foreach($query as $usuario_aptitud)
                return new UsuarioCompetenciaAptitud($usuario_aptitud["archivo"], $competenciaID, $usuarioID, $usuario_aptitud["id"]);

        return null;
    }

    /**
     * Selecciona una aptitud por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function select_usuario_competencia_merito_by_id($id, $usuarioID, $competenciaID)
    {
        $sql = "SELECT ucc.* FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasMeritos} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows)
            foreach($query as $usuario_merito)
                return new UsuarioCompetenciaMerito($usuario_merito["archivo"], $competenciaID, $usuarioID, $usuario_merito["id"]);

        return null;
    }

    /**
     * Elimina un conocimiento por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function eliminar_usuario_competencia_conocimiento($id, $usuarioID, $competenciaID)
    {
        $sql = "DELETE FROM {$this->tableUsuariosCompetenciasConocimientos} WHERE id IN (SELECT id FROM (SELECT ucc.id AS id FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasConocimientos} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id) AS x)";
        $query = mysqli_query($this->conn, $sql);

        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Elimina una habilidad por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function eliminar_usuario_competencia_habilidad($id, $usuarioID, $competenciaID)
    {
        $sql = "DELETE FROM {$this->tableUsuariosCompetenciasHabilidades} WHERE id IN (SELECT id FROM (SELECT ucc.id AS id FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasHabilidades} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id) AS x)";
        $query = mysqli_query($this->conn, $sql);

        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Elimina una aptitud por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function eliminar_usuario_competencia_aptitud($id, $usuarioID, $competenciaID)
    {
        $sql = "DELETE FROM {$this->tableUsuariosCompetenciasAptitudes} WHERE id IN (SELECT id FROM (SELECT ucc.id as id FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasAptitudes} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id) AS x)";
        $query = mysqli_query($this->conn, $sql);

        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Elimina un merito por id de usuarios 
     * competencias.
     *
     * @return void
     */
    public function eliminar_usuario_competencia_merito($id, $usuarioID, $competenciaID)
    {
        $sql = "DELETE FROM {$this->tableUsuariosCompetenciasMeritos} WHERE id IN (SELECT id FROM (SELECT ucc.id AS id FROM {$this->tableUsuariosCompetencias} AS uc, {$this->tableUsuariosCompetenciasMeritos} AS ucc WHERE uc.usuarios_vpcrs_id = {$usuarioID} AND uc.competencias_id = {$competenciaID} AND ucc.id = {$id} AND ucc.usuarios_vpcrs_competencias_id = uc.id) AS x)";
        $query = mysqli_query($this->conn, $sql);

        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Selecciona el nombre de un usuario por id.
     *
     * @return void
     */
    public function select_usuario_name($id)
    {
        $sql = "SELECT nombre 
        FROM usuarios 
        WHERE id = $id";
        $query = mysqli_query($this->conn, $sql);
        $usuarioCH = null;
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $nombre)
            {
                $nombreAUX = $nombre["nombre"];
            }  
            return $nombreAUX;
        }
        return $nombreAUX;
    }


    /**
     * Selecciona el nombre completo de un usuario por id.
     *
     * @return void
     */
    public function select_usuario_name_completo($id)
    {
        $sql = "SELECT tratamiento, nombre, apellidos 
        FROM usuarios 
        WHERE id = $id";
        $query = mysqli_query($this->conn, $sql);
        $usuarioCH = null;
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $nombre)
            {
                $nombreAUX = $nombre["tratamiento"] . " " . $nombre["nombre"] . " " . $nombre["apellidos"];
            }  
            return $nombreAUX;
        }
        return $nombreAUX;
    }

    /**
     * Seleccionar número de colegiado de un usuario por id
     * 
     * @param int $id
     * 
     * @return int
     */
    public function select_usuario_num_colegiado($id) {
        $sql = "SELECT num_colegiado FROM usuarios_datosprofesionales WHERE usuario_id = {$id}";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows)
            foreach($query as $dato_profesional)
                return $dato_profesional["num_colegiado"];

        return null;
    }

    /**
     * Actualiza número de colegiado de un usuario por id
     * 
     * @param int $id
     * 
     * @return int
     */
    public function update_usuario_num_colegiado($id, $num_colegiado) {
        $sql = "UPDATE usuarios_datosprofesionales SET num_colegiado = " . ($num_colegiado ? $num_colegiado : 'NULL') . " WHERE usuario_id = {$id}";
        mysqli_query($this->conn, $sql);
        
        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Obtener registro de VPCR de un usuario por id
     * 
     * @param int $id
     * 
     * @return void
     */
    public function select_user_vpcr($id) {
        // $sql = "SELECT * FROM usuarios_vpcrs WHERE usuarios_id = {$id} AND fecha_concedido IS NULL";
        //Cuando funcione la conexion con VPC cambiar de nuevo
        $sql = "SELECT * FROM usuarios_vpcrs WHERE usuarios_id = {$id}";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);
        
        return ($num_rows ? $query : null);
    }

    /**
     * Insertar registro de VPCR
     * 
     * @param int $id
     * 
     * @return void
     */
    public function insert_user_vpcr($id) {
        $sql = "INSERT INTO usuarios_vpcrs(usuarios_id, fecha_solicitud) VALUES({$id}, NOW());";
        mysqli_query($this->conn, $sql);
        
        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Obtener título y tipo de las competencias completadas de un usuario por id
     * 
     * @param int $id
     * 
     * @return void
     */
    public function select_usuarios_competencias($id) {
        $sql = "SELECT c.titulo, c.competencias_tipos_id FROM {$this->tableCompetencias} c, {$this->tableUsuariosCompetencias} uc WHERE uc.usuarios_vpcrs_id = {$id} AND uc.competencias_id = c.id";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);
        
        return ($num_rows ? $query->fetch_all() : null);
    }

    /**
     * Actualizar fecha de enviado de usuarios_vpcrs
     * 
     * @param int $vpcrID
     * @param int $userID
     * 
     * @return bool
     */
    function update_usuarios_vpcrs_fecha_enviado($vpcrID, $userID) {
        $sql = "UPDATE usuarios_vpcrs SET fecha_enviado = NOW() WHERE id = {$vpcrID} AND usuarios_id = {$userID}";
        mysqli_query($this->conn, $sql);
        
        return (mysqli_affected_rows($this->conn) > 0);
    }

    /**
     * Seleccionar competencias de un usuario por id de dominio
     * 
     * @param int $vpcrID
     * @param int $dominioID
     * 
     * @return void
     */
    function select_competencias_completadas_by_dominio_id($vpcrID, $dominioID) {
        $sql = "SELECT uc.* FROM {$this->tableUsuariosCompetencias} uc, {$this->tableCompetencias} c, {$this->tableDominios} d WHERE uc.usuarios_vpcrs_id = {$vpcrID} AND c.id = uc.competencias_id AND c.dominio_id = d.id AND d.id = {$dominioID}";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);
        
        return ($num_rows ? $query : null);
    }

    /**
     * Actualizar tramo de vpc
     * 
     * @param int $userID
     * @param string $tramo
     * 
     * @return bool
     */
    function update_usuarios_datosprofesionales_vpc_tramo($userID, $tramo) {
        $sql = "UPDATE usuarios_datosprofesionales SET vpc_tramo = '{$tramo}' WHERE usuario_id = {$userID}";
        mysqli_query($this->conn, $sql);
        
        return (mysqli_affected_rows($this->conn) > 0);
    }
}