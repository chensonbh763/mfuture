<?php
// proxy.php — Intermediário entre seu front-end e o Google Sheets
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

// URL do seu Apps Script publicado (troque pelo seu ID)
$googleScriptUrl = "https://script.google.com/macros/s/AKfycbwkP0X6LZTlu_CS459Z3m9SU8SO239kG9kZnUAmbc9uz9Udbn7OV8jeD40WH6XUo-ux/exec";

function enviarRequisicao($url, $metodo = 'GET', $dados = null) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  if ($metodo === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
  }

  $resposta = curl_exec($ch);
  curl_close($ch);
  return $resposta;
}

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $query = $_SERVER['QUERY_STRING'];
    $url = $googleScriptUrl . ($query ? "?$query" : "");
    echo enviarRequisicao($url);
    break;

  case 'POST':
    $input = file_get_contents("php://input");
    echo enviarRequisicao($googleScriptUrl, 'POST', $input);
    break;

  default:
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
