<template>
    <div id="videoSepdtv">

        <!-- Un div para cada nota de sepdtv con su contenido -->
        <div class="row">
            <div v-if="this.video != undefined"  :class="'col-sm-12 '+seccion+' flex-row w-100'">
                <div :class="'col-12 py-3 mb-2 align-items-center bg-'+seccion">
                    <div class="pl-0 col-auto input-group w-100">
                        <input id="sepdtv-reproducciones" v-model="totalReproducciones" type="text" :class="'bg-'+seccion+' w-100 border-0 border-bottom text-white'" readonly>
                    </div>
                </div>
                <div :class="'col-12 mt-3 color-'+seccion">
                    <strong v-html="this.video.titulo"></strong>
                </div>
                <div class="col-12" v-html="this.video.subtitulo"></div>
                <div class="col-12 mt-2">
                    <div id="videoPlayer" class="embed-responsive embed-responsive-16by9">
                    <video :id="'instanciaVideo'+this.video.codigo" controls="controls" :src="rutaVideo(this.video.codigo)" :poster="rutaPoster(this.video.codigo)" class="embed-responsive-item"></video>
                </div>
                <div class="col-12 mt-3" v-html="this.video.descripcion"></div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                laravelData: {},
                video: undefined,

            }
        },
        computed: {
            totalReproducciones: function() {
                return (this.video.contador == 1) ? this.video.contador + " reproducción" : this.video.contador + " reproducciones";
            }
        },
        props: [
            'url',
            'urlBack',
            'seccion'
        ],
        methods: {
            getSepdtv(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#sepdtv-encontrados').val('Recuperando datos...');
                var area = window.location.pathname.split('/')[2];
                if (area != undefined) {
                    area = "&area_tv="+area;
                    //console.log(area);
                    var url_get = '/api/sepdtv?page=' + page + area + filtros;
                }else{
                    var url_get = '/api/sepdtv?page=' + page + filtros;
                }

                axios.get(url_get)
                    .then(response => {
                        this.video = response.data.data[0];
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            
            // ruta al documento o video o....
            rutaVideo: function(enlace) {
                return `${this.urlBack}/storage/sepd_tv/video/${enlace}.mp4`;
            },
            // ruta a la imagen portada
            rutaPoster: function(codigo) {

                return `${this.urlBack}/storage/sepd_tv/portada/${codigo}.jpg`;

            },
            playVideo: function(codigo, titulo, subtitulo, descripcion, contador){
                var data;
                data = {
                    "codigo": codigo,
                    "titulo": titulo,
                    "subtitulo": subtitulo,
                    "descripcion": descripcion,
                    "contador": contador
                };
                
                this.video = data;
            }

        }

    }

</script>
