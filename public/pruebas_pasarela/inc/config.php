<?php

$config = [
    "environment" => "live", // test | live
    "version" => "HMAC_SHA256_V1",
    "tradename" => "FUNDACIÓN ESPAÑOLA DEL APARATO DIGESTIVO",
    "language" => "001",
    "method" => "T",
    "terminal" => 1,
    "currency" => 978,
    "transaction_type" => 0,
    "notification_url" => "https://{$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}notification.php",
    "url_ok" => "https://{$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}message.php?result=OK",
    "url_ko" => "https://{$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}message.php?result=KO",

    "merchant_code" => "",
    "key" => "",
    "form_action" => "",
];

if ($config["environment"] == "test"):
    $config["merchant_code"] = "999008881";
    $config["key"] = "sq7HjrUOBfKmC576ILgskD5srU870gJ7";
    $config["form_action"] = "https://sis-t.redsys.es:25443/sis/realizarPago";
else:
    $config["merchant_code"] = "078204781";
    $config["key"] = "9n2nLjDNVB5B0GaEUbiAHFG4LiztYtlE";
    $config["form_action"] = "https://sis.redsys.es/sis/realizarPago";
endif;
