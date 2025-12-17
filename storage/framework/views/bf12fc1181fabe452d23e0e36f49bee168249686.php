
<?php if($pagina->tipo_slider <> '0'): ?>

<div class="container px-0 mb-3">
    <div class="row">

    
    <?php switch($pagina->tipo_slider):
        case ('2s+1i'): ?>
        <?php case ('2sA+1iA'): ?>
            <div class="col-sm-8 col-slider-con-imagen">
                <?php
                    // variable para la clase CSS del carousel
                    ($pagina->tipo_slider == '2s+1i') ? $formato_carousel = '-2x1' : $formato_carousel = '-2x1A';
                ?>
                <?php echo $__env->make('paginas.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-sm-4 col-imagen-en-slider">
                <?php echo $__env->make('paginas.imagen-slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php break; ?>
        <?php case ('3s+1i'): ?>
            <div class="col-sm-9 col-slider-con-imagen">
                <?php
                    // variable para la clase CSS del carousel
                    $formato_carousel = '-3x1';
                ?>
                <?php echo $__env->make('paginas.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-sm-3 col-imagen-en-slider">
                <?php echo $__env->make('paginas.imagen-slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php break; ?>
        <?php case ('3sA'): ?>
        <?php case ('3s'): ?>
            <div class="col">
                <?php
                    // variable para la clase CSS del carousel
                    ($pagina->tipo_slider == '3sA') ? $formato_carousel = '-A' : $formato_carousel = '';
                ?>
                <?php echo $__env->make('paginas.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php break; ?>
        <?php case ('3i'): ?>
            <div class="col" style="overflow:hidden;">
                <?php echo $__env->make('paginas.imagen-slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php break; ?>

    <?php endswitch; ?>

    </div>
</div>

<?php endif; ?>

<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/slider.blade.php ENDPATH**/ ?>