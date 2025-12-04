<?php
include('view_loader.php');
front_controller();

include('templates/header.php');
?>

<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 800px !important;
            margin-top: 12rem !important;
        }
    }
</style>

<main>
    <?php if (check_vpc(isset($_POST["numeroCGCOM"]) ? $_POST["numeroCGCOM"] : get_user_num_colegiado()) === 'No encontrado') { ?>
        <div class="modal fade show" id="modalAlertVPC" tabindex="-1" role="dialog" aria-labelledby="modalAlertVPCLabel" aria-hidden="true" style="display: block;background-color: rgba(0,0,0,0.4);">
            <div class="modal-dialog" role="document">
                <div class="modal-content p-3">
                    <div class="modal-body">
                        <p>
                            Para poder Recertificarse en Aparato Digestivo deberá tener en vigor la Validación Periódica de la Colegiación, la cual ya está disponible en la página web del Consejo General de Colegios Oficiales de Médicos.<br>
                            Para cualquier consulta o aclaración póngase en contacto con nosotros a través del correo <a href="mailto:validacion@sepd.es" target="_blank" rel="validacion sepd">validacion@sepd.es</a>
                        </p>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" onclick="clossedModal()" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="container">
        <div class="modal-header bg-institucional" style="margin: 0px; margin-bottom: -2px;">
            <h5 class="modal-title text-white" id="modalLoginTitulo">Confirmar datos de solicitud</h5>
        </div>

        <div class="modal-content" style="border: 1px solid #4e25cc">
            <div class="modal-body" style="border: 0px">
                <div class="container fz-2">
                    Estos son los datos con los que se iniciará la solicitud de VPC-R:<br>
                    <b>Nombre:</b> <?= get_user_name_completo() ?><br>
                    <b>Número de colegiado:</b> <?= ((isset($_POST["numeroCGCOM"])) ? $_POST["numeroCGCOM"] : get_user_num_colegiado()) ?><br><br>
                    <b>Estado VPC:</b> <?= check_vpc(isset($_POST["numeroCGCOM"]) ? $_POST["numeroCGCOM"] : get_user_num_colegiado()) ?><br><br>

                    <form action="confirmacion.php?action=confirmar" class="p-0 right save-buttons" method="POST">
                        <input type="hidden" name="numeroCGCOM" value="<?= ((isset($_POST["numeroCGCOM"])) ? $_POST["numeroCGCOM"] : get_user_num_colegiado()) ?>" />

                        <a href="confirmacion.php?action=cambiar">
                            <button type="button" class="sombra-boton btn btn-red">Corregir número de colegiado</button>
                        </a>
                        <?php if (check_vpc(isset($_POST["numeroCGCOM"]) ? $_POST["numeroCGCOM"] : get_user_num_colegiado()) !== 'No encontrado') { ?>
                            <button type="submit" class="sombra-boton btn btn-green">Confirmar e iniciar solicitud</button>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('templates/footer.php'); ?>
</body>

</html>

<script>
    function clossedModal() {
        document.getElementById("modalAlertVPC").style.display = "none";
    }
</script>