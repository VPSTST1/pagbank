<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexão com banco (Heroku ou local)
$DATABASE_URL = getenv("DATABASE_URL");

if ($DATABASE_URL) {
  $db = pg_connect($DATABASE_URL);
} else {
  $db = pg_connect("host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632");
}

// Verifica se cliente_id foi enviado
if (!isset($_GET['cliente_id'])) {
  http_response_code(400);
  echo json_encode(['erro' => 'Cliente não informado']);
  exit;
}

$cliente_id = $_GET['cliente_id'];

// Busca o nome do QR code no banco
$stmt = pg_query_params($db, "SELECT qrcode_nome FROM clientes_pagbank WHERE cliente_id = $1", [$cliente_id]);

if (!$stmt || pg_num_rows($stmt) === 0) {
  http_response_code(404);
  echo json_encode(['erro' => 'QR Code não encontrado']);
  exit;
}

$row = pg_fetch_assoc($stmt);
$path = trim($row['qrcode_nome'] ?? '');

if (!empty($path) && file_exists($path)) {
  echo json_encode(['qrcode' => $path]);
} else {
  echo json_encode(['qrcode' => 'imagens/qrcode-pagbank.png']); // fallback padrão
}
?>
