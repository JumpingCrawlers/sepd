@extends('puzzle.master')

@section('slider')
    @include('puzzle.slider')
@endsection

@section('estilos')
    <style>
		.pagination {
			justify-content: center;
		}
        .pagination .page-link {
            color: #601898;
        }
        .pagination .page-link:hover {
			color: #601898;
		}
        .pagination .page-item.active .page-link {
            background-color: #601898;
            border-color: #601898;
        }
		.card-podcast {
			border-top: 1px solid #d7d7d7;
			padding-top: 20px;
			padding-bottom: 10px;
		}
		.card-podcast iframe {
			height: 85px;
		}
		.grupo-menu-izquierda.prensa a:not(.collapsed) h5,
		.grupo-menu-izquierda.prensa a:not(.collapsed) h5 {
			color: #4e25cc !important;
    		font-weight: 800;
		}
		.grupo-menu-izquierda.prensa[data-activo=true] h5,
		.grupo-menu-izquierda.prensa[data-activo=false] h5 {
			color: #4e25cc;
		}
		.grupo-menu-izquierda.prensa {
			background-color: #ccc;
		}
    </style>
@endsection

@section('contenido')
    <div class="container mb-3">
		<div class="row">
			<div class="col">
				<img src="{{ asset('images/podcast/banner-espacio-podcast.png') }}" alt=""  class="w-full w-100">
			</div>
		</div>
        <div class="row mb-4 mt-4">
			<div class="col-6 col-md-3 text-center | d-flex justify-content-center align-items-center">
				<a target="_blank" href="https://www.ivoox.com/podcast-escucha-saludigestivo_sq_f12369263_1.html">
					<img src="{{ asset('images/podcast/podcast-ivoox.png') }}" alt="ivoox" class="w-full w-100">
				</a>
			</div>
			<div class="col-6 col-md-3 text-center | d-flex justify-content-center align-items-center">
				<a target="_blank" href="https://podcastsconnect.apple.com/my-podcasts/show/escucha-saludigestivo/f9dd0167-4ed2-46d4-8c9c-045914377218">
					<img src="{{ asset('images/podcast/podcast-apple.png') }}" alt="apple" class="w-full w-100">
				</a>
			</div>
			<div class="col-6 col-md-3 text-center | d-flex justify-content-center align-items-center">
				<a target="_blank" href="https://podcasts.google.com/feed/aHR0cHM6Ly9hbmNob3IuZm0vcy9lZTk4ODY3NC9wb2RjYXN0L3Jzcw?sa=X&ved=0CAMQ9sEGahcKEwign-2TnYqEAxUAAAAAHQAAAAAQDQ&hl=es">
					<img src="{{ asset('images/podcast/podcast-google.png') }}" alt="google" class="w-full w-100">
				</a>
			</div>
			<div class="col-6 col-md-3 text-center | d-flex justify-content-center align-items-center">
				<a target="_blank" href="https://www.saludigestivo.es/wp-content/uploads/2023/12/podcast-spotify.png">
					<img src="{{ asset('images/podcast/podcast-spotify.png') }}" alt="spotify" class="w-full w-100">
				</a>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="px-5 mb-5 mt-5">
					<div style="display: flex;">
						<div class="pr-2">
							<img src="{{ asset('images/podcast/img330.jpg') }}" width="50px" height="50px">
						</div>
						<div>
							<h3>
								<strong>Escucha SEPDigestiva</strong>
							</h3>
							<p>
								Una ventana al conocimiento en gastroenterología para profesionales sanitarios. Sumérgete en entrevistas con médicos de renombre y explora temas vanguardistas sobre patologías digestivas. Innovación, experiencia y ciencia en cada episodio, cortesía de SEPD
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-3">		
				<form name="formFiltros" id="formFiltros" method="POST">
					<div class="container">
						<div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-prensa">
							<div class="input-group w-100">
								@include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-prensa'])
							</div>
						</div>
					</div>
	
					<div class="container mb-4">
						@include('puzzle.filtros.podcast-feads_anyos')
					</div>
					<div class="container mb-4">
						@include('puzzle.filtros.podcast-feads_tipos')
					</div>
	
					<input type="hidden" name="filtrosGet" id="filtrosGet">
					<input type="hidden" name="paginaGet" id="paginaGet">
				</form>
	
				{{-- loop de las plantillas de la página, en este caso los valores de ancho y posición no se usan --}}
				@if ($pagina->contenido_extra > 0)
					@foreach ($pagina->pastillas_contenido as $pastilla)
						<div class="mt-3">
							@php $margen_inf = ''; @endphp
							@include('paginas.pastilla')
						</div>
					@endforeach
				@endif
			</div>
			<div class="col-sm-9">		
				<div class="container" id="contenidoVue">
					<podcast-feads-index ref="PodcastFeadsIndex" auth="{{ auth()->user() ? true : false }}">
					</podcast-feads-index>

				</div>
			</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js?v=212') }}"></script>
    <script src="{{ asset('js/podcast-feads.js?v=202502122') }}"></script>
@endsection
