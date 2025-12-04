<template>
    <div class="container" id="listaPodcast">

        <!-- Cabecera: total de la podcasts -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div class="row py-3 mb-2 align-items-center bg-prensa">
            <div class="pl-3 input-group w-100">
                <input id="podcasts-encontrados" v-model="totalPodcasts" type="text" class="bg-prensa w-100 border-0 border-bottom text-white" readonly>
            </div>
        </div>

        <!-- Un div para cada nota de podcasts con su contenido -->
        <div v-for="podcasts, index in laravelData.data" :key="podcasts.id" class="podcasts-index px-0" :data-id-podcasts="podcasts.id">
            <div class="row mb-3 hijo">
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                    <!-- Link  -->
                    <a v-if="podcasts.audio !== ''"  v-on:click="playAudio(podcasts.id)" class="text-nodeco">
                        <img :src="rutaIcono(podcasts.audio)" class="img-fluid">
                    </a>
                </div>
                <div class="col-lg-11 col-md-10 col-sm-10 col-xs-10 callout prensa flex-row w-100">
                    <div class="d-flex flex-column align-items-start">
                        <p>
                            <strong>{{ podcasts.fecha_formateada }}</strong>
                            <br>
                            <em v-html="podcasts.titulo"></em> <!-- Para que se apliquen los caracteres especiales de UTF-8 es necesario usar "v-html" -->
                        </p>
                    <audio controls class="player d-none" :id="'player-'+podcasts.id">
                        <source :src="rutaPodcast(podcasts.audio)" type="audio/mpeg">
                        <source :src="rutaPodcast(podcasts.audio)" type="audio/ogg">
                        Your browser does not support the audio element.
                    </audio>
                        <!--<p v-html="podcasts.texto"></p>-->
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
                }
        },
        computed: {
            totalPodcasts: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " podcast encontrado" : this.laravelData.total + " podcasts encontrados";
            }
        },
        props: [
            'urlWebAntigua',
            'iconos'
        ],
        methods: {
            getPodcast(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#podcasts-encontrados').val('Recuperando datos...');

                var url_get = 'api/podcasts?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            // icono para el link correspondiente de la nota de podcasts, según la extensión del fichero
            rutaIcono: function(enlace) {
                // recuperar la extension del fichero
                var extension = enlace.substring(enlace.lastIndexOf(".")+1);
                var tipoIcono = '';
                switch(extension) {
                    case "mp3":
                    case "wav":
                    case "wma":
                        tipoIcono = 'play';
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
            rutaPodcast: function(enlace) {
                return this.urlWebAntigua + '/contenido/podcast/' + enlace;
            },
            playAudio: function(id){
                $.each($('audio'), function () {
                   var player = document.getElementById(this.id);
                   player.pause();
                   player.currentTime = 0;
                });

                var audio  = document.getElementById("player-"+id);

                $(".player").addClass("d-none");
                $("#player-"+id).removeClass("d-none");
                audio.play();              
            }

        }

    }

</script>
