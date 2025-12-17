<?php 
    include('view_loader.php');
    front_controller();

    $config = include '..\..\..\config\cookie-consent.php'; //Comprueba si la configuracion de cookies permite crear la cookie
    if(isset($_COOKIE[$config['cookie_name']]) && strpos($_COOKIE[$config['cookie_name']], $config['cookies-internas']) !== false){
        if (!isset($_COOKIE["id"])): // Se coloca una competencia por default en caso de que el usuario quiera nevegar por su cuenta 
            $cookie_name = "id";
            $cookie_value = 1;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        endif;
    }
    include('templates/header.php');
?>
    <main>
        <div class="container">
            <div class="modal-header bg-institucional" style="margin: 0px; margin-bottom: -2px;">
                <h5 class="modal-title text-white" id="modalLoginTitulo">Verificación OMC</h5>
            </div>

            <div class="modal-content" style="border: 1px solid #4e25cc">
                <div class="modal-body" style="border: 0px">
                    <div class="container">
                        <div class="row">
                            <div class="col-10 offset-1" style="border: 0px">
                                <input type="hidden" name="_token" value="rTdQEqRVhMgBwP1WaWDo4R80oswJ05EbHI1vXNK1">
                                <label style="margin-left: -15px" for="email">Ingrese Número de colegiado de la OMC</label> 

                                <div class="form-group row">
                                    <div class="col-xs-2">
                                        <div class="input-group">
                                            <form action="confirmacion.php?action=validar" style="border: 0px" method="POST" class="numero_omc__wrapper">
                                                <div class="popup" onclick="myFunction()">
                                                    <span class="popuptext" id="myPopup">Numero invalido</span>
                                                    <input name="numeroCGCOM" class="form-control input-sm" required="required" pattern="[0-9]{1,20}" type="text">
                                                </div>

                                                <span class="input-group-btn" style="margin-left: 5px"></span>
                                                    <button type="submit" class="sombra-boton btn btn-orange">Verificar</button>
                                                </span>
                                            </form>
                                        </div>
                                    </div> 
                                    <div>
                                        <p>&nbsp;</p>
                                        <p>Para poder comprobar su n&uacute;mero de colegiado con la base de datos que dispone la <strong>Organizaci&oacute;n M&eacute;dica Colegial</strong> necesitamos que cumpla con la siguiente estructura:</p>
                                        <div class="alert alert-primary text-center" role="alert"><strong>XXYYZZZZZ</strong></div>
                                        <p>Donde:</p>
                                        <p><strong>XX</strong> = C&oacute;digo del Colegio Oficial de M&eacute;dicos (C.O.M) donde est&aacute; actualmente colegiado.</br>
                                        <strong>YY</strong> = C&oacute;digo del C.O.M donde se colegi&oacute; por primera vez.</br>
                                        Si coincide el C.O.M de inicio con el actual deber&aacute; repetir el c&oacute;digo.</br>
                                        <strong>ZZZZZ</strong> = N&uacute;mero correlativo asignado por su C.O.M.</p>
                                        <p>Ejemplos:</p>
                                        <p>(N&ordm; colegiado Madrid): 2828XXXXX</br>
                                        (N&ordm; colegiado Sevilla): 4141XXXXX</br>
                                        (N&ordm; colegiado Barcelona): 0808XXXXX</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('templates/footer.php'); ?>

    <?php if (isset($_GET["action"]) && ($_GET["action"] == "invalid_number" || $_GET["action"] == "no_conection")): ?>
    <script type="text/javascript">
        let popup = document.getElementById('myPopup');

        <?php if ($_GET["action"] == "invalid_number"): ?>
            popup.innerText = "Numero invalido";
        <?php elseif ($_GET["action"] == "no_conection"): ?>
            popup.innerText = 'No se ha podido establecer la conexión con OMC';
        <?php endif; ?>

        popup.classList.toggle('show');
    </script>
    <?php endif; ?>
</body>
</html>