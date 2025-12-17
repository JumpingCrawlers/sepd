<!DOCTYPE html>
<style>
	body{font-family: "Arial", Georgia, Serif;}


	/* Classes */
	.thumbActive{opacity: 1!important;

	-webkit-box-shadow: 0px 0px 5px 0px rgba(50, 50, 50, 0.75);
	-moz-box-shadow:    0px 0px 5px 0px rgba(50, 50, 50, 0.75);
	box-shadow:         0px 0px 5px 0px rgba(50, 50, 50, 0.75);
	}


	/* Main */
	#divMainContainer{width:800px;border: 1px solid #ccc;margin: auto;position: relative;}
	#divMainContainer #divMainVideo{width:100%;margin: auto;}
	#divMainContainer #divMainVideo video{width:100%;}

	/*
	#divMainContainer #divMainVideo #divLogoSEPD{position:absolute;bottom:250px;right:20px;width: 200px;}
	#divMainContainer #divMainVideo #divLogoSEPD img{width:100%;}
	*/
	h1#divTitle{color:#7c00bf;font-size: 20px;width:800px;display: block;margin: 20px auto 5px auto;}


	#divMainSlides{width: 100%;}
	#divMainSlides #divTotalSlidesContainer{height: 20px;padding: 5px 0px 13px 0px;background-color:#e0c5e5;text-align: center;border-bottom: 2px solid #9648a5;border-top: 2px solid #fae2ff;}
	#divMainSlides #divTotalSlides{background-color: #a14cb2;color:white;padding: 5px 10px;display: inline-block;text-align: center;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}
	#divMainSlides #divTotalSlides span{display: inline-block;margin: 0px auto;text-align: center;}
	#divMainSlides #divSlidesContainer{height:150px;padding: 10px;    white-space:nowrap;overflow-x:auto;overflow-y:hidden;
	-webkit-box-shadow: inset 0px 0px 5px 2px rgba(50, 50, 50, 0.75);
	-moz-box-shadow:    inset 0px 0px 5px 2px rgba(50, 50, 50, 0.75);
	box-shadow:         inset 0px 0px 5px 2px rgba(50, 50, 50, 0.75);
	}
		#divMainSlides #divSlidesContainer > div{border:1px solid #777777;cursor:pointer;overflow:hidden;width: 150px;height: 110px;margin:10px;display:inline-block;vertical-align:top;position: relative;background-color: black;
			opacity: 0.5;
			-webkit-transition: opacity 0.2s ease-in-out;
			-moz-transition: opacity 0.2s ease-in-out;
			-ms-transition: opacity 0.2s ease-in-out;
			-o-transition: opacity 0.2s ease-in-out;
			transition: opacity 0.2s ease-in-out;
		}
		#divMainSlides #divSlidesContainer > div:hover{
			outline: 1px solid #777777;
			-webkit-transition: outline 0.2s ease-in-out;
			-moz-transition: outline 0.2s ease-in-out;
			-ms-transition: outline 0.2s ease-in-out;
			-o-transition: outline 0.2s ease-in-out;
			transition: outline 0.2s ease-in-out;
		}

		#divMainSlides #divSlidesContainer > div img{width: 100%;position: absolute;top:27px;bottom:0;left:-3px;margin: auto;width: 172%;}

	@-moz-document url-prefix() {
		#divMainSlides #divSlidesContainer > div img{top:0px;}
	}


	/* Div Metadata */
	#divMetadata{position: absolute;width:300px;height:200px;top:180px;right:9px;background-color: white;}
	#divMetadata > img{max-width:300px;display: block;margin:auto;}
	@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
		#divMetadata > img{max-width: 265px!important;}
	}

	/* Div Exception */
	#divExceptionBack{position:absolute;z-index:999;min-height:100%;width:100%;background-color:rgba(255, 255, 255, 0.5);}

	#divException{z-index:1000;background-color:#955bb2;color:white;padding: 20px;border:2px solid white;position: absolute;top:0;bottom:0;left:0;right:0;margin:auto;display: inline-block;width: 300px;height:50px;text-align: center;vertical-align: middle;}
	#divExceptionHTML5{z-index:1000;background-color:#955bb2;color:white;padding: 20px;border:2px solid white;position: absolute;top:0;bottom:0;left:0;right:0;margin:auto;display: inline-block;width: 300px;height:50px;text-align: center;vertical-align: middle;}
	#divExceptionHTML5 p{margin: 0px;}

	/* Scrollbars */
	::-webkit-scrollbar {width: 12px;background: rgba(234,234,234,1);}
	::-webkit-scrollbar-track {}
	::-webkit-scrollbar-thumb {-webkit-border-radius: 10px;border-radius: 10px;background: rgba(145,73,153,0.8);-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);}
	::-webkit-scrollbar-thumb:window-inactive {background: rgba(145,73,153,0.4);}

	.vjs-fullscreen-control{ display:none; }
</style>
<?php

define ('ABSPATH', ($migrado ? 'storage/cursos/migrados/contenidos/' : 'storage/cursos-bloques-items/diapositivas/'));
// dd(ABSPATH);
define ('videoFormat',"mp4");
define ('videoExtension',".mp4");


	// Configuration
$mainVideosFolder = "";

	// Get Video Id
try{
	$error= 0;
	$videoName = $enlace;

	if(empty($videoName)){
		throw new Exception("Video no encontrado.", 1);
	}else{

		if(!file_exists(ABSPATH.$mainVideosFolder.$videoName)){
			throw new Exception("Carpeta de video no encontrada.", 1);
		}

		if(!file_exists(ABSPATH.$mainVideosFolder.$videoName."/def.json")){
			throw new Exception("Archivo de configuración no encontrado.", 1);
		}


		// Imagen
		if(file_exists(ABSPATH.$mainVideosFolder.$videoName."/title.jpg")){			
			$imagen = "<img src='/".ABSPATH.$videoName."/title.jpg"."' alt='Datos ponente'/>";
		}else{
			$imagen = "";
		}
	}
} catch (Exception $e) {
	$error = 1;
	echo '<div id="divExceptionBack"></div><div id="divException"><p>'.$e->getMessage()."</p></div>";
}


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Reproductor Diapositivas SEPD</title>

	<!-- JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>


	<!-- Main JS -->
	<script type="text/javascript">

		<?php include(($migrado ? "storage/cursos/migrados/contenidos/" : "storage/cursos-bloques-items/diapositivas/").$mainVideosFolder.$videoName."/def.json"); ?>
		
		var videoName = "<?= !empty($videoName) ? $videoName : '' ?>";
		var mainVideosFolder = "<?= ($migrado ? '/storage/cursos/migrados/contenidos/' : '/storage/cursos-bloques-items/diapositivas/') ?><?= $mainVideosFolder ?>";
		var thumbnailsDir = videoName+"<?= ($migrado ? '/thumbnails/' : '/') ?>";
		var selectedSlide = 0;
		var videoPlaying = 0;
		var init = "";

		var json = manifest;

		var times = new Array();

		$(document).ready(function(){

			$("body").on("contextmenu",function(){
		       return false;
		    }); 

			if (Modernizr.video) {

				// Get Video
				//var video = $("video#divVideo");
				var video = document.getElementById("divVideo");
				video.load();
				video.play();
				//Load thumbs
				renderThumbs(video);

				//Load Video Info
				renderVideoInfo(video);

				video.addEventListener('timeupdate',function() {

					var currentSecond = parseInt(this.currentTime);


					if(currentSecond < init){
				        video.currentTime = init;
						video.play();
					}else{

		 				for(var i in times){

							if((currentSecond >= times[i] && currentSecond < times[parseInt(i)+1]) && selectedSlide != times[i]){
								activeSlide(times[i]);
							}else if(currentSecond < times[0] && selectedSlide != times[0]){
								activeSlide(times[0]);
							}

						}

					}

				},false);

				video.onplay = function() {
				    videoPlaying = 1;
				};


				//Load init
				loadInit(video);

			} else {
				$('body').html('<div id="divExceptionBack"></div><div id="divExceptionHTML5"><p>Su navegador no tiene soporte HTML5 para la reproducción de video. Por favor, actualice su navegador.</p></div>');
			}
			
			//$(document).bind('contextmenu',function() { return false; });

		});

		function loadInit(video){
			if(json.package.metadata['dc:type'] != undefined && json.package.metadata['dc:type'] != ""){
				init = json.package.metadata['dc:type'];
			}

			if(init != ""){
				if(msieversion()){
					video.oncanplay = function() {
		        		video.currentTime = init;
						video.play();
					};
				}else{
	        		video.currentTime = init;
					video.play();
				}
			}
			
		}

		function playVideo(){
			var video = document.getElementById("divVideo");
			video.play();
		}


		function msieversion() {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0)      // If Internet Explorer, return version number
	            return true;
            else                 // If another browser, return 0
	            return false;
        }

		function activeSlide(slideSecond){

			$('#divSlidesContainer .slide').removeClass("thumbActive");
        	$('#divSlidesContainer .slide[currentSecond="'+slideSecond+'"]').addClass("thumbActive");

        	leftPosition = $('#divSlidesContainer .slide[currentSecond="'+slideSecond+'"]').attr("leftPosition");
        	
        	selectedSlide = slideSecond;

        	$('#divMainSlides #divSlidesContainer').animate({scrollLeft: leftPosition*(150+20) }, 400);

		    $('#divTotalSlides #currentSlide').html(parseInt(leftPosition)+1);
		}
	
		function renderThumbs(video){

			var thumbnails = json.package.streams[0].chapters;
			var totalSlides = thumbnails.length;
			
			for(var i in thumbnails){

				var thumb = thumbnails[i];
				var filename = thumb.name;

				// Get second of thumbs
				times[i] = parseInt(thumb.timestamp/1000);

				// ThumbActive
				var thumbActive = "";
				if(i == 0)
					thumbActive = "thumbActive";


				var title = "";

				if(parseInt(thumb.timestamp/1000) < 60){
					var title = "Segundo: "+parseInt(thumb.timestamp/1000); 
				}else{
					var title = "Minuto: "+Math.floor(parseInt(thumb.timestamp/1000) / 60); 
				}

				$("#divSlidesContainer").append($('<div leftPosition="'+i+'" title="'+title+'" currentSecond="'+parseInt(thumb.timestamp/1000)+'" class="slide '+thumbActive+'" videoMin="'+thumb.timestamp+'"><img src="' + mainVideosFolder+thumbnailsDir + filename + '"></img></div>'));

		        // Set Total slides
		        $('#divTotalSlides #currentSlide').html(totalSlides);
		        $('#divTotalSlides #totalSlides').html(totalSlides);

		    }

		    // Slide click
	        $('#divSlidesContainer .slide').click(function(){

	        	$('#divSlidesContainer .slide').removeClass("thumbActive");
	        	$(this).addClass("thumbActive");

	        	var point = $(this).attr('videoMin');
	        	video.currentTime = point/1000;
				video.play();

	        });
		}

		function renderVideoInfo(video){

			var monthNames = ["Ene", "Feb", "Mar","Abr", "May", "Jun", "Jul","Ago", "Sep", "Oct","Nov", "Dic"];

			var metadata = json.package.metadata;

			$('h1#divTitle').html(metadata['dc:title']);

		}

	</script>



</head>
<body>
	<?php if($error != 1): ?>

		<h1 id="divTitle"></h1>

		<!-- Video -->
		<div id="divMainContainer">

			<div id="divMainVideo">
				<video id="divVideo" preload=”auto” height="450" controls>
					<source src="<?= ($migrado ? '/storage/cursos/migrados/contenidos' : '/storage/cursos-bloques-items/diapositivas') ?>/<?= $mainVideosFolder.$videoName ?>/video.mp4" type="video/mp4">

					HTML5 vídeo no es soportado por este navegador
				</video>

			</div>

				<!-- Div Imagen -->
				<div id="divMetadata">
					<?php echo $imagen; ?>
				</div>


				<!-- Slides -->
				<div id="divMainSlides">

					<div id="divTotalSlidesContainer">
						<div id="divTotalSlides">
							<span id="currentSlide"></span> / <span id="totalSlides"></span>
						</div>
					</div>

					<div id="divSlidesContainer">
					</div>

				</div>


			</div>

		<?php endif; ?>

	</body>
	</html>