<?php 
    include('view_loader.php');
    front_controller();

    include('templates/header.php');

    // Get messages depending of the $user_vpcr status
    // options: pending or deleted
    $msg = estado_vpcr();
?>
    <main>
        <div class="container">
            <div class="card mb-3 w-100">
                <div class="card-header text-white bg-primary">
                    <h4 class="m-0">Estado solicitud</h4>
                </div>
                <div class="card-body text-secondary">
                    <h5 class="m-0"><?=$msg?></h5>
                </div>
            </div>
        </div>
    </main>

    <?php include('templates/footer.php'); ?>
</body>
</html>