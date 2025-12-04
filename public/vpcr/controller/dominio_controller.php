<?php 

/**
     * Funcion que selecciona todos los dominios.
     *
     * @return id
     */
function get_all_dominios_controller(){
        $instance = dataBase::getInstance();
        $dominios = $instance->select_all_dominios();
        $listaDominio = array();

        foreach($dominios as $dominio)//Se convierte el array sql a un array por claves para su manejo en el front.
        {
			$listaCompetencias = array();
			foreach ($dominio->competencias as $competencia)// Cada dominio tiene competencias asociadas que se colocan en un array por claves
			{
				
				$listaConocimientos= array();
				$listaHabilidades = array();
				$listaAptitudes = array();
				foreach($competencia->conocimientos as $conocimiento)// Cada competencia tiene conocimientos asociadas que se colocan en un array por claves
				{
					$auxConocimiento = array('id' => $conocimiento->id,'texto' => $conocimiento->texto);
					array_push($listaConocimientos , $auxConocimiento); 
				}
				foreach($competencia->habilidades as $habilidad)// Cada competencia tiene habilidades asociadas que se colocan en un array por claves
				{
					$auxHabilidad = array('id' => $habilidad->id,'texto' => $habilidad->texto);
					array_push($listaHabilidades , $auxHabilidad); 
				}
				foreach($competencia->aptitudes as $aptitud)// Cada competencia tiene aptitudes asociadas que se colocan en un array por claves
				{
					$auxAptitud = array('id' => $aptitud->id,'texto' => $aptitud->texto);
					array_push($listaAptitudes , $auxAptitud); 
				}

				$listaEvaConocimientos= array();
				$listaEvaHabilidades = array();
				$listaEvaAptitudes = array();
		
				foreach($competencia->evaluacion->conocimientos as $conocimiento)// Cada competencia tiene evaluaciones para sus conocimientos asociadas que se colocan en un array por claves
				{
					$auxConocimiento = array('id' => $conocimiento->id,'texto' => $conocimiento->texto);
					array_push($listaEvaConocimientos , $auxConocimiento); 
				}
				foreach($competencia->evaluacion->habilidades as $habilidad)// Cada competencia tiene evaluaciones para sus habilidades asociadas que se colocan en un array por claves
				{
					$auxHabilidad = array('id' => $habilidad->id,'texto' => $habilidad->texto);
					array_push($listaEvaHabilidades , $auxHabilidad); 
				}
				foreach($competencia->evaluacion->aptitudes as $aptitud)// Cada competencia tiene evaluaciones para sus aptitudes asociadas que se colocan en un array por claves
				{
					$auxAptitud = array('id' => $aptitud->id,'texto' => $aptitud->texto);
					array_push($listaEvaAptitudes , $auxAptitud); 
				}

				$evaluacion = array ('id' => $competencia->evaluacion->id, 'competenciaID' => $competencia->evaluacion->competenciaID, 
				'puntosHabilidades' => $competencia->evaluacion->puntosHabilidades, 'puntosAptitudes' => $competencia->evaluacion->puntosAptitudes, 
				'puntosMeritos' => $competencia->evaluacion->puntosMeritos, 'formularioHabilidades' => $competencia->evaluacion->formularioHabilidades, 
				'formularioAptitudes' => $competencia->evaluacion->formularioAptitudes, 'conocimientos' => $listaEvaConocimientos,
				'habilidades' => $listaEvaHabilidades, 'aptitudes' => $listaEvaAptitudes);
				
				$auxComp = array('id' => $competencia->id,'titulo' => $competencia->titulo, 'conocimientos' => $listaConocimientos, 
				'habilidades' => $listaHabilidades, 'aptitudes' => $listaAptitudes, 'evaluacion' => $evaluacion);
				array_push($listaCompetencias , $auxComp); 
			}
			$auxDom = array('id' => $dominio->id, 'titulo' => $dominio->titulo, 'competencias' => $listaCompetencias);
			array_push($listaDominio, $auxDom); 
        }
        
        return $listaDominio;
}