<?php
session_start();

// --- LÓGICA DO "LEMBRAR-ME" ---

// 1. Verifica se existe um cookie de "lembrar-me" ao carregar a página
if (isset($_COOKIE['lembrar_admin_token'])) {
    // Em um sistema real, você validaria este token com o banco de dados.
    // Para este exemplo, vamos confiar no token para fazer o login.
    $_SESSION['admin_logado'] = true;
    $_SESSION['admin_usuario'] = $_COOKIE['lembrar_admin_usuario']; // Usamos um segundo cookie para o nome de usuário
    header('Location: admin.php');
    exit;
}

$usuario_correto = 'admin';
$senha_correta   = '123456'; // IMPORTANTE: No futuro, use hashes de senha. Ex: password_hash('sua_senha', PASSWORD_DEFAULT)
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['usuario'] ?? '';
    $pass = $_POST['senha'] ?? '';

    // 2. Valida o login
    if ($user === $usuario_correto && $pass === $senha_correta) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_usuario'] = $user;

        // 3. Se "Lembrar-me" foi marcado, cria os cookies
        if (isset($_POST['lembrar'])) {
            // Define um token seguro e um cookie com o nome de usuário
            $token = bin2hex(random_bytes(32)); // Token seguro
            setcookie('lembrar_admin_token', $token, time() + (86400 * 30), "/"); // Expira em 30 dias
            setcookie('lembrar_admin_usuario', $user, time() + (86400 * 30), "/");
        } else {
            // Se não foi marcado, apaga cookies existentes
            setcookie('lembrar_admin_token', '', time() - 3600, "/");
            setcookie('lembrar_admin_usuario', '', time() - 3600, "/");
        }

        header('Location: admin.php');
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login do Painel de Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        :root {
            --bg-dark: #1e1e2d;
            --card-bg: rgba(21, 21, 33, 0.6); /* Fundo com transparência */
            --border-color: #323448;
            --primary-color: #009ef7;
            --warning-color: #ffc107;
            --text-light: #f0f0f0;
            --text-muted: #a1a5b7;
        }

        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: linear-gradient(45deg, #151521, #1e1e2d);
            color: var(--text-light);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Frase Motivacional */
        .motivational-phrase {
            position: fixed;
            top: -100px; /* Começa fora da tela */
            left: 50%;
            transform: translateX(-50%);
            color: var(--text-muted);
            font-size: 1.2rem;
            opacity: 0;
            transition: top 1.5s ease-out, opacity 1.5s ease-out;
            text-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }

        .motivational-phrase.visible {
            top: 25%; /* Desce para a posição final */
            opacity: 1;
        }

        /* Caixa de Login */
        .login-box {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            width: 380px;
            z-index: 10;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--warning-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .input-group input {
            width: 100%;
            padding: 14px 14px 14px 45px;
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(45deg, var(--primary-color), var(--warning-color));
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 158, 247, 0.3);
        }

        .erro {
            color: var(--danger-color);
            background: rgba(241, 65, 108, 0.1);
            border: 1px solid var(--danger-color);
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="motivational-phrase" id="phrase"></div>

    <div class="login-box">
        <h2><i class="bi bi-shield-lock-fill"></i> Painel Admin</h2>

        <?php if (!empty($erro)) echo "<div class='erro'>$erro</div>"; ?>
        
        <form method="POST">
            <div class="input-group">
                <i class="bi bi-person"></i>
                <input type="text" name="usuario" placeholder="Usuário" required>
            </div>
            <div class="input-group">
                <i class="bi bi-key"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <div class="options">
                <label class="remember-me" for="lembrar">
                    <input type="checkbox" name="lembrar" id="lembrar">
                    Lembrar-me
                </label>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const phrases = [
                "O sucesso é a soma de pequenos esforços.",
                "Acredite em você e tudo será possível.",
                "A persistência realiza o impossível.",
                "O futuro pertence àqueles que acreditam na beleza de seus sonhos.",
                "Comece onde você está. Use o que você tem. Faça o que você pode."
            ];
            const randomPhrase = phrases[Math.floor(Math.random() * phrases.length)];
            const phraseElement = document.getElementById('phrase');

            phraseElement.innerText = `"${randomPhrase}"`;

            // Adiciona a classe 'visible' para iniciar a animação após um pequeno atraso
            setTimeout(() => {
                phraseElement.classList.add('visible');
            }, 500);
        });
    </script>
</body>
</html>