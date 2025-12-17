<input 
    type="text" 
    class="buscador <?php if(isset($fondo)): ?> <?php echo e($fondo); ?> <?php endif; ?> <?php if(isset($tamanyo)): ?> <?php echo e($tamanyo); ?> <?php endif; ?>" 
    name="search" 
    placeholder="Buscar...."
    value="<?php echo e(app('request')->input('search')); ?>"
>
<button type="submit" class="buscador"></button>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/buscador.blade.php ENDPATH**/ ?>