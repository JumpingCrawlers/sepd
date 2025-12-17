<?php

require_once "./inc/config.php";
require_once "./inc/apiRedsys.php";

$content = "";
foreach ($_POST as $key => $value) $content .= "[{$key}] {$value}\n";

$signature_version = $_POST["Ds_SignatureVersion"];
$params = $_POST["Ds_MerchantParameters"];
$received_signature = $_POST["Ds_Signature"];

$redsys = new RedsysAPI;
$redsys->decodeMerchantParameters($params);
$signature = $redsys->createMerchantSignatureNotif($config["key"], $params);	

$content .= "[Signature] {$signature}\n";
$content .= "[Received_Signature] {$received_signature}\n";
$content .= ("[Result] " . (($signature === $received_signature) ? "ACCEPTED PAYMENT" : "DECLINED PAYMENT") . "\n");

$log_file = "./logs/notifications.log";
$handle = fopen($log_file, "a") or die("Cannot open file: {$log_file}");
fwrite($handle, "[Redsys notification] {$content}\n");
fclose($handle);

return true;
