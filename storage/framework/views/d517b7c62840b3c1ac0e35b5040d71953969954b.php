<?php
    $nombre_menu = $nombre_menu ?? '';
    
?>
<div class="container px-0">
    <nav class="navbar navbar-expand-lg navbar-light p-0 <?php echo e($nombre_menu); ?>">
        
        <a class="navbar-toggler border-0 submenu-text pt-2" data-toggle="collapse" data-target="#menuSecundarioMovil" aria-controls="menuSecundarioMovil" aria-expanded="false" aria-label="Toggle navigation">
            <?php
            if (isset($pagina)) {
                $menu_display_name = $pagina->menu->role->display_name;
                $menu_name = $pagina->menu->name;
            } else {
                $menu_display_name = ucfirst($nombre_menu);
                $menu_name = $nombre_menu;
            }
            ?>
            <?php echo e($menu_display_name); ?> &nbsp; <span class="flecha <?php echo e(strtolower($menu_name)); ?> float-right mt-1"></span>
        </a>

        
        <?php
            if (isset($pagina)) {
                $lista_ids_activos = $pagina->lista_ids_activos;
            } else {
                $lista_ids_activos = '';
            }
        ?>
        
        <div class="collapse navbar-collapse" id="menuSecundario">
            <ul class="navbar-nav mr-auto" role="menu">

                <?php if(auth()->guard()->guest()): ?>
                    <?php echo menu($nombre_menu, 'menusepd.bootstrap_sepd', ['listaIdsActivos' => $lista_ids_activos]); ?>

                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(isset($pagina) && (isset($formacion) && $formacion || !is_null($pagina) && $pagina->slug == "cursos")): ?>
                        <li class="nav-link <?php echo e((($pagina->slug == "aula") ? 'activo bg-formacion' : '' )); ?>">
                            <a class="nav-link" href="<?php echo e((($pagina->slug == "aula") ? '#!' : route('formacion.aula'))); ?>" target="_self">Aula virtual</a>
                        </li>
                        <li class="nav-link <?php echo e((($pagina->slug == "mis_cursos") ? 'activo bg-formacion' : '' )); ?>">
                            <a class="nav-link" href="<?php echo e(route('formacion.mis-cursos')); ?>" target="_self">Mis cursos</a>
                        </li>
                        <li class="nav-link <?php echo e((($pagina->slug == "formacion") ? 'activo bg-formacion' : '' )); ?>">
                            <a class="nav-link" href="<?php echo e((($pagina->slug == "formacion") ? '#!' : route('mensajes.usuario'))); ?>" target="_self">Mensajes con tutores</a>
                        </li>
                        <li class="nav-link <?php echo e((($pagina->slug == "cursos") ? 'activo bg-formacion' : '' )); ?>">
                            <a class="nav-link" href="<?php echo e((($pagina->slug == "cursos") ? '#!' : route('cursos'))); ?>" target="_self">Cursos disponibles</a>
                        </li>
                        <li class="nav-link <?php echo e((($pagina->slug == "historial_acreditaciones") ? 'activo bg-formacion' : '' )); ?>">
                            <a class="nav-link" href="<?php echo e((($pagina->slug == "historial_acreditaciones") ? '#!' : route('formacion.acreditaciones'))); ?>" target="_self">Historial de acreditaciones</a>
                        </li>
                        <li class="nav-link <?php echo e((($pagina->slug == "calculadoras") ? 'activo bg-formacion' : '' )); ?>">
                            <a class="nav-link" href="<?php echo e((($pagina->slug == "calculadoras") ? '#!' : route('formacion.calculadora'))); ?>" target="_self">Calculadoras</a>
                        </li>
                    <?php else: ?>
                        <?php echo menu($nombre_menu, 'menusepd.bootstrap_sepd', ['listaIdsActivos' => $lista_ids_activos]); ?>

                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="menuSecundarioMovil">
            <?php echo menu($nombre_menu, 'menusepd.bootstrap_sepd_movil', ['listaIdsActivos' => $lista_ids_activos]); ?>

        </div>
    </nav>
    <style>
        /* .navbar.comunicacion .navbar-nav.mr-auto { */
        .navbar.comunicacion ul.navbar-nav[role="menu"] {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            width: 100%;
            line-height: 1;
            font-size: .8rem;
            text-align: center;
        }

        .navbar.comunicacion ul.navbar-nav[role="menu"] li:nth-child(-n+6) {
            border-bottom: 1px solid #4e25cc;
            padding-bottom: .2rem;
            padding-top: .2rem;
        }
        .navbar.comunicacion ul.navbar-nav[role="menu"] li.nav-link {
            padding: .3rem .2rem !important;
        }

    </style>
</div>
<?php /**PATH C:\laragon\www\sepd.es\resources\views/puzzle/menu_seccion.blade.php ENDPATH**/ ?>