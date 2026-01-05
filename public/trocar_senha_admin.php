<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login-admin.php');
    exit;
}

$mensagem = '';
$tipo_mensagem = '';

// Lógica para processar o formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    // --- Validação ---
    // ATENÇÃO: Substitua 'admin123' pela sua senha atual real.
    // O ideal é buscar a senha (hashed) do banco de dados e usar password_verify().
    $senha_correta_atual = 'admin123'; 

    if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
        $mensagem = "Todos os campos são obrigatórios.";
        $tipo_mensagem = 'erro';
    } else if ($senha_atual !== $senha_correta_atual) {
        $mensagem = "A senha atual está incorreta.";
        $tipo_mensagem = 'erro';
    } else if ($nova_senha !== $confirma_senha) {
        $mensagem = "A nova senha e a confirmação não correspondem.";
        $tipo_mensagem = 'erro';
    } else {
        // --- SUCESSO ---
        // Aqui você implementaria a lógica para salvar a nova senha.
        // Por exemplo: file_put_contents('config_admin.txt', password_hash($nova_senha, PASSWORD_DEFAULT));
        // Por enquanto, apenas exibimos uma mensagem de sucesso.
        
        $mensagem = "Senha alterada com sucesso!";
        $tipo_mensagem = 'sucesso';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha - Painel PagBank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #1e1e2d; --sidebar-bg: #151521; --card-bg: #27293d;
            --text-light: #f0f0f0; --text-muted: #a1a5b7; --border-color: #323448;
            --primary-color: #009ef7; --danger-color: #f1416c; --success-color: #50cd89;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); display: flex; height: 100vh; overflow: hidden; }
        .sidebar-left { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); }
        .main-content-area { flex-grow: 1; padding: 1.5rem; overflow-y: auto; }
        .header h1 { margin: 0 0 1.5rem 0; font-size: 1.8rem; }
        .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 2rem; max-width: 500px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-muted); }
        .form-group input { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-light); font-size: 1rem; }
        .btn { display: inline-block; padding: 12px 25px; border: none; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: bold; }
        .btn-voltar { background-color: transparent; border: 1px solid var(--text-muted); color: var(--text-muted); }
        .mensagem { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 500; }
        .mensagem.sucesso { background-color: rgba(80, 205, 137, 0.2); color: var(--success-color); }
        .mensagem.erro { background-color: rgba(241, 65, 108, 0.2); color: var(--danger-color); }
    </style>
</head>
<body>
    <aside class="sidebar-left">
        <a href="admin.php" class="btn btn-voltar" style="text-align:center; width: 100%;">Voltar ao Painel</a>
    </aside>

    <main class="main-content-area">
        <div class="header">
            <h1>Trocar Senha</h1>
        </div>
        <div class="card">
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form action="trocar_senha_admin.php" method="POST">
                <div class="form-group">
                    <label for="senha_atual">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                </div>
                <div class="form-group">
                    <label for="confirma_senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirma_senha" name="confirma_senha" required>
                </div>
                <button type="submit" class="btn">Alterar Senha</button>
            </form>
        </div>
    </main>
</body>
</html>