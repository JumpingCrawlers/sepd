
<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    
    <?php
        if (strpos($options->listaIdsActivos, '-'.$item->id.'-') !== false) {
            $caminoOpcionActiva = true;
        } else {
            $caminoOpcionActiva = false;
        }
    ?>

    <?php if(!isset($innerLoop)): ?>
        
        <?php if($item->children->isEmpty()): ?>
            
            <li class="nav-link <?php if(url()->current()==url($item->link())): ?> activo bg-<?php echo e($item->menu()->first()->name); ?> <?php endif; ?>">
        <?php else: ?>
            
            <li class="nav-link position-relative <?php if($caminoOpcionActiva): ?> activo bg-<?php echo e($item->menu()->first()->name); ?> <?php endif; ?>">
        <?php endif; ?>
    <?php else: ?>
        <?php if($item->children->isEmpty()): ?>
            <li class="dropdown-item <?php if($caminoOpcionActiva): ?> activo <?php endif; ?>">
        <?php else: ?>
            <li class="dropdown-submenu <?php if($caminoOpcionActiva): ?> activo <?php endif; ?>">
        <?php endif; ?>
    <?php endif; ?>


    <?php
    
        $listItemClass = null;
        $linkAttributes =  null;
        $styles = null;
        $icon = null;
        $caret = null;

        // Background Color or Color
        if (isset($options->color) && $options->color == true) {
            $styles = 'color:'.$item->color;
        }
        if (isset($options->background) && $options->background == true) {
            $styles = 'background-color:'.$item->color;
        }

        // With Children Attributes
        if(!$item->children->isEmpty()) {
//            $linkAttributes =  'class="dropdown-toggle" data-toggle="dropdown"';
            $linkAttributes =  'dropdown-toggle" data-toggle="dropdown';
            $caret = '<span class="caret"></span>';

            if(url($item->link()) == url()->current()){
                $listItemClass = 'dropdown active';
            }else{
                $listItemClass = 'dropdown';
            }
        }

        // Set Icon
        if(isset($options->icon) && $options->icon == true){
            $icon = '<i class="' . $item->icon_class . '"></i>';
        }
        
    ?>

        <a class="<?php if(!isset($innerLoop)): ?> nav-link <?php else: ?> dropdown-item <?php endif; ?> <?php echo $linkAttributes ?? ''; ?>" href="<?php echo e(url($item->link())); ?>" target="<?php echo e($item->target); ?>" style="<?php echo e($styles); ?>">
            <?php echo $icon; ?>

            <?php echo e($item->title); ?>

            <?php echo $caret; ?>

        </a>

        <?php if(!$item->children->isEmpty()): ?>
            <ul class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
            <?php echo $__env->make('menusepd.bootstrap_sepd', ['items' => $item->children, 'options' => $options, 'innerLoop' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </ul>
        <?php endif; ?>

    </li>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/menusepd/bootstrap_sepd.blade.php ENDPATH**/ ?>