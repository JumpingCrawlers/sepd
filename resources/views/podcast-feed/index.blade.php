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
        {{-- <div class="row">
			@foreach ($podcasts as $podcast)
				<div class="col-12 _col-md-6 _col-lg-4 | card-podcast">
					<div>
						<h4>{{ $podcast->title }}</h4	>
					</div>
					<div>
						<iframe src="{{ $podcast->enlace }}" frameborder="0" class="w-full w-100 bg-light rounded"></iframe>
					</div>
					<div class="px-2">
						{!! $podcast->description !!}
					</div>
				</div>
			@endforeach
        </div>
		<div class="row">
			<div class="col text-center">
				{{ $podcasts->links() }}
			</div>
		</div> --}}
        {{-- sección donde se muestran las noticias --}}
		<div class="row">
			<div class="col">
				<div class="px-5 mb-5 mt-5">
					<h3>
						<strong>Escucha SEPDigestiva</strong>
					</h3>
					<p>
						Una ventana al conocimiento en gastroenterología para profesionales sanitarios. Sumérgete en entrevistas con médicos de renombre y explora temas vanguardistas sobre patologías digestivas. Innovación, experiencia y ciencia en cada episodio, cortesía de SEPD
					</p>
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
	
					<div class="container">
						@include('puzzle.filtros.podcast-feads_anyos')
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
					{{-- iconos='{{ $iconos }}' --}}
					<podcast-feads-index ref="PodcastFeadsIndex" url-web-antigua='{{ setting('site.url_web_antigua') }}' >
					</podcast-feads-index>

				</div>
			</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    <script src="{{ asset('js/podcast-feads.js') }}"></script>
@endsection
