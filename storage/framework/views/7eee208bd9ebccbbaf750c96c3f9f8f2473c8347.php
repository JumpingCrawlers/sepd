

<?php if($pagina->pastillas->count() > 0): ?>

    
    
    
    <div class="container px-0 mb-3">
        
        <div class="row">
            <?php
                $columnas = 12 / $pagina->columnas_pastillas;
            ?>
            <?php $__currentLoopData = $pagina->pastillas_en_columnas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $columna_pastillas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php echo $__env->make('paginas.columna_pastillas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

<?php endif; ?>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/pastillas.blade.php ENDPATH**/ ?>