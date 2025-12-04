// Fichero: seleccionaOpcion.js
// Autor: Javi Fdez
// Fecha: 9 sept 2018
// Entorno: Global, paquete principal
// Descripción: Marcar las opciones de filtros o menús izquierdos

// Marcar la opcion/opciones seleccionadas
export default function marcaOpcion() {
    
    marcaCheck();

}

function marcaAnyo() {

    // Si hay un filtro de años, al cargar se marca el primero
    if ($( "#filtroAnyos" ).length ) {

        // timeout para....
        setTimeout(
            function() {
                $('#filtroAnyos input:checkbox:first').trigger("click");
            },
            100
        );
        if ($(window).width()>639){
          $('#grupo-anyos a').trigger("click"); 
        }
    }
        
}

// Control de las opciones de pastillas menú de la izquierda
function marcaCheck() {
    
    // Debemos almacenar:
    // Primer enlace de las pastillas de la izquierda
    var primerAnchorMenu;
    // Primera ancla dentro del contenido
    var primerAnchorContenido;
    // Punto de scroll de la primera ancla
    var primerAnchorTop;
    // url actual completa, incluyendo # si hay
    var url_completa = document.location.href;
    // url actual completa sin # (ya que no se manda por http)
    var url_sin_ancla = (url_completa.indexOf('#') !== -1) ? url_completa.substr(0,url_completa.indexOf('#')) : url_completa;
    // anchor # , si había
    var ancla = url_completa.split("#").pop();
    // slug de la página
    var slug = url_sin_ancla.split("/").pop();
    // slug+ancla de la página (o solo slug, si no hay ancla
    var slug_ancla = url_completa.split("/").pop();
    
    // control de si hay coincidencia o no
    var coincidencia = false;
        
    // Recorrer los enlaces del contenido extra (columna izquierda del contenido)
    // Y marcar el que está activo: enlace + grupo / solo grupo 
    $('div[id^="contenido-extra"] a.nav-link').each(function () {

        // Se debe comprobar si el enlace es absoluto o con ancla,
        //      ya que hay casos en que un menú tiene diversos grupos pero son todos
        //      anclas de la misma página, y hay casos en que diferentes grupos son de
        //      diferentes páginas. Puede ser que a veces incluyan el /slug en la href
        //      y puede que solo incluyan #ancla
        // Se comprueba el primer caracter y si es un # se homogeneiza añadiendo el slug
        var enlaceOpcion = $(this).attr('href');
        (enlaceOpcion.substr(0,1) == '#') && (enlaceOpcion = '/' + slug + enlaceOpcion);

        // guardar el primer anchor, si es de la página actual
        // guardar: primer anchor del menú + primer anchor del contenido + posición scroll
        if  ( 
              (enlaceOpcion.indexOf('#') !== -1) && 
              (enlaceOpcion.substr(0,enlaceOpcion.indexOf('#')) == '/' + slug) &&
              (primerAnchorMenu === undefined) 
            )
        {
            primerAnchorMenu = $(this);
            // primer anchor del contenido
            primerAnchorContenido = $('#contenido-detalle a[id!=""]:first-child');
            // altura de inicio del anchor o de #contenido-detalle
            primerAnchorTop = (primerAnchorContenido.length > 0) ? $(primerAnchorContenido).offset().top : $('#contenido-detalle').offset().top;
            // programar el scroll
            $(window).scroll(function(){
               if ($(this).scrollTop() < primerAnchorTop) {
                   (!primerAnchorMenu.hasClass('active')) && primerAnchorMenu.addClass('active');
               }
            });
        }

        // comprobar si corresponde con la url actual
        if (enlaceOpcion == '/' + slug_ancla) {
            // hay coincidencia!!
            coincidencia = true;
            // Discernir si es un enlace dentro de un grupo o un grupo sin opciones
            // Un grupo desciende de ".grupo-menu-izquierda"
            // Una opción dentro de grupo desciende de ".container-grupoOpciones"
            // Esos dos son hermanos, ya que uno contiene el collapse
            if ($(this).closest('.container-grupoOpciones').length > 0) {
                // es una opción dentro de un grupo
                // marcar la opción como activa
                $(this).addClass('active');
                // marcar el grupo como activo (para cambios de color)
                $(this).closest('.container-grupoOpciones').prev().attr('data-activo', 'true');
                // desplegar el grupo
                $(this).closest('div[id^="grupo-pastillaMenu-"]').collapse('show');
            } else {
                // es un grupo sin opciones hijas
                // Solo hay que marcar al grupo como activo
                $(this).closest('.grupo-menu-izquierda').attr('data-activo', 'true');
            }

        }
    });

    // Si no ha habido coincidencia -> desplegar el primer grupo
    if (!coincidencia) {

        // desplegar el primero
        var grupo = $('div[id^="contenido-extra"] div[id^="grupo-pastillaMenu-"]').first();
        if (grupo.length > 0) {
            grupo.collapse('show');
            // y marcarlo como activo
            grupo.closest('.container-grupoOpciones').prev().attr('data-activo', 'true');
        }
        if (primerAnchorMenu !== undefined) {
            primerAnchorMenu.addClass('active');
        }
    }
    
    // pendiente de finalizar
    // Ni en biblioteca ni en proyectos hay que marcar el año
    if (url_completa.indexOf('biblioteca') == -1 && url_completa.indexOf('proyectos') == -1 && url_completa.indexOf('cursos') == -1) {
        marcaAnyo();
    } else {
        // si es trabajos SEPD de la biblioteca, desplegar formato
        if (slug == 'tis') {
            var grupo = $('div[id="filtroFormato"]');
            if (grupo.length > 0) {
                grupo.collapse('show');
            }
        }
        // para las páginas de proyectos: dos opciones, o área de gestión o estado
        if (url_completa.indexOf('proyectos') != -1 && slug != 'proyectos') {
            if (slug == 'en_curso' || slug == 'historicos') {
                var grupo = $('div[id="filtroEstados"]');
                if (grupo.length > 0) {
                    grupo.collapse('show');
                }
            } else {
                var grupo = $('div[id="filtroAreagestion"]');
                if (grupo.length > 0) {
                    grupo.collapse('show');
                }
            }
        }
    }

}