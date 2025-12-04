<?php 
/**
 * Clase Usuario Competencia Conocimiento
 *
 * @return void
 */
class UsuarioCompetenciaConocimiento {

    public $id;
    public $titulo;
    public $certificado;
    public $acreditado_por;
    public $fecha_inicio;
    public $fecha_fin;
    public $valor;
    public $valor_tipo;
    public $competencias_id;
    public $usuario_id;

    /**
     * Constructor de la Clase Usuario Competencia Conocimiento
     *
     * @return void
     */
    public function __construct($titulo, $certificado, $acreditado_por, $fecha_inicio, $fecha_fin, $valor, $valor_tipo, $competencias_id, $usuario_id, $id = null)
    {
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $this->titulo = mysqli_real_escape_string($conn, $titulo);
        $this->certificado = mysqli_real_escape_string($conn, $certificado);
        $this->acreditado_por = mysqli_real_escape_string($conn, $acreditado_por);
        $this->fecha_inicio = mysqli_real_escape_string($conn, $fecha_inicio);
        $this->fecha_fin = mysqli_real_escape_string($conn, $fecha_fin);
        $this->valor = (float) $valor;
        $this->valor_tipo = mysqli_real_escape_string($conn, $valor_tipo);
        $this->competencias_id = mysqli_real_escape_string($conn, $competencias_id);
        $this->usuario_id = $usuario_id;
        $this->id = $id;
    }

    /**
     * Insert de usuarios competencias conocimientos
     *
     * @return void
     */
    public function create_usuario_competencia_conocimiento($objeto){
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $titulo = $objeto->titulo;
        $certificado = $objeto->certificado;
        $acreditado_por = $objeto->acreditado_por;
        $fecha_inicio = $objeto->fecha_inicio;
        $fecha_fin = $objeto->fecha_fin;
        $valor = $objeto->valor;
        $valor_tipo = $objeto->valor_tipo;
        $competencias_id = $objeto->competencias_id;
        $usuario_id = $objeto->usuario_id;
        $created_at = date("Y-m-d H:i:s"); 

        // Esta consulta nos dice si la relacion entre usuario y competencia existe o debe ser creada
        $sql = "SELECT * FROM {$instance->tableUsuariosCompetencias} WHERE usuarios_vpcrs_id = {$usuario_id} and competencias_id = {$competencias_id}";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows): // Si la relacion existe simplemente se asgina esta aptitud a esa relacion
            $usuarios_competencias_id = $query->fetch_assoc()["id"]; 
            $sql = "INSERT INTO {$instance->tableUsuariosCompetenciasConocimientos}(titulo, certificado, acreditado_por, fecha_inicio, fecha_fin, valor, valor_tipo, usuarios_vpcrs_competencias_id, created_at) VALUES('{$titulo}', '{$certificado}', '{$acreditado_por}', '{$fecha_inicio}', '{$fecha_fin}', '{$valor}', '{$valor_tipo}', {$usuarios_competencias_id}, '{$created_at}')";
            $query = mysqli_query($conn, $sql);
        else: // Si la relacion no existe se debe crear y luego asignar la aptitud a esta relacion
            $sql = "INSERT INTO {$instance->tableUsuariosCompetencias}(usuarios_vpcrs_id, competencias_id, created_at) VALUES({$usuario_id}, {$competencias_id}, '{$created_at}')";
            $query = mysqli_query($conn, $sql);
            $usuarios_competencias_id = mysqli_insert_id($conn);

            $sql = "INSERT INTO {$instance->tableUsuariosCompetenciasConocimientos}(titulo, certificado, acreditado_por, fecha_inicio, fecha_fin, valor, valor_tipo, usuarios_vpcrs_competencias_id, created_at) VALUES('{$titulo}', '{$certificado}', '{$acreditado_por}', '{$fecha_inicio}', '{$fecha_fin}', '{$valor}', '{$valor_tipo}', {$usuarios_competencias_id}, '{$created_at}')";
            $query = mysqli_query($conn, $sql);
        endif;

        return mysqli_insert_id($conn);
    }

    /**
     * Update de usuarios competencias conocimientos
     *
     * @return void
     */
    public function update_usuario_competencia_conocimiento($objeto){
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $id = $objeto->id;
        $titulo = $objeto->titulo;
        $certificado = $objeto->certificado;
        $acreditado_por = $objeto->acreditado_por;
        $fecha_inicio = $objeto->fecha_inicio;
        $fecha_fin = $objeto->fecha_fin;
        $valor = $objeto->valor;
        $valor_tipo = $objeto->valor_tipo;
        $competencias_id = $objeto->competencias_id;
        $usuario_id = $objeto->usuario_id;
        $updated_at = date("Y-m-d H:i:s"); 

        $sql = "UPDATE {$instance->tableUsuariosCompetenciasConocimientos} SET titulo = '{$titulo}', certificado = '{$certificado}', acreditado_por = '{$acreditado_por}', 
        fecha_inicio = '{$fecha_inicio}', fecha_fin = '{$fecha_fin}', valor = '{$valor}', valor_tipo = '{$valor_tipo}', updated_at = '{$updated_at}' WHERE id = {$id}";
        $query = mysqli_query($conn, $sql);
        
        return (mysqli_affected_rows($conn) > 0);
    }
}