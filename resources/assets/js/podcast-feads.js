// Fichero: podcasts.js
// Autor: Martin Nikolaev
// Fecha: 05 Septiembre 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import PodcastFeadsIndex from './components/podcasts/PodcastFeadsIndex.vue';

window.instanciaVue = new Vue({
	el: '#contenidoVue',
	components: { PodcastFeadsIndex },
	methods: {
		// cargar las podcasts de inicio y/o con filtros
		refrescarPodcast: function () {
			this.$refs.PodcastFeadsIndex.getPodcast();
		}
	}
});

// document ready => programar eventos
$(document).ready(function () {

	// captar los filtros, menos en el buscador
	$("#formFiltros").click(function (event) {
		if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
			var listaParametros = getListaFiltrosActivos();
			$('#filtrosGet').val(listaParametros);
			instanciaVue.refrescarPodcast();
		}
	});

	// captar el enter en search "podcast"
	$('#formFiltros input[name="search"]').on("keypress", function (e) {
		if (e.keyCode == 13) {
			var listaParametros = getListaFiltrosActivos();
			$('#filtrosGet').val(listaParametros);
			instanciaVue.refrescarPodcast();
			event.preventDefault();
		}
	});

	// Iniciar la lista de podcasts
	$('#filtrosGet').val(getListaFiltrosActivos());

	instanciaVue.refrescarPodcast($('#paginaGet').val());
});