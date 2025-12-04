<?php

if (!isset($_GET["result"])):
    header("Location: ./");
    exit;
endif;

$result = strtolower($_GET["result"]);

$log_file = "./logs/messages.log";
$handle = fopen($log_file, "a") or die("Cannot open file: {$log_file}");
fwrite($handle, "Resultado del pago: {$result}\n");
fclose($handle);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultado de pago</title>
</head>
<body>
    <?php if ($result == "ok"): ?>
        <h1>El pago se ha realizado correctamente</h1>
    <?php else: ?>
        <h1>El pago no se ha podido realizar</h1>
    <?php endif; ?>
    
    <b>Resultado recibido:</b> <?= $result ?>
</body>
</html>
