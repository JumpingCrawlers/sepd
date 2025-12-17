


<?php $__env->startSection('estilos'); ?>
    <style>
        #page-quiero-ser-socio {
            color:#ffffff;
            background-color:#db812e;
            position: absolute;
            right: 1.5rem;
            top: 5px;
        }

        table {
            max-width: 100%;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('slider'); ?>

    <?php echo $__env->make('puzzle.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('pastillas'); ?>

    <?php echo $__env->make('puzzle.pastillas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('destacados'); ?>

    <?php echo $__env->make('puzzle.destacados', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido-detalle'); ?>
    <?php if($pagina->slug == 'ventajas_socios'): ?>
        <?php if(url()->current() != route('hazte_socio') && (!Auth::user() || !Auth::user()->socio)): ?>
            <a href="<?php echo e(route('hazte_socio')); ?>"  id="page-quiero-ser-socio" class="btn">
                Quiero ser socio
            </a>
        <?php endif; ?>
    <?php endif; ?>
    <?php echo getHtmlContenido($pagina->contenido, $pagina->menu->name, $pagina_codificada); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>

    <?php echo $__env->make('puzzle.contenido', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('puzzle.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\sepd.es\resources\views/paginas/show.blade.php ENDPATH**/ ?>