<?php

require_once "./inc/config.php";
require_once "./inc/apiRedsys.php";

/**
 * TESTING CARD DETAILS
 * 
 * 4548812049400004
 * 12/20
 * 
 * CVV: 123
 * CIP: 123456
 */

$redsys = new RedsysAPI;
$userId = 8687;
$orderId = rand(1000, 9999) . strtoupper(substr(md5(time() . /* str_random(8) . */ $userId), 0, 8));
$amount = 1; // 1 céntimo

$redsys->setParameter("DS_MERCHANT_AMOUNT", $amount);
$redsys->setParameter("DS_MERCHANT_ORDER", $orderId);
$redsys->setParameter("DS_MERCHANT_MERCHANTCODE", $config["merchant_code"]);
$redsys->setParameter("DS_MERCHANT_CURRENCY", $config["currency"]);
$redsys->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $config["transaction_type"]);
$redsys->setParameter("DS_MERCHANT_TERMINAL", $config["terminal"]);
$redsys->setParameter("DS_MERCHANT_MERCHANTURL", $config["notification_url"]);
$redsys->setParameter("DS_MERCHANT_URLOK", $config["url_ok"]);
$redsys->setParameter("DS_MERCHANT_URLKO", $config["url_ko"]);
$redsys->setParameter("DS_MERCHANT_MERCHANTNAME", $config["tradename"]);
$redsys->setParameter("DS_MERCHANT_CONSUMERLANGUAGE", $config["language"]);
$redsys->setParameter("DS_MERCHANT_PAYMETHODS", $config["method"]);

$params = $redsys->createMerchantParameters();
$signature = $redsys->createMerchantSignature($config["key"]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pasarela de pago</title>
</head>
<body>
    <form action="<?= $config["form_action"] ?>" method="POST">
        <input type="hidden" name="Ds_SignatureVersion" value="<?= $config["version"] ?>" />
        <input type="hidden" name="Ds_MerchantParameters" value="<?= $params ?>" />
        <input type="hidden" name="Ds_Signature" value="<?= $signature ?>" />
        <input type="submit" value="Realizar pago" />
    </form>
</body>
</html>
