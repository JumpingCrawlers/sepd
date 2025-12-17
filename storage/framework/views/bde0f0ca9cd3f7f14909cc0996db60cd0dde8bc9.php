

<?php $__currentLoopData = $redes_sociales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $red): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $elemento = '<a href="'.$red->enlace.'" target="_blank"><img src="'.Voyager::image($red->imagen).'" width="25" height="25" border="0"></a>';
        switch(strtoupper($red->nombre)) {
            case "TWITTER":
                $rrss_ordenado[0] = $elemento;
                break;
            case "YOUTUBE":
                $rrss_ordenado[1] = $elemento;
                break;
            case "LINKEDIN":
                $rrss_ordenado[2] = $elemento;
                break;
            case "FACEBOOK":
                $rrss_ordenado[3] = $elemento;
                break;
            case "INSTAGRAM":
                $rrss_ordenado[4] = $elemento;
                break;
            case "BLUESKY":
                $rrss_ordenado[5] = $elemento;
                break;    
        }
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php ksort($rrss_ordenado) ?>
<?php $__currentLoopData = $rrss_ordenado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $red): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $red; ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/redes_sociales.blade.php ENDPATH**/ ?>