<?php 

/**
 * Funcion que selecciona todas las competencias transversales.
 *
 * @return array
 */
function get_all_transversales_controller(){
        $instance = dataBase::getInstance();
        $transversales = $instance->select_all_transversales();
        $listaTransversales = array();
   
        foreach ($transversales as $transversal) //Se convierte el array sql a un array con claves para su manejo en el front.
        {
            
            $listaConocimientos= array();
            $listaHabilidades = array();
            $listaAptitudes = array();
            foreach($transversal->conocimientos as $conocimiento)//Cada competencia posee un array con claves para sus conocimeintos, habilidades y aptitudes.
            {
                if (is_null($conocimiento->deleted_at)) {
                    $auxConocimiento = array('id' => $conocimiento->id,'texto' => $conocimiento->texto, 'deleted_at' => $conocimiento->deleted_at);
                    array_push($listaConocimientos , $auxConocimiento); 
                }
            }
            foreach($transversal->habilidades as $habilidad)
            {
                if (is_null($habilidad->deleted_at)) {
                    $auxHabilidad = array('id' => $habilidad->id,'texto' => $habilidad->texto, 'deleted_at' => $habilidad->deleted_at);
                    array_push($listaHabilidades , $auxHabilidad); 
                }
            }
            foreach($transversal->aptitudes as $aptitud)
            {
                if (is_null($aptitud->deleted_at)) {
                    $auxAptitud = array('id' => $aptitud->id,'texto' => $aptitud->texto, 'deleted_at' => $aptitud->deleted_at);
                    array_push($listaAptitudes , $auxAptitud); 
                }
            }
            
            $auxComp = array('id' => $transversal->id, 'titulo' => $transversal->titulo, 'deleted_at' => $transversal->deleted_at, 'conocimientos' => $listaConocimientos, 
            'habilidades' => $listaHabilidades, 'aptitudes' => $listaAptitudes);
            array_push($listaTransversales , $auxComp); 
        }
                
        return $listaTransversales;
}

/**
 * Funcion que selecciona una competencia
 *
 * @return array
 */
function get_competencia_by_id($id)
{
    $instance = dataBase::getInstance();
    $competencia = $instance->select_competencia_by_id($id);

    $listaConocimientos= array();
    $listaHabilidades = array();
    $listaAptitudes = array();
    foreach($competencia->conocimientos as $conocimiento)//Una competencia posee un array con claves para sus conocimeintos, habilidades y aptitudes.
    {
        $auxConocimiento = array('id' => $conocimiento->id,'texto' => $conocimiento->texto);
        array_push($listaConocimientos , $auxConocimiento); 
    }
    foreach($competencia->habilidades as $habilidad)
    {
        $auxHabilidad = array('id' => $habilidad->id,'texto' => $habilidad->texto);
        array_push($listaHabilidades , $auxHabilidad); 
    }
    foreach($competencia->aptitudes as $aptitud)
    {
        $auxAptitud = array('id' => $aptitud->id,'texto' => $aptitud->texto);
        array_push($listaAptitudes , $auxAptitud); 
    }

    $listaEvaConocimientos= array();
    $listaEvaHabilidades = array();
    $listaEvaAptitudes = array();

    foreach($competencia->evaluacion->conocimientos as $conocimiento)//Una competencia posee un array con claves para evaluar sus conocimeintos, habilidades y aptitudes.
    {
        $auxConocimiento = array('id' => $conocimiento->id,'texto' => $conocimiento->texto);
        array_push($listaEvaConocimientos , $auxConocimiento); 
    }
    foreach($competencia->evaluacion->habilidades as $habilidad)
    {
        $auxHabilidad = array('id' => $habilidad->id,'texto' => $habilidad->texto);
        array_push($listaEvaHabilidades , $auxHabilidad); 
    }
    foreach($competencia->evaluacion->aptitudes as $aptitud)
    {
        $auxAptitud = array('id' => $aptitud->id,'texto' => $aptitud->texto);
        array_push($listaEvaAptitudes , $auxAptitud); 
    }

    $evaluacion = array ('id' => $competencia->evaluacion->id, 'competenciaID' => $competencia->evaluacion->competenciaID, 'puntosConocimientos' => $competencia->evaluacion->puntosConocimientos, 
    'puntosHabilidades' => $competencia->evaluacion->puntosHabilidades, 'puntosAptitudes' => $competencia->evaluacion->puntosAptitudes, 
    'puntosMeritos' => $competencia->evaluacion->puntosMeritos, 'formularioHabilidades' => $competencia->evaluacion->formularioHabilidades, 
    'formularioAptitudes' => $competencia->evaluacion->formularioAptitudes, 'conocimientos' => $listaEvaConocimientos,
    'habilidades' => $listaEvaHabilidades, 'aptitudes' => $listaEvaAptitudes);
    
    $auxComp = array('id' => $competencia->id,'titulo' => $competencia->titulo, 'conocimientos' => $listaConocimientos, 
    'habilidades' => $listaHabilidades, 'aptitudes' => $listaAptitudes, 'evaluacion' => $evaluacion);
    
    return $auxComp;
}