<?php
include_once("api-keys.php");

$exchanges = array();

$url = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=";
foreach (API_KEYS as $currency => $apiKey) {
    $url = $url.$currency.",";
}

$url = $url."&tsyms=AUD,USD";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = json_decode(curl_exec($curl))->RAW;
curl_close($curl);

foreach ($curl_response as $currency => $data) {
    $exchanges[$currency] = $data;
}

header("Content-Type: application/json");
echo(json_encode($exchanges));
?>