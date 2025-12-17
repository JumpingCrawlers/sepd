@extends('puzzle.master')

@php
    $nombre_menu = "formacion";
    $miga_pan = "-";
    $formacion = true;
@endphp

@section('estilos')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    @include('styles.base')
    @yield('styles')
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.js"></script>
    <script>
        let inter;
        $(document).ready(function() {
            iOS = false;

            pintarRojoEnSuspenso();
            function pintarRojoEnSuspenso(){
                $('b:contains("No te quedan más oportunidades")').parents('.card-body').removeClass('card-green');
                $('b:contains("No te quedan más oportunidades")').parents('.card-body').css('background-color', '#e63912');
            }

            function quickCloseModalHandler(){
                /**
                 * Handler del cierre de modal para el fade del mismo
                 **/
                $("#main-modal").fadeOut("slow", function () {
                    $("body").css("overflow", "auto");
                    $("#modal-content", $(this)).html("");
                    $("#main-modal").attr("data-id", "");
                });
            }

            $(document).on("click", ".open-modal", function() {
                inter = setInterval(function(){
                    saveTime($("#main-modal").attr("data-id"), null, true, null);
                }, 15000);
                var current = $(this);
                var id = current.attr("data-id");
                var content = current.attr("contenido");
                if (!id || !content) return false;
                let src = "";
                var contentArray;
                var diplomaId;
                if (content.startsWith("http") && !content.endsWith("diapositivas")){
                    src = content;
                }
                else if (content.startsWith("diploma:")) {
                    contentArray = content.split(":");
                    diplomaId = contentArray[1];
                    if (!diplomaId) return false;
                    src = "/diploma/" + diplomaId;
                }
                else if (content.endsWith("/") || content.endsWith("diapositivas")) {
                    let folder = "";
                    if (!content.endsWith("diapositivas")) {
                        const contentArray = content.split("/");
                        folder = contentArray[contentArray.length - 2];
                    }
                    else folder = $(this).data("id");
                    if (!folder) return false;
                    src = "{{ route('formacion.diapositivas', '') }}/" + folder;
                }
                else{
                    /**
                     *  3ways Euro Fuenmayor
                     *  Agregda lógica para determinar cuando un diploma se trata de una acreditación de formación presencial
                     **/
                    if(content.startsWith("diplomas/presenciales")){
                        diplomaId =  'P_'+content.split("%20")[1].split('.')[0];
                        src = "/diploma/" + diplomaId;
                    }else{
                        src = "{{ url(config('app.url')) }}/storage/" + content;
                    }
                    //Comprobar si es iOS y pasar el content por googleDocs para que no haya problemas.
                    if(content.endsWith("pdf")){
                        var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                        var isAndroid = /(android)/i.test(navigator.userAgent);
                    }
                }
                $("body").css("overflow", "hidden");
                var toolbar = !diplomaId ? '#toolbar=0' : '';

                if(iOS || isAndroid){
                    $("#modal-content", $("#main-modal")).html('<iframe allowtransparency="true" height="100%" width="100%" allowfullscreen allow="autoplay" src="https://docs.google.com/gview?url=' + src + '&embedded=true"></iframe>');
                }else{
                    $("#modal-content", $("#main-modal")).html('<iframe allowtransparency="true" height="100%" width="100%" allowfullscreen allow="autoplay" src="' + src.replace('watch?v=','embed/') + toolbar + '"></iframe>');
                }
                
                //Abrir enlace externo por duplicado en otra ventana
                if (content.startsWith("http")){
                    window.open(src);
                }

                $("#main-modal").attr("data-id", id);
                $("#main-modal").fadeIn("slow");

                if (!content.startsWith("diploma:")) {
                    startTime = new Date();
                    inModal = true;
                }
            });


            $(document).on("click", "#close-modal", function() {
                /** 3 ways Euro Fuenmayor
                 *  Se detiene la actualizacion recurrente del tiempo de visualización del contenido del curso
                 *  Se agrega el html/css para añadir al boton volver un mensaje de esperare e indicador de actividad
                 *  Se llama al metodo de guardar el tiempo de visualización del contenido del curso (resources/views/cursos/hacer.blade.php)
                 * */
                clearInterval(inter);
                const $itemID = $("#main-modal").attr("data-id");
                if ($itemID != "0") {
                    $('#close-modal').html('<i class="fas fa-angle-double-left"></i> Volver<br><div><span><i style="font-size: 14px" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> Por favor, espere...</div>');
                    saveTime($itemID, null, null, true)
                }else{
                    quickCloseModalHandler();
                }
            });

            var ck = getCookie("main_collapsed");
            if (ck == "false" || !ck.length) {
                var card = $(".card[card-id=\"main\"]");
                var accordion = $(".accordion", card);
                var id = accordion.attr("data-id");
                var box = $("#" + id);
                $(".title > i", accordion).removeClass("fa-plus-circle");
                $(".title > i", accordion).addClass("fa-minus-circle");
                box.slideDown(0, function () {
                    $(this).removeClass("collapsed");
                    $(".title", accordion).removeClass("collapsed");
                });
            }

            $(document).on("click", "#link", function() {
                var current = $(this);
                if (current.attr("target") == "_blank") window.open(current.attr("href"));
                else window.location.href = current.attr("href");
            });

            $(document).on("click", ".card-content", function() {
                return false;
            });

            $(document).on("click", ".accordion", function() {
                var current = $(this);
                var id = current.attr("data-id");
                var box = $("#" + id);
                if (box.hasClass("collapsed")) {
                    $(current).css("height", "auto");
                    $(".title > i", current).removeClass("fa-plus-circle");
                    $(".title > i", current).addClass("fa-minus-circle");
                    $(".title", current).removeClass("collapsed");
                    box.slideDown(500, function() {
                        $(this).removeClass("collapsed");
                    });
                }
                else {
                    $(".title > i", current).removeClass("fa-minus-circle");
                    $(".title > i", current).addClass("fa-plus-circle");
                    box.slideUp(500, function() {
                        $(this).addClass("collapsed");
                        $(".title", current).addClass("collapsed");
                    });
                }

                if (current.attr("set-cookie")) {
                    setCookie(current.attr("set-cookie"), ((getCookie(current.attr("set-cookie")) == "true") ? "false" : "true"), 9999);
                }
            });

            /* Functions */
            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }

                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }

                return "";
            }

            if (window.location.pathname.startsWith("/formacion/cursos/")) {
                const hash = window.location.hash;
                if (hash.length && hash.startsWith("#module-"))
                $(hash).trigger("click");
                window.location.hash = "";
            }
        });
    </script>
@endsection

@section('contenido')
    @yield('content')
@endsection