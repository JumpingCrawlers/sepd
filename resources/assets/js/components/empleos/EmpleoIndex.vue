<template>
    <div id="listaEmpleos">

        <!-- Cabecera: total de las empleos -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div :class="'row py-3 mb-2 align-items-center bg-'+seccion">
            <div class="pl-3 input-group w-100">
                <input id="empleos-encontradas" v-model="totalEmpleo" type="text" :class="'bg-'+seccion+' w-100 border-0 border-bottom text-white'" readonly>
            </div>
        </div>

        <!-- Un div para cada empleo con su contenido -->
        <div v-for="empleos, index in laravelData.data" :key="empleos.id" class="empleos-index px-0" :data-id-noticas="empleos.id">
            <div class="row mb-3">
                <div :class="'col-12 callout '+seccion+' flex-row w-100'">
                       <p>
                            <strong>{{ empleos.fecha_formateada }}</strong>
                            <br>
                            <em v-html="empleos.titulo"></em> <!-- Para que se apliquen los caracteres especiales de UTF-8 es necesario usar "v-html" -->
                        </p>
                        <p v-html="$options.filters.truncate(empleos.texto_formateado, 290)"></p>
                        <div class="container">
                            <div class="row">
                                
                                <div class="col-12 float-left text-right flex-column w-100 p-0">
                                    <!-- Link  -->
                                    <a v-if="empleos.conectado" :href="rutaEmpleo(empleos.id)" class="text-nodeco">
                                        Ver más
                                    </a>
                                    <a v-else href="#" data-toggle="modal" data-target="#modalLogin" class="text-nodeco">
                                        Ver más
                                        <div class="candado d-inline"></div>
                                    </a>

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getEmpleo" ></pagination>

    </div>
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        data() {
                return {
                    laravelData: {}
                }
        },
        computed: {
            totalEmpleo: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " empleo encontrado" : this.laravelData.total + " empleos encontrados";
            }
        },
        props: [
            'urlWebAntigua',
            'iconos',
            'seccion'
        ],
        methods: {
            getEmpleo(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#empleos-encontrados').val('Recuperando datos...');

                var url_get = 'api/empleos?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            // icono para el link correspondiente de las empleos, según la extensión del fichero
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
                    case "enlace":
                        tipoIcono = 'enlace';
                        break;
                    case "null":
                        tipoIcono = '';
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
            // ruta al enlace de la base de datos
            rutaEnlace: function(enlace) {
                return enlace;
            },
            // ruta a la empleo del botón "Ver más" se muestra con el blade "show" de empleos
            rutaEmpleo: function(enlace) {
                return '/empleos/' + enlace;
            }
        }
    }
</script>
