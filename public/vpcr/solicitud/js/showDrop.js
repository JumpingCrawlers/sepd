/**
 * Funcion que muestra un menu dropdown
 *
 * @return void
 */
function show_drop() {
    var dropdown = document.getElementsByClassName("dropdown-menu")
    dropdown = dropdown[0]
    dropdown.classList.toggle("show");
}