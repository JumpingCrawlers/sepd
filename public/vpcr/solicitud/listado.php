<?php
    if (isset($_POST["competencia"])): // Si el usuario selecciona una competencia esta se coloca dentro de un cookie, esto permite que el usuario pueda navegar a esta ventana por su cuenta.
        $config = include '../../../config/cookie-consent.php';
        if(isset($_COOKIE[$config['cookie_name']]) && strpos($_COOKIE[$config['cookie_name']], $config['cookies-internas']) !== false){
            $cookie_name = "id";
            $cookie_value = $_POST["competencia"];
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            header("Location: validar.php");
        }
    endif;

    include('view_loader.php');
    front_controller();

    $listaUsuarioCompetencia = get_all_usuarios_competencias(); // Se obtiene un listado de los dominios con sus competencias para listar
    $dominios = get_all_dominios_controller(); // Obtener todos los dominios
    $transversales = get_all_transversales_controller(); // Obtener todas las competencias transversales

    include('templates/header.php');
?>
    <main role="main">
        <div class="main__wrapper">
            <div class="content__wrapper">
                <div class="title__wrapper">
                    <h2 class="main__title">Validación Periódica de la Colegiación-Recertificación</h2>
                </div>

                <p class="main__text fz-1">
                    Se debe completar un mínimo de <b><?php echo min_competencias_especificas(); ?> competencias específicas</b> y <b><?php echo min_competencias_transversales(); ?> competencia transversal</b> para poder optar por la re-certificación, las competencias se describen a continuación:<br><br>
                    Para poder ver la información de cada apartado debe hacer clic en el título.
                </p>

                <div class="competencias__wrapper">
                    <h2 class="main__title">Competencias específicas</h2>

                    <div class="lista_competencias__wrapper">
                        <?php $counter = 1; ?>
                        <?php $counterComp = 1; ?>
                        <?php foreach($dominios as $dominio): ?>
                            <div class="dominio__wrapper dominio<?= $dominio["id"] ?>" onclick="mostrar_competencias(this);">
                                <button class="selecciona__competencia dominio<?= $dominio["id"] ?>">
                                    Dominio <?= integer_to_roman($counter) . ": {$dominio["titulo"]}" ?> <?= (dominio_contains_completed_competencias($dominio["id"]) ? '<i class="fas fa-circle circle-small ml-2"></i>' : '') ?>
                                </button>
                                
                                <button class="selecciona__competencia"><i class="arrow"></i></button>
                            </div>

                            <div class="listas_competencias lista_dominio<?= $counter ?>">
                                <?php foreach($dominio["competencias"] as $competencia): ?>
                                    <div class="wrapper_botton"> 
                                        <div class="lista_competencias__panel competencia<?= $counterComp ?>" onclick="mostrar_competencia(this);">
                                            <button class="selecciona__competencia competencia<?= $counterComp ?>"><?= "{$counterComp}. {$competencia["titulo"]}" ?></button>
                                            <button class="selecciona__competencia competencia<?= $counterComp ?>"><i class="arrow"></i></button>
                                        </div>
                                        
                                        <?php if (isset($listaUsuarioCompetencia["$counterComp"])): // Si ya ha guardado datos en la competencia ?>
                                        <?php $usuario_vpcr = get_user_vpcr()->fetch_assoc(); ?>
                                        <?php $usuario_competencia = $listaUsuarioCompetencia["$counterComp"]; ?>
                                        <?php if ((isset($usuario_vpcr["fecha_enviado"]) && !empty($usuario_vpcr["fecha_enviado"]) && isset($usuario_competencia["observaciones"]) && !empty($usuario_competencia["observaciones"]) && isset($usuario_vpcr["fecha_envio_observaciones"]) && !empty($usuario_vpcr["fecha_envio_observaciones"])) && ((strtotime($usuario_vpcr["fecha_envio_observaciones"]) > strtotime($usuario_vpcr["fecha_enviado"])) && (strtotime($usuario_vpcr["fecha_envio_observaciones"]) > $usuario_competencia["updated_at"]))): // Si hay usuarios_vpcrs.fecha_enviado, usuarios_vpcrs.observaciones y usuarios_vpcrs.fecha_envio_observaciones se comprueba si usuarios_vpcrs.fecha_envio_observaciones > usuarios_vpcrs.fecha_enviado y usuarios_vpcrs.fecha_envio_observaciones > usuarios_vpcrs_competencias.updated_at ?>
                                                <form method="POST" class="boton_competencia" action="./listado.php">
                                                    <button class="btn competencia__guardar__enviado btn-red">Corregir</button>
                                                    <input class="post__aux" name="competencia" value="<?= $competencia["id"] ?>" />
                                                </form>
                                            <?php else: // Si ya ha corregido los errores, no hay errores en la competencia o aún no ha finalizado el proceso ?>  
                                                <form method="POST" class="boton_competencia" action="./listado.php">
                                                    <button class="btn competencia__guardar__enviado btn-gray">Modificar</button>
                                                    <input class="post__aux" name="competencia" value="<?= $competencia["id"] ?>" />
                                                </form>
                                            <?php endif; ?>
                                        <?php else: // Si no ha guardado ningún dato aún ?>
                                            <form method="POST" class="boton_competencia evaluar_competencia" action="./listado.php">
                                                <button class="btn competencia__guardar btn-orange">Evaluar</button>
                                                <input class="post__aux" name="competencia" value="<?= $competencia["id"] ?>" />
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="competencias__validar competencia<?= $counterComp ?>_panel">
                                        <h2 class="competencias__conocimiento_titulo">Conocimientos</h2>
                                        
                                        <ul class="competencias__lista_conocimientos">
                                            <?php foreach($competencia["conocimientos"] as $conocimiento): ?>
                                            <li class="conocimientos__item"><?= $conocimiento["texto"] ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <div class="conocimientos__lista lista_c<?= $counterComp ?>_panel"></div>
                                        <h2 class="competencias__habilidades">Habilidades</h2>
                                        
                                        <ul class="competencias__lista_habilidades">
                                            <?php foreach($competencia["habilidades"] as $habilidad): ?>
                                            <li class="habilidades__item"><?= $habilidad["texto"] ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <h2 class="competencias__Aptitudes">Aptitudes</h2>

                                        <ul class="competencias__lista_Aptitudes">
                                            <?php foreach($competencia["aptitudes"] as $aptitud): ?>
                                            <li class="habilidades__item"><?= $aptitud["texto"] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php $counterComp++; ?>
                                <?php endforeach; ?>
                                </div>
                            <?php $counter++; ?>
                            <?php endforeach; ?>
                        </div>

                        <h2 class="main__title">Competencias transversales</h2>

                        <div class="lista_competencias__wrapper">
                            <?php foreach($transversales as $competencia): ?> 
                                <?php if (is_null($competencia['deleted_at'])): ?>
                                    <div class="wrapper_botton"> 
                                        <div class="lista_competencias__panel competencia<?= $counterComp ?>"  onclick="mostrar_competencia(this);">
                                            <button class="selecciona__competencia competencia<?= $counterComp ?>"><?= ($counterComp - 34) . ". {$competencia["titulo"]}" ?></button>
                                            <button class="selecciona__competencia competencia<?= $counterComp ?>"><i class="arrow"></i></button>
                                        </div>

                                        <?php if (isset($listaUsuarioCompetencia["$counterComp"])): // Si ya ha guardado datos en la competencia ?>
                                        <?php $usuario_vpcr = get_user_vpcr()->fetch_assoc(); ?>
                                        <?php $usuario_competencia = $listaUsuarioCompetencia["$counterComp"]; ?>
                                        <?php if ((isset($usuario_vpcr["fecha_enviado"]) && !empty($usuario_vpcr["fecha_enviado"]) && isset($usuario_competencia["observaciones"]) && !empty($usuario_competencia["observaciones"]) && isset($usuario_vpcr["fecha_envio_observaciones"]) && !empty($usuario_vpcr["fecha_envio_observaciones"])) && ((strtotime($usuario_vpcr["fecha_envio_observaciones"]) > strtotime($usuario_vpcr["fecha_enviado"])) && (strtotime($usuario_vpcr["fecha_envio_observaciones"]) > $usuario_competencia["updated_at"]))): // Si hay usuarios_vpcrs.fecha_enviado, usuarios_vpcrs.observaciones y usuarios_vpcrs.fecha_envio_observaciones se comprueba si usuarios_vpcrs.fecha_envio_observaciones > usuarios_vpcrs.fecha_enviado y usuarios_vpcrs.fecha_envio_observaciones > usuarios_vpcrs_competencias.updated_at ?>
                                                <form method="POST" class="boton_competencia" action="./listado.php">
                                                    <button class="btn competencia__guardar__enviado btn-red">Corregir</button>
                                                    <input class="post__aux" name="competencia" value="<?= $competencia["id"] ?>" />
                                                </form>
                                            <?php else: // Si ya ha corregido los errores, no hay errores en la competencia o aún no ha finalizado el proceso ?>  
                                                <form method="POST" class="boton_competencia" action="./listado.php">
                                                    <button class="btn competencia__guardar__enviado btn-gray">Modificar</button>
                                                    <input class="post__aux" name="competencia" value="<?= $competencia["id"] ?>" />
                                                </form>
                                            <?php endif; ?>
                                        <?php else: // Si no ha guardado ningún dato aún ?>
                                            <form method="POST" class="boton_competencia evaluar_competencia" action="./listado.php">
                                                <button class="btn competencia__guardar btn-orange">Evaluar</button>
                                                <input class="post__aux" name="competencia" value="<?= $competencia["id"] ?>" />
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                    <div class="competencias__validar competencia<?= $counterComp ?>_panel">
                                        <h2 class="competencias__conocimiento_titulo">Conocimientos</h2>

                                        <ul class="competencias__lista_conocimientos">
                                            <?php foreach($competencia["conocimientos"] as $conocimiento): ?>
                                            <li class="conocimientos__item"><?= $conocimiento["texto"] ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <div class="conocimientos__lista lista_c<?= $counterComp ?>_panel"></div>

                                        <h2 class="competencias__habilidades">Habilidades</h2>

                                        <ul class="competencias__lista_habilidades">
                                            <?php foreach($competencia["habilidades"] as $habilidad): ?>
                                            <li class="habilidades__item"><?= $habilidad["texto"] ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <h2 class="competencias__Aptitudes">Aptitudes</h2>

                                        <ul class="competencias__lista_Aptitudes">
                                            <?php foreach($competencia["aptitudes"] as $aptitud): ?>
                                                <li class="habilidades__item"><?= $aptitud["texto"] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php $counterComp++; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    
                        <button class="sombra-boton btn btn-green btn-lg btn-block mt-4" id="send-vpcr" type="submit">Finalizar y enviar solicitud</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('templates/footer.php'); ?>
    
    <!-- MODALS -->
    <div class="modal fade" id="send_vpcr-modal" tabindex="-1" role="dialog" aria-labelledby="send_vpcr-label">
        <div class="modal-dialog modal-lg" role="document">
            <form action="./listado.php?action=enviar" class="modal-content" method="POST">
                <div class="modal-header pl-4 pr-4">
                    <h4 class="modal-title" id="send_vpcr-label">
                        <b>¿Estás seguro de que quieres finalizar el proceso?</b>
                    </h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body pt-4 pl-4 pr-4">
                    <h5 class="ml-2">
                        <b>Estas son las competencias que has validado:</b>
                    </h5>

                    <ol class="mt-3" id="completed-competencias"></ol>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-red" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-green">Sí, enviar solicitud</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="error-modal_label">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pl-4 pr-4">
                    <h4 class="modal-title">
                        <b id="error-modal_label"></b>
                    </h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-4 pl-4 pr-4" id="error-modal_body"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-orange" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODALS -->
    
    <script type="text/javascript">
        $(document).ready(function() {
            $("#send-vpcr").on("click", function(e) {
                e.preventDefault();
                $("#completed-competencias").html("");

                let especificas = 0;
                let transversales = 0;
                let puntos = 0;

                $.getJSON("api.php?action=completed_competencias").done(function(data) {
                    Object.keys(data).forEach(function(value) {
                        const title = data[value][0];
                        const type = data[value][1];
                        if (!title || !type) return;

                        if (type == 1) especificas++;
                        else transversales++;

                        $("#completed-competencias").append(`<li class="mt-2">${title}</li>`);
                    });

                    puntos = data.puntos_totales;
                }).always(function() {
                    var min_competencias_especificas = <?= min_competencias_especificas() ?>;
                    var min_competencias_transversales = <?= min_competencias_transversales() ?>;
                    if (especificas < min_competencias_especificas || transversales < min_competencias_transversales) { // Verificar el mínimo de competencias requeridas
                        $("#error-modal_label").html("Competencias insuficientes");
                        $("#error-modal_body").html(`Para finalizar y enviar la solicitud es necesario haber completado un mínimo de <b>` + min_competencias_especificas +` competencias específicas</b> y <b>`+ min_competencias_transversales +` competencia transversal</b>.<br><br>Has completado <b>${especificas} competencias específicas</b> y <b>${transversales} competencias transversales</b>`);
                        $("#error-modal").modal("show");
                    } else { // Si todo está correcto, mostrar listado de competencias completadas
                        $("#send_vpcr-modal").modal("show");
                    }
                });
            });

            $(".evaluar_competencia").on("submit", function(e) {
                const currentPoints = <?= get_usuarios_competencias_puntos_totales() ?>;
                var max_puntos_competencias = <?= max_puntos_competencias() ?>;
                if (currentPoints >= max_puntos_competencias) {
                    e.preventDefault();

                    $("#error-modal_label").html("Puntuación máxima alcanzada");
                    $("#error-modal_body").html(`El máximo de puntos permitidos en la solicitud es de <b>` + max_puntos_competencias + ` puntos</b> entre todas las competencias completadas por lo que no puedes empezar una nueva validación.`);
                    $("#error-modal").modal("show");
                }
            });

            
            <?php if (isset($_GET["action"]) && $_GET["action"] == "error_limit_points"): ?>
                $("#error-modal_label").html("Puntuación máxima alcanzada");
                $("#error-modal_body").html(`El máximo de puntos permitidos en la solicitud es de <b>` + max_puntos_competencias + ` puntos</b> entre todas las competencias completadas por lo que no puedes empezar una nueva validación.<br><br>Tienes <b>${(<?= get_usuarios_competencias_puntos_totales() ?> - max_puntos_competencias)} puntos</b> de más.`);
                $("#error-modal").modal("show");
            <?php endif; ?>
        });
    </script>
</body>
</html>