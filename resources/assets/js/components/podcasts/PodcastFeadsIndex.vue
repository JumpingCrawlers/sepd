<template>
	<div class="container" id="listaPodcast">
		<!-- Cabecera: total de la podcasts -->
		<!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
		<div class="row py-3 mb-2 align-items-center bg-prensa">
			<div class="pl-3 input-group w-100">
				<input id="podcasts-encontrados" v-model="totalPodcasts" type="text"
					class="bg-prensa w-100 border-0 border-bottom text-white" readonly>
			</div>
		</div>

		<div v-if="tipo" class="mb-2 pb-2">
			<div>
				<h4 v-html="tipo.title"></h4>
			</div>
			<div v-html="tipo.description"></div>
			<div v-if="tipo.image" style="display: flex">
				<b>Patrocinado por:</b>
				<img :src="tipo.logo_sponsor_src" class="img-fluid" style="height: 35px; margin-left: 5px;">
			</div>
		</div>

		<!-- Un div para cada nota de podcasts con su contenido -->
		<div v-for="podcasts, index in laravelData.data" :key="podcasts.id" class="podcasts-index px-0"
			:data-id-podcasts="podcasts.id">

			<div class="row">
				<div class="col-12 _col-md-6 _col-lg-4 callout prensa mb-5">
					<div class="| card-podcast |">
						<div class="card-podcast__title">
							<span v-show="podcasts.restringido">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
							<h4 v-html="podcasts.title"></h4>
						</div>
						<div style="position: relative;">
							<iframe :src="embedByEnlace(podcasts.enlace)" frameborder="0"
								class="w-full w-100 bg-light rounded"></iframe>
							<div v-if="!(!podcasts.restringido || (podcasts.restringido && auth))" class="card-podcast__restringido" v-on:click="modalLogin()">
								Contenido exclusivo para usuarios registrados
							</div>
						</div>
						<div class="px-2" v-html="podcasts.description">
						</div>
						<div v-if="!podcasts.restringido || (podcasts.restringido && auth)" class="row justify-content-end">
							<div class="redes-sociales pb-0">
								<a :href="'https://twitter.com/intent/tweet?url=' + podcasts.enlace" target="_blank">
									<img src="/storage/partesgraficas/June2024/JXx3ClYRhGPj9hf0zHZw.png" width="25" height="25" border="0">
								</a>
								<a :href="'https://www.linkedin.com/sharing/share-offsite/?url=' + podcasts.enlace" target="_blank">
									<img src="/storage/partesgraficas/May2018/O3EmepiOmzfMzZpqS2hY.png" width="25" height="25" border="0">
								</a>
								<a :href="'https://www.facebook.com/sharer/sharer.php?u=' + podcasts.enlace" target="_blank">
									<img src="/storage/partesgraficas/May2018/jJJMZgOHgg7bnjE5puQf.png" width="25" height="25" border="0">
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Paginación al pie -->
		<pagination :limit="4" :data="laravelData" @pagination-change-page="getPodcast"></pagination>

	</div>
</template>

<script>

Vue.component('pagination', require('laravel-vue-pagination'));

export default {
	data() {
		return {
			laravelData: {},
			tipo: null,
		}
	},
	computed: {
		totalPodcasts: function () {
			return (this.laravelData.total == 1) ? this.laravelData.total + " podcast encontrado" : this.laravelData.total + " podcasts encontrados";
		}
	},
	props: [
		'auth',
		'iconos'
	],
	methods: {
		modalLogin() {
			$('#modalLogin').modal('show');
		},
		getPodcast(page = 1) {
			var instancia = this;
			// es necesario recuperar filtros activos
			var filtros = $('#filtrosGet').val();
			// y guardar la página por si hay un back();
			$('#paginaGet').val(page);
			// mensaje de recuperando cursos...
			$('#podcasts-encontrados').val('Recuperando datos...');

			var url_get = 'api/podcast-feads?page=' + page + filtros;

			axios.get(url_get)
				.then(response => {
					this.laravelData = response.data.podcasts;
					this.tipo = response.data.tipo;
				})
				.catch(function (resp) {
					console.log('Error recuperando artículos');
				});
		},
		
		embedByEnlace: function (enlace) {
			return enlace.replace("/episode", "/embed/episode");
		},
	}
}
</script>

<style>
	.card-podcast__title {
		display: flex;
		gap: 4px;
	}
	.card-podcast__title .fa {
		font-size: 1.4rem;
	}
	.card-podcast__restringido{
		background: #ffffffcf;
		border-radius: 9px;
		font-size: 1.1rem;
		cursor: pointer;
		color: #333;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: bold;
	}
</style>