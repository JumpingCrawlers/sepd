<template>
    <div id="listaPrensa">

        <!-- Cabecera: total de la prensa -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div class="row py-3 mb-2 align-items-center bg-prensa">
            <div class="pl-3 input-group w-100">
                <input id="prensa-encontrados" v-model="totalPrensa" type="text" class="bg-prensa w-100 border-0 border-bottom text-white" readonly>
            </div>
        </div>

        <!-- Un div para cada nota de prensa con su contenido -->
        <div v-for="prensa, index in laravelData.data" :key="prensa.id" class="prensa-index px-0" :data-id-prensa="prensa.id">
            <div class="row mb-4">
                <div class="col-2 col-sm-1 px-1 text-center">
                    <!-- Link  -->
                    <a v-if="prensa.all_file" target='_blank' :href="rutaPrensa(prensa.all_file)">
                        <img :src="rutaIcono(rutaPrensa(prensa.all_file))" class="img-fluid">
                    </a>
                </div>
                <div class="col-10 col-sm-11 callout prensa flex-row w-100">
                    <p class="text-justify">
                        <strong>{{ prensa.fecha_formateada }}</strong>
                        <br>
                        <a v-if="prensa.all_file" target='_blank' :href="rutaPrensa(prensa.all_file)" class="text-dark">
                            <em v-html="prensa.titulo"></em>
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getPrensa"></pagination>

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
            totalPrensa: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " artículo encontrado" : this.laravelData.total + " artículos encontrados";
            }
        },
        props: [
            'urlBack',
            'url',
            'iconos'
        ],
        methods: {
            getPrensa(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#prensa-encontrados').val('Recuperando datos...');

                var url_get = 'api/prensa?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            // icono para el link correspondiente de la nota de prensa, según la extensión del fichero
            rutaIcono: function(enlace) {
                // recuperar la extension del fichero
                var extension = enlace.substring(enlace.lastIndexOf(".")+1);
                var tipoIcono = '';
                switch(extension) {
                    case "jpg":
                    case "jpeg":
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
            rutaPrensa: function(enlace) {
                if (this.checkJson(enlace)) {
                    const jsonData = JSON.parse(enlace);
                    return `${this.urlBack}/storage/${jsonData[0].download_link}`;
                }

                return `${this.urlBack}/storage/prensa/${enlace}`;
            },
            checkJson: function(data) {
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
