<template>
    <div id="listaBiblioteca">

        <!-- Cabecera: Biblioteca -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div :class="'row py-3 mb-2 align-items-center bg-'+seccion">
            <div class="pl-3 input-group w-100">
                <input id="biblioteca-encontrados" v-model="totalBiblioteca" type="text" :class="'bg-'+seccion+' w-100 border-0 border-bottom text-white'" readonly>
            </div>
        </div>

        <!-- Un div para cada resultado con su contenido -->
        <div v-for="biblioteca, index in laravelData.data" :id="biblioteca.id_documento" class="px-0" :data-id-prensa="biblioteca.id_documento">
            <div class="row mb-3">
                <div class="col-2 col-sm-1 px-1 text-center">
                   
                    <a v-if="biblioteca.archivo_biblio && biblioteca.tipo == 'pdf'" target="_blank" :href="rutaBiblioteca(biblioteca.archivo_biblio)">
                        <img :src="rutaIcono(biblioteca.tipo)" class="img-fluid">
                    </a>
                    <a v-else-if="biblioteca.tipo == '1'" target="_blank" :href="rutaCurso(biblioteca.id_documento)">
                        <img :src="rutaIcono('1')" class="img-fluid">
                    </a>
                    <a v-else-if="biblioteca.enlace && biblioteca.tipo == 'enlace' || biblioteca.tipo == 'audio'" target="_blank" :href="biblioteca.enlace">
                        <img :src="rutaIcono(biblioteca.tipo)" class="img-fluid">
                    </a>

                </div>
                <div :class="'col-10 col-sm-11 callout '+seccion+' w-100'">
                    <p>
                        <strong v-if="biblioteca.fecha != '-0001-11-30 00:00:00'">{{ biblioteca.fecha_formateada }}</strong>
                        <strong v-else>Anterior a 2007</strong>
                        <br>
                        <em v-html="biblioteca.titulo"></em> <!-- Para que se apliquen los caracteres especiales de UTF-8 es necesario usar "v-html" -->
                    </p>
                    <p v-html="$options.filters.truncate(biblioteca.descripcion, 300)"></p>
                    
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getBiblioteca"></pagination>

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
            totalBiblioteca: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " documento encontrado" : this.laravelData.total + " documentos encontrados";
            }
        },
        props: [
            'urlBack',
            'url',
            'iconos',
            'seccion'
        ],
        methods: {
            getBiblioteca(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#biblioteca-encontrados').val('Recuperando datos...');

                var area = window.location.pathname.split('/')[2];
                if (area != undefined) {
                    area = "&area="+area;
                    var url_get = '/api/biblioteca?page=' + page + area + filtros;
                } else {
                    var url_get = '/api/biblioteca?page=' + page + filtros;
                }

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando documentos');
                    });
            },
            // icono para el link correspondiente de la biblioteca
            rutaIcono: function(tipo) {

                var tipoIcono = '';
                switch(tipo) {
                    case "enlace":
                        tipoIcono = 'enlace';
                        break;
                    case "audio":
                        tipoIcono = 'play';
                        break;
                    case "pdf":
                        tipoIcono = 'pdf';
                        break;
                    case "1":
                        tipoIcono = 'curso';
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
            rutaBiblioteca: function(enlace) {
                if (this.checkJson(enlace)) { // Verificar si es JSON (Nuevos enlaces) || 3 Ways - Alexis Bogado
                    const jsonData = JSON.parse(enlace);
                    return `${this.urlBack}/storage/${jsonData[0].download_link}`;
                }

                return `${this.url}/storage/biblioteca/${enlace}`;
            },
            // ruta al curso correspondiente
            rutaCurso: function(id) {
                return `${this.url}/cursos/${id}`;
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
