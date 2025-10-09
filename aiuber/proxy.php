<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$payload = file_get_contents("php://input");

$ch = curl_init("https://script.google.com/macros/s/AKfycbwkP0X6LZTlu_CS459Z3m9SU8SO239kG9kZnUAmbc9uz9Udbn7OV8jeD40WH6XUo-ux/exec");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
