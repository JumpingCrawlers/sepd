<template>
    <div class="" id="listaGaleria">

        <!-- Cabecera: total de la galeria -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div :class="'row py-3 mb-2 align-items-center bg-'+ seccion">
            <div class="pl-3 input-group w-100">
                <input id="galeria-encontrados" v-model="totalGaleria" type="text" :class="'bg-' + seccion + ' w-100 border-0 border-bottom text-white'" readonly>
            </div>
        </div>

        <div class="row">
        <!-- Un div (carpeta) para cada galeria con su contenido -->
       
            <div v-cloak :class="'col-12 color-' + seccion + ' pb-1 nopadding'">
                <div v-if="migaPan1 == undefined" class="float-left" v-html="migaPanInicio"></div>
                <div v-else class="pointer float-left" v-on:click="volverInicio()"><strong v-html="migaPanInicio"></strong></div>

                <div v-if="migaPan2 == undefined" class="float-left" v-html="migaPan1"></div>
                <div v-else class="pointer float-left" v-on:click="volverC1()"><strong  v-html="migaPan1"></strong></div>

                <div v-if="migaPan3 == undefined" class="float-left" v-html="migaPan2"></div>
                <div v-else class="pointer float-left" v-on:click="volverC2()"><strong v-html="migaPan2"></strong></div>

                <div class="float-left" v-html="migaPan3"></div>
            </div>

            <div  v-for="galeria, index in laravelData.data" :key="galeria.id"  class="col-sm-6 col-md-4 col-lg-3 galeria-index px-0 text-center" :data-id-galeria="galeria.id">
         
                <div v-if="galeria.imagen==undefined" class="d-flex flex-column py-3 col-12">

                    <div v-if="galeria.carpeta1 && galeria.imagen==undefined" >

                        <div class="col-12 mb-2"><img :src="rutaIcono('galeria.carpeta')" :id="galeria.carpeta1" v-on:click="accederCarpeta(1, galeria.carpeta1,  galeria.nombre)" class="img-fluid img-carpeta pointer" ></div>
                        <div class="col-12 pointer" v-on:click="accederCarpeta(1, galeria.carpeta1,  galeria.nombre)"><em v-html="galeria.nombre"></em></div>
                    </div>

                    <div v-if="galeria.carpeta2 && galeria.imagen==undefined">
                        <div class="col-12 mb-2"><img :src="rutaIcono('galeria.carpeta')" :id="galeria.carpeta2" v-on:click="accederCarpeta(2, galeria.carpeta2, galeria.nombre)" class="img-fluid img-carpeta pointer"></div>
                        <div class="col-12 pointer" v-on:click="accederCarpeta(2, galeria.carpeta2, galeria.nombre)"><em v-html="galeria.nombre"></em></div>
                    </div>

                    <div v-if="galeria.carpeta3 && galeria.imagen==undefined">
                        <div class="col-12 mb-2"><img :src="rutaIcono('galeria.carpeta')" :id="galeria.carpeta3" v-on:click="accederCarpeta(3, galeria.carpeta3, galeria.nombre)" class="img-fluid img-carpeta pointer"></div>
                        <div class="col-12 pointer" v-on:click="accederCarpeta(3, galeria.carpeta3, galeria.nombre)"><em v-html="galeria.nombre"></em></div>
                    </div> 
                    
                </div>
                <div v-if="galeria.imagen!=undefined" class="col-auto embed-responsive embed-responsive-4by3 px-3 text-center foto">
                    <a data-lightbox="roadtrip" :data-title="galeria.titulo" :href="rutaGaleria(galeria.imagen)"><img class="img-fluid embed-responsive-item" :src="rutaGaleria(galeria.imagen)"></a>
                </div>
            </div>  
           
        </div>

        <!-- Paginación al pie -->
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getGaleria"></pagination>

    </div>
    <!-- 

    Lokesh Dhakar

    https://lokeshdhakar.com/projects/lightbox2

    The MIT License (MIT)

Copyright (c) 2015 Lokesh Dhakar

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. -->
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        data() {
                return {
                    laravelData: {},
                    migaPanInicio: "> Inicio",
                    migaPan1: undefined,
                    migaPan2: undefined,
                    migaPan3: undefined
                }
        },
        computed: {
            totalGaleria: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " archivo encontrado" : this.laravelData.total + " archivos encontrados";
            }
        },
        props: [
            'urlBack',
            'url',
            'iconos',
            'seccion'
        ],
        methods: {
            getGaleria(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#galeria-encontrados').val('Recuperando datos...');
                var url_get = '/api/galeria?page=' + page; // url que devuelve los datos
                
                /**
                 * Cuando se accede al primer nivel de las carpetas, se recoge el número de carpeta por el parámetro de la ruta. 
                 * A raiz de esto se comprueba, en localStorage, si estas en alguno de los otros dos niveles ya que estos no se recogen por parámetro.
                 * Dependiendo del nivel en le que te encuentres se monta una url para sacar los datos correspondientes.
                 */
                if(window.location.pathname.split('/')[2]){
                    localStorage.setItem("carpeta1", window.location.pathname.split('/')[2]);

                    // se comprueba qué niveles están declarados en localStorage para hacer la consulta
                    var carpeta1 = localStorage.getItem('carpeta1');
                    var carpeta2 = localStorage.getItem('carpeta2');
                    var carpeta3 = localStorage.getItem('carpeta3');

                    if (carpeta1 != undefined) {
                        url_get += '&carpeta1=' + carpeta1;
                    }
                    if (carpeta2 != undefined) {
                        url_get += '&carpeta2=' + carpeta2;
                    }
                    if (carpeta3 != undefined) {
                        url_get += '&carpeta3=' + carpeta3;
                    }
                }

                // llamada que se realiza para sacar los datos con la url montada
                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        //console.log(resp);
                        console.log('Error recuperando la galeria');
                    });

                // segunda llamada para sacar el nombre de la carpeta del primer nivel pasada por parámetro ya que no existe otra forma de obtenerlo
                var url_get2 = '/api/carpeta_galeria?carpeta1=' + carpeta1; // url que devuelve el nombre de la carpeta1
                axios.get(url_get2)
                    .then(response => {
                        // se crea la miga de pan del primer nivel de la carpeta
                        this.migaPan1 = "&nbsp;> " + response.data[0].nombre;
                    })
                    .catch(function (resp) {
                        //console.log(resp);
                    });   
            },
            // icono para el link correspondiente de la nota de galeria, según la extensión del fichero
            rutaIcono: function(enlace) {
                // recuperar la extension del fichero
                var extension = enlace.substring(enlace.lastIndexOf(".")+1);
                var tipoIcono = '';
                switch(extension) {
                    case "carpeta":
                        tipoIcono = 'carpeta';
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
            rutaGaleria: function(enlace) {
                // Imagen nueva
                if (enlace.startsWith("imagenes\\")) return `${this.urlBack}/storage/${enlace}`;
                if (enlace.startsWith("imagenes/")) return `${this.urlBack}/storage/${enlace}`;

                return `${this.urlBack}/storage/${!enlace.startsWith("imagenes\\") ? `imagenes/${enlace}` : enlace}`;
            },
            accederCarpeta: function(numCarpeta, carpeta, nombreCarpeta) {
                if (numCarpeta == 1) { 
                    // cuando se accede al primer nivel desde el inicio de galeria se redirecciona para que se ponga el parámetro de la carpeta en la que te encuentres.
                    location.href="/galeria/"+carpeta; 
                }

                // se crean las migas de pan segun el nivel en el que te encuentres
                if (numCarpeta == 2) {
                    localStorage.setItem("carpeta2", carpeta);
                     this.migaPan2 = "&nbsp;> " + nombreCarpeta;
                }else if (numCarpeta == 3) {
                    localStorage.setItem("carpeta3", carpeta);
                     this.migaPan3 = "&nbsp;> " + nombreCarpeta;
                }
                this.getGaleria(); // al estar en cualquiera de los otros niveles se refresca el contenido de la view
            },

            // función para volver al inicio, se borran todas las migas de pan y se redirecciona para quitar el parámetro de la url
            volverInicio: function(){
                localStorage.removeItem('carpeta1'); 
                localStorage.removeItem('carpeta2'); 
                localStorage.removeItem('carpeta3'); 
                this.migaPan1 = undefined;
                this.migaPan2 = undefined;
                this.migaPan3 = undefined;
                location.href = "/galeria";
            },
            // función para volver al interior de la carpeta 1
            volverC1: function(){
                localStorage.removeItem('carpeta2'); 
                localStorage.removeItem('carpeta3'); 
                this.migaPan2 = undefined;
                this.migaPan3 = undefined;
                this.getGaleria();
            },
            // función para volver al interior de la carpeta 2
            volverC2: function(){
                localStorage.removeItem('carpeta3');
                this.migaPan3 = undefined;
                this.getGaleria();
            }
        }
    }
</script>
