

    <div class="col-md-<?php echo e($columnas); ?> d-flex flex-column align-content-stretch">
        
        
        <?php
            ($pagina->alguna_pastilla_doble) ? $clase_para_margen = " mb-auto" : $clase_para_margen = " mb-3";
            (count($columna_pastillas) > 1) ? $margen_inf = $clase_para_margen : $margen_inf = "";
        ?>
        <?php $__currentLoopData = $columna_pastillas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pastilla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($loop->last): ?>
                <?php
                    $margen_inf = " align-self-end";
                ?>
            <?php endif; ?>
            
            <?php echo $__env->make('paginas.pastilla', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div><?php /**PATH C:\laragon\www\sepd.es\resources\views/paginas/columna_pastillas.blade.php ENDPATH**/ ?>