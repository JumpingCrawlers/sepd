<?php 

/**
 * Clase competencias
 *
 * @return void
 */
class Competencia {

    public $id;
    public $titulo;
    public $conocimientos;
    public $habilidades;
    public $aptitudes;
    public $evaluacion;
    public $deleted_at;

    /**
     * Constructor de competencias
     *
     * @return Competencia
     */
    public function __construct($id = null, $titulo, $dominio_id = null, $conocimientos = null , $habilidades = null , $aptitudes = null, $evaluacion = null)
    {
        $this->titulo = $titulo;
        $this->conocimientos = $conocimientos;
        $this->habilidades = $habilidades;
        $this->aptitudes = $aptitudes;
        // En un caso normal la competencia siempre tiene un id asociado en caso de que no se busca el id en base al nombre de la competencia
        if ($id != null)
        {
            $this->id = $id;
        }
        else{
            $this->id = $this->get_id_with_name();   
        }
        
        // Una competencia siempre tiene un array de conocimientos
        if(!$this->conocimientos && $id)
        {
            $this->conocimientos =$this->get_conocimientos_with_id();
        }
        else{
            $this->conocimientos = array();
        }
        
        // Una competencia siempre tiene un array de habilidades
        if(!$this->habilidades && $id)
        {
            $this->habilidades =$this->get_habilidades_with_id();
        }
        else{
            $this->habilidades = array();
        }
        
        // Una competencias siempre tiene un array de aptitudes
        if(!$this->aptitudes && $id)
        {
            $this->aptitudes =$this->get_aptitudes_with_id();
        }
        else{
            $this->aptitudes = array();
        }

        // Una competencias siempre tiene una evaluacion 
        if (!$evaluacion)
        {
            $this->evaluacion =$this->get_evaluacion_with_competencia_id();
        }
        else{
            $this->evaluacion = $evaluacion;
        }
    }

    /**
     * Funcion que regresa el id de la competencia 
     * con el nombre, de haber competendias con el mismo nombre
     * retorna la primera
     *
     * @return id
     */
    function get_id_with_name()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();

        $sql = "SELECT * FROM ". $instance->tableCompetencias . " WHERE titulo ='" . $this->titulo . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            return $query->fetch_assoc()["id"]; // Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Funcion que retorna todos los conocimientos de una competencia.
     *
     * @return conocimientos
     */
    function get_conocimientos_with_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $conocmientos = array();

        $sql = "SELECT * FROM ". $instance->tableCompetenciasConocimientos . " WHERE competencias_id ='" . $this->id . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $conocimiento)
            {
                $deleted_at = $conocimiento["deleted_at"];
                $conocimiento = new CompetenciaConocimiento($conocimiento["id"], $conocimiento["texto"]);
                $conocimiento->set_deleted_at($deleted_at);
                if (is_null($deleted_at))
                    array_push($conocmientos, $conocimiento); 
            }  
            return $conocmientos;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Funcion que retorna todas las habilidades de una competencia.
     *
     * @return habilidades
     */
    function get_habilidades_with_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $habilidades = array();

        $sql = "SELECT * FROM ". $instance->tableCompetenciasHabilidades . " WHERE competencias_id ='" . $this->id . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $habilidad)
            {
                $deleted_at = $habilidad["deleted_at"];
                $habilidad = new CompetenciaHabilidad($habilidad["id"], $habilidad["texto"]);
                $habilidad->set_deleted_at($deleted_at);
                if (is_null($deleted_at))
                    array_push($habilidades, $habilidad); 
            }  
            return $habilidades;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Funcion que retorna todas las aptitudes de una competencia.
     *
     * @return aptitudes
     */
    function get_aptitudes_with_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $aptitudes = array();

        $sql = "SELECT * FROM ". $instance->tableCompetenciasAptitudes . " WHERE competencias_id ='" . $this->id . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $aptitud)
            {
                $deleted_at = $aptitud["deleted_at"];
                $aptitud = new CompetenciaAptitud($aptitud["id"], $aptitud["texto"]);
                $aptitud->set_deleted_at($deleted_at);
                if (is_null($deleted_at))
                    array_push($aptitudes, $aptitud); 
            }  
            return $aptitudes;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Funcion que retorna la evaluacion de una competencia.
     *
     * @return evaluacion
     */
    function get_evaluacion_with_competencia_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $aptitudes = array();

        $sql = "SELECT * FROM ". $instance->tableEvaluaciones . " WHERE competencias_id ='" . $this->id . "' AND deleted_at IS NULL";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $evaluacion)
            {
                $evaluacion = new Evaluacion($evaluacion["id"], $evaluacion["competencias_id"], $evaluacion["puntos_conocimientos"], $evaluacion["puntos_habilidades"], $evaluacion["puntos_aptitudes"]
                , $evaluacion["puntos_meritos"], $evaluacion["formulario_habilidades"], $evaluacion["formulario_aptitudes"]);
            }  
            return $evaluacion;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    function set_deleted_at ($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }
}