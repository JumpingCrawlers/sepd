<?php 

/**
 * Clase Competencia conocimiento
 *
 * @return void
 */
class CompetenciaConocimiento {

    public $id;
    public $texto;
    public $deleted_at;

    /**
     * Constructor de competencia conocimiento
     *
     * @return CompetenciaConocimiento
     */
    public function __construct($id = null, $texto)
    {
        $this->texto = $texto;
        // El conocimiento de una competencias siempre tiene que tener un id asociado
        if ($id != null)
        {
            $this->id = $id;
        }
        else{
            $this->id = $this->get_id_with_text();   
        }
         
    }

    /**
     * Funcion que regresa el id de la competencia conocimiento 
     * con el texto interno, de haber conocimientos con el mismo texto
     * retorna la primera
     *
     * @return id
     */
    function get_id_with_text()
    {
        $instance = dataBase::getInstance();
        $conn = $instance->getConnection();

        $sql = "SELECT * FROM ". $instance->tableCompetenciasConocimientos . " WHERE texto ='" . $this->texto . "'";
        $query = mysqli_query($conn,$sql);

        // Comprobación
        $num_rows = mysqli_num_rows($query);

        if( $num_rows ){
            return $query->fetch_assoc()["id"]; // Objeto tipo Base de datos. Es muy similar a un array bidimensional
        }

        return null;
    }


    function set_deleted_at ($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }
}