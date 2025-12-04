<?php 
/**
 * Clase Usuario Competencia Merito
 *
 * @return void
 */
class UsuarioCompetenciaMerito {

    public $id;
    public $archivo;
    public $competencias_id;
    public $usuario_id;

    /**
     * Constructor de la Clase Usuario Competencia Merito
     *
     * @return void
     */
    public function __construct($archivo, $competencias_id, $usuario_id, $id = null)
    {
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $this->archivo = mysqli_real_escape_string($conn, $archivo);
        $this->competencias_id = mysqli_real_escape_string($conn, $competencias_id);
        $this->usuario_id = $usuario_id;
        $this->id = $id;
    }

    /**
     * Insert de usuarios competencias Merito
     *
     * @return void
     */
    public function create_usuario_competencia_meritos($objeto){
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $archivo = $objeto->archivo;
        $competencias_id = $objeto->competencias_id;
        $usuario_id = $objeto->usuario_id;
        $created_at = date("Y-m-d H:i:s"); 

        // Esta consulta nos dice si la relacion entre usuario y competencia existe o debe ser creada
        $sql = "SELECT * FROM {$instance->tableUsuariosCompetencias} WHERE usuarios_vpcrs_id = {$usuario_id} AND competencias_id = {$competencias_id}";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows > 0): // Si la relacion existe simplemente se asgina esta aptitud a esa relacion
            $usuarios_competencias_id = $query->fetch_assoc()["id"]; 
            $sql =  "INSERT INTO {$instance->tableUsuariosCompetenciasMeritos}(archivo, usuarios_vpcrs_competencias_id, created_at) VALUES('{$archivo}', {$usuarios_competencias_id}, '{$created_at}')";
            $query = mysqli_query($conn, $sql);
        else: // Si la relacion no existe se debe crear y luego asignar la aptitud a esta relacion 
            $sql = "INSERT INTO {$instance->tableUsuariosCompetencias}(usuarios_vpcrs_id, competencias_id, created_at) VALUES({$usuario_id}, {$competencias_id}, '{$created_at}')";
            $query = mysqli_query($conn, $sql);
            $usuarios_competencias_id = mysqli_insert_id($conn);

            $sql = "INSERT INTO {$instance->tableUsuariosCompetenciasMeritos}(archivo, usuarios_vpcrs_competencias_id, created_at) VALUES('{$archivo}', {$usuarios_competencias_id}, '{$created_at}')";
            $query = mysqli_query($conn, $sql);
        endif;

        return mysqli_insert_id($conn);
    }

    /**
     * Update de usuarios competencias Merito
     *
     * @return void
     */
    public function update_usuario_competencia_merito($objeto){
        $instance = DataBase::getInstance();
        $conn = $instance->getConnection();

        $id = $objeto->id;
        $archivo = $objeto->archivo;
        $competencias_id = $objeto->competencias_id;
        $usuario_id = $objeto->usuario_id;
        $updated_at = date("Y-m-d H:i:s"); 

        $sql = "UPDATE {$instance->tableUsuariosCompetenciasMeritos} SET archivo = '{$archivo}', updated_at = '{$updated_at}' WHERE id = {$id}";
        $query = mysqli_query($conn, $sql);

        return (mysqli_affected_rows($conn) > 0);
    }
}