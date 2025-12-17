<div class="modal fade show" id="modalNavidad" tabindex="-1" role="dialog" aria-labelledby="modalNavidadTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-700" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                {{--3ways Euro Fuenmayor - agregado año nuevo dinamico e imagen con nombre generico independiente del año en curso --}}
                <!-- <img class="img-fluid d-block" src="/storage/navidad.gif" border="0" alt="Feliz Navidad y un {{(now()->year +1)}} Saludigestivo"> -->
				<img class="img-fluid d-block" src="/storage/POP-UP-SEPD-estrena.gif" border="0" alt="Feliz Navidad y un {{(now()->year +1)}} Saludigestivo">
                <button type="button" class="btn mt-2" {{ getHtmlEstiloBoton() }} data-dismiss="modal" aria-label="Close">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

