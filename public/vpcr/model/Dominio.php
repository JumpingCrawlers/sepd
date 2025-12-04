<?php 

/**
 * Clase Dominio
 *
 * @return void
 */
class Dominio {

    public $id;
    public $titulo;
    public $competencias = array();

    /**
     * Constructor de la clase dominio
     *
     * @return Dominio
     */
    public function __construct($id = null, $titulo, $competencias = null)
    {
        $this->titulo = $titulo;
        
        // Un dominio siempre tiene una lista de competencias asociadas a su id
        if ($competencias != null)
        {
            $this->id = $id;
        }
        else{
            $this->id = $this->get_id_with_name();
        }
        
        // Un dominio siempre tiene una lista de competencias asociadas a su id
        if ($competencias != null)
        {
            $this->competencias = $competencias;
        }
        else{
            $this->competencias = $this->get_competencias();
        }
        
    }

    /**
     * Obtener todas las competencias de un dominio.
     *
     * @return competencias
     */
    function get_competencias()
    {
        if ($this->id != null)
        {
            $instance = dataBase::getInstance();
            $conn = $instance->getConnection();

            $sql = "SELECT * FROM ". $instance->tableCompetencias . " WHERE dominio_id ='" . $this->id . "'";
            $query = mysqli_query($conn, $sql);

            // Comprobación
            $num_rows = mysqli_num_rows($query);

            if( $num_rows ){
               foreach($query as $comp)
               {
                   $competencia = new Competencia($comp["id"], $comp["titulo"]);
                   array_push($this->competencias, $competencia); 
               } 
               return $this->competencias;
            }
            }else{
                return null;
            }
    }

    /**
     * Funcion que regresa el id del dominio 
     * con el nombre, de haber dominios con el mismo texto
     * retorna el primero
     *
     * @return id
     */
    function get_id_with_name()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();

        $sql = "SELECT * FROM ". $instance->tableDominios . " WHERE titulo ='" . $this->titulo . "'";
        $query = mysqli_query($conn, $sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            return $query->fetch_assoc()["id"]; // Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }
}