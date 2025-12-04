// Fichero: consultas.js
// Autor: Xavi Baz
// Fecha: 13 dic 2018
// Entorno: Página de consulta de consultas (excelencia), con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar consultas al hacer click o Enter en algún filtro


// Cargar el componente VUE y crear la instancia VUE
import ConsultasIndex from './components/consultas/ConsultasIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { ConsultasIndex },
    methods: {
            // cargar las consultas de inicio y/o con filtros
            refrescarConsultas: function() {
                this.$refs.ConsultasIndex.getConsultas();
            }
    }
});


// Recorrer el formulario montando la lista de filtros a utilizar en la llamada
// Al mismo tiempo procesar si cada "grupo" está activo o no
// function getListaFiltrosActivos() en FILTROS.JS
    

$(document).ready(function() {

    // captar los filtros
    $("#formFiltros").click(function( event ) {
        if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarConsultas();
        }
    });

    // captar el enter en search "Consultas"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarConsultas();
            event.preventDefault();
        }
    });

    // Iniciar la lista de Consultas
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarConsultas($('#paginaGet').val());
    
    // Abrir el grupo del filtro de áreas
    var grupo = $('#filtroAreagestion');
    if (grupo.length > 0) {
        grupo.collapse('show');
    }

});