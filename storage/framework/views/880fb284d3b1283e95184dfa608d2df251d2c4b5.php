<div class="container px-0">
    <div class="row my-2 px-3">
        <div class="col-sm-8 text-left">
            <?php if(isset($miga_pan)): ?>

                <?php if($miga_pan != '-'): ?>
                
                <?php echo e($miga_pan); ?>

                
                <?php endif; ?>

            <?php elseif(isset($pagina)): ?>

                <?php echo e($pagina->miga_pan); ?>


            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/breadcrumb.blade.php ENDPATH**/ ?>