
<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    
    <?php
        if (strpos($options->listaIdsActivos, '-'.$item->id.'-') !== false) {
            $caminoOpcionActiva = true;
        } else {
            $caminoOpcionActiva = false;
        }
    ?>

    
    
    <?php if($item->children->isEmpty()): ?>
        
        <a class="nav-link <?php if(url()->current()==url($item->link())): ?> menu-movil-activo <?php endif; ?>" 
           href="<?php echo e(url($item->link())); ?>" target="<?php echo e($item->target); ?>">
            <?php echo e($item->title); ?>

        </a>
    <?php else: ?>
        
        <a class="nav-link <?php if($caminoOpcionActiva): ?> menu-movil-activo <?php endif; ?>"
            href="#submenu<?php echo e($item->id); ?>" data-toggle="collapse" aria-expanded="false" aria-controls="submenu<?php echo e($item->id); ?>">
            <div class="d-inline-block">
                <?php echo e($item->title); ?>

                &nbsp;<span class="flecha <?php echo e($item->menu()->first()->name); ?> float-right mt-2"></span>
            </div>
        </a>
        <div class="collapse ml-4 border-left borde-<?php echo e($item->menu()->first()->name); ?>" id="submenu<?php echo e($item->id); ?>">
            <?php echo $__env->make('menusepd.bootstrap_sepd_movil', ['items' => $item->children, 'options' => $options], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    <?php endif; ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/menusepd/bootstrap_sepd_movil.blade.php ENDPATH**/ ?>