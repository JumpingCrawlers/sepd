/**
 * Funcion que muestra un popup de alerta
 *
 * @return void
 */
function myFunction() {
    var popup = document.getElementById("myPopup");
    popup.classList.remove("show");
  }

/**
 * Funcion lanza una alerta
 *
 * @return void
 */
function myalert() 
{
  inputNumero 
  var inputNumero = document.getElementById("inputNumero");
  if (inputNumero.value != "" && inputNumero.value.length > 0){
      alert("Numero verificado."); 
      var boton = document.getElementById("botonDeshabilitado");
      boton.disabled = false;
  }
  else{
      alert("El numero no es valido"); 
  }
} 