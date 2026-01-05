<?php
// --- identificador.php (VERSÃO CORRIGIDA E FINAL) ---

// Inicia a sessão para podermos guardar o ID correto do cliente
session_start();

header('Content-Type: application/json; charset=utf-8');

// --- Bloco de Conexão com o Banco de Dados (use o seu) ---
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    // Substitua pelas suas credenciais locais
    $DATABASE_URL = "host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632";
}
$db = pg_connect($DATABASE_URL);

if (!$db) {
    http_response_code(503);
    // Para depuração, é bom ver o erro
    echo json_encode(['status' => 'error', 'message' => 'Serviço indisponível: ' . pg_last_error()]);
    exit;
}
// --- Fim do Bloco de Conexão ---

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['identificador'])) {

    // --- 1. Coletar os dados ---
    $identificador = $_POST['identificador'];
    $device_type = $_POST['device_type'] ?? 'Desconhecido';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';
    $now_utc = gmdate("Y-m-d H:i:s");

    // --- 2. GERAR um novo valor para o cookie da sessão ---
    // Este valor vai para a coluna 'identificador_cookie'
    $novo_cookie_value = bin2hex(random_bytes(32));

    // --- 3. Deixar o BANCO DE DADOS criar o cliente_id ---
    // Não incluímos 'cliente_id' na lista de colunas.
    // Usamos 'RETURNING cliente_id' para pegar o ID numérico que o banco criou.
    $query = "
        INSERT INTO clientes_pagbank
        (identificador, ip, user_agent, identificador_cookie, device_type, created_at, last_active_at)
        VALUES
        ($1, $2, $3, $4, $5, $6, $7)
        RETURNING cliente_id
    ";

    $params = [
        $identificador, $ip, $user_agent, $novo_cookie_value,
        $device_type, $now_utc, $now_utc
    ];

    $result = pg_query_params($db, $query, $params);

    // --- 4. Processar o resultado ---
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $novo_cliente_id_numerico = $row['cliente_id']; // ID numérico (ex: 1, 2, 3...)

        // ✅ A CORREÇÃO MAIS IMPORTANTE: Salvar o ID NUMÉRICO na sessão
        $_SESSION['cliente_id'] = $novo_cliente_id_numerico;

        // Agora, definimos o cookie no navegador do usuário com o valor que salvamos no banco
        setcookie('identificador_cliente', $novo_cookie_value, time() + 86400 * 30, "/");

        // Retornamos sucesso para o JavaScript redirecionar a página
        echo json_encode(['status' => 'success']);

    } else {
        http_response_code(500);
        // Mostra o erro do banco de dados para facilitar a depuração
        echo json_encode(['status' => 'error', 'message' => 'Falha ao registrar novo cliente.', 'db_error' => pg_last_error($db)]);
    }

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados insuficientes fornecidos.']);
}
?>