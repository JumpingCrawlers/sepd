/**
 * Funcion que muestra una competencias
 *
 * @return void
 */
function mostrar_competencia(element) {
    var competencia = element.classList;
    competencia = "."+competencia[1]+"_panel"
    var competenciaPanel = document.querySelector(competencia)
    if (competenciaPanel.classList.contains("display")){
        $(".competencias__validar").removeClass("display");
        competenciaPanel.classList.toggle("display");
    }
    else{
        $(".competencias__validar").removeClass("display");
    }
    competenciaPanel.classList.toggle("display");
}

/**
 * Funcion que muestra una lista de competencias
 *
 * @return void
 */
function mostrar_competencias(element) {
    var dominio = element.classList;
    dominio = ".lista_"+dominio[1]
    var dominioPanel = document.querySelector(dominio)
    if (dominioPanel.classList.contains("display")){
        $(".listas_competencias").removeClass("display");
        dominioPanel.classList.toggle("display");
    }
    else{
        $(".listas_competencias").removeClass("display");
    }
    dominioPanel.classList.toggle("display");
}