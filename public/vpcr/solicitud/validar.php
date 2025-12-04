<?php
    include('view_loader.php');
    front_controller();

    $id = $_COOKIE["id"];
    $competencia = get_competencia_by_id($id); 
    $evaluacion = $competencia["evaluacion"];
    $usuario_competencia = get_usuarios_competencias_by_id($id); // De existir se trae todos los datos que tenga el usuarios referentes a esta competencias

    if (!$usuario_competencia && get_usuarios_competencias_puntos_totales() >= max_puntos_competencias()): // Si no se ha validado la competencia y tiene más del maximo de puntos permitidos en total, llevar a listado y mostrar error
        header("Location: listado.php?action=error_limit_points");
        die();
    endif;

    include('templates/header.php');
?> 
    <main role="main">
        <div class="main__wrapper">
            <div class="content__wrapper">
                <form action="./validar.php?action=enviar" id="form-general" method="POST" enctype="multipart/form-data">
                    <a href="./listado.php">
                        <div class="sombra-boton btn btn-primary mb-4">< Volver al listado</div>
                    </a>
                    
                    <h2 class="main__title">Validar competencia: <span class="azul_claro"><?= (($id > 34) ? ($id - 34) : $id) . ". {$competencia["titulo"]}"  ?></span></h2>
                    
                    <input type="hidden" name="conocimientos_to_delete" id="conocimientos_to_delete" />
                    <input type="hidden" name="habilidades_to_delete" id="habilidades_to_delete" />
                    <input type="hidden" name="aptitudes_to_delete" id="aptitudes_to_delete" />
                    <input type="hidden" name="meritos_to_delete" id="meritos_to_delete" />

                    <div class="wrapper_botton"> 
                        <div class="lista_competencias__panel competencia<?= $id ?>"  onclick="mostrar_competencia(this);">
                            <button type="button" class="selecciona__competencia competencia<?= $id ?>">Ver criterios de evaluación</button>
                            <button type="button" class="selecciona__competencia competencia<?= $id ?>"><i class="arrow"></i></button>
                        </div>
                    </div>

                    <div class="competencias__validar competencia<?= $id ?>_panel">
                        <h2 class="competencias__conocimiento_titulo">Conocimientos</h2>

                        <ul class="competencias__lista_conocimientos">
                            <?php foreach($competencia["conocimientos"] as $conocimiento): ?>
                            <li class="conocimientos__item"><?= $conocimiento["texto"] ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="conocimientos__lista lista_c<?= $id ?>_panel"></div>

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
                        
                        <h5 class="alert alert-warning mt-4">Para poder validar esta competencia necesita acumular al menos 5 puntos entre las distintas categorías</h5>
                    </div>
                    
                    <p class="main__text_alt">Para poder procesar tu solicitud deberás completar los siguientes requerimientos:</p>

                    <div class="competencias__validar_screen competencia_panel">
                        <!-- CONOCIMIENTOS -->
                        <div class="conocimientos__box">
                            <div class="wrapper_agregar_conocimiento">
                                <h1 class="competencias__habilidades subtitle__alt">Conocimientos</h1>
                            </div>

                            <div class="wrapper__conocimientos">
                                <h2 class="competencias__habilidades">A evaluar:</h2>

                                <ul class="lista__evaluacion">
                                    <?php foreach($evaluacion["conocimientos"] as $conocimiento): ?>
                                    <li class="conocimientos__item"><?= $conocimiento["texto"] ?></li>
                                    <?php endforeach; ?>  
                                </ul>

                                <div class="puntos__wrapper">
                                    <h2 class="competencias__habilidades">Puntuación máxima: <?= $evaluacion["puntosConocimientos"] ?></h2>
                                </div>

                                <div class="alert alert-info">
                                    <h2 class="competencias__habilidades">Instrucciones:</h2>

                                    <p class="main__text">
                                        Por cada titulo es necesario incluir el título del curso, quien lo acredita, fechas de realización (la fecha de realización no puede ser mayor de 6 años atrás) y el número de créditos u horas. En caso de ser horas, se establecerá internamente una equivalencia entre horas y créditos para que todo sea convertido a créditos.
                                    </p>
                                </div>
                                
                                <div class="conocimientos__lista lista_c_panel">
                                    <?php $conocimientoCounter = 1; ?>
                                    <?php if (isset($usuario_competencia["usuarios_competencias_conocimientos"])): ?>
                                    <?php foreach($usuario_competencia["usuarios_competencias_conocimientos"] as $usuario_conocimiento): ?>
                                        <div class="conocimientos__wrapper  lista_c_panel_item conocimiento_agregado">
                                            <div class="wrapper_icon"> 
                                                <div class="trash__icon white cursor-normal fz-17"><?= $conocimientoCounter ?>.</div>

                                                <div id="conocimiento<?= $conocimientoCounter ?>" class="agregar__conocimientos_panel conocimiento<?= $conocimientoCounter ?>" onclick="mostrar_conocimiento(this);">
                                                    <p class="conocimientos__titulo mtconocimiento<?= $conocimientoCounter ?>"><?= $usuario_conocimiento["titulo"] ?></p>
                                                </div>

                                                <a href="<?= $usuario_conocimiento["certificado"] ?>" class="btn trash__icon btn-orange" target="_blank">
                                                    <i class="fa fa-eye fa-1x pr-1" aria-hidden="true"></i> Visualizar
                                                </a>

                                                <button type="button" class="btn trash__icon btn-red" name="delete" onclick="set_for_delete(this, 'conocimientos', <?= $usuario_conocimiento["id"] ?>)">
                                                    <i class="fa fa-trash fa-1x" aria-hidden="true"></i>
                                                    <input value="<?= $usuario_conocimiento["id"] ?>" type="hidden" name="id">
                                                </button>
                                            </div>

                                            <div class="conocimientos__validar conocimiento<?= $conocimientoCounter ?>_panel">
                                                <div class="titulo__wrapper">
                                                    <p class="validar__certificados">Titulo</p>
                                                    <b><?= $usuario_conocimiento["titulo"] ?></b>
                                                </div>
                                                
                                                <div class="titulo__wrapper">
                                                    <p class="validar__titulo">Acreditado por</p>
                                                    <b><?= $usuario_conocimiento["acreditado_por"] ?></b>
                                                </div>

                                                <div class="fecha__wrapper">
                                                    <div>
                                                        <p>Fecha Inicio</p>
                                                        <b><?= date("d/m/Y", strtotime($usuario_conocimiento["fecha_inicio"])) ?></b>
                                                    </div>

                                                    <div  class="fecha__box">
                                                        <p>Fecha Fin</p>
                                                        <b><?= date("d/m/Y", strtotime($usuario_conocimiento["fecha_fin"])) ?></b>
                                                    </div>
                                                </div>

                                                <div class="valor__wrapper">
                                                    <p class="validar__valor">Valor</p>
                                                    <b><?= $usuario_conocimiento["valor"] ?> <?= $usuario_conocimiento["valor_tipo"] ?></b>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $conocimientoCounter++; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                    
                                <div class="wrapper_agregar_conocimiento mt-4 ml-1">
                                    <button type="button" class="agregar__conocimientos lista_c btn btn-darkblue" onclick="agregar_conocimiento(this);">Agregar conocimiento</button>
                                </div>
                            </div>
                        </div>
                        <!-- END CONOCIMIENTOS -->

                        <!-- HABILIDADES -->
                        <div class="conocimientos__box">
                            <div class="wrapper_agregar_conocimiento">
                                <h1 class="competencias__habilidades subtitle__alt">Habilidades</h1>
                            </div>

                            <div class="wrapper__conocimientos">
                                <h2 class="competencias__habilidades">A evaluar:</h2>

                                <ul class="lista__evaluacion">
                                    <?php foreach($evaluacion["habilidades"] as $habilidades): ?>
                                    <li class="conocimientos__item"><?= $habilidades["texto"] ?></li>
                                    <?php endforeach; ?>  
                                </ul>

                                <div class="puntos__wrapper">
                                    <h2 class="competencias__habilidades">Puntuación máxima: <?= $evaluacion["puntosHabilidades"] ?></h2>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h2 class="competencias__habilidades">Instrucciones:</h2>
                                <p class="main__text">Acceda a la plantilla de certificado a través del botón de descarga</p>
                            </div>

                            <div class="file file_habilidades">
                                <?php $habilidadesCounter = 0; ?>
                                <?php if (isset($usuario_competencia["usuarios_competencias_habilidades"])): ?>
                                <?php foreach($usuario_competencia["usuarios_competencias_habilidades"] as $usuario_habilidad): ?>
                                <?php $habilidadesCounter++; ?>
                                    <div class="show_file__wrapper">
                                        <div class="trash__icon__file white cursor-normal fz-17"><?= $habilidadesCounter ?>.</div>

                                        <div id="inputH<?= $habilidadesCounter ?>" class="wrapper_botton_icon width100 habilidad_agregada">
                                            <div class="trash__icon__file darkblue width100 justify-left cursor-normal"><?= basename($usuario_habilidad["archivo"]) ?></div>
                                        </div>

                                        <a href="<?= $usuario_habilidad["archivo"] ?>" class="btn trash__icon__file btn-orange" target="_blank">
                                            <i class="fa fa-eye fa-1x pr-1" aria-hidden="true"></i> Visualizar
                                        </a>

                                        <button type="button" class="btn trash__icon__file btn-red" name="delete<?= $habilidadesCounter ?>" onclick="set_for_delete(this, 'habilidades', <?= $usuario_habilidad["id"] ?>)" value="0">
                                            <i class="fa fa-trash fa-1x" aria-hidden="true"></i>
                                            <input value = "<?= $usuario_habilidad["id"] ?>" type="hidden" name="id<?= $habilidadesCounter ?>">
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                                <?php endif; ?>

                                <input value="<?= $habilidadesCounter ?>" type="hidden" name="files_number" class="files_number">
                            </div>

                            <div class="wrapper_agregar_conocimiento ml-1">
                                <?php
                                    $evaluacion_json = json_decode($evaluacion["formularioHabilidades"], true);
                                    $evaluacion["formularioHabilidades"] = $evaluacion_json[0] ?  'https://sepd.es/storage/' .$evaluacion_json[0]['download_link'] : null;
                                ?>
                                
                                <? if ($evaluacion["formularioHabilidades"]) : ?>
                                    <a href="<?= $evaluacion["formularioHabilidades"] ?>" download>
                                        <button type="button" class="btn selecciona__competencia btn-gray descargar">Descargar plantilla</button>
                                    </a>
                                <? endif ?>

                                <button type="button" class="agregar__conocimientos lista_c habilidades btn btn-darkblue" onclick="agregar_input_habilidades(this);">Agregar habilidad</button>
                            </div>
                            
                            <span id="upload-info">
                                Los tipos de archivos válidos son: <b>.jpg</b>, <b>.png</b>, <b>.jpeg</b>, <b>.gif</b>, <b>.txt</b> y <b>.pdf</b> con un tamaño máximo de <b>5MB</b>
                            </span>
                        </div>
                        <!-- END HABILIDADES -->

                        <!-- APTITUDES -->
                        <div class="conocimientos__box">
                            <div class="wrapper_agregar_conocimiento">
                                <h1 class="competencias__Aptitudes subtitle__alt">Aptitudes</h1>
                            </div>

                            <div class="wrapper__aptitudes">
                                <h2 class="competencias__habilidades"> A evaluar:</h2>

                                <ul class="lista__evaluacion">
                                    <?php foreach($evaluacion["aptitudes"] as $aptitudes): ?>
                                    <li class="conocimientos__item"><?= $aptitudes["texto"] ?></li>
                                    <?php endforeach; ?>  
                                </ul>

                                <div class="puntos__wrapper">
                                    <h2 class="competencias__habilidades">Puntuación máxima: <?= $evaluacion["puntosAptitudes"] ?></h2>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h2 class="competencias__habilidades">Instrucciones:</h2>
                                <p class="main__text">Acceda a la plantilla de certificado a través del botón de descarga</p>
                            </div>

                            <div class="file file_aptitudes">
                                <?php $aptitudesCounter = 0; ?>
                                <?php if (isset($usuario_competencia["usuarios_competencias_aptitudes"])): ?>
                                <?php foreach($usuario_competencia["usuarios_competencias_aptitudes"] as $usuario_aptitud): ?>
                                <?php $aptitudesCounter++; ?>
                                    <div class="show_file__wrapper">
                                        <div class="trash__icon__file white cursor-normal fz-17"><?= $aptitudesCounter ?>.</div>

                                        <div id="inputA<?= $aptitudesCounter ?>" class="wrapper_botton_icon width100 aptitud_agregada">
                                            <div class="trash__icon__file darkblue width100 justify-left cursor-normal"><?= basename($usuario_aptitud["archivo"]) ?></div>
                                        </div>

                                        <a href="<?= $usuario_aptitud["archivo"] ?>" class="btn trash__icon__file btn-orange" target="_blank">
                                            <i class="fa fa-eye fa-1x pr-1" aria-hidden="true"></i> Visualizar
                                        </a>

                                        <button type="button" class="btn trash__icon__file btn-red" name="adelete<?= $aptitudesCounter ?>" onclick="set_for_delete(this, 'aptitudes', <?= $usuario_aptitud["id"] ?>)" value="0">
                                            <i class="fa fa-trash fa-1x" aria-hidden="true"></i>
                                            <input value="<?= $usuario_aptitud["id"] ?>" type="hidden" name="aid<?= $aptitudesCounter ?>">
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <input value="<?= $aptitudesCounter ?>" type="hidden" name="files_number_aptitud" class="files_number_aptitud">
                            </div>

                            <div class="wrapper_agregar_conocimiento ml-1">

                                <?php
                                    $evaluacion_json = json_decode($evaluacion["formularioAptitudes"], true);
                                    $evaluacion["formularioAptitudes"] = $evaluacion_json[0] ?  'https://sepd.es/storage/' .$evaluacion_json[0]['download_link'] : null;
                                ?>
                                
                                <? if ($evaluacion["formularioAptitudes"]) : ?>
                                    <a href="<?= $evaluacion["formularioAptitudes"] ?>" download>
                                        <button type="button" class="btn selecciona__competencia btn-gray descargar">Descargar plantilla</button>
                                    </a>
                                <? endif ?>

                                <button type="button" class="agregar__conocimientos lista_c aptitudes btn btn-darkblue" onclick="agregar_input_aptitudes(this);">Agregar aptitud</button>
                            </div>

                            <span id="upload-info">
                                Los tipos de archivos válidos son: <b>.jpg</b>, <b>.png</b>, <b>.jpeg</b>, <b>.gif</b>, <b>.txt</b> y <b>.pdf</b> con un tamaño máximo de <b>5MB</b>
                            </span>
                        </div>
                        <!-- END APTITUDES -->

                        <!-- MÉRITOS -->
                        <div class="conocimientos__box">
                            <div class="wrapper_agregar_conocimiento">  
                                <h1 class="competencias__meritos subtitle__alt">Otros méritos</h1>
                            </div>

                            <div class="wrapper__meritos">
                                <h2 class="competencias__habilidades">A evaluar:</h2>

                                <ul class="lista__evaluacion">
                                    <li class="meritos__item">Aportar certificados relativos a su participación en proyectos de investigación, estancias cortas, gestión y otros méritos</li>
                                    <li class="meritos__item">Trabajos de investigación, publicaciones etc.</li>
                                    <li class="meritos__item">Registros, certificados y pertenencia a Comisiones, Grupos o actividad en consulta especializada.</li>
                                </ul>
                                
                                <div class="puntos__wrapper">
                                    <h2 class="competencias__habilidades">Puntuación máxima: <?= $evaluacion["puntosMeritos"] ?></h2>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h2 class="competencias__habilidades">Instrucciones:</h2>
                                <p class="main__text">Incluye tus certificados, participación en registros, etc.</p>
                            </div>

                            <div class="file file_meritos">
                                <?php $meritosCounter = 0; ?>
                                <?php if (isset($usuario_competencia["usuarios_competencias_meritos"])): ?>
                                <?php foreach($usuario_competencia["usuarios_competencias_meritos"] as $usuario_merito): ?>
                                <?php $meritosCounter++; ?>
                                    <div class="show_file__wrapper">
                                        <div class="trash__icon__file white cursor-normal fz-17"><?= $meritosCounter ?>.</div>

                                        <div id="inputM<?= $meritosCounter ?>" class="wrapper_botton_icon width100 merito_agregado">
                                            <div class="trash__icon__file darkblue width100 justify-left cursor-normal"><?= basename($usuario_merito["archivo"]) ?></div>
                                        </div>

                                        <a href="<?= $usuario_merito["archivo"] ?>" class="btn trash__icon__file btn-orange" target="_blank">
                                            <i class="fa fa-eye fa-1x pr-1" aria-hidden="true"></i> Visualizar
                                        </a>

                                        <button type="button" class="btn trash__icon__file btn-red" name="mdelete<?= $meritosCounter ?>" onclick="set_for_delete(this, 'meritos', <?= $usuario_merito["id"] ?>)" value="0">
                                            <i class="fa fa-trash fa-1x" aria-hidden="true"></i>
                                            <input value = "<?= $usuario_merito["id"] ?>" type="hidden" name="mid<?= $meritosCounter ?>">
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <input value = "<?= $meritosCounter;?>" type="hidden" name="files_number_merito" class="files_number_merito">
                            </div>
                                
                            <div class="wrapper_agregar_conocimiento ml-1">  
                                <button type="button" class="agregar__conocimientos lista_c meritos btn btn-darkblue" onclick="agregar_input_meritos(this);">Agregar mérito</button>
                            </div>

                            <span id="upload-info">
                                Los tipos de archivos válidos son: <b>.jpg</b>, <b>.png</b>, <b>.jpeg</b>, <b>.gif</b>, <b>.txt</b> y <b>.pdf</b> con un tamaño máximo de <b>5MB</b>
                            </span>
                        </div>
                        <!-- END MÉRITOS -->

                        <div class="save-buttons row">
                            <div class="col-sm-12">
                                <button class="sombra-boton btn btn-green btn-lg btn-block" id="send-competencia" type="button">Guardar competencia</button>
                            </div>
                        </div>

                        <input type="hidden" name="puntos_conocimientos" id="puntos_conocimientos" />
                        <input type="hidden" name="puntos_habilidades" id="puntos_habilidades" />
                        <input type="hidden" name="puntos_aptitudes" id="puntos_aptitudes" />
                        <input type="hidden" name="puntos_meritos" id="puntos_meritos" />
                    </div>
                </form>
            </div>
        </div>     
    </main>

    <?php include('templates/footer.php'); ?>
    
    <!-- MODALS -->
    <div class="modal fade" id="alert-puntos-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title puntos-confirm-label">¡Necesita al menos 5 puntos para finalizar la validación!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body texto-puntos">
                    Ha acumulado <b>3</b> puntos con la documentación subida. Necesita acumular <b>2</b> puntos más.
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-orange" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODALS -->

    <script type="text/javascript">
        $(document).ready(function() {
            $("#send-competencia").on("click", function(e) {
                e.preventDefault();
                
                // Comprobar que en todos los inputs haya un archivo
                let inputContainsFiles = true;
                $('input[type="file"]').each(function() {
                    if ($(this).get(0).files.length === 0) inputContainsFiles = false;
                });

                if (!inputContainsFiles) {
                    $(".puntos-confirm-label").html("Validación incompleta");
                    $(".texto-puntos").html("Hay campos que no contienen archivos, compruébelo antes de guardar la validación.");
                    $("#alert-puntos-modal").modal("show");

                    return;
                }

                // Check all inputs file type (jpg, jpeg, png, gif, txt, pdf)
                if (!validFilesExtension()) {
                    $(".puntos-confirm-label").html("Validación incompleta");
                    $(".texto-puntos").html("Formato del archivo invalido.");
                    $("#alert-puntos-modal").modal("show");

                    return;
                }  

                // Check if final date is greater than initial date
                if (!validFinalDates()) {
                    $(".puntos-confirm-label").html("Validación incompleta");
                    $(".texto-puntos").html("Error en la fecha de inicio y final.");
                    $("#alert-puntos-modal").modal("show");

                    return;
                }  

                //Comprobar que alcanza el min. de 5 puntos entre los documentos subidos para enviar la competencia
                var num_conocimientos = $('.conocimiento_agregado').length;
                var puntos_totales = 0;
                var creditosConocimientos = 0;
                var horasConocimientos = 0;
              
                $(".valor__creditos").each(function(){                    
                    var posConocimiento = $(this).attr("name").substring(0,($(this).attr("name").length - 6));
                    // console.log(posConocimiento);
                    if($('select[name="'+posConocimiento+'_tipo_valor"]').val() == "Creditos"){
                        creditosConocimientos +=  +$(this).val();
                    }else{
                        horasConocimientos += +$(this).val();
                    }
                });

                //Un crédito corresponde con 5 horas, sumamos los creditos resultantes al resto de los créditos.
                creditosConocimientos += +Math.floor(horasConocimientos/5);
                (creditosConocimientos >=2 ) ? puntos_totales += +"<?php echo $evaluacion['puntosConocimientos']?>" : puntos_totales += +0;
                var num_habilidades = $('.habilidad_agregada').length;
                (num_habilidades > 0 ) ? puntos_totales += +"<?php echo $evaluacion['puntosHabilidades']?>" : puntos_totales += +0;
                var num_aptitudes = $('.aptitud_agregada').length;
                (num_aptitudes > 0 ) ? puntos_totales += +"<?php echo $evaluacion['puntosAptitudes']?>" : puntos_totales += +0;
                var num_meritos = $('.merito_agregado').length;
                (num_meritos > 0 ) ? puntos_totales += +"<?php echo $evaluacion['puntosMeritos']?>" : puntos_totales += +0;

                if(puntos_totales < 5){
                    $(".puntos-confirm-label").html("¡Necesita al menos 5 puntos para finalizar la validación!");
                    $(".texto-puntos").html(" Ha acumulado <b>"+puntos_totales+"</b> puntos con la documentación subida. Necesita acumular <b>"+(5-puntos_totales)+"</b> más.");
                    $('#alert-puntos-modal').modal('show');
                }else {
                    $("#puntos_conocimientos").val((creditosConocimientos >= 2) ? "<?= $evaluacion['puntosConocimientos'] ?>" : "0");
                    $("#puntos_habilidades").val((num_habilidades > 0) ? "<?= $evaluacion['puntosHabilidades'] ?>" : "0");
                    $("#puntos_aptitudes").val((num_aptitudes > 0) ? "<?= $evaluacion['puntosAptitudes'] ?>" : "0");
                    $("#puntos_meritos").val((num_meritos > 0 ) ? "<?= $evaluacion['puntosMeritos']?>" : "0");

                    $("#form-general").submit();
                }
            });

            //Actualizar titulo del conocimiento con el titulo introducido
            $(document).on("input", "[data-uso=titulo]" , function() {
                var classes = $(this).attr("class").split(/\s+/);
                $(".m"+classes[0].substring(1,classes[0].length)).html($(this).val());
            });

            

            $(document).on("change", "input[type=file]" , function() {
                validFilesExtension();
            });
            
            // Check if input file type contains a valid file(jpg, jpeg, png, gif, txt, pdf)
            let validFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf'];
            function validFilesExtension() { 
                var elements = document.querySelectorAll('input[type=file]');
                let response = true;

                if(elements.length > 0){
                    for (var i = 0; i < elements.length; i++) {
                        if(!validFileExtensions.includes(elements[i].value.split('.').pop())){
                            // Check if input already has invalid-input class
                            if(!elements[i].nextSibling.classList.contains("invalid-input")){
                                elements[i].nextSibling.classList.add("invalid-input");
                            }
                            response = false;
                        }else{
                            elements[i].nextSibling.classList.remove("invalid-input");
                        }
                    }
                }  
                return response;
            }; 

            /* 
                Check Init date and Finish date
                Condition: init_date < finish_date
                id - id of inputs
            */
            function validDates(id){
                let initDate = $('input[name="conocimiento'+ id +'_fecha_inicio"]');
                let finishDate = $('input[name="conocimiento' + id + '_fecha_fin"]');
                let response = false;

                // check if date inputs have values
                if(initDate.val()!='' && finishDate.val()!=''){

                    // Create Date objects to compare dates
                    let date1 = new Date(initDate.val());
                    let date2 = new Date(finishDate.val());

                    // Check if init date is greater than finish date
                    // if so add class invalid-input
                    if(date1 > date2){
                        finishDate.addClass('invalid-input');
                        if(finishDate.next('.error_message').length<1){
                            finishDate.parent().append('<span class="error_message">La fecha fin debe ser mayor a la fecha inicio</span>');
                        }
                    }else{
                        finishDate.removeClass('invalid-input');
                        finishDate.next('.error_message').remove();
                        response = true;
                    }
                }
                return response;
            }

            /**
                Check all inputs type date fecha_fin
             */
             function validFinalDates(){
                 let response = true;
                 var finalDates = document.querySelectorAll("input[name*='fecha_fin']");
                 if(finalDates.length > 0){
                    for (var i = 0; i < finalDates.length; i++) {
                        // Check if final date is greater than initial date
                        if(!validDates(getId(finalDates[i].getAttribute('name')))){
                            response = false;
                            break;
                        }
                    }
                 }
                 return response;
             }

            /* Extract id from attribut name of input date
                attr - param string 
                return int
            */
            function getId(attr){
                return +attr.match(/\d+/)[0];
            }

            $(document).on("change", "input[name*='fecha_inicio'], input[name*='fecha_fin']" , function() {
                validDates(getId($(this).attr('name')));
            });

            //Colapsar la sección de conocimiento para hacer efecto Guardado
             $(document).on("click", ".boton_guardar_conocimiento" , function() {
                var classes = $(this).attr("class").split(/\s+/);
                // console.log("1");
                // console.log($('input[name="'+classes[1]+'_fileToUpload'+classes[1].substring(12)+'"]').get(0).files.length === 0);

                //Comprobar que los campos de conocimiento estan completados antes de permitir guardar
                if($('input[name="'+classes[1]+'_titulo"]').val() == "" || $('input[name="'+classes[1]+'_fileToUpload'+classes[1].substring(12)+'"]').get(0).files.length === 0 || $('input[name="'+classes[1]+'_acreditador"]').val() == "" || $('input[name="'+classes[1]+'_fecha_inicio"]').val() == "" || $('input[name="'+classes[1]+'_fecha_fin"]').val() == "" || $('input[name="'+classes[1]+'_valor"]').val() == ""){
                    // console.log(classes[1]);
                    
                    $(".puntos-confirm-label").html("Campos sin rellenar");
                    $(".texto-puntos").html("Tiene que rellenar todos los campos para guardar un conocimiento.");
                    $("#alert-puntos-modal").modal("show");
                }else if(!validFilesExtension()){
                    $(".puntos-confirm-label").html("Validación incompleta");
                    $(".texto-puntos").html("Formato del archivo invalido.");
                    $("#alert-puntos-modal").modal("show");
                }else if(!validFinalDates()){
                    $(".puntos-confirm-label").html("Validación incompleta");
                    $(".texto-puntos").html("Error en la fecha de inicio y final.");
                    $("#alert-puntos-modal").modal("show");
                }else{
                    $("#"+classes[1]).click();
                }   
            });
        });
    </script>
</body>
</html>