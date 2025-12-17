<div class="container px-0 mb-3">
    <div class="row">
        <?php if(isset($pagina) && isset($pagina->contenido_extra) && $pagina->contenido_extra): ?>
            <?php
                // controlar que las columnas es correcto (entre 1 y 6)
                ($pagina->columnas_extra < 1 || $pagina->columnas_extra > 6) ? $columnas_extra = 3 : $columnas_extra = $pagina->columnas_extra;
                ($pagina->posicion_extra == "derecha") ? $posicion = " order-last"  : $posicion = "";
            ?>
            <div id="contenido-extra" class="col-sm-<?php echo e($columnas_extra); ?><?php echo e($posicion); ?> <?php if(isset($pagina->contenido_extra_flotante) && $pagina->contenido_extra_flotante): ?> flotante <?php endif; ?>">
                <?php $__currentLoopData = $pagina->pastillas_contenido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pastilla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php $margen_inf = 'mb-3'; ?>
                    <?php echo $__env->make('paginas.pastilla', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        <?php else: ?>
            <?php
                $columnas_extra = 0;
            ?>
        <?php endif; ?>
        <div class="col-sm-<?php echo e(12 - $columnas_extra); ?> pl-4" id="contenido-detalle">
            <?php echo $__env->yieldContent('contenido-detalle'); ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/contenido.blade.php ENDPATH**/ ?>