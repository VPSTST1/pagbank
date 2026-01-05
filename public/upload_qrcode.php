<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco
$DATABASE_URL = getenv("DATABASE_URL");
if ($DATABASE_URL) {
  $db = pg_connect($DATABASE_URL);
} else {
  $db = pg_connect("host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632");
}

// Verifica se veio imagem
if (!isset($_FILES['qrcode']) || !isset($_POST['cliente_id'])) {
  http_response_code(400);
  echo "Erro: imagem ou cliente_id ausente.";
  exit;
}

$cliente_id = $_POST['cliente_id'];
$arquivo = $_FILES['qrcode'];

// Verifica erros no upload
if ($arquivo['error'] !== UPLOAD_ERR_OK) {
  http_response_code(400);
  echo "Erro no upload do arquivo.";
  exit;
}

// Cria pasta se não existir
$caminhoUpload = 'uploads';
if (!is_dir($caminhoUpload)) {
  mkdir($caminhoUpload, 0755, true);
}

// Define nome único do arquivo
$ext = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
$nomeArquivo = $caminhoUpload . '/qr_' . $cliente_id . '.' . $ext;

// Move o arquivo para o local correto
if (!move_uploaded_file($arquivo['tmp_name'], $nomeArquivo)) {
  http_response_code(500);
  echo "Erro ao mover o arquivo.";
  exit;
}

// Atualiza o banco com o caminho da imagem
$query = "UPDATE clientes_pagbank SET qrcode_nome = $1 WHERE cliente_id = $2";
$result = pg_query_params($db, $query, [$nomeArquivo, $cliente_id]);

if ($result) {
  echo "QR Code enviado com sucesso!";
} else {
  http_response_code(500);
  echo "Erro ao atualizar o banco.";
}
?>
