<?php
// Exemplo simples para criar uma preferência Mercado Pago usando cURL
// Ajuste o access_token com o seu valor (sandbox ou produção).

$access_token = 'COLOQUE_SEU_ACCESS_TOKEN_AQUI';

// Leitura de parâmetros da querystring (sempre limpar/validar em produção)
$title = isset($_GET['title']) ? $_GET['title'] : 'Produto';
$price = isset($_GET['price']) ? floatval($_GET['price']) : 0;

if ($price <= 0) {
    die('Preço inválido');
}

$body = [
    "items" => [
        [
            "title" => $title,
            "quantity" => 1,
            "unit_price" => $price
        ]
    ],
    "back_urls" => [
        "success" => "https://ktivar.page.gd/success.html",
        "failure" => "https://ktivar.page.gd/failure.html",
        "pending" => "https://ktivar.page.gd/pending.html"
    ],
    "auto_return" => "approved"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/checkout/preferences");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo "cURL Error: $err";
    exit;
}

$data = json_decode($res, true);
if (isset($data['init_point'])) {
    header('Location: ' . $data['init_point']);
    exit;
} else {
    echo "Falha ao criar preferência: <pre>";
    print_r($data);
    echo "</pre>";
    exit;
}
