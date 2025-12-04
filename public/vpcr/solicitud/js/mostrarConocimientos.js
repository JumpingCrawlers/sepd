/**
 * Funcion que muestra un conocimiento
 *
 * @return void
 */
function mostrar_conocimiento(element) {
    var conociminto = element.classList;
    var aux = conociminto[1]
    conociminto = "."+conociminto[1]+"_panel"
    var conocimientoPanel = document.querySelector(conociminto)

    //var target = event.target ? event.target : event.srcElement;

    //if(target.id===aux) { 
        conocimientoPanel.classList.toggle("display__alt");
    //}
}

/** NO EN USO
 * Funcion que muestra el boton de salvado en conocimeinto si el titulo es valido
 *
 * @return void
 */
function mostrar_conocimiento_boton(element) {
    var conociminto = element.classList;
    var aux = conociminto[1]
    conociminto = "."+conociminto[1]+"_panel"
    var conocimientoPanel = document.querySelector(conociminto)

    //var target = event.target ? event.target : event.srcElement;

    //if(target.id===aux) { 
    //}
    var titulo = document.querySelector(".st" + aux)
    console.log(titulo.value)
    if (titulo.value == "" || titulo.value == null || titulo.value .length < 2){
        alert("El titulo no es valido!")
        return
    }
    conocimientoPanel.classList.toggle("display__alt");
    var tituloPrincipal = document.querySelector(".mt" + aux)
    tituloPrincipal.textContent = titulo.value

}

