<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login-admin.php');
    exit;
}

// LÓGICA DE BLOQUEIO DE ACESSO (PHP) - Adicionada aqui também
$data_expiracao = new DateTime('2025-10-31 23:59:59');
$agora = new DateTime();
$acesso_expirado = $agora > $data_expiracao;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Admin - Painel PagBank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #1e1e2d; --sidebar-bg: #151521; --card-bg: #27293d;
            --text-light: #f0f0f0; --text-muted: #a1a5b7; --border-color: #323448;
            --primary-color: #009ef7; --danger-color: #f1416c; --success-color: #50cd89;
            --warning-color: #ffc107;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); display: flex; height: 100vh; overflow: hidden; }
        .sidebar-left { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); }
        .main-content-area { flex-grow: 1; padding: 1.5rem; overflow-y: auto; }
        .header h1 { margin: 0 0 1.5rem 0; font-size: 1.8rem; }
        .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 2rem; }
        .card h2 { margin-top: 0; margin-bottom: 1rem; }
        .card p { margin: 10px 0; line-height: 1.6; color: var(--text-muted); }
        .btn-voltar { display: inline-block; margin-top: 1.5rem; padding: 10px 20px; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 6px; }

        /* CSS PARA TIMER E TELA DE BLOQUEIO */
        .access-timer {
            background-color: rgba(255, 193, 7, 0.1); border: 1px solid var(--warning-color);
            border-radius: 8px; padding: 15px; text-align: center; margin-top: 2rem;
        }
        .access-timer .timer-title { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px; }
        .access-timer #countdown { font-size: 1.5rem; font-weight: bold; color: var(--warning-color); }

        .lock-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.9); z-index: 1000;
            display: flex; justify-content: center; align-items: center; text-align: center;
        }
        .lock-content { background-color: var(--card-bg); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color); }
        .lock-content i { font-size: 4rem; color: var(--danger-color); margin-bottom: 1rem; }
        .lock-content h2 { font-size: 1.8rem; color: var(--text-light); margin-bottom: 1rem; }
        .lock-content p { color: var(--text-muted); max-width: 400px; }
    </style>
</head>
<body>
    <?php if ($acesso_expirado): ?>
        <div class="lock-overlay">
            <div class="lock-content">
                <i class="bi bi-lock-fill"></i>
                <h2>Acesso Expirado</h2>
                <p>Sua licença de acesso ao painel terminou. Por favor, entre em contato com o suporte para renovar seu acesso.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!$acesso_expirado): ?>
        <aside class="sidebar-left">
            <a href="admin.php" class="btn-voltar" style="text-align:center;">Voltar ao Painel</a>
        </aside>

        <main class="main-content-area">
            <div class="header">
                <h1>Perfil do Administrador</h1>
            </div>
            <div class="card">
                <h2>Informações do Perfil</h2>
                <p><strong>Nome de Usuário:</strong> <?php echo htmlspecialchars($_SESSION['admin_usuario'] ?? 'N/A'); ?></p>
                <p><strong>Nível de Acesso:</strong> Total</p>

                <div class="access-timer">
                    <div class="timer-title">Seu acesso ao painel expira em:</div>
                    <div id="countdown">Calculando...</div>
                </div>
            </div>
        </main>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!document.querySelector('.lock-overlay')) {
                iniciarContagemRegressiva();
            }
        });

        function iniciarContagemRegressiva() {
            const countdownElement = document.getElementById('countdown');
            if (!countdownElement) return; // Só continua se o elemento existir

            const dataFinal = new Date('2025-07-30T23:59:59').getTime();

            const interval = setInterval(() => {
                const agora = new Date().getTime();
                const diferenca = dataFinal - agora;

                if (diferenca < 0) {
                    clearInterval(interval);
                    window.location.reload(); // Recarrega para ativar o bloqueio PHP
                    return;
                }

                const dias = Math.floor(diferenca / (1000 * 60 * 60 * 24));
                const horas = Math.floor((diferenca % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutos = Math.floor((diferenca % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((diferenca % (1000 * 60)) / 1000);

                countdownElement.innerHTML = `${dias}d ${horas}h ${minutos}m ${segundos}s`;

            }, 1000);
        }
    </script>
</body>
</html>