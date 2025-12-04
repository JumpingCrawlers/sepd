// Fichero: filtros.js
// Autor: Xavi Baz
// Fecha: 16 mayo 2018
// Entorno: Página de consulta de contenido con filtros (Vue.js)
// Descripción: Funciones generales utilizadas en filtros


// getListaFiltrosActivos()
// Descripción: Función general que recoge un click dentro del formulario de filtros y refresca la lista
//              de elementos que satisfacen los filtros
//              - Restricciones: nombreFormulario => formFiltros (reusado en otras páginas)
window.getListaFiltrosActivos = function getListaFiltrosActivos() {

    var filtrosEnParametro = '';
    var grupos = {};

    // Recorrer el formulario montando la lista de filtros a utilizar en la llamada
    // Al mismo tiempo procesar si cada "grupo" está activo o no
    $.each($('input','#formFiltros'), function (k) {
        switch ($(this).attr('name')) {
            case 'tipo':
                    filtrosEnParametro += "&tipo=" + $(this).val();
                    break;
            // distinguir entre el texto y los checkboxes
            case 'search':
                    if ($(this).val() != '') {
                        filtrosEnParametro += "&texto_busqueda=" + $(this).val();
                    }
                    break;
            // filtrosGet y paginaGet => nada
            case 'tipo_contenido':
                let selector = document.querySelector('input[name="tipo_contenido"]:checked');
                if (selector) {
                    filtrosEnParametro += "&tipo_contenido=" + selector.value;
                }
                break;
            case 'filtrosGet':
            case 'paginaGet':
                    break;
            // para todos los checkboxes
            default:
                    // controlar si el grupo ya existe en la lista de grupos
                    // recuperamos el id del grupo-menu-izquierda (padre)
                    var nombre_grupo = $(this).closest('[id^=filtro]').siblings('.grupo-menu-izquierda').attr('id');
                    // si no está, se añade con valor "false"
                    if (grupos[nombre_grupo] === undefined) {
                        grupos[nombre_grupo] = false;
                    };

                    // añadir el filtro si toca, y marcar el grupo como activo                    
                    if ($(this).prop('checked')) {
                        filtrosEnParametro += "&" + $(this).attr('name') + "=on";
                        // cambiar el valor "activo" del grupo a "true"
                        grupos[nombre_grupo] = true;
                    }
        }
    });

    // recorrer los grupos y marcar los activos y los que no
    for (var grupo in grupos) {
        (grupos[grupo] === true) ? $('#' + grupo).attr('data-activo', 'true') : $('#' + grupo).attr('data-activo', 'false');
    }

    return filtrosEnParametro;
}


