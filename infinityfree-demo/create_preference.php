<?php
// Criar preferência Mercado Pago a partir de JSON POST (esqueleto para demo)
// Substitua pelo seu Access Token do Mercado Pago (sandbox ou produção)

$access_token = 'APP_USR-677e53e3-73c2-44ff-8c17-da9bee16fa3f';

// Determina base URL automaticamente para back_urls
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base = $scheme . '://' . $_SERVER['HTTP_HOST'];

// Ler entrada JSON (esperamos { items: [ {title, quantity, unit_price}, ... ] })
$input = file_get_contents('php://input');
$data_in = json_decode($input, true);

if (!$data_in || !isset($data_in['items']) || !is_array($data_in['items'])) {
    http_response_code(400);
    echo json_encode(["error"=>"Requisição inválida. Envie JSON com 'items'."]);
    exit;
}

$items = [];
foreach ($data_in['items'] as $it) {
    $title = isset($it['title']) ? $it['title'] : 'Produto';
    $quantity = isset($it['quantity']) ? intval($it['quantity']) : 1;
    $unit = isset($it['unit_price']) ? floatval($it['unit_price']) : 0;
    if ($unit <= 0) continue;
    $items[] = ["title" => $title, "quantity" => $quantity, "unit_price" => $unit];
}

if (empty($items)) {
    http_response_code(400);
    echo json_encode(["error"=>"Nenhum item válido no carrinho."]);
    exit;
}

$body = [
    "items" => $items,
    "back_urls" => [
        "success" => $base . "/success.html",
        "failure" => $base . "/failure.html",
        "pending" => $base . "/pending.html"
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
    http_response_code(500);
    echo json_encode(["error"=>"cURL Error: $err"]);
    exit;
}

$data = json_decode($res, true);
// Para facilitar testes, retornamos JSON com a resposta (o JS pode redirecionar usando init_point)
header('Content-Type: application/json');
if ($data) {
    echo json_encode($data);
    exit;
} else {
    http_response_code(500);
    echo json_encode(["error"=>"Resposta inválida do Mercado Pago"]);
    exit;
}
