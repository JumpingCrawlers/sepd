@extends('puzzle.master')

{{-- Todas las páginas pueden tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

{{-- Del mismo modo, todas las paginas pueden tener pastillas, depende de 
     si tienen pastillas asignadas (se muestran en 2 ó 3 columnas.    --}}

@section('pastillas')

    @include('puzzle.pastillas')

@endsection

{{-- También con los destacados --}}

@section('destacados')

    @include('puzzle.destacados')

@endsection

@section('contenido-detalle')

    {!! getHtmlContenido($pagina->contenido, $pagina->menu->name, $pagina_codificada) !!}

@endsection

@section('contenido')

    @include('puzzle.contenido')
	
	<div class="modal" id="modalChatBot" tabindex="-1" aria-labelledby="modalChatBotTitulo" aria-modal="true" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header bg-institucional">
					<h5 class="modal-title text-white" id="modalChatBotTitulo">AVISO INTERACCION PLATAFORMA IA</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="container">
						DESDE LA SEPD/FEAD LE INFORMAMOS A TODOS LOS INTERESADOS QUE EL ACCESO A LA PLATAFORMA IA (INTELIGENCIA ARTIFICIAL) QUE APORTA CONOCIMIENTO SOBRE CÁNCER COLORRECTAL PARA SU USO POR FACULTATIVOS Y PERSONAL SANITARIO, IMPLICA LA GRABACIÓN DE LAS INTERACCIONES  MANTENIDAS CON DICHA PLATAFORMA. 
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	@parent
	<script>
		$(window).on('load',function(){        
			$('#modalChatBot').modal('show');
		});
	</script>
@endsection