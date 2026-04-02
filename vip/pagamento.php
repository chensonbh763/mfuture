<?php
// Defina sua chave de API corretamente
$chave = "sk_nI4bptfQQx0qXk0rf6rQ5XTrojMbW2QtP8R5X-GvD0TVY4_-";

function criarPix($nome, $email, $cpf, $valor, $chave) {
    $valor = intval($valor) * 100;

    $data = [
        "amount" => $valor,
        "paymentMethod" => "pix",
        "pix" => ["expiresInDays" => 1],
        "customer" => [
            "name" => $nome,
            "email" => $email,
            "document" => $cpf
        ],
        "items" => [[
            "title" => "Pagamento",
            "unitPrice" => $valor,
            "quantity" => 1
        ]]
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.quantumpayments.com.br/v1/transactions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $chave",
            "Content-Type: application/json"
        ],
    ]);

    $res = curl_exec($curl);
    curl_close($curl);

    return json_decode($res, true);
}

function buscarPix($id, $chave) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.quantumpayments.com.br/v1/transactions/$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $chave"
        ],
    ]);

    $res = curl_exec($curl);
    curl_close($curl);

    return json_decode($res, true);
}

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['gerar'])) {
        $resultado = criarPix(
            $_POST['nome'],
            $_POST['email'],
            $_POST['cpf'],
            $_POST['valor'],
            $chave
        );
    }

    if (isset($_POST['consultar'])) {
        $resultado = buscarPix($_POST['txid'], $chave);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerador PIX</title>
<style>
body {
    font-family: Arial;
    background: #0f172a;
    color: white;
    text-align: center;
    padding: 30px;
}
.container {
    max-width: 400px;
    margin: auto;
    background: #1e293b;
    padding: 20px;
    border-radius: 12px;
}
input, button {
    width: 100%;
    padding: 12px;
    margin: 5px 0;
    border-radius: 8px;
    border: none;
}
button {
    background: #22c55e;
    color: white;
    font-weight: bold;
    cursor: pointer;
}
.qr {
    margin-top: 20px;
}
.copy {
    background: #334155;
    padding: 10px;
    word-break: break-all;
    border-radius: 8px;
    margin-top: 10px;
}
</style>
</head>
<body>
<div class="container">
    <h2>Gerar PIX</h2>
    <form method="POST">
        <input name="nome" placeholder="Nome" required>
        <input name="email" placeholder="Email" required>
        <input name="cpf" placeholder="CPF" required>
        <input name="valor" placeholder="Valor (ex: 27)" required>
        <button type="submit" name="gerar">Gerar PIX</button>
    </form>

    <hr>

    <h3>Consultar Status</h3>
    <form method="POST">
        <input name="txid" placeholder="ID da Transação">
        <button type="submit" name="consultar">Ver Status</button>
    </form>

    <?php if ($resultado): ?>
    <div class="qr">
        <p><b>Status:</b> <?= htmlspecialchars($resultado['status'] ?? 'N/A') ?></p>
        <p><b>ID:</b> <?= htmlspecialchars($resultado['id'] ?? 'N/A') ?></p>

        <?php if (isset($resultado['pix'])): ?>
            <img src="data:image/png;base64,<?= htmlspecialchars($resultado['pix']['qrcode']) ?>">
            <div class="copy" id="pixCode">
                <?= htmlspecialchars($resultado['pix']['emv']) ?>
            </div>
            <button onclick="copiar()">Copiar Código</button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function copiar() {
    let text = document.getElementById("pixCode").innerText;
    navigator.clipboard.writeText(text);
    alert("Copiado!");
}
</script>
</body>
</html>
