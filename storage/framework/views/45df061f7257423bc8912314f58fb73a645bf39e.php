<?php $__env->startSection('estilos'); ?>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' />
    <link href="<?php echo e(asset('css/calendario.css')); ?>" rel="stylesheet">
    
<?php $__env->stopSection(); ?>

<?php echo $calendar->calendar(); ?>


<?php $__env->startSection('scripts'); ?>

<?php echo $calendar->script(); ?>


<?php $__env->appendSection(); ?><?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/calendario.blade.php ENDPATH**/ ?>