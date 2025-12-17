<?php

$cookieDatas = "";
$ch = curl_init('http://sepd-xavi.pen');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 1);
$result = curl_exec($ch);

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
foreach($matches[1] as $item) {
    preg_match_all('/(.*)[=](.*)/', $item, $cookieData);
    $cookieDatas .= $cookieData[1][0] . "=" . $cookieData[2][0] . "; ";
}

$cookieDatas = str_replace("%3D", "", substr($cookieDatas, 0, (strlen($cookieDatas) - 1)));
echo $cookieDatas;
echo "<br>Your userdata is: <br>" . file_get_contents("http://sepd-xavi.pen/api/check-auth");

?>