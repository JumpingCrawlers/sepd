<?php 
/**
 * Clase Evaluacion
 *
 * @return void
 */
class Evaluacion {

    public $id;
    public $formularioHabilidades;
    public $formularioAptitudes;
    public $puntosConocimientos;
    public $puntosHabilidades;
    public $puntosAptitudes;
    public $puntosMeritos;
    public $conocimientos;
    public $habilidades;
    public $aptitudes;
    public $competenciaID;

    /**
     * Constructor de la clase Evaluacion
     *
     * @return Evaluacion
     */
    public function __construct($id = null, $competenciaID = null, $puntosConocimientos, $puntosHabilidades, $puntosAptitudes, $puntosMeritos, $formularioHabilidades
    , $formularioAptitudes, $conocimientos = array(), $habilidades = array(), $aptitudes = array())
    {
        $this->id = $id;
        $this->competenciaID = $competenciaID;
        $this->puntosConocimientos = $puntosConocimientos;
        $this->puntosHabilidades = $puntosHabilidades;
        $this->puntosAptitudes = $puntosAptitudes;
        $this->puntosMeritos = $puntosMeritos;
        $this->formularioAptitudes = $formularioAptitudes;
        $this->conocimientos = $conocimientos;
        $this->habilidades = $habilidades;
        $this->aptitudes = $aptitudes;
        // $this->formularioHabilidades = json_decode($formularioHabilidades);
        $this->formularioHabilidades = $formularioHabilidades;

        // $this->formularioHabilidades = $this->formularioHabilidades[0] ? $this->formularioHabilidades[0] : $this->formularioHabilidades;

        // Una evaluacion siempre tiene un array de conocimientos, habilidades y aptitudes asociados, dependiendo del uso este array
        // se puede buscar por id de la evaluacion, id de la competencia o estar vacios
        if($competenciaID || $id)
        {
            if($id)
            {
                $this->conocimientos = $this->get_conocimientos_with_id();
                $this->habilidades = $this->get_habilidades_with_id();
                $this->aptitudes = $this->get_aptitudes_with_id();
            }
            else{
                $this->conocimientos = $this->get_conocimientos_with_competencia_id();
                $this->habilidades = $this->get_habilidades_with_competencia_id();
                $this->aptitudes = $this->get_aptitudes_with_competencia_id();
            }
        }
        else{
            $this->conocimientos = array();
            $this->habilidades = array();
            $this->aptitudes = array();
        }

    }

    /**
     * Obtener todos los conocimientos de una evaluacion con su id.
     *
     * @return competencias
     */
    function get_conocimientos_with_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $conocmientos = array();

        $sql = "SELECT * FROM ". $instance->tableEvaluacionesConocimientos . " WHERE evaluacion_id ='" . $this->id . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $conocimiento)
            {
                $conocimiento = new CompetenciaConocimiento($conocimiento["id"], $conocimiento["texto"]);
                array_push($conocmientos, $conocimiento); 
            }  
            return $conocmientos;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Obtener todas las habilidades de una evaluacion con su id.
     *
     * @return competencias
     */
    function get_habilidades_with_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $habilidades = array();

        $sql = "SELECT * FROM ". $instance->tableEvaluacionesHabilidades . " WHERE evaluacion_id ='" . $this->id . "'";
        $query = mysqli_query($conn,$sql);
        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $habilidad)
            {
                $habilidad = new CompetenciaHabilidad($habilidad["id"], $habilidad["texto"]);
                array_push($habilidades, $habilidad); 
            }  
            return $habilidades;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Obtener todas las aptitudes de una evaluacion con su id.
     *
     * @return competencias
     */
    function get_aptitudes_with_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $aptitudes = array();

        $sql = "SELECT * FROM ". $instance->tableEvaluacionesAptitudes . " WHERE evaluacion_id ='" . $this->id . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $aptitud)
            {
                $aptitud = new CompetenciaAptitud($aptitud["id"], $aptitud["texto"]);
                array_push($aptitudes, $aptitud); 
            }  
            return $aptitudes;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Obtener todos los conocimientos de una evaluacion con el id de 
     * la competencia.
     *
     * @return competencias
     */
    function get_conocimientos_with_competencia_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $conocmientos = array();

        $sql = "SELECT ec.* 
        FROM ".$instance->tableEvaluacionesConocimientos." as ec, ".$instance->tableEvaluaciones." as e 
        where e.competencias_id = ".$this->competenciaID." and e.id = ec.evaluacion_id";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $conocimiento)
            {
                $conocimiento = new CompetenciaConocimiento($conocimiento["id"], $conocimiento["texto"]);
                array_push($conocmientos, $conocimiento); 
            }  
            return $conocmientos;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Obtener todas los habilidades de una evaluacion con el id de 
     * la competencia.
     *
     * @return competencias
     */
    function get_habilidades_with_competencia_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $habilidades = array();

        $sql = "SELECT ec.* 
        FROM ".$instance->tableEvaluacionesHabilidades." as ec, ".$instance->tableEvaluaciones." as e 
        where e.competencias_id = ".$this->competenciaID." and e.id = ec.evaluacion_id";
        $query = mysqli_query($conn,$sql);
        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $habilidad)
            {
                $habilidad = new CompetenciaHabilidad($habilidad["id"], $habilidad["texto"]);
                array_push($habilidades, $habilidad); 
            }  
            return $habilidades;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }

    /**
     * Obtener todas los aptitudes de una evaluacion con el id de 
     * la competencia.
     *
     * @return competencias
     */
    function get_aptitudes_with_competencia_id()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();
        $aptitudes = array();

        $sql = "SELECT ec.* 
        FROM ".$instance->tableEvaluacionesAptitudes." as ec, ".$instance->tableEvaluaciones." as e 
        where e.competencias_id = ".$this->competenciaID." and e.id = ec.evaluacion_id";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            foreach($query as $aptitud)
            {
                $aptitud = new CompetenciaAptitud($aptitud["id"], $aptitud["texto"]);
                array_push($aptitudes, $aptitud); 
            }  
            return $aptitudes;// Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }
}