<template>
    <div class="container" id="listaDossier">

        <!-- Cabecera: total de dossier -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div class="row py-3 mb-2 align-items-center bg-prensa">
            <div class="pl-3 input-group w-100">
                <input id="dossier-encontrados" v-model="totalDossier" type="text" class="bg-prensa w-100 border-0 border-bottom text-white" readonly>
            </div>
        </div>

        <!-- Un div para cada dossier con su contenido -->
        <div v-for="dossier, index in laravelData.data" :key="dossier.id" class="dossier-index px-0" :data-id-dossier="dossier.id">
            <div class="row mb-3">
                <div class="col-2 col-sm-1 px-1 text-center">
                    <!-- Link  -->
                    <a v-if="dossier.all_file" target='_blank' :href="rutaDossier(dossier.all_file)">
                        <img :src="rutaIcono(dossier.all_file)" class="img-fluid">
                    </a>
                </div>
                <div class="col-10 col-sm-11 callout prensa flex-row w-100">
                    <div class="d-flex flex-column align-items-start">
                        <p>
                            <strong>{{ dossier.fecha_formateada }}</strong>
                            <br>
                            <em v-html="dossier.titulo"></em>
                        </p>
                        <a v-if="dossier.all_file" target='_blank' :href="rutaDossier(dossier.all_file)" class="text-dark">
                            <p v-html="dossier.texto"></p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getDossier"></pagination>

    </div>
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        data() {
                return {
                    laravelData: {},
                }
        },
        computed: {
            totalDossier: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " artículo encontrado" : this.laravelData.total + " artículos encontrados";
            }
        },
        props: [
            'urlBack',
            'url',
            'iconos'
        ],
        methods: {
            getDossier(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#dossier-encontrados').val('Recuperando datos...');

                var url_get = 'api/dossier?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            // icono para el link correspondiente del dossier, según la extensión del fichero
            rutaIcono: function(enlace) {
                // recuperar la extension del fichero
                var extension = enlace.substring(enlace.lastIndexOf(".")+1,enlace.lastIndexOf(".")+4);
                var tipoIcono = '';
                switch(extension) {
                    case "jpg":
                    case "jpe":
                    case "png":
                        tipoIcono = 'imagen';
                        break;
                    case "pdf":
                        tipoIcono = 'pdf';
                        break;
                }

                // recorrer el array de iconos buscando el correspondiente
                var arrayIconos = JSON.parse(this.iconos);
                for (var i = 0; i < arrayIconos.length; i++) {
                    if ( arrayIconos[i].key.indexOf(tipoIcono)>=0 ) {
                        return arrayIconos[i].src;
                    }
                }

                return '';
            },
            // ruta al documento o video o....
            rutaDossier: function(enlace) {
                if (this.checkJson(enlace)) { // Verificar si es JSON (Nuevos enlaces) || 3 Ways - Alexis Bogado
                    const jsonData = JSON.parse(enlace);
                    return `${this.urlBack}/storage/${jsonData[0].download_link}`;
                }

                return `${this.urlBack}/storage/dossier/${enlace}`;
            },
            checkJson: function(data) { // Verificar si es JSON (Nuevos enlaces) || 3 Ways - Alexis Bogado
                try {
                    JSON.parse(data);
                } catch (e) {
                    return false;
                }

                return true;
            }
        }
    }
</script>
