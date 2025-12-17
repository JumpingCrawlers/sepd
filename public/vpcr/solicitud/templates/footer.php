    <footer class="container text-white mt-4">
        <a href="javascript:" id="return-to-top">
            <i class="glyphicon glyphicon-edit m-r-20"></i>
        </a>

        <div class="row mb-3">
            <div class="col-sm-4 offset-sm-1 mb-3">
                <a href="https://stamp.wma.comb.es/es/seal/693" target="_blank">
                    <img src="https://stamp.wma.comb.es/stamp/imglogo.ashx?INTWMA=693&lang=es&size=big" border="0" alt="Web Médica Acreditada. Ver más información" longdesc="http://wma.comb.es" title="Web Médica Acreditada. Ver más información" />
                </a>

                <!-- <a href="https://www.healthonnet.org/HONcode/Spanish/?HONConduct963113" target="_blank">
                    <img src="./index_files/CUUtDYdk1YcjWNdyyldb.gif" border="0" class="img-fluid" />
                </a> -->
            </div>

            <div class="col-sm-3">
                <p>
                    <strong>Contacto:</strong>
                    <br>C/ Sancho Dávila, 6 - 28028 Madrid
                    <br>(Sede de la SEPD y de la FEAD)
                    <br>Tel.: 91 402 13 53
                </p>
            </div>

            <div class="col-sm-3">
                <p>
                    <strong>Horario:</strong>
                    <br>Lunes a jueves de 9:00 a 18:00h.
                    <br>Viernes de 9:00 a 15:00 h.
                </p>

                <p>
                    <strong>Horario de verano:</strong>
                    <br>Lunes a jueves de 9:00 a 16:00h.
                    <br>Viernes de 9:00 a 15:00 h.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <p>
                    © 2018 Sociedad Española de Patología Digestiva (SEPD)
                    · <a href="http://www.sepd.es/privacidad">Privacidad</a>
                    · <a href="http://www.sepd.es/contacto">Contacto</a>
                    · <a href="http://www.sepd.es/mapa_web">Mapa web</a>
                    · <a href="#" id="cookiesFooter">Política de Cookies</a>
                </p>

                <p>
                    <span> Última actualización: 24-04-2019 <span></span></span>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="./index_files/app.js"></script>
    <script src="./index_files/api.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(window).scroll(function() {
                if ($(this).scrollTop() >= 450) { // If page is scrolled more than 450px
                    $("#return-to-top").fadeIn(200); // Fade in the arrow
                } else {
                    $("#return-to-top").fadeOut(200); // Else fade out the arrow
                }
            });

            $("#return-to-top").click(function() { // When arrow is clicked
                $("body,html").animate({
                    scrollTop: 0 // Scroll to top of body
                }, 500);
            });
        });

        //Actualizar tramo vpc antes de descargar certificado
        function actualizarTramoVpc() {
            $.get("confirmacion.php?action=actualizarTramo", function(data) {
                console.log("Actualizado tramo VPC para el usuario");
                window.open("/vpcrs/descargar");
            });
        }

        $('#cookiesFooter').on('click', function() {
            $('#modalCookiesConfiguracion').modal('show');
        });
    </script>

    <script src="js/agregarConocimiento.js"></script>
    <script src="js/mostrarConocimientos.js"></script>
    <script src="js/mostrarCompetencia.js"></script>
    <script src="js/showDrop.js"></script>