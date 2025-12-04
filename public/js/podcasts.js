/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 76);
/******/ })
/************************************************************************/
/******/ ({

/***/ 1:
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 2:
/***/ (function(module, exports) {

module.exports = {
	props: {
		data: {
			type: Object,
			default: function() {
				return {
					current_page: 1,
					data: [],
					from: 1,
					last_page: 1,
					next_page_url: null,
					per_page: 10,
					prev_page_url: null,
					to: 1,
					total: 0,
				}
			}
		},
		limit: {
			type: Number,
			default: 0
		}
	},

	template: '<ul class="pagination" v-if="data.total > data.per_page">\
		<li class="page-item pagination-prev-nav" v-if="data.prev_page_url">\
			<a class="page-link" href="#" aria-label="Previous" @click.prevent="selectPage(--data.current_page)">\
				<slot name="prev-nav">\
					<span aria-hidden="true">&laquo;</span>\
					<span class="sr-only">Previous</span>\
				</slot>\
			</a>\
		</li>\
		<li class="page-item pagination-page-nav" v-for="n in getPages()" :class="{ \'active\': n == data.current_page }">\
			<a class="page-link" href="#" @click.prevent="selectPage(n)">{{ n }}</a>\
		</li>\
		<li class="page-item pagination-next-nav" v-if="data.next_page_url">\
			<a class="page-link" href="#" aria-label="Next" @click.prevent="selectPage(++data.current_page)">\
				<slot name="next-nav">\
					<span aria-hidden="true">&raquo;</span>\
					<span class="sr-only">Next</span>\
				</slot>\
			</a>\
		</li>\
	</ul>',

	methods: {
		selectPage: function(page) {
			if (page === '...') {
				return;
			}

			this.$emit('pagination-change-page', page);
		},
		getPages: function() {
			if (this.limit === -1) {
				return 0;
			}

			if (this.limit === 0) {
				return this.data.last_page;
			}

			var current = this.data.current_page,
				last = this.data.last_page,
				delta = this.limit,
				left = current - delta,
				right = current + delta + 1,
				range = [],
				pages = [],
				l;

			for (var i = 1; i <= last; i++) {
				if (i == 1 || i == last || (i >= left && i < right)) {
					range.push(i);
				}
			}

			range.forEach(function (i) {
				if (l) {
					if (i - l === 2) {
						pages.push(l + 1);
					} else if (i - l !== 1) {
						pages.push('...');
					}
				}
				pages.push(i);
				l = i;
			});

			return pages;
		}
	}
};


/***/ }),

/***/ 76:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(77);


/***/ }),

/***/ 77:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_podcasts_PodcastIndex_vue__ = __webpack_require__(78);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_podcasts_PodcastIndex_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_podcasts_PodcastIndex_vue__);
// Fichero: podcasts.js
// Autor: Martin Nikolaev
// Fecha: 05 Septiembre 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE


window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { PodcastIndex: __WEBPACK_IMPORTED_MODULE_0__components_podcasts_PodcastIndex_vue___default.a },
    methods: {
        // cargar las podcasts de inicio y/o con filtros
        refrescarPodcast: function refrescarPodcast() {
            this.$refs.PodcastIndex.getPodcast();
        }
    }
});

///////////
// function getListaFiltrosActivos() en FILTROS.JS
///////////

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

/***/ }),

/***/ 78:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(79)
/* template */
var __vue_template__ = __webpack_require__(80)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\podcasts\\PodcastIndex.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-42a57cd4", Component.options)
  } else {
    hotAPI.reload("data-v-42a57cd4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 79:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


Vue.component('pagination', __webpack_require__(2));

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            laravelData: {}
        };
    },

    computed: {
        totalPodcasts: function totalPodcasts() {
            return this.laravelData.total == 1 ? this.laravelData.total + " podcast encontrado" : this.laravelData.total + " podcasts encontrados";
        }
    },
    props: ['urlWebAntigua', 'iconos'],
    methods: {
        getPodcast: function getPodcast() {
            var _this = this;

            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;

            var instancia = this;
            // es necesario recuperar filtros activos
            var filtros = $('#filtrosGet').val();
            // y guardar la página por si hay un back();
            $('#paginaGet').val(page);
            // mensaje de recuperando cursos...
            $('#podcasts-encontrados').val('Recuperando datos...');

            var url_get = 'api/podcasts?page=' + page + filtros;

            axios.get(url_get).then(function (response) {
                _this.laravelData = response.data;
            }).catch(function (resp) {
                console.log('Error recuperando artículos');
            });
        },

        // icono para el link correspondiente de la nota de podcasts, según la extensión del fichero
        rutaIcono: function rutaIcono(enlace) {
            // recuperar la extension del fichero
            var extension = enlace.substring(enlace.lastIndexOf(".") + 1);
            var tipoIcono = '';
            switch (extension) {
                case "mp3":
                case "wav":
                case "wma":
                    tipoIcono = 'play';
                    break;
            }

            // recorrer el array de iconos buscando el correspondiente
            var arrayIconos = JSON.parse(this.iconos);
            for (var i = 0; i < arrayIconos.length; i++) {
                if (arrayIconos[i].key.indexOf(tipoIcono) >= 0) {
                    return arrayIconos[i].src;
                }
            }

            return '';
        },
        // ruta al documento o video o....
        rutaPodcast: function rutaPodcast(enlace) {
            return this.urlWebAntigua + '/contenido/podcast/' + enlace;
        },
        playAudio: function playAudio(id) {
            $.each($('audio'), function () {
                var player = document.getElementById(this.id);
                player.pause();
                player.currentTime = 0;
            });

            var audio = document.getElementById("player-" + id);

            $(".player").addClass("d-none");
            $("#player-" + id).removeClass("d-none");
            audio.play();
        }

    }

});

/***/ }),

/***/ 80:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container", attrs: { id: "listaPodcast" } },
    [
      _c("div", { staticClass: "row py-3 mb-2 align-items-center bg-prensa" }, [
        _c("div", { staticClass: "pl-3 input-group w-100" }, [
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.totalPodcasts,
                expression: "totalPodcasts"
              }
            ],
            staticClass: "bg-prensa w-100 border-0 border-bottom text-white",
            attrs: { id: "podcasts-encontrados", type: "text", readonly: "" },
            domProps: { value: _vm.totalPodcasts },
            on: {
              input: function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.totalPodcasts = $event.target.value
              }
            }
          })
        ])
      ]),
      _vm._v(" "),
      _vm._l(_vm.laravelData.data, function(podcasts, index) {
        return _c(
          "div",
          {
            key: podcasts.id,
            staticClass: "podcasts-index px-0",
            attrs: { "data-id-podcasts": podcasts.id }
          },
          [
            _c("div", { staticClass: "row mb-3 hijo" }, [
              _c(
                "div",
                { staticClass: "col-lg-1 col-md-2 col-sm-2 col-xs-2" },
                [
                  podcasts.audio !== ""
                    ? _c(
                        "a",
                        {
                          staticClass: "text-nodeco",
                          on: {
                            click: function($event) {
                              _vm.playAudio(podcasts.id)
                            }
                          }
                        },
                        [
                          _c("img", {
                            staticClass: "img-fluid",
                            attrs: { src: _vm.rutaIcono(podcasts.audio) }
                          })
                        ]
                      )
                    : _vm._e()
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass:
                    "col-lg-11 col-md-10 col-sm-10 col-xs-10 callout prensa flex-row w-100"
                },
                [
                  _c(
                    "div",
                    { staticClass: "d-flex flex-column align-items-start" },
                    [
                      _c("p", [
                        _c("strong", [
                          _vm._v(_vm._s(podcasts.fecha_formateada))
                        ]),
                        _vm._v(" "),
                        _c("br"),
                        _vm._v(" "),
                        _c("em", {
                          domProps: { innerHTML: _vm._s(podcasts.titulo) }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "audio",
                        {
                          staticClass: "player d-none",
                          attrs: { controls: "", id: "player-" + podcasts.id }
                        },
                        [
                          _c("source", {
                            attrs: {
                              src: _vm.rutaPodcast(podcasts.audio),
                              type: "audio/mpeg"
                            }
                          }),
                          _vm._v(" "),
                          _c("source", {
                            attrs: {
                              src: _vm.rutaPodcast(podcasts.audio),
                              type: "audio/ogg"
                            }
                          }),
                          _vm._v(
                            "\n                    Your browser does not support the audio element.\n                "
                          )
                        ]
                      )
                    ]
                  )
                ]
              )
            ])
          ]
        )
      }),
      _vm._v(" "),
      _c("pagination", {
        attrs: { limit: 4, data: _vm.laravelData },
        on: { "pagination-change-page": _vm.getPodcast }
      })
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-42a57cd4", module.exports)
  }
}

/***/ })

/******/ });
