

<?php if($pagina->destacados->count() > 0): ?>

    <div class="container bloque-destacados mb-3">
        <div class="row text-center justify-content-center">
            <?php $__currentLoopData = $pagina->destacados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destacado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            
            <?php
                $destino = '';
                if ($destacado->enlace) {
                    if ($destacado->destino_enlace == 'Nuevo') {
                        $destino = ' target="_blank"';
                    }
                } else {
                    // recuperarlo del menú item
                    $destino = ' target='.$destacado->menuitem->target;
                }
            ?>

            <div class="col-sm border borde-medio <?php if($loop->last): ?> mr-auto <?php else: ?> mr-4 <?php endif; ?>  borde-<?php echo e($pagina->menu->name); ?>">
                <a class="nav-link" href="<?php echo e($destacado->enlace ?: $destacado->menuitem->url); ?>"<?php echo e($destino); ?>>
                    
                    <?php if($destacado->partesgrafica): ?>
                    <img class='icono-destacado' src="<?php echo e(Voyager::image($destacado->partesgrafica->imagen)); ?>">
                    <?php endif; ?>
                    <p style="display:inline"><?php echo e($destacado->texto ?: $destacado->menuitem->title); ?></p>
                </a>
            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

<?php endif; ?>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/destacados.blade.php ENDPATH**/ ?>